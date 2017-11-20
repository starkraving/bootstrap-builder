<?php
/**

/**
 * Adds the iframe to display content for the media upload page
 *
 * @since 2.5.0
 *
 * @param string|callable $content_func
 */
if ( !function_exists('ho_iframe') ) {

function ho_iframe($content_func /* ... */) {
	_wp_admin_html_begin();
?>
<title><?php bloginfo('name') ?> &rsaquo; <?php _e('Uploads'); ?> &#8212; <?php _e('WordPress'); ?></title>
<?php

wp_enqueue_style( 'colors' );
// Check callback name for 'media'
if ( ( is_array( $content_func ) && ! empty( $content_func[1] ) && 0 === strpos( (string) $content_func[1], 'media' ) )
	|| ( ! is_array( $content_func ) && 0 === strpos( $content_func, 'media' ) ) )
	wp_enqueue_style( 'media' );
wp_enqueue_style( 'ie' );
$CI =& get_instance();
?>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var ajaxurl = '<?php echo ci_site_url( 'theme/media/'.$CI->session->userdata('permalink') ); ?>', pagenow = 'media-upload-popup', adminpage = 'media-upload-popup',
isRtl = <?php echo (int) is_rtl(); ?>;
</script>
<?php
	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_enqueue_scripts', 'media-upload-popup' );

	/**
	 * Fires when admin styles enqueued for the legacy (pre-3.5.0) media upload popup are printed.
	 *
	 * @since 2.9.0
	 */
	do_action( 'admin_print_styles-media-upload-popup' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_print_styles' );

	/**
	 * Fires when admin scripts enqueued for the legacy (pre-3.5.0) media upload popup are printed.
	 *
	 * @since 2.9.0
	 */
	do_action( 'admin_print_scripts-media-upload-popup' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_print_scripts' );

	/**
	 * Fires when scripts enqueued for the admin header for the legacy (pre-3.5.0)
	 * media upload popup are printed.
	 *
	 * @since 2.9.0
	 */
	do_action( 'admin_head-media-upload-popup' );

	/** This action is documented in wp-admin/admin-header.php */
	do_action( 'admin_head' );

if ( is_string( $content_func ) ) {
	/**
	 * Fires in the admin header for each specific form tab in the legacy
	 * (pre-3.5.0) media upload popup.
	 *
	 * The dynamic portion of the hook, `$content_func`, refers to the form
	 * callback for the media upload type. Possible values include
	 * 'media_upload_type_form', 'media_upload_type_url_form', and
	 * 'media_upload_library_form'.
	 *
	 * @since 2.5.0
	 */
	do_action( "admin_head_{$content_func}" );
}
?>
</head>
<body<?php if ( isset($GLOBALS['body_id']) ) echo ' id="' . $GLOBALS['body_id'] . '"'; ?> class="wp-core-ui no-js">
<script type="text/javascript">
document.body.className = document.body.className.replace('no-js', 'js');
</script>
<?php
	$args = func_get_args();
	$args = array_slice($args, 1);
	call_user_func_array($content_func, $args);

	/** This action is documented in wp-admin/admin-footer.php */
	do_action( 'admin_print_footer_scripts' );
?>
<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
</body>
</html>
<?php
}
}
