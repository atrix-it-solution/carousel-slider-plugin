<?php
require_once plugin_dir_path(__FILE__) . 'Puc/v5p1/Factory.php';

$myUpdateChecker = Puc_v5p1_Factory::buildUpdateChecker(
    'https://github.com/atrix-it-solution/carousel-slider-plugin',
    __FILE__,
    'carousel-slider-plugin'
);
// $myUpdateChecker->setBranch('main');

// $myUpdateChecker->setAuthentication('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'); // Optional GitHub token if private repo

// $myUpdateChecker->getVcsApi()->enableReleaseAssets(); // Use ZIP from GitHub Releases