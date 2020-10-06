<?php

/**
 * Class to render admin options
 */

class SBWC_Admin {

   use SBWCRMA_Admin_Modals;

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
            <input value="<?php print get_option('sbwcrma_emails'); ?>" type="text" id="sbwcrma_emails" placeholder="<?php pll_e('email address 1, email address 2 etc'); ?>">
         </div>

         <!-- from email address -->
         <div class="sbwcrma_input_cont">
            <label class="sbwcrma_admin_labels" for="sbwcrma_email_from">
               <?php pll_e('The email address or addresses all RMA related emails will originate from:'); ?>
            </label>
            <input value="<?php print get_option('sbwcrma_emails_from'); ?>" type="text" id="sbwcrma_emails_from" placeholder="<?php pll_e('email address 1, email address 2 etc'); ?>">
         </div>

         <!-- save settings -->
         <div id="sbwcrma_settings_submit">
            <a class="sbwcrma_save_settings" href="javascript:void(0)"><?php pll_e('Save Settings'); ?></a>
         </div>

      </div>

   <?php
      wp_enqueue_script('rma_settings_js');
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

         <?php
         // instructions modal
         self::rma_instructions();
         ?>

         <!-- review rma request -->
         <a id="sbwcrma_review" href="javascript:void(0)"><?php pll_e('Mark RMA as received/being reviewed'); ?></a>

         <?php
         // rma review modal
         self::reviewing_rma();
         ?>

         <!-- approve rma request -->
         <a id="sbwcrma_approve" href="javascript:void(0)"><?php pll_e('Approve RMA Request'); ?></a>

         <?php
         // rma approve modal
         self::approve_rma();
         ?>

         <!-- reject rma with reasons -->
         <a id="sbwcrma_reject" href="javascript:void(0)"><?php pll_e('Reject RMA Request'); ?></a>

