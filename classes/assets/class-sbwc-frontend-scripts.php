<?php

/**
 * Class which loads frontend javascript and css files
 * Loaded via class to enable passing of certain php variable data directly to files
 */

class SBWC_Frontend_Scripts
{

    /**
     * Class init
     */
    public static function init()
    {
        // enqueue
        wp_enqueue_script('sbwc-front-js', self::sbwc_front_js(), ['jquery'], '1.0.0');
        wp_enqueue_style('sbwc-front-css', self::sbwc_front_css(), '', '1.0.0');
    }

    /**
     * JS
     */
    public static function sbwc_front_js()
    { ?>
        <script>
            jQuery(document).ready(function($) {

            });
        </script>
    <?php }

    /**
     * CSS
     */
    public static function sbwc_front_css()
    { ?>
        <style>

        </style>
<?php }
}
