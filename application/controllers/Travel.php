<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Travel extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->database('diniz');
	}

	public function get_locales() {
		$this->db->from('locales');
		$this->db->where('descripcion is NOT NULL');
		$this->db->select('local, descripcion');
		$this->db->order_by('descripcion', 'ASC');
		$result = $this->db->get();

		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type("application/json")
			->set_output(json_encode($result->result_array()));
	}
}