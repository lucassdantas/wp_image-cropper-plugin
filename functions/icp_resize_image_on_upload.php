<?php
add_filter('wp_handle_upload_prefilter', 'custom_upload_resize');

function custom_upload_resize($file) {
    // Verifica se o arquivo é uma imagem
    $image_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $image_types)) {
        return $file; // Retorna sem alterações se não for uma imagem
    }

    // Caminho temporário do arquivo
    $file_path = $file['tmp_name'];

    // Obtém as dimensões da imagem
    $image_info = getimagesize($file_path);
    if (!$image_info) {
        return $file; // Retorna sem alterações se não conseguir obter informações da imagem
    }

    list($width, $height) = $image_info;

    // Define o tamanho máximo permitido
    $max_size = 1920;

    // Verifica se a imagem precisa ser redimensionada
    if ($width > $max_size || $height > $max_size) {
        $ratio = $width / $height;

        // Calcula as novas dimensões mantendo a proporção
        if ($width >= $height) {
            $new_width = $max_size;
            $new_height = intval($max_size / $ratio);
        } else {
            $new_height = $max_size;
            $new_width = intval($max_size * $ratio);
        }

        // Carrega a imagem no editor do WordPress
        $image_editor = wp_get_image_editor($file_path);
        if (!is_wp_error($image_editor)) {
            // Redimensiona e salva a imagem no caminho temporário
            $image_editor->resize($new_width, $new_height, true); // O terceiro parâmetro (true) força o crop apenas se necessário
            $result = $image_editor->save($file_path);
            $result = $image_editor->generate_filename();
            error_log(print_r($result, true));
            error_log(print_r($file, true));

            // Verifica se a imagem foi salva com sucesso
            if (is_wp_error($result)) error_log('Erro ao salvar a imagem redimensionada: ' . $result->get_error_message());
        } else {
            error_log('Erro ao carregar o editor de imagem: ' . $image_editor->get_error_message());
        }
    }

    return $file;
}
add_filter('intermediate_image_sizes_advanced', function($sizes) {
  return []; // Remove todos os tamanhos intermediários
});
