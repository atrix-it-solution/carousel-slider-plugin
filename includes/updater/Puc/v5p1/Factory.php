<?php
if ( !class_exists('Puc_v5p1_Factory', false) ) {
    class Puc_v5p1_Factory {
        public static function buildUpdateChecker($metadataUrl, $pluginFile, $slug) {
            require_once dirname(__FILE__) . '/Plugin/UpdateChecker.php';
            return new Puc_v5p1_Plugin_UpdateChecker($metadataUrl, $pluginFile, $slug);
        }
    }
}
