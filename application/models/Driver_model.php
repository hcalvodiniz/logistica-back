<?php 

class Driver_model extends CI_Model {
	public $table = 'drivers';

	function __construct() {
		parent::__construct();
		$this->load->database('default');
	}
}