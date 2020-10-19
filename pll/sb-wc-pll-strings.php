<?php

/**
 * Register polylang strings
 */
if (function_exists('pll_register_string')) {

   //  admin columns
   pll_register_string('sbwcrma_cols_1', 'Date');
   pll_register_string('sbwcrma_cols_2', 'Submitted By');
   pll_register_string('sbwcrma_cols_3', 'Order No');
   pll_register_string('sbwcrma_cols_4', 'Warehouse');
   pll_register_string('sbwcrma_cols_5', 'RMA Status');
   pll_register_string('sbwcrma_cols_6', 'Contact');
   pll_register_string('sbwcrma_cols_7', 'Click to send an email to this user');

   // admin modals
   pll_register_string('sbwcrma_admin_modals_1', 'Send RMA instructions to client');
   pll_register_string('sbwcrma_admin_modals_2', 'Ship to warehouse:');
   pll_register_string('sbwcrma_admin_modals_3', 'Instructions to client:');
   pll_register_string('sbwcrma_admin_modals_4', 'Add your message to the client here');
   pll_register_string('sbwcrma_admin_modals_5', 'Send instructions');
   pll_register_string('sbwcrma_admin_modals_6', 'Instructions have already been sent to this client.');
   pll_register_string('sbwcrma_admin_modals_7', 'RMA under review notification has already been sent to this client.');
   pll_register_string('sbwcrma_admin_modals_8', 'Send RMA received and being reviewed notification');
   pll_register_string('sbwcrma_admin_modals_9', 'Message to client (optional):');
   pll_register_string('sbwcrma_admin_modals_10', 'Add your message to the client here');
   pll_register_string('sbwcrma_admin_modals_11', 'Send RMA under review message to client');
   pll_register_string('sbwcrma_admin_modals_12', 'RMA approval notification has already been sent to this client.');
   pll_register_string('sbwcrma_admin_modals_13', 'Approve RMA');
   pll_register_string('sbwcrma_admin_modals_14', 'Message to client (optional):');
   pll_register_string('sbwcrma_admin_modals_17', 'RMA rejection notification has already been sent to this client.');
   pll_register_string('sbwcrma_admin_modals_18', 'Reject RMA');
   pll_register_string('sbwcrma_admin_modals_19', 'Message to client (required):');

   // product select modal
   pll_register_string('sbwcrma_prod_select_modal_1', 'Cancel');
   pll_register_string('sbwcrma_prod_select_modal_2', 'Select the product(s) and product quantities you would like to return:');
   pll_register_string('sbwcrma_prod_select_modal_3', 'Select');
   pll_register_string('sbwcrma_prod_select_modal_4', 'Product');
   pll_register_string('sbwcrma_prod_select_modal_5', 'Ordered QTY');
   pll_register_string('sbwcrma_prod_select_modal_6', 'Return QTY');
   pll_register_string('sbwcrma_prod_select_modal_7', 'Select at least one product.');
   pll_register_string('sbwcrma_prod_select_modal_8', 'Specify reason for return request (required):');
   pll_register_string('sbwcrma_prod_select_modal_9', 'Please provide a reason for this return.');
   pll_register_string('sbwcrma_prod_select_modal_10', 'Submit Return Request');

   // rma data modal
   pll_register_string('sbwcrma_rma_data_modal_1', 'Current data for this return request:');
   pll_register_string('sbwcrma_rma_data_modal_2', 'Product ID');
   pll_register_string('sbwcrma_rma_data_modal_3', 'Product Name');
   pll_register_string('sbwcrma_rma_data_modal_4', 'Qty');
   pll_register_string('sbwcrma_rma_data_modal_5', 'Not specified');
   pll_register_string('sbwcrma_rma_data_modal_6', 'Already shipped? Submit shipping data');
   pll_register_string('sbwcrma_rma_data_modal_7', 'Specify shipping company:*');
   pll_register_string('sbwcrma_rma_data_modal_8', 'Specify package tracking number:*');
   pll_register_string('sbwcrma_rma_data_modal_9', 'Please provide all required shipping data.');
   pll_register_string('sbwcrma_rma_data_modal_10', 'Submit shipping data');
   pll_register_string('sbwcrma_rma_data_modal_11', 'Order Number');
   pll_register_string('sbwcrma_rma_data_modal_12', 'RMA Number');
   pll_register_string('sbwcrma_rma_data_modal_13', 'Your email address');
   pll_register_string('sbwcrma_rma_data_modal_14', 'Your name');
   pll_register_string('sbwcrma_rma_data_modal_15', 'Your delivery address');
   pll_register_string('sbwcrma_rma_data_modal_16', 'Reason for return');
   pll_register_string('sbwcrma_rma_data_modal_17', 'Products');
   pll_register_string('sbwcrma_rma_data_modal_18', 'Return status');
   pll_register_string('sbwcrma_rma_data_modal_19', 'Return warehouse');
   pll_register_string('sbwcrma_rma_data_modal_20', 'Warehouse address');
   pll_register_string('sbwcrma_rma_data_modal_21', 'Shipping company');
   pll_register_string('sbwcrma_rma_data_modal_22', 'Tracking number');

   // admin class
   pll_register_string('sbwcrma_admin_1', 'RMA Settings');
   pll_register_string('sbwcrma_admin_2', 'List of email addresses, separated by commas, to which RMA submission emails should be sent:');
   pll_register_string('sbwcrma_admin_3', 'email address 1, email address 2 etc');
   pll_register_string('sbwcrma_admin_4', 'The email address or addresses all RMA related emails will originate from:');
   pll_register_string('sbwcrma_admin_5', 'Save Settings');
   pll_register_string('sbwcrma_admin_6', 'RMA No');
   pll_register_string('sbwcrma_admin_6_5', 'Please provide an RMA number the customer should use as reference');
   pll_register_string('sbwcrma_admin_6_6', 'Warehouse names and addresses which will be used for RMAs:');
   pll_register_string('sbwcrma_admin_6_7', 'Warehouse name');
   pll_register_string('sbwcrma_admin_6_8', 'Warehouse shipping address');
   pll_register_string('sbwcrma_admin_6_9', 'Add warehouse');
   pll_register_string('sbwcrma_admin_6_9_1', 'Delete warehouse');
   pll_register_string('sbwcrma_admin_6_9_2', 'Use the inputs below to add additional warehouses to your warehouse list.');
   pll_register_string('sbwcrma_admin_6_9_3', 'Warehouses currently defined:');
   pll_register_string('sbwcrma_admin_7', 'Client name');
   pll_register_string('sbwcrma_admin_8', 'Client email address');
   pll_register_string('sbwcrma_admin_9', 'Client shipping address/location');
   pll_register_string('sbwcrma_admin_10', 'Specify warehouse to which RMA items should be sent');
   pll_register_string('sbwcrma_admin_11', 'Shipping company');
   pll_register_string('sbwcrma_admin_12', 'to be completed by client');
   pll_register_string('sbwcrma_admin_13', 'RMA shipment tracking number');
   pll_register_string('sbwcrma_admin_14', 'Pending');
   pll_register_string('sbwcrma_admin_15', 'Instructions sent');
   pll_register_string('sbwcrma_admin_16', 'Items shipped');
   pll_register_string('sbwcrma_admin_17', 'Items received - pending inspection');
   pll_register_string('sbwcrma_admin_18', 'Approved');
   pll_register_string('sbwcrma_admin_19', 'Rejected');
   pll_register_string('sbwcrma_admin_20', 'Reason for RMA request');
   pll_register_string('sbwcrma_admin_21', 'RMA Products');
   pll_register_string('sbwcrma_admin_22', 'Product Name');
   pll_register_string('sbwcrma_admin_23', 'RMA Qty');
   pll_register_string('sbwcrma_admin_24', 'Mark RMA as received/being reviewed');
   pll_register_string('sbwcrma_admin_25', 'Please enter instructions to the client!');
   pll_register_string('sbwcrma_admin_26', 'Destination warehouse and RMA number is required!');
   pll_register_string('sbwcrma_admin_27', 'A reason for the rejection of this RMA is required!');
   pll_register_string('sbwcrma_admin_28', 'RMA settings saved successfully.');
   pll_register_string('sbwcrma_admin_29', 'RMA settings could not be saved. Please reload the page and try again.');
   pll_register_string('sbwcrma_admin_30', 'Instructions successfully sent.');
   pll_register_string('sbwcrma_admin_31', 'Could not process your request. Please reload the page and try again.');
   pll_register_string('sbwcrma_admin_32', 'Your request has been processed.');

   // front class
   pll_register_string('sbwcrma_front_1', 'Submit return request');
   pll_register_string('sbwcrma_front_2', 'Submitted returns');
   pll_register_string('sbwcrma_front_3', 'Select the order below you would like to log a return for');
   pll_register_string('sbwcrma_front_4', 'Order Date');
   pll_register_string('sbwcrma_front_5', 'Order Value');
   pll_register_string('sbwcrma_front_6', 'Select Products');
   pll_register_string('sbwcrma_front_7', 'Return submitted');
   pll_register_string('sbwcrma_front_8', 'Click to Select');
   pll_register_string('sbwcrma_front_9', 'You have not placed any orders yet.');
   pll_register_string('sbwcrma_front_10', 'Below is a list of returns you have submitted.<br><b>If you have already shipped a return, click on the <u>View Details</u> link to add the associated shipping data (shipping company and package tracking number) so that our staff members can keep track of the progress of your return.</b>');
   pll_register_string('sbwcrma_front_11', 'Return ID');
   pll_register_string('sbwcrma_front_12', 'Submitted On');
   pll_register_string('sbwcrma_front_13', 'Status');
   pll_register_string('sbwcrma_front_14', 'View Details');
   pll_register_string('sbwcrma_front_15', 'There are no returns on record for your account.');
   pll_register_string('sbwcrma_front_16', 'Thank you. One of our staff members will review and process your request.');
   pll_register_string('sbwcrma_front_17', 'Return submission failed. Please reload the page and try again.');
   pll_register_string('sbwcrma_front_18', 'Please select at least one product to return.');
   pll_register_string('sbwcrma_front_19', 'Shipping data updated. Thank you.');
   pll_register_string('sbwcrma_front_20', 'Could not update shipping data. Please reload the page and try again.');

   // shortcode
   pll_register_string('sbwcrma_sc_1', 'Looking to return an item or items? You can begin the process by entering your email address below so that we can retrieve a list of orders you have placed with us.');
   pll_register_string('sbwcrma_sc_2', 'Your email address:');
   pll_register_string('sbwcrma_sc_3', 'Submit');
   pll_register_string('sbwcrma_sc_4', 'According to our customer database a user with that email address is already registered. Please log in using the form below to continue.');
   pll_register_string('sbwcrma_sc_5', 'No orders were found for the supplied email address. Please make sure you have entered the correct email address and try again.');
}
