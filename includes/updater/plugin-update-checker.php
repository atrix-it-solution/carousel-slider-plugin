<?php
require_once plugin_dir_path(__FILE__) . 'Puc/v5p1/Factory.php';

$myUpdateChecker = Puc_v5p1_Factory::buildUpdateChecker(
    'https://github.com/atrix-it-solution/carousel-slider-plugin',
    __FILE__,
    'carousel-slider-plugin'
);