         <?php
         // rma reject modal
         self::reject_rma();
         ?>

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
         if (isset($_POST['sbwcrma_warehouse'])) {
            update_post_meta($post_id, 'sbwcrma_warehouse', $_POST['sbwcrma_warehouse']);
         }
         // rma status
         if (isset($_POST['sbwcrma_status'])) {
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
               var emails_from = $('input#sbwcrma_emails_from').val();

               var data = {
                  'action': 'rma_ajax',
                  'sbwcrma_emails': emails,
                  'sbwcrma_emails_from': emails_from
               };

               $.post(ajaxurl, data, function(response) {
                  // console.log(response);
                  alert(response);
                  location.reload();
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

            // set rma status
            var rma_status = '<?php echo get_post_meta(get_the_ID(), 'sbwcrma_status', true); ?>';
            $('#sbwcrma_status').val(rma_status);

            // RMA actions: send instructions, approve rma or reject rma
            $('a#sbwcrma_send_instructions').click(function(e) {
               e.preventDefault();

               var whouse = $('input#sbwcrma_warehouse').val();

               if (whouse) {
                  $('div#sbwcrma_instructions_overlay, div#sbwcrma_instructions_modal').show();
                  $('input#sbwcrma_warehouse').val(whouse);

                  $('a.sbwcrma_send_instructions').click(function(e) {
                     e.preventDefault();

                     var instructions = $('textarea#sbwcrma_instructions').val();
                     var rma_id = $(this).attr('rma-id');

                     if (instructions) {

                        var data = {
                           'action': 'rma_ajax',
                           'rma_id': rma_id,
                           'whouse': whouse,
                           'instr': instructions
                        };

                        $.post(ajaxurl, data, function(response) {
                           alert(response);
                           location.reload();
                        });

                     } else {
                        alert('<?php pll_e('Please enter instructions to the client!'); ?>')
                     }

                  });

               } else {
                  alert('<?php pll_e('Destination warehouse is required!'); ?>');
               }


            });

            // mark rma as received/under review
            $('a#sbwcrma_review').click(function(e) {
               e.preventDefault();
               $('div#sbwcrma_review_overlay, div#sbwcrma_review_modal').show();

               $('a.sbwcrma_review').click(function(e) {
                  e.preventDefault();
                  var rma_id = $(this).attr('rma-id');
                  var msg = $('textarea#sbwcrma_review_message').val();

                  var data = {
                     'action': 'rma_ajax',
                     'under_review': true,
                     'review_msg': msg,
                     'rma_id': rma_id
                  };
                  $.post(ajaxurl, data, function(response) {
                     alert(response);
                     location.reload();
                  });

               });

            });

            // approve rma
            $('a#sbwcrma_approve').click(function(e) {
               e.preventDefault();
               $('div#sbwcrma_approve_overlay, div#sbwcrma_approve_modal').show();

               $('a.sbwcrma_approve').click(function(e) {
                  e.preventDefault();

                  var rma_id = $(this).attr('rma-id');
                  var msg = $('textarea#sbwcrma_approval_message').val();

                  var data = {
                     'action': 'rma_ajax',
                     'approve_rma': true,
                     'rma_id': rma_id,
                     'approve_msg': msg
                  };
                  $.post(ajaxurl, data, function(response) {
                     alert(response);
                     location.reload();
                  });
               });

            });

            // reject rma
            $('a#sbwcrma_reject').click(function(e) {
               e.preventDefault();
               $('div#sbwcrma_reject_overlay, div#sbwcrma_reject_modal').show();

               $('a.sbwcrma_reject').click(function(e) {
                  e.preventDefault();

                  var rma_id = $(this).attr('rma-id');
                  var msg = $('#sbwcrma_rejection_message').val();

                  if (!msg) {
                     alert('<?php pll_e('A reason for the rejection of this RMA is required!'); ?>');
                  } else {
                     var data = {
                        'action': 'rma_ajax',
                        'reject_rma': true,
                        'reject_msg': msg,
                        'rma_id': rma_id
                     };
                     $.post(ajaxurl, data, function(response) {
                        alert(response);
                        location.reload();
                     });
                  }
               });
            });

            // close rma admin modals
            $('a.sbwcrma_admin_modal_close, .sbwcrma_admin_overlay').click(function(e) {
               e.preventDefault();
               $('.sbwcrma_admin_overlay, .sbwcrma_admin_modal').hide();
            });

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

         input#sbwcrma_emails,
         input#sbwcrma_emails_from {
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
            width: 24.8%;
            text-align: center;
            background: #007cba;
            color: white;
            font-size: 16px;
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

         /* modals/lightboxes */
         .sbwcrma_admin_overlay {
            position: fixed;
            z-index: 1000;
            width: 100vw;
            height: 100vh;
            background: #0000004a;
            top: 0;
            left: 0;
         }

         .sbwcrma_admin_modal {
            position: absolute;
            width: 50vw;
            min-width: 360px;
            top: 0;
            background: white;
            z-index: 1001;
            padding: 30px;
            border-radius: 4px;
            left: 17vw;
         }

         a.sbwcrma_admin_modal_close {
            width: 20px !important;
            height: 20px;
            border-radius: 50% !important;
            position: absolute;
            right: 10px;
            top: 10px;
            text-align: center !important;
            line-height: 1.13 !important;
            background: lightgray !important;
            color: grey !important;
         }

         a.sbwcrma_send_instructions,
         a.sbwcrma_review,
         a.sbwcrma_approve,
         a.sbwcrma_reject {
            width: 100% !important;
         }

         a.sbwcrma_reject {
            background: #ca4a1f !important;
         }

         p.sbwcrma_done {
            font-size: 15px;
            text-align: center;
            background: #efefef;
            padding: 15px;
         }

         .sbwcrma_admin_modal>h1 {
            margin-bottom: 20px !important;
            padding-left: 5px !important;
         }
      </style>

<?php }

   /**
    * Ajax to save/update settings
    */
   public static function rma_ajax() {

      // save rma settings
      if (isset($_POST['sbwcrma_emails'])) {

         // print_r($_POST);

         $emails_saved = update_option('sbwcrma_emails', $_POST['sbwcrma_emails']);
         $from_emails_saved = update_option('sbwcrma_emails_from', $_POST['sbwcrma_emails_from']);

         if ($emails_saved || $from_emails_saved) {
            pll_e('RMA settings saved successfully.');
         } else {
            pll_e('RMA settings could not be saved. Please reload the page and try again.');
         }
      }

      // send rma instructions email
      if (isset($_POST['instr'])) {

         // post data
         $instructions = $_POST['instr'];
         $rma_id = $_POST['rma_id'];
         $whouse = $_POST['whouse'];

         // update post meta
         $status_updated = update_post_meta($rma_id, 'sbwcrma_status', 'instructions sent');
         $whouse_updated = update_post_meta($rma_id, 'sbwcrma_warehouse', $whouse);
         $insructions_updated = update_post_meta($rma_id, 'sbwcrma_instructions', $instructions);

         // email data
         $user_name = get_post_meta($rma_id, 'sbwcrma_user_name', true);
         $user_email = get_post_meta($rma_id, 'sbwcrma_user_email', true);
         $from_name = get_bloginfo('name');
         $subject = pll__('Important RMA instructions');
         $from = get_option('sbwcrma_emails_from');
         $message = "Good day $user_name<br><br>";
         $message .= "<b>Instructions for further processing of your return follows.</b><br><br>";
         $message .= "<b>Your return needs to be shipped to: $whouse</b><br><br>";
         $message .= $instructions . "<br><br>";
         $message .= "If you have any questions or concerns please do not hesitate to contact us by responding to this email.<br><br>";
         $message .= "You can submit shipping data for your return via your accounts dashboard, or, if you do not have an account, via the return submission page where you originally submitted your return request.<br><br>";
         $message .= "Regards, <br><br>$from_name";
         $headers[] = "From: $from_name <$from>";
         $headers[] = "Content-Type: text/html; charset=UTF-8";

         // send email if post meta updated successfully
         if ($status_updated || $whouse_updated || $insructions_updated) {
            wp_mail($user_email, $subject, $message, $headers);
            pll_e('Instructions successfully sent.');
         } else {
            pll_e('Could not process your request. Please reload the page and try again.');
         }
      }

      // rma under review
      if (isset($_POST['under_review'])) {

         // post vars
         $msg = $_POST['review_msg'];
         $rma_id = $_POST['rma_id'];

         // user vars
         $name = get_post_meta($rma_id, 'sbwcrma_user_name', true);
         $to_mail = get_post_meta($rma_id, 'sbwcrma_user_email', true);
         $from_name = get_bloginfo('name');
         $from_mail = get_option('sbwcrma_emails_from');

         // mail vars
         $subject = 'Your return has been received and is under review';
         $message = "Good day $name<br><br>";
         $message .= "<b>We have received your return and are currently reviewing it.</b><br><br>";
         if ($msg) {
            $message .= "<b>Message from admin:</b> $msg<br><br>";
         }
         $message .= 'Once this process has been completed you will be informed of the outcome.<br><br>';
         $message .= "Regards,<br> $from_name";
         $headers[] = "From: $from_name <$from_mail>";
         $headers[] = "Content-Type: text/html; charset=UTF-8";

         // update rma meta and send mail if successfull
         $status_changed = update_post_meta($rma_id, 'sbwcrma_status', 'items received - pending inspection');

         if ($status_changed) {
            wp_mail($to_mail, $subject, $message, $headers);
            pll_e('Your request has been processed.');
         } else {
            pll_e('Could not process your request. Please reload the page and try again.');
         }
      }

      // approve rma
      if (isset($_POST['approve_rma'])) {
         // post vars
         $msg = $_POST['approve_msg'];
         $rma_id = $_POST['rma_id'];

         // user vars
         $name = get_post_meta($rma_id, 'sbwcrma_user_name', true);
         $to_mail = get_post_meta($rma_id, 'sbwcrma_user_email', true);
         $from_name = get_bloginfo('name');
         $from_mail = get_option('sbwcrma_emails_from');

         // mail vars
         $subject = 'Your return has been approved';
         $message = "Good day $name<br><br>";
         $message .= "<b>We are happy to inform you that your return has been approved.</b><br><br>";
         if ($msg) {
            $message .= "<b>Message from admin:</b> $msg <br><br>";
         }
         $message .= 'If required, one of our staff members will be in touch with further instructions.<br><br>';
         $message .= "Regards,<br> $from_name";
         $headers[] = "From: $from_name <$from_mail>";
         $headers[] = "Content-Type: text/html; charset=UTF-8";

         // update rma meta and send mail if successfull
         $status_changed = update_post_meta($rma_id, 'sbwcrma_status', 'approved');

         if ($status_changed) {
            wp_mail($to_mail, $subject, $message, $headers);
            pll_e('Your request has been processed.');
         } else {
            pll_e('Could not process your request. Please reload the page and try again.');
         }
      }

      // reject rma
      if (isset($_POST['reject_rma'])) {
         // post vars
         $msg = $_POST['reject_msg'];
         $rma_id = $_POST['rma_id'];

         // user vars
         $name = get_post_meta($rma_id, 'sbwcrma_user_name', true);
         $to_mail = get_post_meta($rma_id, 'sbwcrma_user_email', true);
         $from_name = get_bloginfo('name');
         $from_mail = get_option('sbwcrma_emails_from');

         // mail vars
         $subject = 'Your return has been rejected';
         $message = "Good day $name<br><br>";
         $message .= "<b>Unfortunately your request for return has been rejected.</b><br><br>";
         $message .= "<b>Message from admin:</b> $msg <br><br>";
         $message .= 'If you have any queries please feel free to contact us.<br><br>';
         $message .= "Regards,<br> $from_name";
         $headers[] = "From: $from_name <$from_mail>";
         $headers[] = "Content-Type: text/html; charset=UTF-8";

         // update rma meta and send mail if successfull
         $status_changed = update_post_meta($rma_id, 'sbwcrma_status', 'rejected');

         if ($status_changed) {
            wp_mail($to_mail, $subject, $message, $headers);
            pll_e('Your request has been processed.');
         } else {
            pll_e('Could not process your request. Please reload the page and try again.');
         }
      }

      wp_die();
   }
}

SBWC_Admin::init();
