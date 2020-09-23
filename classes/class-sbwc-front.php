<?php

/**
 * Class to render user dashboard options on front
 * Extends SBWC_Frontend_Scripts
 */

class SBWCRMA_Front extends SBWCRMA_Frontend_Scripts {

    use SBWCRMA_Prod_Select_Modal;

    /**
     * Class init
     */
    public static function init() {
        // add my account menu item
        add_filter('woocommerce_account_menu_items', [__CLASS__, 'sbwcrma_menu_item'], 40);

        // add rewrite endpoints
        add_action('init', [__CLASS__, 'sbwcrma_endpoints']);

        // display RMA/returns data
        add_action('woocommerce_account_returns_endpoint', [__CLASS__, 'sbwcrma_acc_content']);

        // rma ajax
        add_action('wp_ajax_nopriv_sbwcrma_submit', [__CLASS__, 'sbwcrma_submit']);
        add_action('wp_ajax_sbwcrma_submit', [__CLASS__, 'sbwcrma_submit']);
    }

    /**
     * Add my account menu item for RMAs
     */
    public static function sbwcrma_menu_item($menu_links) {
        $menu_links = array_slice($menu_links, 0, 5, true)
            + array('returns' => 'Returns')
            + array_slice($menu_links, 5, NULL, true);

        return $menu_links;
    }

    /**
     * Add rewrite endpoints for menu item
     */
    public static function sbwcrma_endpoints() {
        add_rewrite_endpoint('returns', EP_PAGES);

        // flush rewrite rules to affect changes
        flush_rewrite_rules();
    }

