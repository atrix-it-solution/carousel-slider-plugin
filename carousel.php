<?php
/**
 * Plugin Name: Carousel
 * Plugin URI: https://mysite.com/carousel-plug
 * Description: Manage carousel functionality in WordPress.
 * Version: 1.0.2
 * Author: Webshouters
 * Author URI: https://www.mysite.com/
 * Text Domain: carousel
 * GitHub Plugin URI: atrix-it-solution/carousel-slider-plugin
 * GitHub Branch: main
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MY_CAROUSEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_CAROUSEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MY_CAROUSEL_VERSION', '1.0.2');

// Load the update checker with error handling
try {
    $pucPath = MY_CAROUSEL_PLUGIN_DIR . 'plugin-update-checker-master/plugin-update-checker.php';
    if (!file_exists($pucPath)) {
        throw new Exception('Plugin Update Checker not found at: ' . $pucPath);
    }
    
    require_once $pucPath;
    
    if (!class_exists('YahnisElsts\PluginUpdateChecker\v5\PucFactory')) {
        throw new Exception('PucFactory class not found. Check plugin-update-checker version.');
    }
} catch (Exception $e) {
    add_action('admin_notices', function() use ($e) {
        echo '<div class="error"><p>Carousel Plugin Error: ' . esc_html($e->getMessage()) . '</p></div>';
    });
    return; // Stop further execution
}

// Initialize the update checker
add_action('plugins_loaded', function() {
    try {
        $myUpdateChecker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            'https://github.com/atrix-it-solution/carousel-slider-plugin',
            __FILE__,
            'carousel-slider-plugin'
        );
        
        $myUpdateChecker->setBranch('main');
        
        // Only set authentication if needed (remove comment if required)
        // $myUpdateChecker->setAuthentication('your-token-here');
        
    } catch (Exception $e) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="error"><p>Carousel Update Checker Error: ' . esc_html($e->getMessage()) . '</p></div>';
        });
    }
});

// Include required files with error handling
$requiredFiles = [
    'includes/shortcodes.php',
    'includes/settings.php',
    'includes/post.php',
    'includes/update.php'
];

foreach ($requiredFiles as $file) {
    $filePath = MY_CAROUSEL_PLUGIN_DIR . $file;
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        add_action('admin_notices', function() use ($file) {
            echo '<div class="error"><p>Carousel Plugin Error: Missing required file - ' . esc_html($file) . '</p></div>';
        });
    }
}

// Enqueue frontend assets
add_action('wp_enqueue_scripts', 'my_carousel_enqueue_assets');
function my_carousel_enqueue_assets() {
    // Slick CSS
    wp_enqueue_style(
        'slick-css',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        [],
        '1.8.1'
    );
    
    // Slick theme CSS
    wp_enqueue_style(
        'slick-theme-css',
        'https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css',
        ['slick-css'],
        '1.5.0'
    );
    
    // Custom CSS
    wp_enqueue_style(
        'my-carousel-css',
        MY_CAROUSEL_PLUGIN_URL . 'css/frontend.css',
        ['slick-theme-css'],
        MY_CAROUSEL_VERSION
    );
    
    // Slick JS
    wp_enqueue_script(
        'slick-js',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        ['jquery'],
        '1.8.1',
        true
    );
    
    // Custom JS
    wp_enqueue_script(
        'my-carousel-js',
        MY_CAROUSEL_PLUGIN_URL . 'js/frontend.js',
        ['jquery', 'slick-js'],
        MY_CAROUSEL_VERSION,
        true
    );
}