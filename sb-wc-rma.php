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
    include SBWCRMA_PATH . 'cpt/sb-wc-rma-cpt.php';

    // register pll strings
    include SBWCRMA_PATH . 'pll/sb-wc-pll-strings.php';

    // admin class
    include SBWCRMA_PATH . 'classes/traits/trait-admin-modals.php';
    include SBWCRMA_PATH . 'classes/traits/trait-admin-columns.php';
    include SBWCRMA_PATH . 'classes/class-sbwcrma-admin.php';

    // class front
    include SBWCRMA_PATH . 'classes/traits/trait-product-select-modal.php';
    include SBWCRMA_PATH . 'classes/traits/trait-rma-data-modal.php';
    include SBWCRMA_PATH . 'classes/assets/class-backend-scripts.php';
    include SBWCRMA_PATH . 'classes/assets/class-frontend-scripts.php';

    // enqueue frontend js and css
    add_action('wp_footer', 'sbwcrma_front_scripts');

    function sbwcrma_front_scripts()
    {
        wp_enqueue_script('sbwcrma_js_front', SBWCRMA_Frontend_Scripts::sbwc_front_js(), ['jquery'], '1.0.0', true);
        wp_enqueue_style('sbwcrma_css_front', SBWCRMA_Frontend_Scripts::sbwc_front_css(), [], '1.0.0');
    }

    // admin enqueue js and css
    add_action('admin_footer', 'sbwc_rma_admin_scripts');
    function sbwc_rma_admin_scripts()
    {
        wp_enqueue_script('sbwcrma_js_front', SBWCRMA_Backend_Scripts::sbwc_admin_js(), ['jquery'], '1.0.0', true);
        wp_enqueue_style('sbwcrma_css_front', SBWCRMA_Backend_Scripts::sbwc_admin_css(), [], '1.0.0');
    }

    // front class
    include SBWCRMA_PATH . 'classes/class-sbwcrma-front.php';

    // shortcode
    include SBWCRMA_PATH . 'sc/sbwcrma-shortcode.php';
}
