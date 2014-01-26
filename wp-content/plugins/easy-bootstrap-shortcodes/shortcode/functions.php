<?php

// Add Shortcode buttons in TinyMCE
$css = get_option( 'EBS_BOOTSTRAP_CSS_LOCATION', 1 );
if($css==3){
    define('EBS_CONTAINER_CLASS',' oscitas-bootstrap-container');
    define('EBS_POPOVER_TEMPLATE','<div class="popover'.EBS_CONTAINER_CLASS.'"><div class="arrow'.EBS_CONTAINER_CLASS.'"></div><h3 class="popover-title'.EBS_CONTAINER_CLASS.'"></h3><div class="popover-content'.EBS_CONTAINER_CLASS.' "></div></div>');
    define('EBS_TOOLTIP_TEMPLATE','<div class="tooltip'.EBS_CONTAINER_CLASS.'"><div class="tooltip-arrow'.EBS_CONTAINER_CLASS.'"></div><div class="tooltip-inner'.EBS_CONTAINER_CLASS.'"></div></div>');
} else{
    define('EBS_CONTAINER_CLASS','');
    define('EBS_POPOVER_TEMPLATE','');
    define('EBS_TOOLTIP_TEMPLATE','');
}
$elements = array(
    'toggles',
    'tabs',
    'lists',
    'deslist',
    'buttons',
    'btngrptool',
    'btngrp',
    'notifications',
    'wpcolumns',
    'tables',
    'tooltip',
    'iconhead',
    'panel',
    'oscpopover',
    'dropdown',
    'labels',
    'well',
    'thumbnail',
    'icon',
    'image',
    'progressbar',
    'servicebox'

);

foreach ($elements as $element) {
    include( $element . '/plugin_shortcode.php');
}

add_action('init', 'osc_add_ebs_buttons_to_tinymce');

function osc_add_ebs_buttons_to_tinymce() {
    $ebsp_editor_opt=get_option('EBS_EDITOR_OPT','icon');
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
        return;

    if (get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "osc_add_ebs_plugin");
        if($ebsp_editor_opt=='icon'){
            add_filter('mce_buttons_3', 'osc_register_ebs_button');
        } else{
            add_filter('mce_buttons', 'osc_register_ebs_dropdown');
        }
    }
}
function osc_register_ebs_dropdown($buttons){
    $buttons[] = 'oscitas_main_dropdown_button';
    return $buttons;
}
function osc_register_ebs_button($buttons) {
    global $elements;
    foreach ($elements as $element) {
        $buttons[] = 'oscitas' . $element;
    }
    return $buttons;
}

function osc_add_ebs_plugin($plugin_array) {
    global $elements;
    foreach ($elements as $element) {
        $plugin_array['oscitas' . $element] = plugins_url('', __FILE__) . '/' . $element . '/' . $element . '_plugin.js';
    }
    $plugin_array['oscitas_main_dropdown']=EBS_PLUGIN_URL.'js/oscitas_main_dropdown.js';
    return $plugin_array;
    return $plugin_array;
}

