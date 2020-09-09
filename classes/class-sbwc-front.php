<?php

/**
 * Class to render user dashboard options on front
 * Extends SBWC_Frontend_Scripts
 */

class SBWC_Front extends SBWC_Frontend_Scripts
{

    /**
     * Class init
     */
    public static function init()
    {
        // add my account menu item
        add_filter('woocommerce_account_menu_items', [__CLASS__, 'sbwcrma_menu_item'], 40);

        // add rewrite endpoints
        add_action('init', [__CLASS__, 'sbwcrma_endpoints']);

        // display RMA/returns data
        add_action('woocommerce_account_returns_endpoint', [__CLASS__, 'sbwcrma_acc_content']);
    }

    /**
     * Add my account menu item for RMAs
     */
    public static function sbwcrma_menu_item($menu_links)
    {
        $menu_links = array_slice($menu_links, 0, 5, true)
            + array('returns' => 'Returns')
            + array_slice($menu_links, 5, NULL, true);

        return $menu_links;
    }

    /**
     * Add rewrite endpoints for menu item
     */
    public static function sbwcrma_endpoints()
    {
        add_rewrite_endpoint('returns', EP_PAGES);

        // flush rewrite rules to affect changes
        flush_rewrite_rules();
    }

    /**
     * Display RMA page content
     */
    public static function sbwcrma_acc_content()
    {
        print 'rma content goes here';
    }


    /**
     * Submit RMA request (user side)
     */
    public static function sbwcrma_submit()
    {
    }


    /**
     * Send CS email
     */
    public static function sbwcrma_send_email()
    {
    }
}
SBWC_Front::init();
