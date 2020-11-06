<?php

/**
 * Class which loads frontend javascript and css files
 * Loaded via class to enable passing of certain php variable data directly to files
 */

class SBWCRMA_Frontend_Scripts {
    /**
     * JS
     */
    public static function sbwc_front_js() { ?>
        <script>
            jQuery(document).ready(function($) {

                // show orders list
                $('a#sbwcrma_submit_show').on('click', function(e) {
                    e.preventDefault();
                    $(this).addClass('sbwcrma_active');
                    $('a#sbwcrma_returns_show').removeClass('sbwcrma_active');
                    $('div#sbwcrma_submit_returns').show();
                    $('div#sbwcrma_submitted_returns, div#sbwcrma_submitted_returns_no_reg').hide();
                });

                // show returns list
                $('a#sbwcrma_returns_show').on('click', function(e) {
                    e.preventDefault();
                    $(this).addClass('sbwcrma_active');
                    $('a#sbwcrma_submit_show').removeClass('sbwcrma_active');
                    $('div#sbwcrma_submit_returns').hide();
                    $('div#sbwcrma_submitted_returns, div#sbwcrma_submitted_returns_no_reg').show();
                });

                // show order products modal
                $('a.sbwcrma_prod_modal_show').each(function() {
                    $(this).on('click', function(e) {
                        e.preventDefault();

                        var order_id = $(this).attr('order-id');

                        $('.sbwcrma_prod_select_modal_overlay, .sbwcrma_prod_select_modal').each(function() {
                            var modal_order_id = $(this).attr('order-id');
                            if (order_id == modal_order_id) {
                                $(this).show();
                                $(this).find('a.sbwcrma_submit_return, a.sbwcrma_submit_return_no_reg').attr('active', 'yes');
                            }
                        });

                    });
                });

                // hide order products modal
                $('.sbwcrma_prod_select_modal_overlay, a.sbwcrma_modal_close').on('click', function(e) {
                    $('.sbwcrma_prod_select_modal_overlay, .sbwcrma_prod_select_modal').hide();
                    $('.sbwcrma_prod_select_modal').find('a.sbwcrma_submit_return, a.sbwcrma_submit_return_no_reg').attr('active', 'no');
                });

                // submit rma request
                $('a.sbwcrma_submit_return, a.sbwcrma_submit_return_no_reg').each(function() {
                    $(this).on('click', function(e) {

                        // see which rma request is active before sending, otherwise rma request will be submitted for each of user's orders
                        if ($(this).attr('active') == 'yes') {

                            // ajax url
                            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

                            // get order id
                            var order_id = $(this).attr('order-id');

                            // get rma reason
                            var rma_reason = $('#sbwcrma_return_reason_' + order_id).val();

                            // prod data array
                            var prod_ids = [];
                            var prod_qtys = [];

                            // find checked checkboxes
                            $('input.sbwcrma_prod_checkbox:checked').each(function() {
                                var prod_id = $(this).attr('prod-id');
                                var target = $(this).attr('target');
                                var qty = $(':selected', target).val();
                                prod_ids.push(prod_id);
                                prod_qtys.push(qty);
                            });

                            // submit via ajax if reason is present, else display error
                            if (rma_reason) {
                                var data = {
                                    'action': 'sbwcrma_submit',
                                    'prod_ids': prod_ids,
                                    'prod_qtys': prod_qtys,
                                    'order_id': order_id,
                                    'rma_reason': rma_reason
                                };

                                $.post(ajaxurl, data, function(response) {
                                    alert(response);
                                    location.reload();
                                });
                            } else {
                                $('p.sbwcrma_required').show();
                            }
                        }
                    });
                });

                // show/hide rma data modal
                $('a.sbwcrma_view_rma_dets').each(function() {
                    $(this).click(function(e) {
                        var rma_id = $(this).attr('rma-id');
                        $('#sbwcrma_data_overlay_' + rma_id + ', #sbwcrma_data_modal_' + rma_id).show();
                    });
                });

                $('a.sbwcrma_data_modal_close, .sbwcrma_data_overlay').click(function() {
                    $('.sbwcrma_data_overlay, .sbwcrma_data_modal').hide();
                });

                // show shipping data input
                $('a.sbwcrma_submit_ship_data').click(function(e) {
                    e.preventDefault();
                    $('.sbwcrma_ship_data').toggle();
                });

                // submit shipping data
                $('a.sbwcrma_submit_shipp_data').click(function(e) {
                    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                    var shipco = $('input#sbwcrma_ship_co').val();
                    var shiptrack = $('input#sbwcrma_ship_track_no').val();
                    var rma_id = $(this).attr('rma-id');

                    if (!shipco || !shiptrack) {
                        $('.shipp_error').show();
                    } else {
                        $('.shipp_error').hide();

                        var data = {
                            'action': 'sbwcrma_submit',
                            'shipco': shipco,
                            'shiptrack': shiptrack,
                            'rma_id': rma_id
                        };

                        $.post(ajaxurl, data, function(response) {
                            alert(response);
                            location.reload();
                        });
                    }
                });
            });
        </script>
    <?php }

