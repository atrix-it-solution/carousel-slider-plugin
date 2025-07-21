<?php

// Register Custom Post Type
function carousel_create_post_type() {
    $labels = array(
       
        'menu_name'             => __('Carousel', 'Carousel'),
        'name'                  => _x('Carousel', 'Post Type General Name', 'carousel'),
        'singular_name'         => _x('Carousel', 'Post Type Singular Name', 'carousel'),
        'menu_name'             => __('Carouselss', 'carousel'),
        'all_items'             => __('All Carousel', 'carousel'),
        'add_new_item'          => __('Add New Carousel', 'carousel'),
        'add_new'               => __('Add New', 'carousel'),
        'edit_item'             => __('Edit Carousel', 'carousel'),
        'update_item'           => __('Update Carousel', 'carousel'),
        'view_item'             => __('View Carousel', 'carousel'),
     
    );

    $args = array(
        'label'                 => __('Carousel', 'Carousel'),
        'labels'                => $labels,
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-slides',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'           => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'show_in_rest'          => true,
        'supports' => array('title')
    );

    register_post_type('carousel', $args);
}


// 
add_action('init', 'carousel_create_post_type', 0);

// Add shortcode column to carousel admin list
function add_carousel_shortcode_column($columns) {
    $new_columns = array();
    
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'title') {
            $new_columns['shortcode'] = __('Shortcode', 'carousel');
        }
    }
    
    return $new_columns;
}
add_filter('manage_carousel_posts_columns', 'add_carousel_shortcode_column');

// Display the shortcode in the column
function display_carousel_shortcode_column($column, $post_id) {
    if ($column === 'shortcode') {
        $shortcode = '[mycarousel id="' . esc_attr($post_id) . '"]';
        echo '<code>' . esc_html($shortcode) . '</code>';
        echo '<button class="button button-small copy-shortcode" data-clipboard-text="' . esc_attr($shortcode) . '">';
        echo esc_html__('Copy', 'carousel');
        echo '</button>';
    }
}
add_action('manage_carousel_posts_custom_column', 'display_carousel_shortcode_column', 10, 2);




