<?php

/* Trait which renders RMA data modal */

trait SBWCRMA_Data_Modal {

    public static function rma_data_modal($rma_id) {

        // get post meta
        $rma_meta = get_post_meta($rma_id);

?>

        <!-- overlay -->
        <div class="sbwcrma_data_overlay"></div>

        <!-- modal -->
        <div class="sbwcrma_data_modal">

            <!-- close/dismiss -->
            <a class="sbwcrma_data_modal_close" href="javascript:void(0)">x</a>

            <h1><?php pll_e('Current data for this return request:'); ?></h1>

            <?php
            foreach ($rma_meta as $key => $value) {

                // change key names to readable format
                switch ($key) {
                    case $key == 'sbwcrma_order_id':
                        $key = 'Order ID';
                        break;
                    case $key == 'sbwcrma_user_email':
                        $key = 'Your email address';
                        break;
                    case $key == 'sbwcrma_user_name':
                        $key = 'User name';
                        break;
                    case $key == 'sbwcrma_customer_location':
                        $key = 'Delivery address';
                        break;
                    case $key == 'sbwcrma_reason':
                        $key = 'Reason for return';
                        break;
                    case $key == 'sbwcrma_products':
                        $key = 'Products';
                        break;
                    case $key == 'sbwcrma_status':
                        $key = 'Return status';
                        break;
                    default:
                } ?>

                <div class="sbwcrma_data_row">
                    <span class="sbwcrma_data_key"><?php echo $key; ?></span>
                    <span class="sbwcrma_data_val"><?php echo $value[0]; ?></span><br>
                </div>

            <?php }

            ?>

        </div>

<?php }
}
