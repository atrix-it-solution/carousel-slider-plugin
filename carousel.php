<?php
/**
 * Plugin Name: Carousel
 * Plugin URI: https://mysite.com/carousel-plug
 * Description: Manage carousel functionality in WordPress.
 * Version: 1.0.1
 * Author: Webshouters
 * Author URI: https://www.mysite.com/
 * Text Domain: carousel
 * GitHub Plugin URI: atrix-it-solution/carousel-slider-plugin
 * GitHub Branch: main
 */


// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Define plugin constants
define('MY_CAROUSEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_CAROUSEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MY_CAROUSEL_VERSION', '1.0.1');


require 'plugin-update-checker-master/plugin-update-checker.php';
// Initialize the update checker
$my_carousel_update_checker = Puc_v5_Factory::buildUpdateChecker(
    'https://github.com/atrix-it-solution/carousel-slider-plugin',
    __FILE__,
    'carousel-slider-plugin'
);
$my_carousel_update_checker->setAuthentication('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');
$my_carousel_update_checker->setBranch('main');


// Include required files
require_once MY_CAROUSEL_PLUGIN_DIR . 'includes/shortcodes.php';
require_once MY_CAROUSEL_PLUGIN_DIR . 'includes/settings.php';
require_once MY_CAROUSEL_PLUGIN_DIR . 'includes/post.php';



function my_carousel_enqueue_assets() {
    // Slick CSS (from jsdelivr)
    wp_enqueue_style(
        'slick-css',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        array(),
        '1.8.1'
    );
    
    // Slick theme CSS (from jsdelivr - different version)
    wp_enqueue_style(
        'slick-theme-css',
        'https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css',
        array('slick-css'),
        '1.5.0'
    );
    
    // Your custom CSS
    wp_enqueue_style(
        'my-carousel-css',
        MY_CAROUSEL_PLUGIN_URL . 'css/frontend.css',
        array('slick-theme-css'),
        '1.0'
    );
    
    // Don't enqueue jQuery again (WordPress already includes it)
    // Just use 'jquery' as a dependency
    
    // Slick JS (from jsdelivr)
    wp_enqueue_script(
        'slick-js',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        array('jquery'),
        '1.8.1',
        true
    );
    
    // Your custom JS (load after Slick)
    wp_enqueue_script(
        'my-carousel-js',
        MY_CAROUSEL_PLUGIN_URL . 'js/frontend.js',
        array('jquery', 'slick-js'),
        '1.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'my_carousel_enqueue_assets');


// // Enqueue scripts and styles
// function my_carousel_enqueue_assets() {
//     // Slick CSS
//     wp_enqueue_style(
//         'slick-css',
//         'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css',
//         array(),
//         '1.8.1'
//     );
    
//     // Slick theme CSS (for default styling of arrows and dots)
//     wp_enqueue_style(
//         'slick-theme-css',
//         'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css',
//         array('slick-css'),
//         '1.8.1'
//     );
    
//     // Your custom CSS
//     wp_enqueue_style(
//         'my-carousel-css',
//         MY_CAROUSEL_PLUGIN_URL . 'css/frontend.css',
//         array('slick-theme-css'),
//         '1.0'
//     );
    
//     // Slick JS
//     wp_enqueue_script(
//         'slick-js',
//         'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js',
//         array('jquery'),
//         '1.8.1',
//         true
//     );
    
//     // Your custom JS (load after Slick)
//     wp_enqueue_script(
//         'my-carousel-js',
//         MY_CAROUSEL_PLUGIN_URL . 'js/frontend.js',
//         array('jquery', 'slick-js'),
//         '1.0',
//         true
//     );
// }
// add_action('wp_enqueue_scripts', 'my_carousel_enqueue_assets');

// // Admin assets
// function my_carousel_admin_assets($hook) {
//     if ('toplevel_page_my-carousel-settings' === $hook) {
//         wp_enqueue_style('my-carousel-admin', MY_CAROUSEL_PLUGIN_URL . 'css/admin.css');
//         wp_enqueue_script('my-carousel-admin', MY_CAROUSEL_PLUGIN_URL . 'js/admin.js', array('jquery'), '1.0', true);
//     }
// }
// add_action('admin_enqueue_scripts', 'my_carousel_admin_assets');