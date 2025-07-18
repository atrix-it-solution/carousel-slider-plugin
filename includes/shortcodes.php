<?php

function mycarousel_shortcode($atts) {
    $atts = shortcode_atts([
        'id' => 0,
        'autoplay' => null,
        'autoplay_speed' => null,
        'dots' => null,
        'arrows' => null,
        'fade' => null,
        'speed' => null,
        'infinite' => null,
        'slides_to_show' => null,
        'slides_to_scroll' => null,
        'lazy_load' => null,
        'cssEase' =>null
    ], $atts);

    $post_id = intval($atts['id']);
    if (!get_post_status($post_id)) return '<p class="carousel-error">Carousel not found</p>';

    // Retrieve settings from DB or override with shortcode
    $settings = [
        'autoplay' => isset($atts['autoplay']) ? (bool)$atts['autoplay'] : (bool)get_post_meta($post_id, '_autoplay', true),
        'autoplaySpeed' => isset($atts['autoplay_speed']) ? (int)$atts['autoplay_speed'] : (int)get_post_meta($post_id, '_autoplay_speed', true),
        'dots' => isset($atts['dots']) ? (bool)$atts['dots'] : (bool)get_post_meta($post_id, '_show_dots', true),
        'arrows' => isset($atts['arrows']) ? (bool)$atts['arrows'] : (bool)get_post_meta($post_id, '_show_arrows', true),
        'fade' => isset($atts['fade']) ? (bool)$atts['fade'] : (bool)get_post_meta($post_id, '_fade', true),
        'speed' => isset($atts['speed']) ? (int)$atts['speed'] : (int)get_post_meta($post_id, '_speed', true),
        'infinite' => isset($atts['infinite']) ? (bool)$atts['infinite'] : (bool)get_post_meta($post_id, '_infinite', true),
        'slidesToShow' => isset($atts['slides_to_show']) ? (int)$atts['slides_to_show'] : (int)get_post_meta($post_id, '_slides_to_show', true),
        'slidesToScroll' => isset($atts['slides_to_scroll']) ? (int)$atts['slides_to_scroll'] : (int)get_post_meta($post_id, '_slides_to_scroll', true),
        'lazyLoad' => (isset($atts['lazy_load']) ? (bool)$atts['lazy_load'] : (bool)get_post_meta($post_id, '_lazy_load', true)) ? 'ondemand' : false,
        'cssEase' => isset($atts['cssEase']) ? $atts['cssEase'] : (get_post_meta($post_id, '_css_ease', true) ?: 'ease'),
       
    ];



    $slides = get_post_meta($post_id, 'carousel_slides', true);
    if (empty($slides)) return '<p class="carousel-error">No slides found</p>';

    ob_start();
    ?>
    <div class="my-carousel-wrapper">
        <div class="my-slick-carousel" data-slick='<?php echo esc_attr(json_encode($settings)); ?>'>
            <?php foreach ($slides as $slide): ?>
                <div class="slick-slide-item">
                    <div class="slide-inner">
                        <?php if (!empty($slide['image_id'])): ?>
                           <div class="slide-image">
                                <?php 
                                $img_attrs = [
                                    'class' => 'slide-img',
                                    'loading' => $settings['lazyLoad'] === 'ondemand' ? 'lazy' : 'eager'
                                ];
                                echo wp_get_attachment_image($slide['image_id'], 'large', false, $img_attrs); 
                                ?>
                            </div>
                        <?php endif; ?>
                        <?php 
                        // Check if slide has any content
                        $has_content = !empty($slide['heading']) || 
                                      !empty($slide['description']) || 
                                      (!empty($slide['button_text']) && !empty($slide['button_url']));
                        
                        if ($has_content): ?>

                        <div class="slide-content">
                            <?php if (!empty($slide['heading'])): ?>
                                <h3 class="slide-title"><?php echo esc_html($slide['heading']); ?></h3>
                            <?php endif; ?>
                            <?php if (!empty($slide['description'])): ?>
                                <div class="slide-text">
                                    <?php echo wpautop(esc_html($slide['description'])); ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($slide['button_text']) && !empty($slide['button_url'])): ?>
                                <a href="<?php echo esc_url($slide['button_url']); ?>" class="slide-button">
                                    <?php echo esc_html($slide['button_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                           <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('mycarousel', 'mycarousel_shortcode');


