<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class Bootstrap_Builder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function create_custom_post() {
		$labels = array(
			'name' 					=> 'Bootstrap Page Builder',
			'singular_name'			=> 'Bootstrap Page',
			'menu_name'				=> 'Bootstrap Pages',
			'add_new'				=> 'Add New',
			'add_new_item'  		=> 'Add New Bootstrap Page',
			'view_item'				=> 'Edit Page'
		);
		$args = array(
			'labels' => $labels,
			'has_archive' => false,
	 		'public' => true,
	 		'show_ui' => true,
	 		'show_in_nav_menus' => false,
	 		'show_in_menu' => true,
	 		'show_in_admin_bar' => false,
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
			'exclude_from_search' => true,
			'capability_type' => 'page',
		);
		register_post_type('bootstrap-builder', $args);

		// flush the permalink rewrite rules if the plugin is being activated
		if ( defined('BOOTSTRAP_BUILDER_ACTIVATED') ) {
			flush_rewrite_rules();
		}
	}

	public function remove_permalink_slugs($post_link, $post, $leavename) {

	    if('bootstrap-builder' != $post->post_type ||'publish' != $post->post_status) {
	        return $post_link;
	    }

	    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

	    return $post_link;
	}


	public function parse_permalink_slugs($query) {

	    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
	        return;
	    }
	 
		if ( isset($query->query['pagename'] ) && !isset($query->queried_object ) ) {
			$query->query_vars['post_type'] = 'bootstrap-builder';
			$query->query_vars['name'] = $query->query['pagename'];
			$query->is_single = true;
		}
	}

	public function add_editor_script() {
		if ( current_user_can('administrator') 
			&& NULL !== ($post = get_queried_object()) 
			&& is_a($post, 'WP_Post') 
			&& $post->post_type == 'bootstrap-builder' ) {
			if ( isset($_GET['mode']) && 'edit' == $_GET['mode'] ) {
				wp_enqueue_media($post->ID);
				wp_enqueue_style( $this->plugin_name, BOOTSTRAP_BUILDER_BASEURL . '/assets/css/bootstrap-builder-ui.css', array(), $this->version, 'all' );
				wp_enqueue_script( $this->plugin_name, BOOTSTRAP_BUILDER_BASEURL . '/assets/bootstrap/dist/js/bootstrap.min.js', array('jquery'), $this->version, 'all' );
				add_filter('the_content', array($this, 'add_editor_angular'));
			} else {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bootstrap-builder-public.css', array(), $this->version, 'all' );
				add_action('admin_bar_menu', $this->add_editor_link($post), 40);
			}
		}
	}

	private function add_editor_link($post) {
		return function($bar) use($post) {
			$permalink = get_permalink($post);
			$permalink .= (( strpos($permalink, '?') !== FALSE ) ? '&' : '?') . 'mode=edit';
			$bar->add_menu(array(
				'id' => 'bootstrap_builder_editbutton',
				'title' => 'Live Bootstrap Builder',
				'href' => $permalink,
				'meta' => array(
					'class' => 'bootstrap_builder_editbutton'
				)
			));
		};
	}

	public function add_editor_angular() {
		ob_start();
		include BOOTSTRAP_BUILDER_BASEDIR . 'ci/application/views/page/editor.php';
		$content = ob_get_clean();
		return $content;
	}

}
