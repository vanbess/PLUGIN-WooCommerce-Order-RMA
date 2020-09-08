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

// custom post type
require_once SBWCRMA_PATH . 'cpt/sb-wc-rma-cpt.php';

// register pll strings
require_once SBWCRMA_PATH . 'pll/sb-wc-pll-strings.php';

// admin class
require_once SBWCRMA_PATH . 'classes/assets/class-sbwc-admin-scripts.php';
require_once SBWCRMA_PATH . 'classes/class-sbwc-admin.php';

// class front
require_once SBWCRMA_PATH . 'classes/assets/class-sbwc-frontend-scripts.php';
require_once SBWCRMA_PATH . 'classes/class-sbwc-front.php';
