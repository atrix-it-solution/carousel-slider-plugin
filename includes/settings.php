<?php

// 1. Register the settings page in admin menu
function carousel_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=carousel', 
        'Carousel Settings',            
        'Carousel Settings',                
        'edit_posts',  // Changed from 'manage_options' to 'edit_posts' for broader access
        'carousel-settings',            
        'carousel_render_settings_page' 
    );
}
add_action('admin_menu', 'carousel_add_settings_page');

// 2. Register settings and fields
function carousel_register_settings() {
    // Register a setting group
    register_setting(
        'carousel_settings_group',
        'carousel_options',
        array(
            'sanitize_callback' => 'carousel_sanitize_options'
        )
    );
    
    // Add settings section
    add_settings_section(
        'carousel_main_section',
        'Main Settings',
        'carousel_section_callback',
        'carousel-settings'
    );
    
    // Add fields
    add_settings_field(
        'carousel_type',
        'Carousel Type',
        'carousel_type_callback',
        'carousel-settings',
        'carousel_main_section'
    );
}
add_action('admin_init', 'carousel_register_settings');

// 3. Callback functions for rendering
function carousel_render_settings_page() {
    // Check user capabilities
    if (!current_user_can('edit_posts')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    ?>
    <div class="wrap">
        <h1>Carousel Plugin Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('carousel_settings_group');
            do_settings_sections('carousel-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function carousel_section_callback() {
    echo '<p>Configure your carousel settings</p>';
}

function carousel_type_callback() {
    $options = get_option('carousel_options');
    $current = isset($options['carousel_type']) ? $options['carousel_type'] : 'none'; // Default to 'none'
    ?>
    <fieldset>
        <!-- <label>
            <input type="radio" name="carousel_options[carousel_type]" value="none" <?php checked($current, 'none'); ?>>
            None (Disabled)
            <p class="description">No carousel type selected</p>
        </label>
        <br> -->
        <label>
            <input type="radio" name="carousel_options[carousel_type]" value="backgroundimageandtext" <?php checked($current, 'backgroundimageandtext'); ?>>
            Background Image and Text Carousel
            <p class="description">Display a carousel of Background Image and Text with optional captions</p>
        </label>
        <br>
        <label>
            <input type="radio" name="carousel_options[carousel_type]" value="logo" <?php checked($current, 'logo'); ?>>
            Logo Carousel
            <p class="description">Display a carousel of client/brand logos</p>
        </label>
    </fieldset>
    <?php
}

function carousel_sanitize_options($input) {
    $sanitized = array();
    
    // Validate carousel type
    if (isset($input['carousel_type'])) {
        $allowed_types = array('none', 'backgroundimageandtext', 'logo');
        $sanitized['carousel_type'] = in_array($input['carousel_type'], $allowed_types) 
            ? $input['carousel_type'] 
            : 'none'; // Default to 'none' if invalid
    } else {
        $sanitized['carousel_type'] = 'none'; // Default if not set
    }
    
    return $sanitized;
}