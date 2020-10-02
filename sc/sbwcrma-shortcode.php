<?php

/** Shortcode to render RMA functionality on the frontend for non-registered buyers/clients */
add_shortcode('sbwcrma_sc', 'sbwcrma_sc');

/* css */
add_action('wp_head', 'sbwcrma_noreg_css');

function sbwcrma_noreg_css() { ?>
    <style>
        div#sbwcrma_noreg_email_input {
            overflow: auto;
            width: 50vw;
            margin: 0 auto;
            min-width: 360px;
            padding: 30px 0;
        }

        p.sbwcrma_noreg_note {
            background: #efefef;
            padding: 15px;
            margin-bottom: 30px;
        }

        p.sbwcrma_noreg_error {
            background: #fff3ea;
            padding: 15px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        div#sbwcrma_noreg_label,
        div#sbcrma_noreg_submit {
            width: 20%;
            float: left;
        }

        div#sbwcrma_noreg_input {
            width: 60%;
            float: left;
        }

        div#sbwcrma_noreg_label label {
            position: relative;
            top: 7px;
            font-size: 16px;
        }

        div#sbcrma_noreg_submit>button {
            color: white;
            background: #0c0c0c;
            width: 100%;
            margin: 0;
        }

        form#sbwcrma_noreg_email_form {
            display: block;
            overflow: auto;
        }

        div#sbwcrma_noreg_login .button {
            width: 100%;
        }

        .sbwcrma_prod_select_modal.no_reg {
            top: 0;
            left: 31%;
        }

        .sbwcrma_prod_select_modal.no_reg th {
            text-align: center;
        }

        .sbwcrma_prod_select_modal.no_reg label {
            text-align: left;
        }

        .sbwcrma_prod_select_modal.no_reg a {
            color: white !important;
            text-decoration: none !important;
        }

        .sbwcrma_prod_select_modal.no_reg a:first-child {
            line-height: 1.4;
        }

        .sbwcrma_prod_select_modal.no_reg td {
            text-align: center;
        }
    </style>
<?php }

/* js */
add_action('wp_footer', 'sbwcrma_noreg_js');

function sbwcrma_noreg_js() { ?>

    <script>
        jQuery(document).ready(function($) {

        });
    </script>

<?php }

/* order product select modal */
function sbwcrma_noreg_prod_select_modal($order_id) {

    $order_data = wc_get_order($order_id);
    $products = $order_data->get_items();
?>
    <!-- modal overlay -->
    <div class="sbwcrma_prod_select_modal_overlay" order-id="<?php print $order_id; ?>" style="display: none;"></div>

    <!-- modal actual -->
    <div class="sbwcrma_prod_select_modal no_reg" order-id="<?php print $order_id; ?>" style="display: none;">

        <!-- close modal link -->
        <a class="sbwcrma_modal_close" href="javascript:void(0)" title="<?php pll_e('Cancel'); ?>">x</a>

        <!-- instructions -->
        <p class="sbwcrma_instructions">
            <?php pll_e('Select the product(s) and product quantities you would like to return:'); ?>
        </p>

        <!-- product select table -->
        <table class="sbwcrma_prod_select_table">
            <thead>
                <tr>
                    <th><?php pll_e('Select'); ?></th>
                    <th><?php pll_e('Product'); ?></th>
                    <th><?php pll_e('Ordered QTY'); ?></th>
                    <th><?php pll_e('Return QTY'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // loop to display products
                foreach ($products as $order_line_prod_id => $prod_data) {

                    //decode prod data (it is a JSON string)
                    $prod_data = json_decode($prod_data, true);

                    // get correct product id
                    if (!empty($prod_data['variation_id'])) {
                        $prod_id = $prod_data['variation_id'];
                    } else {
                        $prod_id = $prod_data['product_id'];
                    }
                ?>
                    <tr>
                        <td>
                            <input class="sbwcrma_prod_checkbox" type="checkbox" prod-id="<?php echo $prod_id; ?>" target="#sbwcma_prod_qty_<?php echo $prod_id; ?>">
                        </td>
                        <td><?php pll_e($prod_data['name']); ?></td>
                        <td><?php echo $prod_data['quantity']; ?></td>
                        <td>
                            <select id="sbwcma_prod_qty_<?php echo $prod_id; ?>">
                                <?php $max_qty = $prod_data['quantity'];
                                for ($i = 1; $i <= $max_qty; $i++) { ?>
                                    <option value="<?php print $i ?>"><?php print $i; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                <?php }
                ?>
            </tbody>
        </table>

        <p class="sbwcrma_required_prods" style="display: none; text-align: left;"><?php pll_e('Select at least one product.'); ?></p>

        <!-- return reason -->
        <div>
            <label for="sbwcrma_return_reason_<?php echo $order_id ?>"><?php pll_e('Specify reason for return request (required):'); ?></label>
            <textarea class="sbwcrma_return_reason" id="sbwcrma_return_reason_<?php echo $order_id ?>" cols="30" rows="10"></textarea>
            <p class="sbwcrma_required" style="display: none; text-align: left;"><?php pll_e('Please provide a reason for this return.'); ?></p>
        </div>

        <!-- submit rma -->
        <a class="sbwcrma_submit_return" order-id="<?php echo $order_id; ?>" href="javascript:void(0)">
            <?php pll_e('Submit Return Request'); ?>
        </a>
    <?php }

/* rma status modal */
function sbwcrma_noreg_rma_status_modal($rma_id) {
}

/* submitted returns list */
function sbwcrma_noreg_submitted_rmas() { ?>

        <!-- submitted returns -->
        <div id="sbwcrma_submitted_returns" style="display: none;">

            <?php

            $rmas = new WP_Query([
                'post_type' => 'rma',
                'post_status' => 'publish',
                'meta_key' => 'sbwcrma_user_email',
                'meta_value' => $_GET['sbwcrma_noreg_email'],
                'posts_per_page' => -1
            ]);

            if ($rmas->have_posts()) { ?>

                <p class="sbwcrma_list">
                    <?php pll_e('Below is a list of returns you have submitted.<br><b>If you have already shipped a return, click on the <u>View Details</u> link to add the associated shipping data (shipping company and package tracking number) so that our staff members can keep track of the progress of your return.</b>'); ?>
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
                            <td><?php echo ucfirst(get_post_meta(get_the_ID(), 'sbwcrma_status', true)); ?></td>
                            <td>
                                <a class="sbwcrma_view_rma_dets" rma-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)"><?php pll_e('View Details'); ?></a>
                            </td>
                        </tr>
                    <?php
                        // rma data modal
                        sbwcrma_noreg_rma_status_modal(get_the_ID());
                    } ?>
                </table>
            <?php
                wp_reset_postdata();
            } else { ?>
                <p class="sbwcrma_no_returns" style="padding: 15px;">
                    <?php pll_e('There are no returns on record for your account.'); ?>
                </p>
            <?php }

            ?>

        </div><!-- #sbwcrma_submitted_returns -->

    <?php }


