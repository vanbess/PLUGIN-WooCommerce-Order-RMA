<?php

/* Renders admin modals which are used for managing RMAs via admin */

trait SBWCRMA_Admin_Modals {

    /**
     * Send RMA instructions
     */
    public static function rma_instructions() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- ship to warehouse -->
            <label for="sbwcrma_warehouse"><?php pll_e('Ship to warehouse:'); ?></label>
            <input id="sbwcrma_warehouse" type="text" value="">

            <!-- message/instructions -->
            <label for="sbwcrma_instructions"><?php pll_e('Instructions to client:'); ?></label>
            <textarea id="sbwcrma_instructions" cols="30" rows="10"><?php pll_e('Add your message to the client here'); ?></textarea>

            <a class="sbwcrma_send_instructions" href="javascript:void(0)"><?php pll_e('Send instructions'); ?></a>
        </div>
    <?php }

    /**
     * Approve RMA with optional reason
     */
    public static function approve_rma() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- approval message to client (optional at this stage) -->
            <label for="sbwcrma_approval_message"><?php pll_e('Message to client (optional):'); ?></label>
            <textarea id="sbwcrma_approval_message" cols="30" rows="10"><?php pll_e('Add your message to the client here'); ?></textarea>

            <a class="sbwcrma_approve" href="javascript:void(0)"><?php pll_e('Approve RMA'); ?></a>
        </div>

    <?php }

    /**
     * Reject RMA with required reasons
     */
    public static function reject_rma() { ?>

        <!-- overlay -->
        <div class="sbwcrma_admin_overlay"></div>

        <!-- data -->
        <div class="sbwcrma_admin_modal">
            <!-- modal close -->
            <a class="sbwcrma_admin_modal_close" href="javascript:void(0)">x</a>

            <!-- rejection message to client (required at this stage) -->
            <label for="sbwcrma_rejection_message"><?php pll_e('Message to client (required):'); ?></label>
            <textarea id="sbwcrma_rejection_message" cols="30" rows="10"><?php pll_e('Add your message to the client here'); ?></textarea>

            <a class="sbwcrma_reject" href="javascript:void(0)"><?php pll_e('Approve RMA'); ?></a>
        </div>
<?php }
}
