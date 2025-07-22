<?php
/**
 * Plugin Name: Carousel
 * Plugin URI: https://mysite.com/carousel-plug
 * Description: Manage carousel functionality in WordPress.
 * Version: 1.0.5
 * Author: Webshouters
 * Author URI: https://www.mysite.com/
 * Text Domain: carousel
 * GitHub Plugin URI: atrix-it-solution/carousel-slider-plugin
 * GitHub Branch: main
 */

if (!defined('ABSPATH')) exit;

// Define plugin constants
define('MY_CAROUSEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MY_CAROUSEL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MY_CAROUSEL_VERSION', '1.0.5');

// Load the update checker with error handling
// Replace the existing PUC loading code with this:
try {
    $pucPath = MY_CAROUSEL_PLUGIN_DIR . 'plugin-update-checker/plugin-update-checker.php';
    
    if (!file_exists($pucPath)) {
        // Provide a more helpful error message
        $message = 'Plugin Update Checker not found at: ' . $pucPath . '<br>';
        $message .= 'Please download it from: https://github.com/YahnisElsts/plugin-update-checker<br>';
        $message .= 'And place it in: ' . MY_CAROUSEL_PLUGIN_DIR . 'plugin-update-checker/';
        
        throw new Exception($message);
    }
    
    require_once $pucPath;
    
    if (!class_exists('YahnisElsts\PluginUpdateChecker\v5p6\PucFactory')) {
        throw new Exception('PucFactory class not found. Please ensure you have version 5.x of the Plugin Update Checker.');
    }
} catch (Exception $e) {
    add_action('admin_notices', function() use ($e) {
        echo '<div class="error"><p><strong>Carousel Plugin Error:</strong> ' . esc_html($e->getMessage()) . '</p></div>';
    });
    return;
}

// Initialize the update checker
add_action('plugins_loaded', function() {
    try {
        $myUpdateChecker = YahnisElsts\PluginUpdateChecker\v5p6\PucFactory::buildUpdateChecker(
            'https://github.com/atrix-it-solution/carousel-slider-plugin',
            __FILE__,
            'carousel-slider-plugin'
        );
        
        $myUpdateChecker->setBranch('main');
        
        // Only set authentication if needed
        // $myUpdateChecker->setAuthentication('your-token-here');
        
    } catch (Exception $e) {
        add_action('admin_notices', function() use ($e) {
            echo '<div class="error"><p>Carousel Update Checker Error: ' . esc_html($e->getMessage()) . '</p></div>';
        });
    }
});

// Include required files
$requiredFiles = [
    'includes/shortcodes.php',
    'includes/settings.php',
    'includes/post.php'
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

// Enqueue assets
add_action('wp_enqueue_scripts', 'my_carousel_enqueue_assets');
function my_carousel_enqueue_assets() {
    wp_enqueue_style(
        'slick-css',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
        [],
        '1.8.1'
    );
    
    wp_enqueue_style(
        'slick-theme-css',
        'https://cdn.jsdelivr.net/jquery.slick/1.5.0/slick-theme.css',
        ['slick-css'],
        '1.5.0'
    );
    
    wp_enqueue_style(
        'my-carousel-css',
        MY_CAROUSEL_PLUGIN_URL . 'css/frontend.css',
        ['slick-theme-css'],
        MY_CAROUSEL_VERSION
    );
    
    wp_enqueue_script(
        'slick-js',
        '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        ['jquery'],
        '1.8.1',
        true
    );
    
    wp_enqueue_script(
        'my-carousel-js',
        MY_CAROUSEL_PLUGIN_URL . 'js/frontend.js',
        ['jquery', 'slick-js'],
        MY_CAROUSEL_VERSION,
        true
    );
}