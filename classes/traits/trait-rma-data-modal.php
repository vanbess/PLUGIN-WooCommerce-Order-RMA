<?php

/* Trait which renders RMA data modal */

trait SBWCRMA_Data_Modal {

    public static function rma_data_modal($rma_id) {

        // get post meta
        $rma_meta = get_post_meta($rma_id);
?>
        <!-- overlay -->
        <div class="sbwcrma_data_overlay" id="sbwcrma_data_overlay_<?php echo $rma_id; ?>" style="display: none;"></div>

        <!-- modal -->
        <div class="sbwcrma_data_modal" id="sbwcrma_data_modal_<?php echo $rma_id; ?>" style="display: none;">

            <!-- close/dismiss -->
            <a class="sbwcrma_data_modal_close" href="javascript:void(0)">x</a>

            <h1><?php pll_e('Current data for this return request:'); ?></h1>

            <?php

            // generate meta data array
            $meta_arr = [];

            // loop to pupolate $meta_arr with data
            foreach ($rma_meta as $key => $value) {

                // change key names to readable format
                switch ($key) {
                    case $key == 'sbwcrma_order_id':
                        $key = 'Order ID';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_user_email':
                        $key = 'Your email address';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_user_name':
                        $key = 'User name';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_customer_location':
                        $key = 'Delivery address';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_reason':
                        $key = 'Reason for return';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_products':
                        $key = 'Products';
                        $meta_arr[$key] = '';
                        break;
                    case $key == 'sbwcrma_status':
                        $key = 'Return status';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_warehouse':
                        $key = 'Return warehouse';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_shipping_co':
                        $key = 'Shipping company';
                        $meta_arr[$key] = $value[0];
                        break;
                    case $key == 'sbwcrma_tracking_no':
                        $key = 'Tracking number';
                        $meta_arr[$key] = $value[0];
                        break;
                    default:
                }
            }

            // loop through and display meta to user
            foreach ($meta_arr as $key => $value) { ?>
                <div class="sbwcrma_data_row">
                    <span class="sbwcrma_data_key"><?php echo $key; ?></span>
                    <span class="sbwcrma_data_val">
                        <?php if ($value != '') {
                            echo ucfirst($value);
                        } elseif ($key == 'Products') {
                            $products = maybe_unserialize(get_post_meta($rma_id, 'sbwcrma_products', true)); ?>
                            <div class="sbwcrma_data_modal_prods_cont">
                                <span><?php pll_e('Product ID'); ?></span>
                                <span><?php pll_e('Product Name'); ?></span>
                                <span><?php pll_e('Qty'); ?></span>
                                <?php foreach ($products as $id => $qty) { ?>
                                    <div class="sbwcrma_rma_modal_prod_row">
                                        <span><?php echo $id; ?></span>
                                        <span><?php echo get_the_title($id); ?></span>
                                        <span><?php echo $qty; ?></span>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } else {
                            pll_e('Not specified');
                        } ?>
                    </span>
                </div>
            <?php }

            // check rma status; if 'instructions sent', display button for user to add shipping details for package
            $rma_status = get_post_meta($rma_id, 'sbwcrma_status', true);

            if ($rma_status == 'instructions sent' && get_post_meta($rma_id, 'sbwcrma_warehouse', true)) { ?>
                <a class="sbwcrma_submit_ship_data" href="javascript:void(0)"><?php pll_e('Already shipped? Submit shipping data'); ?></a>

                <div class="sbwcrma_ship_data" style="display: none;">
                    <!-- shipping company -->
                    <label for="sbwcrma_ship_co"><?php pll_e('Specify shipping company:*'); ?></label>
                    <input type="text" id="sbwcrma_ship_co">

                    <!-- tracking number -->
                    <label for="sbwcrma_ship_track_no"><?php pll_e('Specify package tracking number:*'); ?></label>
                    <input type="text" id="sbwcrma_ship_track_no">

                    <!-- shipping data error -->
                    <span class="shipp_error" style="display: none;"><?php pll_e ('Please provide all required shipping data.'); ?></span>

                    <!-- submit shipping data -->
                    <a class="sbwcrma_submit_shipp_data" rma-id="<?php echo $rma_id; ?>" href="javascript:void(0)"><?php pll_e ('Submit shipping data'); ?></a>
                </div>
            <?php }

            ?>

        </div>

<?php }
}
