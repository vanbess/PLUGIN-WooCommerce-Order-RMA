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
    { ?>
        <!-- rma outer container -->
        <div id="sbwcrma_acc_container">

            <!-- orders list and submit return -->
            <div id="sbwcrma_submit_returns" class="sbwcrma_show">
                <?php
                // get current user id
                $user_id = get_current_user_id();

                // get user orders
                $orders = wc_get_orders(['customer_id' => $user_id]);

                // if orders present list them, else display error message
                if ($orders && is_array($orders) || is_object($orders)) { ?>
                    <p><?php pll_e('Select the order below you would like to log a return for.'); ?></p>

                    <table id="sbwcrma_order_list">
                        <tr>
                            <th></th>
                            <th><?php pll_e('Order Date'); ?></th>
                            <th><?php pll_e('Order ID'); ?></th>
                            <th><?php pll_e('Order Value'); ?></th>
                            <th><?php pll_e('View'); ?></th>
                        </tr>

                        <?php
                        foreach ($orders as $order) {
                            $currency = $order->data['currency'];
                            $value = $order->data['total'];
                            $order_id = $order->id;
                            $date = get_post_field('post_date', $order_id);
                        ?>
                            <tr class="sbwcrma_order_data">
                                <td><input type="checkbox" class="sbwcrma_select_prod" product-id="<?php echo $order_id; ?>"></td>
                                <td><?php echo date('j F Y', strtotime($date)); ?></td>
                                <td><?php echo $order_id; ?></td>
                                <td><?php echo $currency.' '.$value; ?></td>
                                <td><a href="javascript:void(0)" rel="noopener noreferrer" product-id="<?php echo $order_id; ?>"><?php pll_e ('View Details'); ?></a></td>
                            </tr>

                        <?php }
                        ?>
                    </table>
                <?php } else { ?>
                    <p><?php pll_e('You have not placed any orders yet.'); ?></p>
                <?php }


                ?>
            </div>

            <!-- submitted returns -->
            <div id="sbwcrma_submitted_returns">
            </div>

        </div>
<?php }


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
