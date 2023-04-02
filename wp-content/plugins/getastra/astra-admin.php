<?php

add_action('admin_menu', 'astra_add_pages');

/**
 * Function add pages
 *
 * @return void
 */
function astra_add_pages()
{
    add_menu_page(__('ASTRA', 'menu-test'), __('ASTRA', 'menu-test'), 'manage_options', 'astra-security', 'astra_admin_page', 'dashicons-shield-alt');
}

/**
 * Function admin pages
 *
 * @return void
 */
function astra_admin_page()
{
    if (! current_user_can('manage_options')) {
        die("Sorry, but you do not have permission to perform this action.");
    }
    $autoload_file = "Astra.php";
    $config_file   = "astra-config.php";


    $firewall_path = ASTRA_PLUGIN_PATH . 'astra/';
    if (file_exists($firewall_path . $autoload_file)) {
        if (is_admin()) {
            include_once $firewall_path . $autoload_file;
            $astra = new Astra();
            $token = $astra->get_sso_token();
            astra_display_area();
        }
    } else {
        astra_display_area();
    }
}


/**
 * Function security scripts
 *
 * @return void
 */
function astra_security_scripts()
{

    // create my own version codes
    $astra_style         = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'css/style.css'));
    $astra_all           = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'css/all.css'));
    
    
    wp_register_script('astra_security_bootstrap_js',plugins_url('js/bootstrap.min.js', __FILE__) , array('jquery'), '3.4.1');
    wp_register_style('astra_security_bootstrap_css', plugins_url('css/bootstrap.min.css', __FILE__) , false, '3.4.1');
    wp_register_style('astra_security_style_css', plugins_url('css/style.css', __FILE__), false, $astra_style);
    wp_register_style('astra_security_all_css', plugins_url('css/all.css', __FILE__), false, $astra_all);
    wp_enqueue_style('astra_security_bootstrap_css');
    wp_enqueue_style('astra_security_style_css');
    wp_enqueue_style('astra_security_all_css');
    wp_enqueue_style('astra_security_font_css');
    wp_enqueue_script('astra_security_bootstrap_js');
}

/**
 * Function display area
 *
 * @return void
 */
