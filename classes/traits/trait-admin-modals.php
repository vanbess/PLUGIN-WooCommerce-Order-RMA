<?php

/* Renders admin modals which are used for managing RMAs via admin */

trait SBWCRMA_Admin_Modals {

    /**
     * Send RMA instructions
     */
    public static function rma_instructions() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay" id="sbwcrma_instructions_overlay" style="display:none;"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal" id="sbwcrma_instructions_modal" style="display:none;">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- message/instructions -->
            <?php if (get_post_meta(get_the_ID(), 'sbwcrma_status', true) == 'pending') { ?>

                <!-- ship to warehouse -->
                <label for="sbwcrma_warehouse"><?php pll_e('Ship to warehouse:'); ?></label>
                <input id="sbwcrma_warehouse" type="text" value="" readonly>

                <label for="sbwcrma_instructions"><?php pll_e('Instructions to client:'); ?></label>
                <textarea id="sbwcrma_instructions" cols="30" rows="10" placeholder="<?php pll_e('Add your message to the client here'); ?>"></textarea>
                <a class="sbwcrma_send_instructions" rma-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)"><?php pll_e('Send instructions'); ?></a>
                
            <?php } else { ?>
                <p class="sbwcrma_done"><?php pll_e('Instructions have already been sent to this client.'); ?></p>
            <?php } ?>
        </div>
    <?php }

    /**
     * RMA received and in the process of being reviewed
     */
    public static function reviewing_rma() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay" id="sbwcrma_review_overlay" style="display:none;"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal" id="sbwcrma_review_modal" style="display:none;">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- approval message to client (optional at this stage) -->
            <label for="sbwcrma_review_message"><?php pll_e('Message to client (optional):'); ?></label>
            <textarea id="sbwcrma_review_message" cols="30" rows="10" placeholder="<?php pll_e('Add your message to the client here'); ?>"></textarea>

            <a class="sbwcrma_review" rma-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)"><?php pll_e('Send RMA under review message to client'); ?></a>
        </div>

    <?php }

    /**
     * Approve RMA with optional reason
     */
    public static function approve_rma() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay" id="sbwcrma_approve_overlay" style="display:none;"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal" id="sbwcrma_approve_modal" style="display:none;">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- approval message to client (optional at this stage) -->
            <label for="sbwcrma_approval_message"><?php pll_e('Message to client (optional):'); ?></label>
            <textarea id="sbwcrma_approval_message" cols="30" rows="10" placeholder="<?php pll_e('Add your message to the client here'); ?>"></textarea>

            <a class="sbwcrma_approve" rma-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)"><?php pll_e('Approve RMA'); ?></a>
        </div>

    <?php }

    /**
     * Reject RMA with required reasons
     */
    public static function reject_rma() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay" id="sbwcrma_reject_overlay" style="display:none;"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal" id="sbwcrma_reject_modal" style="display:none;">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- rejection message to client (required at this stage) -->
            <label for="sbwcrma_rejection_message"><?php pll_e('Message to client (required):'); ?></label>
            <textarea id="sbwcrma_rejection_message" cols="30" rows="10" placeholder="<?php pll_e('Add your message to the client here'); ?>"></textarea>

            <a class="sbwcrma_reject" rma-id="<?php echo get_the_ID(); ?>" href="javascript:void(0)"><?php pll_e('Reject RMA'); ?></a>
        </div>
<?php }
}
