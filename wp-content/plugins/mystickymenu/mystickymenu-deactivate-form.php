<style>
    .mystickymenu--hidden {
        overflow: hidden;
    }

    .mystickymenu--popup-overlay .mystickymenu--internal-message {
        margin: 3px 0 3px 22px;
        display: none;
    }

    .mystickymenu--reason-input {
        margin: 3px 0 3px 22px;
        display: none;
    }

    .mystickymenu--reason-input input[type="text"] {
        width: 100%;
        display: block;
    }

    .mystickymenu--popup-overlay {
        background: rgba(0, 0, 0, .8);
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        z-index: 1000;
        overflow: auto;
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease-in-out :
    }

    .mystickymenu--popup-overlay.mystickymenu--active {
        opacity: 1;
        visibility: visible;
    }

    .mystickymenu--serveypanel {
        width: 600px;
        background: #fff;
        margin: 65px auto 0;
    }

    .mystickymenu--popup-header {
        background: #f1f1f1;
        padding: 20px;
        border-bottom: 1px solid #ccc;
    }

    .mystickymenu--popup-header h2 {
        margin: 0;
    }

    .mystickymenu--popup-body {
        padding: 10px 20px;
    }

    .mystickymenu--popup-footer {
        background: #f9f3f3;
        padding: 10px 20px;
        border-top: 1px solid #ccc;
    }

    .mystickymenu--popup-footer:after {
        content: "";
        display: table;
        clear: both;
    }

    .action-btns {
        float: right;
    }

    .mystickymenu--anonymous {
        display: none;
    }

    .attention, .error-message {
        color: red;
        font-weight: 600;
        display: none;
    }

    .mystickymenu--spinner {
        display: none;
    }

    .mystickymenu--spinner img {
        margin-top: 3px;
    }

    .mystickymenu--hidden-input {
        padding: 10px 0 0;
        display: none;
    }
    .mystickymenu--popup-body textarea {
        padding: 10px;
        width: 100%;
        height: 100px;
        margin: 0 0 10px 0;
    }

    span.mystickymenu--error-message {
        color: #dd0000;
        font-weight: 600;
    }
    .mystickymenu--popup-body h3 {
        line-height: 24px;
    }
    .mystickymenu--popup-overlay .form-control input {
        width: 100%;
        margin: 0 0 15px 0;
    }
</style>

