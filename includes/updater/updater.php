<?php
/**
 * Plugin Updater
 */

// Include the updater class
require_once plugin_dir_path(__FILE__) . 'includes/updater/plugin-update-checker.php';

// Initialize the updater
function my_carousel_plugin_updater() {
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
        'https://github.com/atrix-it-solution/carousel-slider-plugin',
        __FILE__,
        'carousel-slider-plugin'
    );
    
    // Optional: Set the branch that contains the stable release
    $myUpdateChecker->setBranch('main');
}
add_action('admin_init', 'my_carousel_plugin_updater');