// Add meta box for carousel slides
function add_carousel_slides_meta_box() {
    add_meta_box(
        'carousel_slides_meta_box',
        'Carousel Slides',
        'render_carousel_slides_meta_box',
        'carousel',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_carousel_slides_meta_box');

function render_carousel_slides_meta_box($post) {
    wp_nonce_field('save_carousel_slides', 'carousel_slides_nonce');
    
    $slides = get_post_meta($post->ID, 'carousel_slides', true);
    $slides = is_array($slides) ? $slides : array();
    
    wp_enqueue_media();
    wp_enqueue_script('carousel-admin', MY_CAROUSEL_PLUGIN_URL . 'js/admin.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
    
    // Add CSS for the hidden class
    echo '<style>
        .hidden { display: none !important; }
        .carousel-slides-list td, .carousel-slides-list th {
            vertical-align: middle !important;
        }
        .slide-actions {
            display: flex;
            gap: 5px;
        }
        .slide-actions button {
            min-width: 30px;
            padding: 0 5px;
        }
    </style>';
    ?>
    <div class="carousel-slides-container">
        <table class="widefat">
            <thead>
                <tr>
                    <th></th>
                    <th>Heading</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th >Button Text</th>
                    <th class="button-url-header <?php echo !has_button_text($slides) ? 'hidden' : ''; ?>">Button URL</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="carousel-slides-list">
                <?php foreach ($slides as $index => $slide): ?>
                    <tr class="carousel-slide" data-index="<?php echo $index; ?>">
                        <td class="slide-handle">
                            <h3>Slide <?php echo $index + 1; ?></h3>
                        </td>
                        <td>
                            <input type="text" name="carousel_slides[<?php echo $index; ?>][heading]" 
                                   value="<?php echo esc_attr($slide['heading'] ?? ''); ?>" class="widefat" >
                        </td>
                        <td>
                            <textarea name="carousel_slides[<?php echo $index; ?>][description]" 
                                      class="widefat"><?php echo esc_textarea($slide['description'] ?? ''); ?></textarea>
                        </td>
                        <td>
                            <input type="hidden" class="image-id" name="carousel_slides[<?php echo $index; ?>][image_id]" 
                                   value="<?php echo esc_attr($slide['image_id'] ?? ''); ?>">
                            <div class="image-preview">
                                <?php if (!empty($slide['image_id'])): ?>
                                    <?php echo wp_get_attachment_image($slide['image_id'], 'thumbnail'); ?>
                                <?php else: ?>
                                    <span>No image selected</span>
                                <?php endif; ?>
                            </div>
                            <button class="upload-image-button button">Add image</button>
                            <button class="remove-image-button button" style="<?php echo empty($slide['image_id']) ? 'display:none;' : ''; ?>">Remove</button>
                        </td>
                        <td>
                            <input type="text" name="carousel_slides[<?php echo $index; ?>][button_text]" 
                                   value="<?php echo esc_attr($slide['button_text'] ?? ''); ?>" class="widefat">
                        </td>
                        <td class="button-url-cell <?php echo empty($slide['button_text']) ? 'hidden' : ''; ?>">
                            <input type="url" name="carousel_slides[<?php echo $index; ?>][button_url]" 
                                   value="<?php echo esc_url($slide['button_url'] ?? ''); ?>" class="widefat" <?php echo !empty($slide['button_text']) ? 'required' : ''; ?>>
                        </td>
                        <td>
                            <div class="slide-actions">
                                <button class="slide-action add-slide-above" title="Add slide above">+</button>
                                <button class="slide-action remove-slide" title="Remove slide">-</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">
                        <button class="add-slide button">Add Row</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
}


// Save the meta box data
// function save_carousel_slides_meta_box($post_id) {
//     if (!isset($_POST['carousel_slides_nonce']) || !wp_verify_nonce($_POST['carousel_slides_nonce'], 'save_carousel_slides')) {
//         return;
//     }
    
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }
    
//     if (!current_user_can('edit_post', $post_id)) {
//         return;
//     }
    
//     if (isset($_POST['carousel_slides'])) {
//         $slides = array_values($_POST['carousel_slides']);
//         update_post_meta($post_id, 'carousel_slides', $slides);
//     } else {
//         delete_post_meta($post_id, 'carousel_slides');
//     }
// }
// add_action('save_post_carousel', 'save_carousel_slides_meta_box');

function has_button_text($slides) {
    foreach ($slides as $slide) {
        if (!empty($slide['button_text']) && trim($slide['button_text']) !== '') {
            return true;
        }
    }
    return false;
}

// Enqueue admin scripts and styles
function carousel_admin_scripts($hook) {
    global $post_type;
    
    // Load for carousel edit screens
    if (($hook == 'post-new.php' || $hook == 'post.php') && $post_type == 'carousel') {
        wp_enqueue_script('carousel-admin', MY_CAROUSEL_PLUGIN_URL . 'js/admin.js', array('jquery', 'jquery-ui-sortable'), '1.0', true);
        wp_enqueue_style('carousel-admin', MY_CAROUSEL_PLUGIN_URL . 'css/admin.css');
        
        // Add shortcode notice on edit screen
        wp_add_inline_script('carousel-admin', '
            jQuery(document).ready(function($) {
                // Add shortcode notice after title
                $("#titlediv").after(\'<div class="notice notice-info"><p>After saving, use shortcode: <code>[mycarousel id="\' + $("#post_ID").val() + \'"]</code></p></div>\');
            });
        ');
    }
    
     if ($hook == 'edit.php' && $post_type == 'carousel') {
        wp_enqueue_script(
            'carousel-admin-list',
            MY_CAROUSEL_PLUGIN_URL . 'js/admin-list.js',
            array('jquery'),
            '1.0',
            true
        );

       
        
       
    }
}
add_action('admin_enqueue_scripts', 'carousel_admin_scripts');





function settings_add_carousel_slides_meta_box() {
    add_meta_box(
        'settings_carousel_slides_meta_box',
        'Carousel Navigation Settings',
        'settings_carousel_slides',
        'carousel', 
        'side', 
        'default'
    );
}
add_action('add_meta_boxes', 'settings_add_carousel_slides_meta_box');

function settings_carousel_slides($post) {
    // Add a nonce field for security
    wp_nonce_field('settings_carousel_slides_nonce', 'settings_carousel_slides_nonce');
    
    // Get current values with defaults
    $show_dots = get_post_meta($post->ID, '_show_dots', true);
    $show_dots = ($show_dots === '') ? '1' : $show_dots;
    
    $show_arrows = get_post_meta($post->ID, '_show_arrows', true);
    $show_arrows = ($show_arrows === '') ? '1' : $show_arrows;
    
    $show_infinite = get_post_meta($post->ID, '_infinite', true);
    $show_infinite = ($show_infinite === '') ? '1' : $show_infinite;
    
    $show_speed = get_post_meta($post->ID, '_speed', true);
    $show_speed = ($show_speed === '') ? '300' : $show_speed;
    
    $show_autoplay = get_post_meta($post->ID, '_autoplay', true);
    $show_autoplay = ($show_autoplay === '') ? '1' : $show_autoplay;
    
    $show_autoplay_speed = get_post_meta($post->ID, '_autoplay_speed', true);
    $show_autoplay_speed = ($show_autoplay_speed === '') ? '3000' : $show_autoplay_speed;
    
    $show_slidesToShow = get_post_meta($post->ID, '_slides_to_show', true);
    $show_slidesToShow = ($show_slidesToShow === '') ? '1' : $show_slidesToShow;
    
    $show_slidesToScroll = get_post_meta($post->ID, '_slides_to_scroll', true);
    $show_slidesToScroll = ($show_slidesToScroll === '') ? '1' : $show_slidesToScroll;
    
    $show_lazyLoad = get_post_meta($post->ID, '_lazy_load', true);
    $show_lazyLoad = ($show_lazyLoad === '') ? '0' : $show_lazyLoad;

    $show_cssEase = get_post_meta($post->ID, '_css_ease', true);
    $show_cssEase = ($show_cssEase === '') ? 'ease' : $show_cssEase;
    ?>
    
    <div style="margin-bottom: 15px;">
        <label for="show_dots">
            <input type="checkbox" id="show_dots" name="show_dots" value="1" <?php checked($show_dots, '1'); ?>>
            Show Navigation Dots
        </label>
        <p class="description">Display pagination dots at the bottom of the carousel</p>
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="show_arrows">
            <input type="checkbox" id="show_arrows" name="show_arrows" value="1" <?php checked($show_arrows, '1'); ?>>
            Show Navigation Arrows
        </label>
        <p class="description">Display previous/next arrows on the sides</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_infinite">
            <input type="checkbox" id="show_infinite" name="show_infinite" value="1" <?php checked($show_infinite, '1'); ?>>
            Infinite
        </label>
        <p class="description">Loop the carousel infinitely</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_speed"> Transition Speed (ms)
            <input type="number" id="show_speed" name="show_speed" value="<?php echo esc_attr($show_speed); ?>">
           
        </label>
        <p class="description">Speed of slide transition in milliseconds</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_autoplay">
            <input type="checkbox" id="show_autoplay" name="show_autoplay" value="1" <?php checked($show_autoplay, '1'); ?>>
            Autoplay
        </label>
        <p class="description">Enable automatic sliding</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_autoplay_speed"> Autoplay Speed (ms) 
            <input type="number" id="show_autoplay_speed" name="show_autoplay_speed" value="<?php echo esc_attr($show_autoplay_speed); ?>">
           
        </label>
        <p class="description">Delay between transitions in milliseconds</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_slidesToShow">Slides To Show
            <input type="number" id="show_slidesToShow" name="show_slidesToShow" value="<?php echo esc_attr($show_slidesToShow); ?>">
            
        </label>
        <p class="description">Number of slides to show at once</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_slidesToScroll">Slides To Scroll
            <input type="number" id="show_slidesToScroll" name="show_slidesToScroll" value="<?php echo esc_attr($show_slidesToScroll); ?>">
        </label>
        <p class="description">Number of slides to scroll at once</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_lazyLoad">
            <input type="checkbox" id="show_lazyLoad" name="show_lazyLoad" value="1" <?php checked($show_lazyLoad, '1'); ?>>
            Lazy Load Images
        </label>
        <p class="description">Load images on demand</p>
    </div>

    <div style="margin-bottom: 15px;">
        <label for="show_cssEase">CSS Ease
            <select id="show_cssEase" name="show_cssEase">
                <?php 
                $options = ['ease', 'linear'
                // , 'ease-in', 'ease-out', 'ease-in-out'
            ];
                foreach ($options as $option): ?>
                    <option value="<?php echo esc_attr($option); ?>" <?php selected($show_cssEase, $option); ?>>
                        <?php echo esc_html($option); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <p class="description">Animation easing (e.g., linear for continuous scroll)</p>
    </div>

    <script>
        jQuery(document).ready(function($) {
            function handleLinearMode() {
                var isLinear = $('#show_cssEase').val() === 'linear';
                
                // Handle speed input
                if (isLinear) {
                    $('#show_speed').val('10000').attr('min', '10000').prop('readonly', true);
                } else {
                    $('#show_speed').attr('min', '100').prop('readonly', false);
                }
                
                // Handle autoplay speed input
                if (isLinear) {
                    $('#show_autoplay_speed').val('0').prop('readonly', true).css('background-color', '#f0f0f0');
                    $('#show_autoplay').prop('checked', true).prop('readonly', true);
                    $('#show_infinite').prop('checked', true).prop('readonly', true);
                    $('#show_slidesToScroll').val('1').prop('readonly', true).css('background-color', '#f0f0f0');
                } else {
                    $('#show_autoplay_speed').prop('readonly', false).css('background-color', '');
                    $('#show_autoplay').prop('readonly', false);
                    $('#show_infinite').prop('readonly', false);
                    $('#show_slidesToScroll').prop('readonly', false).css('background-color', '');
                }
            }
            
            // Initialize on load
            handleLinearMode();
            
            // Run when CSS Ease selection changes
            $('#show_cssEase').change(handleLinearMode);
            
            // Prevent manual changes when in linear mode
            $('#show_autoplay_speed').on('change input', function() {
                if ($('#show_cssEase').val() === 'linear') {
                    $(this).val('0');
                }
            });
        });
    </script>

    <?php
}

function save_carousel_slides_meta($post_id) {
    if (!isset($_POST['carousel_slides_nonce']) || 
        !wp_verify_nonce($_POST['carousel_slides_nonce'], 'save_carousel_slides')) {
        return;
    }

    if (!isset($_POST['settings_carousel_slides_nonce']) || 
    !wp_verify_nonce($_POST['settings_carousel_slides_nonce'], 'settings_carousel_slides_nonce')) {
    return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
     $slides = array();
    
    if (isset($_POST['carousel_slides']) && is_array($_POST['carousel_slides'])) {
        foreach ($_POST['carousel_slides'] as $slide) {
            // Remove the empty check - allow slides with just images
            $valid_slide = array(
                'heading' => sanitize_text_field($slide['heading'] ?? ''),
                'description' => sanitize_textarea_field($slide['description'] ?? ''),
                'image_id' => absint($slide['image_id'] ?? 0),
                'button_text' => sanitize_text_field($slide['button_text'] ?? ''),
                'button_url' => esc_url_raw($slide['button_url'] ?? '')
            );
            
            // Only require either heading OR image
            if (!empty($valid_slide['heading']) || $valid_slide['image_id'] > 0) {
                $slides[] = $valid_slide;
            }
        }
    }
    
    update_post_meta($post_id, 'carousel_slides', $slides);


    // Save all settings
    $settings = [
        '_show_dots' => isset($_POST['show_dots']) ? '1' : '0',
        '_show_arrows' => isset($_POST['show_arrows']) ? '1' : '0',
        '_infinite' => isset($_POST['show_infinite']) ? '1' : '0',
        '_speed' => isset($_POST['show_speed']) ? (int)$_POST['show_speed'] : 300,
        '_autoplay' => isset($_POST['show_autoplay']) ? '1' : '0',
        '_autoplay_speed' => isset($_POST['show_autoplay_speed']) ? (int)$_POST['show_autoplay_speed'] : 3000,
        '_slides_to_show' => isset($_POST['show_slidesToShow']) ? (int)$_POST['show_slidesToShow'] : 1,
        '_slides_to_scroll' => isset($_POST['show_slidesToScroll']) ? (int)$_POST['show_slidesToScroll'] : 1,
        '_lazy_load' => isset($_POST['show_lazyLoad']) ? '1' : '0',
        '_css_ease'          => isset($_POST['show_cssEase']) ? sanitize_text_field($_POST['show_cssEase']) : 'ease',
       

    ];

    foreach ($settings as $key => $value) {
        update_post_meta($post_id, $key, $value);
    }

}
add_action('save_post', 'save_carousel_slides_meta');



