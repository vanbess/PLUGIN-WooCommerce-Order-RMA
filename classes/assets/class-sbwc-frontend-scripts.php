<?php

/**
 * Class which loads frontend javascript and css files
 * Loaded via class to enable passing of certain php variable data directly to files
 */

class SBWC_Frontend_Scripts
{
    /**
     * JS
     */
    public static function sbwc_front_js()
    { ?>
        <script>
            jQuery(document).ready(function($) {

                $('a#sbwcrma_submit_show').on('click', function(e){
                    e.preventDefault();
                    $(this).addClass('sbwcrma_active');
                    $('a#sbwcrma_returns_show').removeClass('sbwcrma_active');
                    $('div#sbwcrma_submit_returns').show();
                    $('div#sbwcrma_submitted_returns').hide();
                });
                
                $('a#sbwcrma_returns_show').on('click', function(e){
                    e.preventDefault();
                    $(this).addClass('sbwcrma_active');
                    $('a#sbwcrma_submit_show').removeClass('sbwcrma_active');
                    $('div#sbwcrma_submit_returns').hide();
                    $('div#sbwcrma_submitted_returns').show();
                });

            });
        </script>
    <?php }

    /**
     * CSS
     */
    public static function sbwc_front_css()
    { ?>
        <style>
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

            table#sbwcrma_order_list>tbody>tr>th {
                text-align: center;
            }

            tr.sbwcrma_order_data>td {
                text-align: center;
            }

            table#sbwcrma_order_list a {
                color: #267cc3;
                text-decoration: underline;
            }
        </style>
<?php }
}