    /**
     * CSS
     */
    public static function sbwc_front_css() { ?>
        <style>
            /* my account -> returns */
            div#sbwcrma_nav_cont a {
                display: inline-block;
                width: 49.7%;
                text-align: center;
                line-height: 3;
                border: 1px solid #e6e6e6;
            }

            .sbwcrma_active {
                background: #efefef !important;
            }

            div#sbwcrma_nav_cont {
                margin-bottom: 30px;
            }

            div#sbwcrma_submit_returns>p,
            p.sbwcrma_no_returns {
                background: #efefef;
                line-height: 2;
                text-align: center;
                margin-bottom: 30px;
                border: 1px solid #e6e6e6;
            }

            table#sbwcrma_order_list>tbody>tr>th,
            #sbwcrma_order_list>thead>tr>th,
            #sbwcrma_order_list>tbody>tr>td {
                text-align: center;
            }

            table#sbwcrma_order_list a {
                color: #267cc3;
                text-decoration: underline;
            }

            /* product select modal */
            .sbwcrma_prod_select_modal_overlay {
                background: #000000a3;
                width: 100%;
                height: 100%;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 100;
            }

            .sbwcrma_prod_select_modal {
                position: absolute;
                top: -75%;
                left: 0;
                background: white;
                padding: 30px;
                z-index: 101;
                border-radius: 3px;
            }

            a.sbwcrma_modal_close {
                display: block;
                width: 20px;
                height: 20px;
                background: grey;
                color: white;
                position: absolute;
                right: 10px;
                top: 10px;
                text-align: center;
                border-radius: 50%;
                line-height: 1.1;
            }

            #sbwcrma_submit_returns>div>table>thead>tr>th {
                text-align: center;
            }

            #sbwcrma_submit_returns>div>table>tbody>tr>td {
                text-align: center;
            }

            input.sbwcrma_prod_checkbox {
                cursor: pointer;
            }

            p.sbwcrma_instructions {
                text-align: center;
                line-height: 2;
                margin-bottom: 30px;
                background: #efefef;
                border: 1px solid #e6e6e6;
                margin-top: 15px;
                padding: 0 15px;
            }

            a.sbwcrma_submit_return {
                display: block;
                background: #0073aa;
                color: white;
                font-size: 20px;
                text-transform: uppercase;
                text-align: center;
                line-height: 2.2;
                margin-top: 30px;
                font-weight: 700;
            }

            a.sbwcrma_submit_understood {
                display: block;
                text-align: center;
                text-transform: uppercase;
                background: #0073aa;
                color: white;
                line-height: 2.2;
                font-size: 20px;
                font-weight: 700;
                border-radius: 3px;
            }

            span.sbwcrma_submitted {
                display: block;
                background: green;
                padding: 5px;
                color: white;
            }

            p.sbwcrma_list {
                background: #efefef;
                line-height: 2;
                text-align: center;
                margin-bottom: 30px;
                border: 1px solid #e6e6e6;
                padding: 15px;
            }

            div#sbwcrma_submitted_returns table tr td,
            div#sbwcrma_submitted_returns table tr th {
                text-align: center;
            }

            div#sbwcrma_submitted_returns table tr td a {
                color: #267cc3;
                text-decoration: underline;
            }

            table.sbwcrma_prod_select_table {
                margin-bottom: 30px;
            }

            .sbwcrma_prod_select_modal label {
                text-transform: uppercase;
                color: #555555;
            }

            p.sbwcrma_required,
            p.sbwcrma_required_prods {
                color: #de1010;
                font-weight: bold;
                margin-top: -12px;
            }

            /* rma data modal */
            .sbwcrma_data_overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: #00000075;
                z-index: 100000;
            }

            .sbwcrma_data_modal {
                position: absolute;
                z-index: 100001;
                top: -40vh;
                left: -11vw;
                width: 50vw;
                min-width: 320px;
                padding: 40px;
                border-radius: 3px;
                background: white;
            }

            div#sbwcrma_submitted_returns {
                position: relative;
            }

            a.sbwcrma_data_modal_close {
                display: block;
                width: 20px;
                height: 20px;
                background: lightgray;
                text-align: center;
                line-height: 1.1;
                border-radius: 50%;
                position: absolute;
                right: 15px;
                top: 15px;
            }

            span.sbwcrma_data_key {
                display: inline-block;
                width: 30%;
                font-weight: 600;
                line-height: 2.2;
                text-align: right;
                padding-right: 15px;
            }

            span.sbwcrma_data_val {
                display: inline-block;
                vertical-align: top;
                line-height: 2.2;
                padding-left: 15px;
                width: 69%;
            }

            #sbwcrma_submitted_returns h1 {
                font-size: 20px;
                text-align: center;
                background: #efefef;
                line-height: 2;
            }

            .sbwcrma_data_modal_prods_cont>span {
                display: inline-block;
                width: 32%;
                text-align: center;
            }

            .sbwcrma_data_modal_prods_cont>span {
                display: inline-block;
                width: 32.9%;
                box-sizing: border-box;
                border: 1px solid #ccc;
                margin-bottom: 5px;
                background: #efefef;
                font-weight: 600;
            }

            .sbwcrma_rma_modal_prod_row>span {
                display: inline-block;
                width: 32.9%;
                text-align: center;
                vertical-align: middle;
                padding: 5px 0;
            }

            .sbwcrma_data_modal_prods_cont {
                font-size: 14px;
                padding: 5px 0;
            }

            .sbwcrma_rma_modal_prod_row {
                border: 1px solid #ccc;
                line-height: 1.5;
                margin-bottom: 5px;
            }

            a.sbwcrma_submit_ship_data {
                display: block;
                margin: 30px 0;
                text-align: center;
                font-size: 20px;
                background: #267cc3;
                color: white;
                line-height: 2.2;
                border-radius: 3px;
                font-weight: 600;
            }

            a.sbwcrma_submit_shipp_data {
                display: block;
                margin: 30px 0;
                text-align: center;
                font-size: 20px;
                border: 1px solid #267cc3;
                color: #267cc3;
                line-height: 2.2;
                border-radius: 3px;
                font-weight: 600;
            }

            .sbwcrma_ship_data label {
                font-size: 16px;
                color: #666;
            }

            span.shipp_error {
                color: red;
                font-weight: 600;
            }

            p.sbwcrma_ship_data_submitted {
                text-align: center;
                font-weight: 600;
                background: #efefef;
                line-height: 2.5;
            }

            /* NON REGISTERED USERS */
            div#sbwcrma_noreg_email_input {
                overflow: auto;
                overflow-x: hidden;
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

            a.sbwcrma_submit_return_no_reg {
                display: block;
                width: 100%;
                background: #267cc3;
                color: white;
                font-size: 20px;
                font-weight: 600;
                line-height: 2;
                border-radius: 3px;
            }

            .sbwcrma_data_modal.no_reg {
                top: -19%;
                left: 25vw;
                text-align: left;
            }

            .sbwcrma_data_modal.no_reg>h1 {
                text-align: center;
            }

            .sbwcrma_data_modal_prods_cont.no_reg>span,
            .sbwcrma_rma_modal_prod_row.no_reg>span {
                width: 32.85%;
            }

            table.sbcrma_rma_data_table_no_reg th,
            table.sbcrma_rma_data_table_no_reg td {
                text-align: center;
            }

            table.sbcrma_rma_data_table_no_reg td a {
                color: #2675b9;
                text-decoration: underline;
            }
        </style>
<?php }
}
