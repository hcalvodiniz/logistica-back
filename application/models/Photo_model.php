<?php 

class Photo_model extends CI_Model {
	public $table = 'trucks_photos';

	function __construct() {
		parent::__construct();
		$this->load->database('default');
	}
}