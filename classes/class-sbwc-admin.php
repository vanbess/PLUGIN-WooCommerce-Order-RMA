<?php

/**
 * Class to render admin options
 */

class SBWC_Admin
{

   /**
    * Class init
    */
   public static function init()
   {
      // add settings page
      add_submenu_page('edit.php?post_type=product', 'RMA Settings', 'RMA Settings', 'manage_options', 'sbwc-rma-settings', [__CLASS__, 'rma_settings']);

      // scripts
      add_action('admin_head', self::rma_css_js());

      // cpt meta boxes and saving of data
      add_action('admin_init', [__CLASS__, 'rma_metabox']);
      add_action('save_post', [__CLASS__, 'rma_data_save'], 10, 2);

      // ajax
      add_action('wp_ajax_nopriv_rma_ajax', [__CLASS__, 'rma_ajax']);
      add_action('wp_ajax_rma_ajax', [__CLASS__, 'rma_ajax']);
   }

   /**
    * Settings page content
    */
   public static function rma_settings()
   { ?>

      <div id="sbwcrma_settings_cont">

         <h1><?php pll_e('RMA Settings'); ?></h1>

         <!-- rma email addresses -->
         <div class="sbwcrma_input_cont">
            <label class="sbwcrma_admin_labels" for="sbwcrma_emails">
               <?php pll_e('List of email addresses, separated by commas, to which RMA submission emails should be sent:'); ?>
            </label>
            <input type="text" name="sbwcrma_emails" id="sbwcrma_emails" placeholder="<?php pll_e('email address 1, email address 2 etc'); ?>">
         </div>

         <!-- save settings -->
         <div id="sbwcrma_settings_submit">
            <a class="sbwcrma_save_settings" href="javascript:void(0)"><?php pll_e('Save Settings'); ?></a>
         </div>

      </div>

   <?php }

   /**
    * Add RMA post type metabox
    */
   public static function rma_metabox()
   {
      add_meta_box('rma_meta_box', 'RMA Data', [__CLASS__, 'rma_metabox_data'], 'rma', 'normal', 'high');
   }

   /**
    * Display RMA post type metabox data
    */
   public static function rma_metabox_data()
   { ?>
      <!-- 
      rma status: 
      pending OR 
      instructions sent OR 
      items shipped OR 
      pending approval OR 
      approved OR 
      rejected  
      -->
      <div id="sbwcrma_status_cont" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_status"><?php pll_e('RMA Status'); ?></label>
         <select name="sbwcrma_status" id="sbwcrma_status">
            <option value="pending"><?php pll_e('Pending'); ?></option>
            <option value="instructions sent"><?php pll_e('Instructions sent'); ?></option>
            <option value="items shipped"><?php pll_e('Items shipped'); ?></option>
            <option value="pending approval"><?php pll_e('Pending approvial'); ?></option>
            <option value="approved"><?php pll_e('Approved'); ?></option>
            <option value="rejected"><?php pll_e('Rejected'); ?></option>
         </select>
      </div>

      <!-- order and rma id -->
      <div id="sbwcrma_order_rma_id" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_order_id"><?php pll_e('Order ID'); ?></label>
         <input type="text" name="sbwcrma_order_id" id="sbwcrma_order_id">
         <br>
         <label for="sbwrma_rma_id"><?php pll_e ('RMA ID'); ?></label>
         <input type="text" name="sbwrma_rma_id" id="sbwrma_rma_id" readonly="true">
      </div>

      <!-- rma reason -->
      <div id="sbwcrma_request_reason" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_reason"><?php pll_e('Reason for RMA request'); ?></label>
         <textarea name="sbwcrma_reason" id="sbwcrma_reason" cols="30" rows="10"></textarea>
      </div>

      <!-- client data -->
      <div id="sbwcrma_client_data" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_user_name"><?php pll_e('Client name'); ?></label>
         <input type="text" name="sbwcrma_user_name" id="sbwcrma_user_name">
         <br>
         <label for="sbwcrma_user_email"><?php pll_e('Client email address'); ?></label>
         <input type="email" name="sbwcrma_user_email" id="sbwcrma_user_email">
         <br>
         <label for="sbwcrma_customer_location"><?php pll_e('Customer location'); ?></label>
         <textarea name="sbwcrma_customer_location" id="sbwcrma_customer_location" cols="30" rows="10"></textarea>
      </div>

      <!-- rma products -->
      <div id="sbwcrma_products_cont" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_products"><?php pll_e('RMA Products'); ?></label>
         <table>
            <tr>
               <th><?php pll_e('Product ID'); ?></th>
               <th><?php pll_e('Product Name'); ?></th>
               <th><?php pll_e('RMA Qty'); ?></th>
            </tr>
            <tr>
               <td>1234</td>
               <td>Prod name here</td>
               <td>RMA qty here</td>
            </tr>
         </table>
      </div>

      <!-- rma shipping dets -->
      <div id="sbwcrma_shipping_dets" class="sbwcrma_metadata_bits">
         <label for="sbwcrma_warehouse"><?php pll_e('Specify warehouse to which RMA items should be sent'); ?></label>
         <input type="text" name="sbwcrma_warehouse" id="sbwcrma_warehouse">
         <br>
         <label for="sbwcrma_shipping_co"><?php pll_e('Shipping company'); ?></label>
         <input type="text" name="sbwcrma_shipping_co" id="sbwcrma_shipping_co">
         <br>
         <label for="sbwcrma_tracking_no"><?php pll_e('RMA shipment tracking number'); ?></label>
         <input type="text" name="sbwcrma_tracking_no" id="sbwcrma_tracking_no">
         <br>
      </div>

      <!-- rma actions -->
      <div id="sbwcrma_actions" class="sbwcrma_metadata_bits">

         <!-- send instructions to customer -->
         <a id="sbwcrma_send_instructions" href="javascript:void(0)" title="<?php pll_e('Send RMA instructions to customer'); ?>">
            <?php pll_e('Send RMA Instructions'); ?>
         </a>

         <!-- reject rma with reasons -->
         <a id="sbwcrma_reject" href="javascript:void(0)"><?php pll_e('Reject RMA Request'); ?></a>

         <!-- approve rma request -->
         <a id="sbwcrma_approve" href="javascript:void(0)"><?php pll_e('Approve RMA Request'); ?></a>

      </div>

   <?php }

   /**
    * Save RMA custom data if needed
    */
   public static function rma_data_save($post_id, $post)
   {
      if ($post->post_type == 'rma') {
         if (isset($_POST['meta'])) {
            foreach ($_POST['meta'] as $key => $value) {
               update_post_meta($post_id, $key, $value);
            }
         }
      }
   }


   /**
    * CSS and JS
    */
   public static function rma_css_js()
   { ?>

      <!-- js -->
      <script>
      </script>

      <!-- css -->
      <style>
         label.sbwcrma_admin_labels {
            display: block;
            font-size: 14px;
            font-weight: 500;
            padding: 7px 2px;
         }

         input#sbwcrma_emails {
            display: block;
            width: 100%;
            margin-bottom: 15px;
         }

         a.sbwcrma_save_settings {
            display: block;
            background: #0073aa;
            color: white;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            line-height: 2;
            font-size: 16px;
            font-weight: 500;
         }

         div#sbwcrma_settings_cont {
            width: 40%;
            min-width: 360px;
         }
      </style>

<?php }

   /**
    * Ajax to save/update settings
    */
   public static function rma_ajax()
   {

      wp_die();
   }
}

SBWC_Admin::init();
