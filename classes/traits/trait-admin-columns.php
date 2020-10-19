<?php

/** Custom columns for RMA post type */

trait SBWCRMA_Custom_Cols {

    /* init */
    public static function init() {

        // add custom cols
        add_filter('manage_rma_posts_columns', [__CLASS__, 'sbwcrma_custom_cols']);

        // add custom col data
        add_action('manage_rma_posts_custom_column', [__CLASS__, 'sbwcrma_custom_cols_content']);
    }

    // add custom post columns
    public static function sbwcrma_custom_cols($columns) {

        // remove date and title col
        unset($columns['date']);
        unset($columns['title']);

        // add custom cols
        $columns['rma_date'] = pll__('Date');
        $columns['rma_by'] = pll__('Submitted By');
        $columns['rma_order'] = pll__('Order No');
        $columns['rma_whouse'] = pll__('Warehouse');
        $columns['rma_status'] = pll__('RMA Status');
        $columns['rma_contact'] = pll__('Contact');

        return $columns;
    }

    // add column data
    public static function sbwcrma_custom_cols_content($column) {
        switch ($column) {
            case 'rma_date':
                echo get_the_date('j F Y', get_the_ID());
                break;
            case 'rma_by':
                echo get_post_meta(get_the_ID(), 'sbwcrma_user_name', true);
                break;
            case 'rma_order':
                $order_id =  get_post_meta(get_the_ID(), 'sbwcrma_order_id', true);
                echo get_post_meta($order_id, '_order_number_formatted', true);
                break;
            case 'rma_whouse':
                if (get_post_meta(get_the_ID(), 'sbwcrma_warehouse', true)) {
                    echo get_post_meta(get_the_ID(), 'sbwcrma_warehouse', true);
                } else {
                    echo '---';
                }
                break;
            case 'rma_status':
                pll_e(get_post_meta(get_the_ID(), 'sbwcrma_status', true));
                break;
            case 'rma_contact': ?>
                <a title="<?php pll_e('Click to send an email to this user'); ?>" href="mailto:<?php echo get_post_meta(get_the_ID(), 'sbwcrma_user_email', true); ?>"><?php echo get_post_meta(get_the_ID(), 'sbwcrma_user_email', true); ?></a>
<?php break;
        }
    }
}
