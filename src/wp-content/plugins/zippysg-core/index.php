<?php
/*
Plugin Name: ZippySG Core
Plugin URI: https://zippy.sg/
Description: Don't Remove. Extends Code important.
Version: 1.0
Author: Zippy SG
Author URI: https://zippy.sg/
License: GNU General Public License v3.0
License URI: https://zippy.sg/
*/
/*** Disable XML-RPC ***/
add_filter('xmlrpc_enabled', '__return_false');

/***<!-- ---####*** Edit teamplate design Post/Page ***####--- ***/
add_filter('use_block_editor_for_post', '__return_false');

/***<!-- ---####*** ADD SMPT Not Need Plugin ***####--- ***/
add_action( 'phpmailer_init', 'setup_phpmailer_init' );
function setup_phpmailer_init( $phpmailer ) {
    $phpmailer->Host = 'smtp.gmail.com'; // for example, smtp.mailtrap.io
    $phpmailer->Port = 587; // set the appropriate port: 465, 2525, etc.
    $phpmailer->Username = 'smtp.connect.server@gmail.com'; // your SMTP username
    $phpmailer->Password = 'zsgobbdpfbwfskns'; // your SMTP password
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = 'tls'; // preferable but optional
    $phpmailer->IsSMTP();
}

/*** How to Change the Logo and URL on the WordPress Login Page ***/
add_filter('login_headerurl','custom_loginlogo_url');
function custom_loginlogo_url($url) {
	return '/';
}

// <!-- ---####*** Disable All Update Notifications with Code ***####--- --> 
function remove_core_updates(){
	global $wp_version;return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates');
add_filter('pre_site_transient_update_plugins','remove_core_updates');
add_filter('pre_site_transient_update_themes','remove_core_updates');

/*** Add css in Login Form on Dashboard ***/
function my_login_stylesheet() {?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image:url("/wp-content/uploads/2023/12/logo.png");
            width: 100%;
            background-size: 48%;
        }
        #login form#loginform .input, #login form#registerform .input, #login form#lostpasswordform .input {
            border-width: 0px;
            border-radius: 0px;
            box-shadow: unset;
        }
        #login form#loginform .input, #login form#registerform .input, #login form#lostpasswordform .input {border-bottom: 1px solid #d2d2d2;}
        #login form .submit .button {
            background-color: #1f2222;
            width: 100%;
            height: 40px;
            border-width: 0px;
            margin-top: 10px;
        }
        .login .button.wp-hide-pw{color: #1f2222;}
    </style>

<?php }
add_action( 'login_enqueue_scripts', 'my_login_stylesheet' );

// remove version from head
remove_action('wp_head', 'wp_generator');

// remove version from rss
add_filter('the_generator', '__return_empty_string');

// remove version from scripts and styles
function collectiveray_remove_version_scripts_styles($src) {
	if (strpos($src, 'ver=')) {
		$src = remove_query_arg('ver', $src);
	}
	return $src;
}
add_filter('style_loader_src','collectiveray_remove_version_scripts_styles',9999);
add_filter('script_loader_src','collectiveray_remove_version_scripts_styles',9999);

// Hiding the WordPress version
function dartcreations_remove_version() {
	return '';
} 
add_filter('the_generator', 'dartcreations_remove_version');

/* ### ---Simplest way to redirect all 404 to homepage in wordpress.--- ### */

if( !function_exists('redirect_404_to_homepage') ){

    add_action( 'template_redirect', 'redirect_404_to_homepage' );

    function redirect_404_to_homepage(){
        if(is_404()):
            wp_safe_redirect( home_url('/') );
            exit;
        endif;
    }
}
