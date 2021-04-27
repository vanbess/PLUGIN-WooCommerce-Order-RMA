<?php

/**
 * Class which loads backend javascript and css files
 * Loaded via class to enable passing of certain php variable data directly to files
 */

class SBWCRMA_Backend_Scripts
{
    /**
     * JS
     */
    public static function sbwc_admin_js()
    { ?>
        <script>
            jQuery(document).ready(function() {

                // add warehouse data
                $(document).on('click', 'a.sbwcrma_add_wh', function(e) {
                    e.preventDefault();

                    var append = '<hr class="sbwcrma_wh_hr">';
                    append += '<div class="sbwcrma_wh_data_cont">';
                    append += '<input type="text" class="sbwcrma_wh_name" placeholder="Warehouse name">';
                    append += '<input type="text" class="sbwcrma_wh_address" placeholder="Warehouse shipping address">';
                    append += '<div class="sbwcrma_add_rem_wh_btns">';
                    append += '<a class="sbwcrma_add_wh" href="javascript:void(0)" title="Add warehouse">+</a>';
                    append += '<a class="sbwcrma_rem_wh" href="javascript:void(0)" title="Delete warehouse">-</a>';
                    append += '</div>';
                    append += '</div>';

                    $('div#sbwcrma_wh_data').append(append);
                });

                // remove warehouse data
                $(document).on('click', 'a.sbwcrma_rem_wh', function() {
                    $(this).parent().parent().remove();
                });

                // save rma settings
                $('a.sbwcrma_save_settings').on('click', function(e) {
                    e.preventDefault();

                    // get data to submit
                    var emails = $('input#sbwcrma_emails').val();
                    var emails_from = $('input#sbwcrma_emails_from').val();
                    var wh_names = [];
                    var wh_addys = [];

                    // wh names
                    $('input.sbwcrma_wh_name').each(function() {
                        if ($(this).val()) {
                            wh_names.push($(this).val());
                        }
                    });

                    // wh addys
                    $('input.sbwcrma_wh_address').each(function() {
                        if ($(this).val()) {
                            wh_addys.push($(this).val());
                        }
                    });

                    var data = {
                        'action': 'rma_ajax',
                        'sbcrma_save_settings': true,
                        'sbwcrma_emails': emails,
                        'sbwcrma_emails_from': emails_from,
                        'sbwcrma_wh_names': wh_names,
                        'sbwcrma_wh_addys': wh_addys
                    };

                    $.post(ajaxurl, data, function(response) {
                        // console.log(response);
                        alert(response);
                        location.reload();
                    });
                });

                // *******************
                // RMA FUNCTIONALITY
                // *******************

                // set selected wh
                var selected_wh = $('select#sbwcrma_wh_name').attr('current');
                $('select#sbwcrma_wh_name').val(selected_wh);

                // wh select on change
                $('select#sbwcrma_wh_name').change(function(e) {
                    e.preventDefault();
                    $('input#sbwcrma_wh_address').val($(this).find(':selected').attr('addy'));
                });

                // set rma status
                var rma_status = '<?php echo get_post_meta(get_the_ID(), 'sbwcrma_status', true); ?>';
                $('#sbwcrma_status').val(rma_status);

                // RMA actions: send instructions, approve rma or reject rma
                $('a#sbwcrma_send_instructions').click(function(e) {
                    e.preventDefault();

                    var whouse = $('select#sbwcrma_wh_name').val();
                    var rma_no = $('input#sbwcrma_no').val();

                    if (whouse && rma_no) {
                        $('div#sbwcrma_instructions_overlay, div#sbwcrma_instructions_modal').show();
                        $('input#sbwcrma_warehouse').val(whouse);
                        $('input#sbwcrma_no').val(rma_no);

                        $('a.sbwcrma_send_instructions').click(function(e) {
                            e.preventDefault();

                            var instructions = $('textarea#sbwcrma_instructions').val();
                            var rma_id = $(this).attr('rma-id');

                            if (instructions) {

                                var data = {
                                    'action': 'rma_ajax',
                                    'rma_id': rma_id,
                                    'whouse': whouse,
                                    'whouse_addy': $('input#sbwcrma_wh_address').val(),
                                    'rma_no': $('input#sbwcrma_no').val(),
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
                        alert('<?php pll_e('Destination warehouse and RMA number is required!'); ?>');
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
    public static function sbwc_admin_css()
    { ?>
        <!-- css -->
        <style>
            input.sbwcrma_wh_name,
            input.sbwcrma_wh_address {
                display: block;
                width: 95%;
                margin-bottom: 15px;
            }

            hr.sbwcrma_wh_hr {
                border: 0;
                border-top: 1px dashed #b9b9b9;
                border-bottom: 1px dashed #fafafa;
                margin-bottom: 13px;
            }

            p.sbwcrma_add_additional_whs {
                font-weight: 700;
                font-style: italic;
            }

            .sbwcrma_wh_data_cont {
                position: relative;
            }

            .sbwcrma_add_rem_wh_btns {
                position: absolute;
                z-index: 10;
                right: 0;
                top: 0;
            }

            a.sbwcrma_add_wh {
                display: block;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #0073aa;
                color: white;
                text-decoration: none;
                text-align: center;
                line-height: 1.5;
                margin-bottom: 5px;
            }

            a.sbwcrma_rem_wh {
                display: block;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background: #ca4a1f;
                color: white;
                text-decoration: none;
                text-align: center;
                line-height: 1.5;
            }

            a.sbwcrma_add_wh:hover,
            a.sbwcrma_rem_wh:hover {
                color: white;
            }

            span.sbcwrma_error {
                color: red;
                font-weight: 700;
                font-style: italic;
                padding-bottom: 15px;
                display: block;
            }

            label.sbwcrma_admin_labels {
                display: block;
                font-size: 15px;
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

            a.sbwcrma_save_settings:hover {
                color: white;
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
}
