<?php
/*
Plugin Name: Image Cropper Plugin
Description: Redimensionamento automático e opção de recortar imagens na biblioteca de mídia do WordPress.
Version: 1.0
Author: WP SOS
Author URI: https://soswordpress.com.br/
Plugin URI: https://github.com/lucassdantas/wp_image-cropper-plugin
*/

defined('ABSPATH') or die('you do not have permission to access that');

define( 'ICP_PLUGIN_PATH', '/wp-content/plugins/image-cropper-plugin/');


require_once plugin_dir_path(__FILE__) . 'functions/icp_add_crop_button.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_enqueue_scripts.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_add_crop_popup.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_save_cropped_image.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_admin_page.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_resize_image_on_upload.php';



