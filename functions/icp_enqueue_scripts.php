<?php
function icp_enqueue_scripts($hook) {
  // Verifica se estamos na página de upload.php (Biblioteca de mídia)
  if ($hook === 'upload.php') {
      // Enfileira o estilo do Cropper.js
      wp_enqueue_style('croppercss', ICP_PLUGIN_PATH . 'css/lib/cropperjs.css');
      wp_enqueue_style('icp-crop-style', ICP_PLUGIN_PATH . 'css/icp-style.css');
      
      // Enfileira o script do Cropper.js
      wp_enqueue_script('cropperjs', ICP_PLUGIN_PATH . 'js/lib/cropperjs.js', [], '', false);

      // Enfileira o script personalizado para o crop
      wp_enqueue_script('icp-crop-script', ICP_PLUGIN_PATH . 'js/icp-crop.js', ['jquery', 'cropperjs'], '1.0', true);

      // Inclui o jQuery UI Dialog e seus estilos
      wp_enqueue_script('jquery-ui-dialog');
      wp_enqueue_style('wp-jquery-ui-dialog');
  }
}

add_action('admin_enqueue_scripts', 'icp_enqueue_scripts');