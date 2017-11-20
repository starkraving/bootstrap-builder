<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
* WordPres Integration Class
*
* This class enables the use of wordpress functions
*
* @author	   Oscar Dias
* @link	 http://oscardias.com/codeigniter/integrating-wordpress-with-codeigniter
*/
class Wpintegration {
 
	public function __construct() {
		global $table_prefix, $wp_embed, $wp_widget_factory, $_wp_deprecated_widgets_callbacks, $wp_locale, $wp_rewrite;
		// Additional WordPress global variables
		//$wpdb, $current_user, $auth_secure_cookie, $wp_roles, $wp_the_query, $wp_query, $wp, $_updated_user_settings,
		//$wp_taxonomies, $wp_filter, $wp_actions, $merged_filters, $wp_current_filter, $wp_registered_sidebars,
		//$wp_registered_widgets, $wp_registered_widget_controls, $wp_registered_widget_updates, $_wp_deprecated_widgets_callbacks,
		//$posts, $post, $wp_did_header, $wp_did_template_redirect, $wp_version, $id, $comment, $user_ID;
	 	try {
	 		require_once(ABSPATH.'wp-load.php');
	 	} catch ( any $err ) {
	 		// not sure what to do here;
	 	}
		
	}

	public function isLoggedIn() {
		return is_user_logged_in();
	}
	
	public function isSuperAdmin() {
		return ( wp_get_current_user()->user_level >= 10 );
	}
	
	public function loginLink() {
		$CI = & get_instance();
		$CI->load->helper('url');
		$redirect = ci_current_url();
	 
		return wp_login_url()."?redirect_to=$redirect";
	}
 
	public function logoutLink() {
		$CI = & get_instance();
		$CI->load->helper('url');
		$redirect = ci_current_url();
	 
		return wp_logout_url()."&redirect_to=$redirect";
	}
	
	
}
/* End of file Wpintegration.php */