<?php
function icp_add_crop_popup() {
  $file_path = plugin_dir_path(__FILE__) . '../html/popup.html'; // Caminho absoluto do arquivo no sistema
  if (file_exists($file_path)) {
      $popup_html = file_get_contents($file_path);
      echo $popup_html;
  } else {
      echo '<div class="error">' . __("Error: popup.html not found. Path: $file_path", 'icp') . '</div>';
  }
}

add_action('admin_footer', 'icp_add_crop_popup');
