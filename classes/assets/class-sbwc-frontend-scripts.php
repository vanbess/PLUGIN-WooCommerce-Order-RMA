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
                    $('div#sbwcrma_submitted_returns').hide();
                });

                // show returns list
                $('a#sbwcrma_returns_show').on('click', function(e) {
                    e.preventDefault();
                    $(this).addClass('sbwcrma_active');
                    $('a#sbwcrma_submit_show').removeClass('sbwcrma_active');
                    $('div#sbwcrma_submit_returns').hide();
                    $('div#sbwcrma_submitted_returns').show();
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
                            }
                        });

                    });
                });

                // hide order products modal
                $('.sbwcrma_prod_select_modal_overlay, a.sbwcrma_modal_close').on('click', function(e) {
                    $('.sbwcrma_prod_select_modal_overlay, .sbwcrma_prod_select_modal').hide();
                });

                // submit rma request
                $('a.sbwcrma_submit_return').each(function() {
                    $(this).on('click', function(e) {

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
                                console.log(response);
                            });
                        } else {
                            $('p.sbwcrma_required').show();
                        }

                    });
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
        </style>
<?php }
}