    /**
     * Display RMA page content
     */
    public static function sbwcrma_acc_content() { ?>
        <!-- rma outer container -->
        <div id="sbwcrma_acc_container">

            <!-- links -->
            <div id="sbwcrma_nav_cont">
                <a id="sbwcrma_submit_show" class="sbwcrma_active" href="javascript:void(0)"><?php pll_e('Submit return request'); ?></a>
                <a id="sbwcrma_returns_show" href="javascript:void(0)"><?php pll_e('Submitted returns'); ?></a>
            </div>

            <!-- orders list and submit return -->
            <div id="sbwcrma_submit_returns">
                <?php
                // get current user id
                $user_id = get_current_user_id();

                // get user orders
                $orders = wc_get_orders(['customer_id' => $user_id]);

                // if orders present list them, else display error message
                if ($orders && is_array($orders) || is_object($orders)) { ?>
                    <p><?php pll_e('Select the order below you would like to log a return for.'); ?></p>

                    <!-- user orders list -->
                    <table id="sbwcrma_order_list">
                        <thead>
                            <tr>
                                <th><?php pll_e('Order ID'); ?></th>
                                <th><?php pll_e('Order Date'); ?></th>
                                <th><?php pll_e('Order Value'); ?></th>
                                <th><?php pll_e('Select Products'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // loop through orders
                            foreach ($orders as $order) {
                                $currency = $order->data['currency'];
                                $value = $order->data['total'];
                                $order_id = $order->id;
                                $date = get_post_field('post_date', $order_id);
                            ?>
                                <tr class="sbwcrma_order_data">
                                    <td><?php echo $order_id; ?></td>
                                    <td><?php echo date('j F Y', strtotime($date)); ?></td>
                                    <td><?php echo $currency . ' ' . $value; ?></td>
                                    <td>
                                        <?php
                                        // check whether RMA has already been submitted for order; display RMA submitted message if true, else display product select
                                        // modal link
                                        if (get_post_meta($order_id, 'sbwcrma_request_submitted', true) && get_post_meta($order_id, 'sbwcrma_request_submitted', true) == 'yes') : ?>
                                            <span class="sbwcrma_submitted"><?php pll_e('Return submitted'); ?></span>
                                        <?php else : ?>
                                            <a class="sbwcrma_prod_modal_show" href="javascript:void(0)" order-id="<?php echo $order_id; ?>"><?php pll_e('Click to Select'); ?></a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php
                    // product select modal
                    foreach ($orders as $order) {
                        $order_id = $order->id;
                        self::display_modal($order_id);
                    }
                } else { ?>
                    <p><?php pll_e('You have not placed any orders yet.'); ?></p>
                <?php }
                ?>
            </div><!-- #sbwcrma_acc_container end -->

            <!-- submitted returns -->
            <div id="sbwcrma_submitted_returns" style="display: none;">

                <?php

                $rmas = new WP_Query([
                    'post_type' => 'rma',
                    'post_status' => 'publish',
                    'post_author' => $user_id,
                    'posts_per_page' => -1
                ]);

                if ($rmas->have_posts()) { ?>

                    <p class="sbwcrma_list">
                        <?php pll_e('Below is a list of returns you have submitted.'); ?>
                    </p>

                    <table>
                        <tr>
                            <th><?php pll_e('Return ID'); ?></th>
                            <th><?php pll_e('Submitted On'); ?></th>
                            <th><?php pll_e('Status'); ?></th>
                            <th><?php pll_e('View Details'); ?></th>
                        </tr>
                        <?php while ($rmas->have_posts()) {
                            $rmas->the_post(); ?>
                            <tr>
                                <td><?php echo get_the_ID(); ?></td>
                                <td><?php echo get_the_date('j F Y'); ?></td>
                                <td><?php echo get_post_meta(get_the_ID(), 'sbwcrma_status', true); ?></td>
                                <td>
                                    <a class="sbwcrma_view_rma_dets" href="javascript:void(0)"><?php pll_e('View Details'); ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php
                    wp_reset_postdata();
                } else { ?>
                    <p class="sbwcrma_no_returns">
                        <?php pll_e('There are no returns on record for your account.'); ?>
                    </p>
                <?php }

                ?>

            </div>

        </div>
<?php
        // enqueues
        wp_enqueue_script('jquery');
        wp_enqueue_style('sbwc-front-css');
        wp_enqueue_script('sbwc-front-js');
    }


    /**
     * Submit RMA request (user side)
     */
    public static function sbwcrma_submit() {

        if (isset($_POST['prod_ids'])) {

            // submitted data
            $prod_ids = $_POST['prod_ids'];
            $prod_qtys = $_POST['prod_qtys'];
            $combined = array_combine($prod_ids, $prod_qtys);
            $order_id = $_POST['order_id'];
            $rma_reason = $_POST['rma_reason'];

            // user data
            $order_data = wc_get_order(($order_id));
            $user_email = $order_data->get_billing_email();
            $user_fname = $order_data->get_billing_first_name();
            $user_lname = $order_data->get_billing_last_name();

            // shipping data
            $shipp_address = $order_data->get_formatted_shipping_address();

            // user id
            $user_id = $order_data->get_customer_id();

            //if $prod_ids/$prod_qtys not empty, insert RMA post, else throw error
            if (is_array($combined) && !empty($combined)) {

                // insert order meta to show whether or not RMA has already been submitted for order
                add_post_meta($order_id, 'sbwcrma_request_submitted', 'yes');

                // insert RMA
                $rma_inserted  = wp_insert_post([
                    'post_type' => 'rma',
                    'post_status' => 'publish',
                    'post_author' => $user_id,
                    'post_title' => pll__('RMA request submitted by ') . $user_fname . ' ' . $user_lname,
                    'meta_input' => [
                        'sbwcrma_order_id' => $order_id,
                        'sbwcrma_user_email' => $user_email,
                        'sbwcrma_user_name' => $user_fname . ' ' . $user_lname,
                        'sbwcrma_customer_location' => $shipp_address,
                        'sbwcrma_reason' => $rma_reason,
                        'sbwcrma_products' => maybe_serialize($combined),
                        'sbwcrma_status' => 'Pending'
                    ]
                ]);

                if ($rma_inserted) {
                    pll_e('Thank you. One of our staff members will review and process your request.');
                } else {
                    pll_e('Return submission failed. Please reload the page and try again.');
                }
            } elseif (empty($combined)) {
                pll_e('Please select at least one product to return.');
            }
        }
        wp_die();
    }


    /**
     * Send CS email
     */
    public static function sbwcrma_send_email() {
    }
}
SBWCRMA_Front::init();
