<?php
/*
Plugin Name: Image Cropper Plugin
Description: Adiciona um botão para recortar imagens na biblioteca de mídia do WordPress.
Version: 1.0
Author: Seu Nome
*/

// Adiciona o botão de recorte abaixo da URL da imagem na biblioteca de mídia
function icp_add_crop_button($form_fields, $post) {
    $form_fields['icp_crop_button'] = [
        'label' => __('Crop Image', 'icp'),
        'input' => 'html',
        'html' => '<button type="button" class="button icp-crop-button" data-attachment-id="' . $post->ID . '">' . __('Crop Image', 'icp') . '</button>',
    ];
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'icp_add_crop_button', 10, 2);

// Adiciona scripts e estilos necessários
function icp_enqueue_scripts($hook) {
    if ($hook === 'post.php' || $hook === 'upload.php') {
        wp_enqueue_script('icp-crop-script', plugin_dir_url(__FILE__) . 'js/icp-crop.js', ['jquery'], '1.0', true);
        wp_enqueue_style('icp-crop-style', plugin_dir_url(__FILE__) . 'css/icp-style.css');

        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');
    }
}
add_action('admin_enqueue_scripts', 'icp_enqueue_scripts');

// Adiciona o HTML para o popup de recorte
function icp_add_crop_popup() {
  $file_path = plugin_dir_path(__FILE__) . 'html/popup.html';
  if (file_exists($file_path)) {
      $popup_html = file_get_contents($file_path);
      echo $popup_html;
  } 
  else echo '<div class="error">' . __('Error: popup.html not found.', 'icp') . '</div>';
}

add_action('admin_footer', 'icp_add_crop_popup');


// Processa o recorte da imagem no backend
function icp_crop_image() {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(__('You do not have permission to perform this action.', 'icp'));
    }

    $attachment_id = intval($_POST['attachment_id']);
    $crop_data = $_POST['crop_data'];

    $src = wp_get_attachment_url($attachment_id);
    $editor = wp_get_image_editor($src);

    if (is_wp_error($editor)) {
        wp_send_json_error($editor->get_error_message());
    }

    $editor->crop($crop_data['x'], $crop_data['y'], $crop_data['width'], $crop_data['height'], 800, 600);
    $saved = $editor->save($src);

    if (is_wp_error($saved)) {
        wp_send_json_error($saved->get_error_message());
    }

    wp_send_json_success(__('Image cropped successfully!', 'icp'));
}
add_action('wp_ajax_icp_crop_image', 'icp_crop_image');


