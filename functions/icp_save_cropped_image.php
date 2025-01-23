<?php
// Processa e salva a imagem recortada
function icp_save_cropped_image() {
    // Verifica permissões
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(__('Você não tem permissão para realizar esta ação.', 'icp'));
    }

    // Verifica os dados enviados
    $attachment_id = intval($_POST['attachment_id']);
    $cropped_image = $_POST['cropped_image'];

    if (!$attachment_id || !$cropped_image) {
        wp_send_json_error(__('Dados inválidos fornecidos.', 'icp'));
    }

    // Decodifica a imagem base64
    $image_data = explode(',', $cropped_image);
    $decoded_image = base64_decode($image_data[1]);

    // Obtém o caminho do arquivo original
    $attachment = get_post($attachment_id);
    $upload_dir = wp_upload_dir();
    $original_path = get_attached_file($attachment_id);

    if (!$original_path) {
        wp_send_json_error(__('Caminho do arquivo original não encontrado.', 'icp'));
    }

    // Cria um novo nome para o arquivo recortado
    $file_info = pathinfo($original_path);
    $new_file_name = $file_info['filename'] . '-cropped.' . $file_info['extension'];
    $new_file_path = $upload_dir['path'] . '/' . $new_file_name;

    // Salva o arquivo recortado
    file_put_contents($new_file_path, $decoded_image);

    // Adiciona o arquivo recortado como um novo anexo no WordPress
    $attachment_data = [
        'post_mime_type' => wp_check_filetype($new_file_path)['type'],
        'post_title'     => $attachment->post_title . ' (Recortada)',
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];
    $new_attachment_id = wp_insert_attachment($attachment_data, $new_file_path);

    // Gera os metadados do anexo
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($new_attachment_id, $new_file_path);
    wp_update_attachment_metadata($new_attachment_id, $attach_data);

    wp_send_json_success(__('Imagem recortada e salva com sucesso!', 'icp'));
}
add_action('wp_ajax_icp_save_cropped_image', 'icp_save_cropped_image');
