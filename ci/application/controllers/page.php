<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
		
	public function __construct() {
		parent::__construct();
		if ( ! $this->wpintegration->isSuperAdmin() ) {
			redirect(site_url('wp-admin'));
		}
	}

	public function launch($postID) {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('post_title', 'Post Title', 'required');
		$resp = array();
		if ( $this->form_validation->run() ) {
			$permalink = get_permalink($postID);
			$permalink .= (( strpos($permalink, '?') !== FALSE ) ? '&' : '?') . 'mode=edit';
			$resp['success'] = TRUE;
			$resp['redirect'] = $permalink;
		} else {
			$resp['success'] = FALSE;
		}

		header('Content-type: application/json');
		echo json_encode($resp);
	}

	public function contents($postID) {
		header('Content-type: application/json');
		echo ( !empty($contents = get_post_meta($postID, '_bootstrap_builder_content', TRUE)) )
			? json_encode($contents) : '[]';
	}

	public function save($postID) {
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('contents', 'Page Contents', 'required|callback__valid_contents');
		if ( FALSE === ($success = $this->form_validation->run()) ) {
			if ( empty($message = validation_errors()) ) {
				// if the form didn't submit properly and there are no validation errors it's a GET
				echo form_open('page/save/'.$postID, 'id="frmSave"', array('contents' => '')).form_close();
			} else {
				// if there are validation errors display them
				header('Content-type: application/json');
				echo json_encode(compact('success', 'message'));
			}
		} else {
			// form submitted properly, handle the contents
			$this->load->helper('partials');
			$contents = $this->input->post('contents');
			update_post_meta($postID, '_bootstrap_builder_content', $contents);
			wp_update_post(array(
				'ID' => $postID,
				'post_content' => $this->load->view('page/wrapper', array('contents' => $contents), TRUE)
			));
			header('Content-type: application/json');
			echo json_encode(compact('success'));
		}
	}

	public function _valid_contents($contents) {
		if ( get_magic_quotes_gpc() || strpos($contents, '\\\\\\"') !== FALSE ) {
			$contents = stripslashes($contents); 
		}
		$contents = preg_replace_callback(
			'/([\xc0-\xdf].)/s', 
			function($m){
				return '&#' . ((ord(substr($m[1], 0, 1)) - 192) * 64 + (ord(substr($m[1], 1, 1)) - 128)) . ';';
			}, 
			$contents);
		$contents = preg_replace_callback(
			'/([\xe0-\xef]..)/s', 
			function($m){
				return '&#' . ((ord(substr($m[1], 0, 1)) - 224) * 4096 + (ord(substr($m[1], 1, 1)) - 128) * 64 + (ord(substr($m[1], 2, 1)) - 128)) . ';';
			},
			$contents); 
		$decodedContent =@ json_decode(str_replace(array('&lt;','&gt;',' < ',' > '),array('<','>',' &lt; ',' &gt; '),htmlentities($contents,ENT_NOQUOTES,'UTF-8',false)),true);
		if ( empty($decodedContent) ) {
			$this->form_validation->set_message('_valid_contents', 'The %s field is required and must be valid content JSON');
			return FALSE;
		}
		return $decodedContent;
	}
	
}
