<?php 

class Truck_model extends CI_Model {

	public $insertRules = [
		[
			'field' => 'brand',
			'label' => 'Brand',
			'rules' => 'required',
		],
		[
			'field' => 'model',
			'label' => 'Model',
			'rules' => 'required',
		],
		[
			'field' => 'year',
			'label' => 'Year',
			'rules' => 'required|numeric|max_length[4]',
		],
		[
			'field' => 'mileage',
			'label' => 'Mileage',
			'rules' => 'required|numeric',
		],
		[
			'field' => 'serial_number',
			'label' => 'Serial Number',
			'rules' => 'required|is_unique[trucks.serial_number]',
		]
	];

	public $updateRules = [
		[
			'field' => 'brand',
			'label' => 'Brand',
			'rules' => 'required',
		],
		[
			'field' => 'model',
			'label' => 'Model',
			'rules' => 'required',
		],
		[
			'field' => 'year',
			'label' => 'Year',
			'rules' => 'required|numeric|max_length[4]',
		],
		[
			'field' => 'mileage',
			'label' => 'Mileage',
			'rules' => 'required|numeric',
		],
		[
			'field' => 'serial_number',
			'label' => 'Serial Number',
			'rules' => 'required',
		]
	];
	
	public $table = 'trucks';
	
	function __construct() {
		parent::__construct();
		$this->load->database('default');
	}
}