<div class="mystickymenu--popup-overlay">
    <div class="mystickymenu--serveypanel">
        <form action="#" method="post" id="mystickymenu--deactivate-form">
            <div class="mystickymenu--popup-header">
                <h2><?php _e('Quick feedback about My Sticky Menu', "mystickymenu"); ?> 🙏</h2>
            </div>
            <div class="mystickymenu--popup-body">
                <h3><?php _e('Your feedback will help us improve the product, please tell us why did you decide to deactivate My Sticky Menu :)', "mystickymenu"); ?></h3>
                <div class="form-control">
                    <input type="email" value="<?php echo get_option( 'admin_email' ) ?>" placeholder="<?php echo _e("Email address", "mystickymenu") ?>" id="mystickymenu-deactivation-email_id">
                </div>
                <div class="form-control">                    
                    <textarea placeholder="<?php echo _e("Your comment", "mystickymenu") ?>" id="mystickymenu-deactivation-comment"></textarea>
                </div>
            </div>
            <div class="mystickymenu--popup-footer">
                <label class="mystickymenu--anonymous">
                    <input type="checkbox"/><?php _e('Anonymous feedback', "mystickymenu"); ?>
                </label>
                <input type="button" class="button button-secondary button-skip mystickymenu--popup-skip-feedback" value="Skip &amp; Deactivate">
                <div class="action-btns">
                    <span class="mystickymenu--spinner"><img src="<?php echo admin_url('/images/spinner.gif'); ?>" alt=""></span>
                    <input type="submit" class="button button-secondary button-deactivate mystickymenu--popup-allow-deactivate" value="Submit &amp; Deactivate" disabled="disabled">
                    <a href="#" class="button button-primary mystickymenu--popup-button-close"><?php _e('Cancel', "mystickymenu"); ?></a>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    (function ($) {

        $(function () {

            var pluginSlug = 'mystickymenu';
            // Code to fire when the DOM is ready.

            $(document).on('click', 'tr[data-slug="' + pluginSlug + '"] .deactivate', function (e) {
                e.preventDefault();

                $('.mystickymenu--popup-overlay').addClass('mystickymenu--active');
                $('body').addClass('mystickymenu--hidden');
            });
            $(document).on('click', '.mystickymenu--popup-button-close', function () {
                close_popup();
            });
            $(document).on('click', ".mystickymenu--serveypanel,tr[data-slug='" + pluginSlug + "'] .deactivate", function (e) {
                e.stopPropagation();
            });

            $(document).on( 'click', function () {
                close_popup();
            });
            $('.mystickymenu--reason label').on('click', function () {
                $(".mystickymenu--hidden-input").hide();
                jQuery(".mystickymenu--error-message").remove();
                if ($(this).find('input[type="radio"]').is(':checked')) {
                    $(this).closest("li").find('.mystickymenu--hidden-input').show();
                }
            });
            $(document).on("keyup", "#mystickymenu-deactivation-comment", function(){
                if($.trim($(this).val()) == "") {
                    $(".mystickymenu--popup-allow-deactivate").attr("disabled", true);
                } else {
                    $(".mystickymenu--popup-allow-deactivate").attr("disabled", false);
                }
            });
            $('input[type="radio"][name="mystickymenu--selected-reason"]').on('click', function (event) {
                $(".mystickymenu--popup-allow-deactivate").removeAttr('disabled');
            });
            $(document).on('submit', '#mystickymenu--deactivate-form', function (event) {
                event.preventDefault();
                _reason = "";
                if(jQuery.trim(jQuery("#mystickymenu-deactivation-comment").val()) == "") {
                    jQuery("#alt_plugin").after("<span class='mystickymenu--error-message'>Please provide your feedback</span>");
                    return false;
                } else {
                    _reason = jQuery.trim(jQuery("#mystickymenu-deactivation-comment").val());
                }

                jQuery('[name="mystickymenu--selected-reason"]:checked').val();

                var email_id = jQuery.trim(jQuery("#mystickymenu-deactivation-email_id").val());

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'mystickymenu_plugin_deactivate',
                        reason: _reason,
                        email_id: email_id,
                        nonce: '<?php echo wp_create_nonce("mystickymenu_deactivate_nonce") ?>'
                    },
                    beforeSend: function () {
                        $(".mystickymenu--spinner").show();
                        $(".mystickymenu--popup-allow-deactivate").attr("disabled", "disabled");
                    }
                }).done(function (status) {
                    $(".mystickymenu--spinner").hide();
                    $(".mystickymenu--popup-allow-deactivate").removeAttr("disabled");
                    window.location.href = $("tr[data-slug='" + pluginSlug + "'] .deactivate a").attr('href');
                });
            });

            $('.mystickymenu--popup-skip-feedback').on('click', function (e) {
                window.location.href = $("tr[data-slug='" + pluginSlug + "'] .deactivate a").attr('href');
            })

            function close_popup() {
                $('.mystickymenu--popup-overlay').removeClass('mystickymenu--active');
                $('#mystickymenu--deactivate-form').trigger("reset");
                $(".mystickymenu--popup-allow-deactivate").attr('disabled', 'disabled');
                $(".mystickymenu--reason-input").hide();
                $('body').removeClass('mystickymenu--hidden');
                $('.message.error-message').hide();
            }
        });

    })(jQuery); // This invokes the function above and allows us to use '$' in place of 'jQuery' in our code.
</script>
