<?php

// BFCM 2022 Campaign
if ( ( get_option( 'ig_offer_bfcm_2022_icegram' ) !== 'yes' ) && Icegram::is_offer_period( 'bfcm') ) { 
    $img_url = $this->plugin_url .'/assets/images/bfcm_2022.png';
    $ig_plan = get_option( 'ig_engage_plan', 'lite' );
    if( 'max' === $ig_plan ){
        $img_url = $this->plugin_url .'/assets/images/common_bfcm_2022.png';
    }
    // elseif( 'plus' === $ig_plan || 'pro' === $ig_plan ){
    //     $img_url = $this->plugin_url .'/assets/images/bfcm2021_pro.png';
    // }

    ?>
    <style type="text/css">
        .ig_sale_offer {
            width: 65%;
            margin: 0 auto;
            text-align: center;
            padding-top: 1.2em;
        }

    </style>
    <div class="ig_sale_offer">
        <a target="_blank" href="?ig_dismiss_admin_notice=1&ig_option_name=ig_offer_bfcm_2022"><img src="<?php echo $img_url; ?>"/></a>
    </div>
<?php } ?>