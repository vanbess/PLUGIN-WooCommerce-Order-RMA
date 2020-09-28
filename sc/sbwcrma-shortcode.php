<?php

/** Shortcode to render RMA functionality on the frontend for non-registered buyers/clients */
add_shortcode('sbwcrma_sc', 'sbwcrma_sc');

function sbwcrma_sc() {

    // enqueue css/js
    wp_enqueue_style('sbwcrma-noreg', SBWCRMA_URI . 'sc/sbwcrma_noreg.css');
    wp_enqueue_script('sbwcrma-noreg', SBWCRMA_URI . 'sc/sbwcrma_noreg.js', ['jquery']);

    // check if user is logged in and redirect to dashboard if true, else display email address input
    if (is_user_logged_in()) {
        wp_redirect('my-account');
    } else { ?>
        <div id="sbwcrma_noreg_email_input">
            <p class="sbwcrma_noreg_note">
                <?php pll_e('Looking to return an item or items? You can begin the process by entering your email address below so that we can retrieve a list of orders you have placed with us.'); ?>
            </p>

            <form action="" method="get">
                <div id="sbwcrma_noreg_label">
                    <label for="sbwcrma_noreg_email"><?php pll_e('Your email address:'); ?></label>
                </div>
                <div id="sbwcrma_noreg_input">
                    <input type="email" name="sbwcrma_noreg_email" placeholder="your@email.com">
                </div>
                <div id="sbcrma_noreg_submit">
                    <a href="javascript:void(0)" type="submit"><?php pll_e('Submit'); ?></a>
                </div>
            </form>
        </div>
<?php }

    /*check submitted email address against registered email addresses; 
/*if found, redirect user to my account/login page, else retrieve orders tied to email address*/
    if (isset($_GET['sbwcrma_noreg_email'])) {
        print $_GET['sbwcrma_noreg_email'];
    }
}
