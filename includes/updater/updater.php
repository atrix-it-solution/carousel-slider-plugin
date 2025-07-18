<?php
/**
 * Plugin Updater
 */

// Include the updater class
require_once plugin_dir_path(__FILE__) . 'includes/updater/updater.php';

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

// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//     'https://ACCESS_TOKEN@github.com/atrix-it-solution/carousel-slider-plugin',
//     __FILE__,
//     'carousel-slider-plugin'
// );

// In wp-config.php
// define('GITHUB_TOKEN', 'ghp_AbC123...');

// // In your plugin updater
// $update_url = 'https://' . GITHUB_TOKEN . '@github.com/atrix-it-solution/carousel-slider-plugin';
// $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//     $update_url,
//     __FILE__,
//     'carousel-slider-plugin'
// );