function astra_display_area()
{
    astra_security_scripts();
    $authData = ''; ?>

<div class="main-col-inner">
    <div id="messages"></div>
    <div class="content-header">
        <table cellspacing="0">
            <tbody>
            <tr>
                <td style="width:50%;">
                    <img
                        src="<?php //echo $this->escapeHtml($logo)
                        ?>"
                        style="width:70px; float:left"
                    />
                    <h3 style="line-height:70px; margin:0 auto; margin-left:8px; font-size:24px">
                        <?php //echo $this->escapeHtml('Security')
                        ?>
                    </h3></td>
            </tr>
            </tbody>
        </table>

    </div>
    <div class="entry-edit">
        <div id="astrasecurity_content">
            <div class="astra_loader"></div>
            <div class="row">
                <div class="col-lg-12" data-url="<?php echo get_site_token(); ?>">
                    <?php if (astra_core_module_installed_status()) { ?>

                    <div class="full-width">
                        <div class="row header-img">
                            <div class="col-sm">
                            </div>
                            <div class="col-sm">
                                <figure class="logo-fix">
                                    <img src="<?php echo plugins_url('/img/astra-s-white-logo.png', __FILE__); ?>"
                                         title="Astra Web Security" alt="Astra Web Security">
                                    <figcaption class="widget-image-caption wp-caption-text">Wordpress Security
                                    </figcaption>
                                </figure>
                            </div>
                            <div class="col-sm">
                            </div>

                        </div>

                    </div>

                    <div class="full-width margin-to-div">
                        <div class="row">

                            <div class="col-sm-3 center-text">
                                <a target="_blank" href="https://dash.getastra.com/dashboard/<?php echo CZ_SITE_KEY; ?>"
                                   class="btn btn-primary mb-2 dashboard">Dashboard</a>
                            </div>
                            <div class="col-sm-3 center-text">
                                <a target="_blank" href="https://dash.getastra.com/threats/<?php echo CZ_SITE_KEY; ?>"
                                   class="btn btn-primary mb-2 threats">Threats</a>
                            </div>
                            <div class="col-sm-3 center-text">
                                <a target="_blank" href="https://dash.getastra.com/settings/<?php echo CZ_SITE_KEY; ?>"
                                   class="btn btn-primary mb-2 settings">Settings</a>
                            </div>
                            <div class="col-sm-3 center-text">
                                <a target="_blank" href="https://dash.getastra.com/activity/<?php echo CZ_SITE_KEY; ?>"
                                   class="btn btn-primary mb-2 activity">Activity</a>
                            </div>
                        </div>

                    <?php } else { ?>
                            <div class="full-width">
                                <div class="row header-img">
                                    <div class="col-sm">
                                    </div>
                                    <div class="col-sm">
                                        <figure class="logo-fix">
                                            <img
                                                src="<?php echo plugins_url('/img/astra-s-white-logo.png', __FILE__); ?>"
                                                title="Astra Web Security" alt="Astra Web Security">
                                            <figcaption class="widget-image-caption wp-caption-text">Wordpress
                                                Security
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-sm">
                                    </div>

                                </div>

                            </div>

                            <!-------- Second section -------->

                            <div class="full-width">
                                <div class="row second-section">
                                    <div class="col-sm-6">
                                        <h1 class="">Already a customer?</h1>
                                    </div>
                                    <div class="col-sm-5">
                                        <h1><a target="_blank"
                                               href="https://dash.getastra.com/connect?iframe=&<?php echo astra_get_auth_data(); ?>">Connect
                                                to Astra</a></h1>
                                    </div>
                                </div>
                            </div>
                            <!------- 3rd section -------->

                            <div class="full-width">
                                <div class="row three-section">
                                    <div class="col-sm-6">
                                        <h1 class="">New customer?</h1>
                                    </div>
                                    <div class="col-sm-5">
                                        <h1><a href="#">Follow Steps Below</a></h1>
                                    </div>
                                </div>
                            </div>

                            <!------- four section -------->

                            <div class="full-width">

                                <div class="row four-section">
                                    <div class="col-sm-6">
                                    <div class="four-section-first">
                                    <span class="col-sm-1">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                            <div class="col-sm-10 four-section-second">
                                                <h3>STEP 1 - MAKE ASTRA ACCOUNT</h3>
                                                <p class="elementor-icon-box-description">Choose a plan and <a
                                                        href="https://astra.sh/pricing?utm_source=WordPress"
                                                        target="_blank">Sign-up from here</a> to create an Astra account.
                                             
                                                 </p>
                                            </div>
                                        </div>

                                        <div class="four-section-first">
                                            <span class="col-sm-1">
                                                <i class="fas fa-bolt"></i>
                                            </span>
                                            <div class="col-sm-10 four-section-second">
                                                <h3>STEP 2 - ENTER YOUR WEBSITE </h3>
                                                <p class="elementor-icon-box-description">Enter your website in the Astra dashboard and connect to the dashboard with the following button below.
                                                </p>
                                            </div>
                                        </div>

                                        <div class="four-section-first">
                                            <span class="col-sm-1">
                                                <i class="fas fa-bullseye"></i>
                                            </span>
                                            <div class="col-sm-10 four-section-second">
                                                <h3>STEP 3 - CLICK ON CONNECT TO ASTRA</h3>
                                                <p class="elementor-icon-box-description">Once your account is added,
                                                    simply click on 'Connect to Astra' button here & login to your Astra
                                                    dashboard. Astra will now be installed on your website 🙂</p>
                                            </div>
                                        </div>

                                        <div class="four-section-first">

                                            <div class="col-sm-12 four-section-second connect-to-astra">
                                                <a href="https://dash.getastra.com/connect?iframe=&<?php echo astra_get_auth_data(); ?>">Connect
                                                    to Astra</a>
                                            </div>
                                        </div>

                                    </div>

                                    <!-------- 4th second -------->
                                    <div class="col-sm-6">
                                        <div class="img-four-section">

                                            <img
                                                src="<?php echo plugins_url('/img/Astra-dashboard1.png', __FILE__); ?>"
                                                class="attachment-full size-full" alt="Astra-dashboard-wordpress"/>

                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="full-width">

                                <div class="row testimonials">
                                    <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                        <!-- Indicators -->


                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner">
                                            <div class="item active">
                                                <div class="col-sm-1 img-div-testimon"><img
                                                        src="<?php echo plugins_url('/img/Ingrid_bw.png', __FILE__); ?>"
                                                        alt="Los Angeles"></div>
                                                <div class="col-sm-10 elementor-testimonial__text"><p>Astra saved my
                                                        WordPress site from the dreaded Japanese SEO hack. Ever since I
                                                        am super happy with their effort. The team is always super
                                                        helpful, super quick to resolve any issues as well as
                                                        friendly! </p>
                                                    <cite class="col-sm-12 elementor-testimonial__cite">
                                                        <span class="elementor-testimonial__name">Ingrid Kjelling</span>
                                                        </br>
                                                        <span class="elementor-testimonial__title"> Owner Ingrid Kjelling Photography</span>
                                                    </cite>
                                                </div>

                                            </div>

                                            <div class="item">
                                                <div class="col-sm-1 img-div-testimon"><img
                                                        src="<?php echo plugins_url('/img/download.jpeg', __FILE__); ?>"
                                                        alt="Los Angeles"></div>
                                                <div class="col-sm-10 elementor-testimonial__text"><p>Great support! The
                                                        team at Astra immediately addressed all my concerns and our
                                                        website was secured within minutes. I'd definitely recommend to
                                                        others. </p>
                                                    <cite class="col-sm-12 elementor-testimonial__cite">
                                                        <span class="elementor-testimonial__name">Brian Reiff</span>
                                                        </br>
                                                        <span class="elementor-testimonial__title"> President DHS Digital</span></cite>
                                                </div>

                                            </div>

                                            <div class="item">
                                                <div class="col-sm-1 img-div-testimon"><img
                                                        src="<?php echo plugins_url('/img/jamiefeldman.jpg', __FILE__); ?>"
                                                        alt="Los Angeles"></div>
                                                <div class="col-sm-10 elementor-testimonial__text"><p>We are highly
                                                        impressed with the prompt service and level of professionalism
                                                        Astra showed. Human customer support 24x7. They go the extra
                                                        mile for their customer, Legends. </p>
                                                    <cite class="col-sm-12 elementor-testimonial__cite">
                                                        <span class="elementor-testimonial__name">Jamie Feldman</span>
                                                        </br>
                                                        <span class="elementor-testimonial__title"> Founder Think Organic</span></cite>
                                                </div>

                                            </div>
                                        </div>
                                        <ol class="carousel-indicators">
                                            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                                            <li data-target="#myCarousel" data-slide-to="1"></li>
                                            <li data-target="#myCarousel" data-slide-to="2"></li>
                                        </ol>
                                        <!-- Left and right controls -->

                                    </div>
                                </div>
                            </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * Function core module installed status
 *
 * @return bool
 */
function astra_core_module_installed_status()
{
    /**
     * Checks whether astra core module is installed or not
     */
    $firewall_path = ASTRA_PLUGIN_PATH . 'astra/';
    //@codingStandardsIgnoreStart
    if (is_dir($firewall_path)) {
        return true;
    }
    
    return false;
        
    //@codingStandardsIgnoreEnd
}

/**
 * Function get auth data
 *
 * @return bool
 */
function astra_get_auth_data()
{
    $authData = array(
        'cms'        => 'wordpress',
        'version'    => get_bloginfo('version'),
        'client_uri' => admin_url('admin-ajax.php') . '?action=Astra_install',
        'token'      => get_site_token(),
    );

    return http_build_query($authData);
}


/**
 * Function site token
 *
 * @return bool
 */
function get_site_token()
{
    $dbname = DB_NAME;
    $key    = site_url('/');

    //echo $dbname.$key.__FILE__;
    return sha1($dbname . $key . __DIR__);
}

?>
