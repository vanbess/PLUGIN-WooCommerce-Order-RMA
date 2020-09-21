<?php

/**
 * Class which loads admin javascript and css files
 * Loaded via class to enable passing of certain php variable data directly to files
 */

class SBWCRMA_Admin_Scripts
{

    /**
     * Class init
     */
    public static function init()
    {
        // enqueue
        wp_enqueue_script('sbwc-admin-js', self::sbwc_admin_js(), ['jquery'], '1.0.0');
        wp_enqueue_style('sbwc-admin-css', self::sbwc_admin_css(), '', '1.0.0');
    }

    /**
     * JS
     */
    public static function sbwc_admin_js()
    { ?>
        <script>
            jQuery(document).ready(function($) {

            });
        </script>
    <?php }

    /**
     * CSS
     */
    public static function sbwc_admin_css()
    { ?>
        <style>

        </style>
<?php }
}