/* display orders */
function sbwcrma_noreg_display_orders($orders) { ?>

        <div id="sbwcrma_noreg_orders_cont">

            <!-- links -->
            <div id="sbwcrma_nav_cont">
                <a id="sbwcrma_submit_show" class="sbwcrma_active" href="javascript:void(0)"><?php pll_e('Submit return request'); ?></a>
                <a id="sbwcrma_returns_show" href="javascript:void(0)"><?php pll_e('Submitted returns'); ?></a>
            </div>

            <div id="sbwcrma_submit_returns">

                <p class="sbwcrma_noreg_note"><?php pll_e('Select the order below you would like to log a return for.'); ?></p>

                <div class="sbwcrma_no_reg_orders_list_cont">
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
                                // delete_post_meta($order_id, 'sbwcrma_request_submitted');
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
                                        <?php sbwcrma_noreg_prod_select_modal($order_id);
                                        endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <?php sbwcrma_noreg_submitted_rmas();
    }

    /* core/render email address input */
    function sbwcrma_sc() {

        // check if user is logged in and redirect to dashboard if true, else display email address input
        if (is_user_logged_in()) {
            wp_redirect('my-account');
        } else { ?>

            <div id="sbwcrma_noreg_email_input">
                <p class="sbwcrma_noreg_note">
                    <?php pll_e('Looking to return an item or items? You can begin the process by entering your email address below so that we can retrieve a list of orders you have placed with us.'); ?>
                </p>

                <form id="sbwcrma_noreg_email_form" action="" method="get">
                    <div id="sbwcrma_noreg_label">
                        <label for="sbwcrma_noreg_email"><?php pll_e('Your email address:'); ?></label>
                    </div>
                    <div id="sbwcrma_noreg_input">
                        <input type="email" name="sbwcrma_noreg_email" placeholder="your@email.com" required value="<?php echo $_GET['sbwcrma_noreg_email']; ?>">
                    </div>
                    <div id="sbcrma_noreg_submit">
                        <button type="submit">Submit</button>
                    </div>
                </form>
                <?php

                /*check submitted email address against registered email addresses; 
            /*if found, redirect user to my account/login page, else retrieve orders tied to email address*/
                if (isset($_GET['sbwcrma_noreg_email'])) {

                    // get submitted email address
                    $usermail = $_GET['sbwcrma_noreg_email'];

                    // if user email is registered, redirect to my account page, else continue
                    if (get_user_by('email', $usermail)) { ?>
                        <p class="sbwcrma_noreg_error">
                            <?php pll_e('According to our customer database a user with that email address is already registered. Please log in using the form below to continue.'); ?>
                        </p>
                        <div id="sbwcrma_noreg_login">
                            <?php wp_login_form(); ?>
                        </div>
                        <?php } else {

                        // get orders by billing_email
                        $orders = wc_get_orders([
                            'billing_email' => $usermail,
                            'limit' => -1,
                            'status' => 'completed'
                        ]);

                        // only go further if orders actually present for email address
                        if (!empty($orders) && is_array($orders) || is_object($orders)) {
                            sbwcrma_noreg_display_orders($orders);
                        } else { ?>
                            <div>
                                <p class="sbwcrma_noreg_error">
                                    <?php pll_e('No orders were found for the supplied email address. Please make sure you have entered the correct email address and try again.'); ?>
                                </p>
                            </div>
                        <?php } ?>
            </div>
<?php }
                }
            }
        }
