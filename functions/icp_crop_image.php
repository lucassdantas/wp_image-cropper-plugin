<?php 
function icp_crop_image() {
  if (!current_user_can('edit_posts')) wp_send_json_error(__('You do not have permission to perform this action.', 'icp'));

  $attachment_id = intval($_POST['attachment_id']);
  $crop_data = $_POST['crop_data'];

  $src = wp_get_attachment_url($attachment_id);
  $editor = wp_get_image_editor($src);

  if (is_wp_error($editor)) wp_send_json_error($editor->get_error_message());

  $editor->crop($crop_data['x'], $crop_data['y'], $crop_data['width'], $crop_data['height'], 800, 600);
  $saved = $editor->save($src);

  if (is_wp_error($saved)) wp_send_json_error($saved->get_error_message());

  wp_send_json_success(__('Image cropped successfully!', 'icp'));
}
add_action('wp_ajax_icp_crop_image', 'icp_crop_image');