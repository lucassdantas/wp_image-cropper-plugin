<?php
function icp_enqueue_scripts($hook) {
    // Verifica se estamos em upload.php, post.php ou post-new.php
    if (in_array($hook, ['upload.php', 'post.php', 'post-new.php'])) {
        // Enfileira o estilo do Cropper.js
        wp_enqueue_style('croppercss', ICP_PLUGIN_PATH . 'css/lib/cropperjs.css');
        wp_enqueue_style('icp-crop-style', ICP_PLUGIN_PATH . 'css/icp-style.css');
        
        // Enfileira o script do Cropper.js
        wp_enqueue_script('cropperjs', ICP_PLUGIN_PATH . 'js/lib/cropperjs.js', [], '', false);

        // Enfileira o script personalizado para o crop
        wp_enqueue_script('icp-crop-script', ICP_PLUGIN_PATH . 'js/icp-crop.js', ['jquery', 'cropperjs'], '1.0', true);
        
        wp_localize_script('icp-crop-script', 'icpCropSettings', [
            'width' => get_option('icp_crop_width', 800),
            'height' => get_option('icp_crop_height', 600),
        ]);

        // Inclui o jQuery UI Dialog e seus estilos
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style('wp-jquery-ui-dialog');
    }
}

add_action('admin_enqueue_scripts', 'icp_enqueue_scripts');
