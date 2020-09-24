<?php

/**
 * Class to render admin options
 */

class SBWC_Admin {

   /**
    * Class init
    */
   public static function init() {
      // add settings page
      add_submenu_page('edit.php?post_type=rma', 'RMA Settings', 'RMA Settings', 'manage_options', 'sbwc-rma-settings', [__CLASS__, 'rma_settings']);

      // scripts
      add_action('admin_enqueue_scripts', [__CLASS__, 'rma_register_scripts']);

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
   public static function rma_settings() { ?>

      <div id="sbwcrma_settings_cont">

         <h1><?php pll_e('RMA Settings'); ?></h1>

         <!-- rma email addresses -->
         <div class="sbwcrma_input_cont">
            <label class="sbwcrma_admin_labels" for="sbwcrma_emails">
               <?php pll_e('List of email addresses, separated by commas, to which RMA submission emails should be sent:'); ?>
            </label>
            <input value="<?php print get_option('sbwcrma_emails'); ?>" type="text" name="sbwcrma_emails" id="sbwcrma_emails" placeholder="<?php pll_e('email address 1, email address 2 etc'); ?>">
         </div>

         <!-- save settings -->
         <div id="sbwcrma_settings_submit">
            <a class="sbwcrma_save_settings" href="javascript:void(0)"><?php pll_e('Save Settings'); ?></a>
         </div>

      </div>

   <?php
      wp_enqueue_script('rma_rma_settings_js');
   }

   /**
    * Add RMA post type metabox
    */
   public static function rma_metabox() {
      add_meta_box('rma_meta_box', 'RMA Data', [__CLASS__, 'rma_metabox_data'], 'rma', 'normal', 'high');
   }

   /**
    * Display RMA post type metabox data
    */
   public static function rma_metabox_data() { ?>

      <!-- data column left -->
      <div id="sbwcrma_data_left">

         <!-- order and rma id -->
         <div id="sbwcrma_order_rma_id" class="sbwcrma_metadata_bits">
            <label for="sbwcrma_order_id"><?php pll_e('Order ID'); ?></label>
            <input readonly type="text" name="sbwcrma_order_id" id="sbwcrma_order_id" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_order_id', true); ?>">
            <br>
            <label for="sbwrma_rma_id"><?php pll_e('RMA ID'); ?></label>
            <input type="text" name="sbwrma_rma_id" id="sbwrma_rma_id" readonly="true" value="<?php echo get_the_ID(); ?>">
         </div>

         <!-- client data -->
         <div id="sbwcrma_client_data" class="sbwcrma_metadata_bits">
            <label for="sbwcrma_user_name"><?php pll_e('Client name'); ?></label>
            <input readonly type="text" name="sbwcrma_user_name" id="sbwcrma_user_name" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_user_name', true); ?>">
            <br>
            <label for="sbwcrma_user_email"><?php pll_e('Client email address'); ?></label>
            <input readonly type="email" name="sbwcrma_user_email" id="sbwcrma_user_email" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_user_email', true); ?>">
            <br>
            <label for="sbwcrma_customer_location"><?php pll_e('Client shipping address/location'); ?></label>
            <div id="sbwcrma_customer_location">
               <?php echo get_post_meta(get_the_ID(), 'sbwcrma_customer_location', true); ?>
            </div>
         </div>

         <!-- rma shipping dets -->
         <div id="sbwcrma_shipping_dets" class="sbwcrma_metadata_bits">
            <label for="sbwcrma_warehouse"><?php pll_e('Specify warehouse to which RMA items should be sent'); ?></label>
            <input type="text" name="sbwcrma_warehouse" id="sbwcrma_warehouse" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_warehouse', true); ?>">
            <br>
            <label for="sbwcrma_shipping_co"><?php pll_e('Shipping company'); ?></label>
            <input type="text" name="sbwcrma_shipping_co" id="sbwcrma_shipping_co" readonly="true" placeholder="<?php pll_e('to be completed by client'); ?>" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_shipping_co', true); ?>">
            <br>
            <label for="sbwcrma_tracking_no"><?php pll_e('RMA shipment tracking number'); ?></label>
            <input type="text" name="sbwcrma_tracking_no" id="sbwcrma_tracking_no" readonly="true" placeholder="<?php pll_e('to be completed by client'); ?>" value="<?php echo get_post_meta(get_the_ID(), 'sbwcrma_tracking_no', true); ?>">
            <br>
         </div>
      </div>

      <!-- data column right -->
      <div id="sbwcrma_data_right">
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
               <option value="items received - pending inspection"><?php pll_e('Items received - pending inspection'); ?></option>
               <option value="approved"><?php pll_e('Approved'); ?></option>
               <option value="rejected"><?php pll_e('Rejected'); ?></option>
            </select>
         </div>

         <!-- rma reason -->
         <div id="sbwcrma_request_reason" class="sbwcrma_metadata_bits">
            <label for="sbwcrma_reason"><?php pll_e('Reason for RMA request'); ?></label>
            <textarea name="sbwcrma_reason" readonly id="sbwcrma_reason" cols="30" rows="10"><?php echo get_post_meta(get_the_ID(), 'sbwcrma_reason', true); ?></textarea>
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

               <?php

               // get rma prods
               $rma_prods = maybe_unserialize(get_post_meta(get_the_ID(), 'sbwcrma_products', true));

               // loop through rma products
               foreach ($rma_prods as $prod_id => $qty) {
                  ?>
                  <tr>
                     <td><?php echo $prod_id; ?></td>
                     <td><?php echo get_the_title($prod_id); ?></td>
                     <td><?php echo $qty; ?></td>
                  </tr>
               <?php } ?>

            </table>
         </div>
      </div>

      <!-- rma actions -->
      <div id="sbwcrma_actions">

         <!-- send instructions to customer -->
         <a id="sbwcrma_send_instructions" href="javascript:void(0)" title="<?php pll_e('Send RMA instructions to customer'); ?>">
            <?php pll_e('Send RMA Instructions'); ?>
         </a>

         <!-- approve rma request -->
         <a id="sbwcrma_approve" href="javascript:void(0)"><?php pll_e('Approve RMA Request'); ?></a>

         <!-- reject rma with reasons -->
         <a id="sbwcrma_reject" href="javascript:void(0)"><?php pll_e('Reject RMA Request'); ?></a>

      </div>

   <?php

      // enqueue scripts
      wp_enqueue_style('rma_css');
      wp_enqueue_script('rma_js');
   }

   /**
    * Save RMA custom data if needed
    */
   public static function rma_data_save($post_id, $post) {
      if ($post->post_type == 'rma') {
         // shipping warehouse
         if(isset($_POST['sbwcrma_warehouse'])){
            update_post_meta($post_id, 'sbwcrma_warehouse', $_POST['sbwcrma_warehouse']);
         }
         // rma status
         if(isset($_POST['sbwcrma_status'])){
            update_post_meta($post_id, 'sbwcrma_status', $_POST['sbwcrma_status']);
         }
      }
   }

   /**
    * Register scripts
    */
   public static function rma_register_scripts() {
      wp_register_script('rma_js', self::rma_js(), ['jquery'], '1.0.0');
      wp_register_script('rma_settings_js', self::rma_settings_js(), ['jquery'], '1.0.0');
      wp_register_style('rma_css', self::rma_css(), '', '1.0.0');
   }

   /**
    * Settings JS
    */
   public static function rma_settings_js() { ?>
      <script>
         jQuery(document).ready(function() {
            // save rma settings
            $('a.sbwcrma_save_settings').on('click', function(e) {
               e.preventDefault();

               // get data to submit
               var emails = $('input#sbwcrma_emails').val();
               var instructions = $('textarea#sbwcrma_instr_email').val();
               var approval = $('textarea#sbwcrma_accept_email').val();
               var rejection = $('textarea#sbwcrma_reject_email').val();

               var data = {
                  'action': 'rma_ajax',
                  'sbwrma_emails': emails,
                  'sbwcrma_instr_email': instructions,
                  'sbwcrma_accept_email': approval,
                  'sbwcrma_reject_email': rejection
               };

               $.post(ajaxurl, data, function(response) {
                  // console.log(response);
                  alert(response);
               });
            });
         });
      </script>
   <?php }

   /**
    * JS
    */
   public static function rma_js() { ?>

      <!-- js -->
      <script>
         jQuery(document).ready(function($) {

            // send rma instructions
            $('a#sbwcrma_send_instructions').on('click', function(e) {
               e.preventDefault();

               var data = {
                  'action': 'rma_ajax',
                  'send_instructions': true
               };

               $.post(ajaxurl, data, function(response) {

               });
            });

            // reject rma request
            $('a#sbwcrma_reject').on('click', function(e) {
               e.preventDefault();

               var data = {
                  'action': 'rma_ajax',
                  'reject_rma': '<?php echo get_the_ID(); ?>'
               };

               $.post(ajaxurl, data, function(response) {

               });
            });

            // approve rma request
            $('a#sbwcrma_approve').on('click', function(e) {
               e.preventDefault();

               var data = {
                  'action': 'rma_ajax',
                  'approve_rma': '<?php echo get_the_ID(); ?>'
               };

               $.post(ajaxurl, data, function(response) {

               });
            });

            // set rma status
            var rma_status = '<?php echo get_post_meta(get_the_ID(), 'sbwcrma_status', true); ?>';
            $('#sbwcrma_status').val(rma_status);

         });
      </script>

   <?php }

   /**
    * CSS
    */
   public static function rma_css() { ?>

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
            border-radius: 3px;
         }

         div#sbwcrma_settings_cont textarea {
            width: 100%;
            margin-bottom: 15px;
         }

