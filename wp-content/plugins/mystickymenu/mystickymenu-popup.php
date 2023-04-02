<div class="mystickymenu-popup" id="mystickymenu-intro-popup">
    <div class="mystickymenu-popup-box">
        <div class="mystickymenu-popup-header">
            Welcome to myStickymenu &#127881;
            <button class="close-mystickymenu-popup"><span class="dashicons dashicons-no-alt"></span></button>
            <div class="clear"></div>
        </div>
        <div class="mystickymenu-popup-content">
            With myStickymenu you can make your website's menu sticky. You can also use it to create a welcome notification bar. Need help? Visit our <a target="_blank" href="https://premio.io/help/mystickymenu/?utm_source=pluginonboarding">Help Center</a>.
            <iframe width="420" height="240" src="https://www.youtube.com/embed/5sebFgUMpDA"></iframe>
        </div>
        <div class="mystickymenu-popup-footer">
            <button type="button">Go to myStickymenu</button>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery(document).on("click", ".mystickymenu-popup-box button, #mystickymenu-intro-popup", function(e){
            e.stopPropagation();
            var nonceVal = "<?php echo wp_create_nonce("mystickymenu_update_popup_status") ?>";
            jQuery("#mystickymenu-intro-popup").remove();
            jQuery.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'mystickymenu_update_popup_status',
                    nonce: nonceVal
                },
                beforeSend: function (xhr) {

                },
                success: function (res) {

                },
                error: function (xhr, status, error) {

                }
            });
        });

        jQuery(document).on("click", ".mystickymenu-popup-box", function(e){
            e.stopPropagation();
        });
    });
</script>