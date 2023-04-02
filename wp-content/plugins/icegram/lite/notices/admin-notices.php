<?php
global $post;
if ( 'ig_campaign' == $post->post_type && ( get_option( 'ig_new_admin_ui_icegram' ) !== 'yes' ) ) { 
	$dismiss_url = '?ig_dismiss_admin_notice=1&ig_option_name=ig_new_admin_ui';
	$contact_us = 'https://www.icegram.com/contact/';
	?>
    <div class="notice notice-success is-dismissable">
        <p>
            <?php echo wp_kses_post(' <strong>New:</strong> We are revamping the admin UI of the campaign setting. <strong><a target="_blank" href="' . $dismiss_url . '" >Here is</a></strong> the sneak-peek of it. Feel free to provide your feedback <strong><a target="_blank" href="' . $contact_us . '" >here</a></strong>'); ?>
        </p>
    </div>

<?php }



 ?>