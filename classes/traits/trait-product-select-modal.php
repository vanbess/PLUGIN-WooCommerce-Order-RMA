<?php

/**
 * Shows product select modal
 */

trait SBWCRMA_Prod_Select_Modal
{
    /**
     * Display RMA product select modal
     */
    private static function display_modal($order_id)
    {
        $order_data = wc_get_order($order_id);
        $products = $order_data->get_items();
?>

        <!-- modal overlay -->
        <div class="sbwcrma_prod_select_modal_overlay" order-id="<?php print $order_id; ?>" style="display: none;"></div>

        <!-- modal actual -->
        <div class="sbwcrma_prod_select_modal" order-id="<?php print $order_id; ?>" style="display: none;">

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
                    foreach ($products as $product) { ?>
                        <tr>
                            <td><input class="sbwcrma_prod_checkbox" type="checkbox" prod-id="<?php echo $product->get_id(); ?>"></td>
                            <td><?php pll_e($product->get_name()); ?></td>
                            <td><?php print $product->get_quantity(); ?></td>
                            <td>
                                <select class="sbwcma_prod_qty">
                                    <?php $max_qty = $product->get_quantity();
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

            <p class="sbwcrma_required_prods" style="display: none;"><?php pll_e ('Select at least one product.'); ?></p>

            <!-- return reason -->
            <div>
                <label for="sbwcrma_return_reason_<?php echo $order_id ?>"><?php pll_e('Specify reason for return request (required):'); ?></label>
                <textarea class="sbwcrma_return_reason" id="sbwcrma_return_reason_<?php echo $order_id ?>" cols="30" rows="10"></textarea>
                <p class="sbwcrma_required" style="display: none;"><?php pll_e ('Please provide a reason for this return.'); ?></p>
            </div>

            <!-- submit rma -->
            <a class="sbwcrma_submit_return" order-id="<?php echo $order_id; ?>" href="javascript:void(0)">
                <?php pll_e('Submit Return Request'); ?>
            </a>

        </div>
<?php }
}