         div#sbwcrma_settings_cont {
            width: 40%;
            min-width: 360px;
         }

         /* cpt metaboxes */
         div#rma_meta_box label {
            display: block;
            margin-bottom: 5px;
            padding-left: 5px;
            font-size: 14px;
            font-weight: 500;
         }

         div#rma_meta_box input,
         div#rma_meta_box select,
         div#rma_meta_box textarea {
            width: 100%;
            margin-bottom: 15px;
         }

         div#rma_meta_box table {
            width: 100%;
            border: 2px solid #888;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 4px;
         }

         div#rma_meta_box table th {
            background: #f1f1f1;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #999;
         }

         div#rma_meta_box table td {
            text-align: center;
            font-size: 14px;
            border: 1px solid #999;
         }

         .sbwcrma_metadata_bits {
            padding: 10px;
         }

         div#sbwcrma_data_left {
            overflow: auto;
            width: 50%;
            float: left;
         }

         div#sbwcrma_data_right {
            overflow: auto;
            width: 50%;
            float: left;
         }

         div#sbwcrma_actions {
            overflow: auto;
            clear: both;
            padding: 10px;
         }

         div#sbwcrma_actions a {
            display: inline-block;
            width: 33%;
            text-align: center;
            background: #007cba;
            color: white;
            font-size: 18px;
            text-decoration: none;
            line-height: 2.5;
            border-radius: 3px;
         }

         a#sbwcrma_reject {
            background: #ca4a1f !important;
         }

         div#rma_meta_box {
            overflow: auto;
         }

         div#sbwcrma_customer_location {
            padding: 10px;
            font-size: 14px;
            background: #eeeeee;
         }
      </style>

<?php }

   /**
    * Ajax to save/update settings
    */
   public static function rma_ajax() {

      // save rma settings
      if (isset($_POST['sbwrma_emails'])) {

         $emails_saved = add_option('sbwcrma_emails', $_POST['sbwrma_emails']);
         $instructions_saved = add_option('sbwcrma_instr_email', $_POST['sbwcrma_instr_email']);
         $accept_email_saved = add_option('sbwcrma_accept_email', $_POST['sbwcrma_accept_email']);
         $reject_email_saved = add_option('sbwcrma_reject_email', $_POST['sbwcrma_reject_email']);

         if ($emails_saved || $instructions_saved || $accept_email_saved || $reject_email_saved) {
            pll_e('RMA settings saved successfully');
         } else {
            pll_e('RMA settings could not be saved. Please reload the page and try again.');
         }
      }

      // send rma instructions email
      if (isset($_POST['send_instructions'])) {
      }

      // approve rma
      if (isset($_POST['approve_rma'])) {
      }

      // reject rma
      if (isset($_POST['reject_rma'])) {
      }

      wp_die();
   }
}

SBWC_Admin::init();
