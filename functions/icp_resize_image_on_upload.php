<?php
// Desabilita o escalonamento automático de imagens grandes
add_filter('big_image_size_threshold', '__return_false');

// Redimensiona a imagem original usando o wp_generate_attachment_metadata
add_filter('wp_generate_attachment_metadata', 'icp_resize_image_after_upload', 10, 2);

function icp_resize_image_after_upload($metadata, $attachment_id) {
    $auto_resize = get_option('icp_auto_resize', 1); // Padrão: ativado
    if (!$auto_resize) {
        return $metadata;
    }  
  // Obtém o caminho completo da imagem original
    $file_path = get_attached_file($attachment_id);

    // Verifica se o arquivo existe
    if (!file_exists($file_path)) {
        error_log('Arquivo não encontrado: ' . $file_path);
        return $metadata;
    }

    // Obtém as dimensões da imagem
    $image_info = getimagesize($file_path);
    if (!$image_info) {
        error_log('Não foi possível obter informações da imagem: ' . $file_path);
        return $metadata;
    }

    list($width, $height) = $image_info;

    // Define as dimensões máximas permitidas
    $max_width = 1920;
    $max_height = 1280;

    // Verifica se a imagem precisa ser redimensionada
    if ($width > $max_width || $height > $max_height) {
        $ratio = $width / $height;

        // Calcula as novas dimensões mantendo a proporção
        if ($ratio > 1) { // Imagem mais larga que alta
            $new_width = $max_width;
            $new_height = intval($max_width / $ratio);
        } else { // Imagem mais alta que larga
            $new_height = $max_height;
            $new_width = intval($max_height * $ratio);
        }

        // Carrega a imagem no editor do WordPress
        $image_editor = wp_get_image_editor($file_path);
        if (!is_wp_error($image_editor)) {
            // Redimensiona e salva a imagem no caminho original
            $image_editor->resize($new_width, $new_height, false);
            $result = $image_editor->save($file_path);

            // Verifica se a imagem foi salva com sucesso
            if (is_wp_error($result)) {
                error_log('Erro ao salvar a imagem redimensionada: ' . $result->get_error_message());
            } else {
                // Atualiza as dimensões no metadata
                $metadata['width'] = $new_width;
                $metadata['height'] = $new_height;
                error_log('Imagem redimensionada para ' . $new_width . 'x' . $new_height);
            }
        } else {
            error_log('Erro ao carregar o editor de imagem: ' . $image_editor->get_error_message());
        }
    }

    return $metadata;
}

// Remove tamanhos intermediários
add_filter('intermediate_image_sizes_advanced', function($sizes) {
    return []; // Remove todos os tamanhos intermediários
});
