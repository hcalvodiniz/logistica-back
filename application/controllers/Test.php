<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$files = $_FILES['files'];

		$routes = $this->doUpload('pruebita', $files);

		$array_batch = [];

		foreach($routes as $v) {
			$array_batch[] = [
				'filename' => $v,
				'truck_id' => 2
			];
		}

		if ($routes === FALSE) {
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type("application/json")
				->set_output(json_encode([
					'success' => FALSE,
					'msg' => $this->upload->display_errors(),
				]));
				return;
		}

		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type("application/json")
			->set_output(json_encode($array_batch));
	}

	protected function doUpload($title, $files) {
		$config = [
			'upload_path' => './uploads/trucks/',
			'allowed_types' => 'jpeg|png',
			'overwrite' => 1,
			'max_size' => 5120
		];
		$this->load->library('upload', $config);

		foreach ($files['name'] as $key => $image) {
			$_FILES['images']['name'] = $files['name'][$key];
			$_FILES['images']['type'] = $files['type'][$key];
			$_FILES['images']['tmp_name'] = $files['tmp_name'][$key];
			$_FILES['images']['error'] = $files['error'][$key];
			$_FILES['images']['size'] = $files['size'][$key];

			$config['file_name'] = $title.'_'.$image;

			$this->upload->initialize($config);

			if ($this->upload->do_upload('images')) {
				$names[] = $this->upload->data('file_name');
			} else {
				return FALSE;
			}
		}
		return $names;
	}
}