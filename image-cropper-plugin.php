<?php
/*
Plugin Name: Image Cropper Plugin
Description: Adiciona um botão para recortar imagens na biblioteca de mídia do WordPress.
Version: 1.0
Author: Seu Nome
*/

defined('ABSPATH') or die('you do not have permission to access that');

define( 'ICP_PLUGIN_PATH', '/wp-content/plugins/image-cropper-plugin/');


require_once plugin_dir_path(__FILE__) . 'functions/icp_add_crop_button.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_enqueue_scripts.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_add_crop_popup.php';
require_once plugin_dir_path(__FILE__) . 'functions/icp_save_cropped_image.php';



