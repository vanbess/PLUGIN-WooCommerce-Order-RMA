<?php

/**
 * Plugin Name: SB WooCommerce RMA
 * Description: Allows users to submit return requests and admins to process said requests
 * Author: WC Bessinger
 * Version: 1.0.0
 */

//  no direct access allowed
if (!defined('ABSPATH')) {
    exit();
}

//  constants
define('SBWCRMA_PATH', plugin_dir_path(__FILE__));
define('SBWCRMA_URI', plugin_dir_url(__FILE__));

add_action('plugins_loaded', 'sbwcrma_init');

function sbwcrma_init()
{
    // custom post type
    require_once SBWCRMA_PATH . 'cpt/sb-wc-rma-cpt.php';

    // register pll strings
    require_once SBWCRMA_PATH . 'pll/sb-wc-pll-strings.php';

    // admin class
    require_once SBWCRMA_PATH . 'classes/assets/class-sbwc-admin-scripts.php';
    require_once SBWCRMA_PATH . 'classes/class-sbwc-admin.php';

    // class front
    require_once SBWCRMA_PATH . 'classes/traits/trait-product-select-modal.php';
    require_once SBWCRMA_PATH . 'classes/assets/class-sbwc-frontend-scripts.php';

    // enqueue frontend js and css
    add_action('wp_footer', 'sbwcrma_front_scripts');

    function sbwcrma_front_scripts()
    {
        wp_enqueue_script('sbwcrma_js_front', SBWCRMA_Frontend_Scripts::sbwc_front_js(), ['jquery']);
        wp_enqueue_style('sbwcrma_css_front', SBWCRMA_Frontend_Scripts::sbwc_front_css());
    }

    require_once SBWCRMA_PATH . 'classes/class-sbwc-front.php';
}
