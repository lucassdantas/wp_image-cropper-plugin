<?php 
function icp_add_crop_button($form_fields, $post) {
    $form_fields['icp_crop_button'] = [
        'label' => __('Crop Image', 'icp'),
        'input' => 'html',
        'html' => '<button type="button" class="button icp-crop-button" data-attachment-id="' . $post->ID . '">' . __('Crop Image', 'icp') . '</button>',
    ];
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'icp_add_crop_button', 10, 2);