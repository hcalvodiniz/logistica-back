<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('driver_model');
	}

	public function index() {
		$drivers = $this->driver_model->all();
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type('application/json')
			->set_output(json_encode($drivers));
	}

	public function show($id) {
		$driver = $this->driver_model->find($id);
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => TRUE,
				'msg' => '',
				'data' => $driver
			]));
	}

	public function store() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('noempl', 'No. Empleado', 'required|numeric');
		$this->form_validation->set_rules('name', 'Nombre', 'required');
		$this->form_validation->set_rules('license_number', 'No. Licencia', 'required|is_unique[drivers.license_number]|numeric');
		$this->form_validation->set_rules('expiration_date', 'Fecha de Expiracion', 'callback_valid_date');

		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if ($this->form_validation->run() == FALSE) {
			$response = [
				'errors' => $this->form_validation->error_array(),
				'success' => FALSE
			];
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type('application/json')
				->set_output(json_encode($response));
		} else {
			$data = $this->input->post();
			$model = $this->driver_model->insert($data);
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => TRUE,
					'data' => $model,
					'msg' => '!Se ha dado de alta al conductor exitosamente!'
				]));
		}
	}

	public function update() {
		$this->load->library('form_validation');
		$this->form_validation->set_rules('noempl', 'No. Empleado', 'required|numeric');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('license_number', 'License Number', 'required');
		$this->form_validation->set_rules('expiration_date', 'Expiration Date', 'callback_valid_date');
		$this->form_validation->set_message('required','El Campo {field} es requerido');
		$this->form_validation->set_message('numeric', 'El Campo {field} debe ser un valor númerico');
		$this->form_validation->set_message('is_unique', 'El valor del campo {field} ya esta registrado');

		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		if ($this->form_validation->run() == FALSE) {
			$response = [
				'success' => FALSE,
				'errors' => $this->form_validation->error_array(),
			];
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type('application/json')
				->set_output(json_encode($response));
		} else {
			$data = $this->input->post();
			if (!isset($data['id'])) {
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type('application/json')
					->set_output(json_encode([
						'success' => FALSE,
						'data' => "No ID present, can't update. Try again!"
					]));
			} else {
				$id = $data['id'];
				unset($data['id']);

				$model = $this->driver_model->update($data, $id);
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type('application/json')
					->set_output(json_encode([
						'success' => TRUE,
						'data' => $model
					]));
			}
		}
	}

	public function delete($id) {
		if($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			$this->truck_model->delete($id);
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_header("Access-Control-Allow-Methods: DELETE")
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => TRUE,
					'msg' => '¡Se ha eliminado exitosamente el conductor!',
					'data' => null
				]));
		} else {
			show_error('Not allowed '.$_SERVER['REQUEST_METHOD'].' method', 400);
		}
	}

	public function list() {
		$drivers = $this->driver_model->all();
		$drivers = $this->pluck($drivers, 'name', 'id');
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type("application/json")
			->set_output(json_encode($drivers));
	}

	public function valid_date($date) {
		$this->load->library('form_validation');
		if(empty($date)) {
			$this->form_validation->set_message('valid_date', 'El Campo {field} es requerido');

			return FALSE;
		}
		$day = substr($date, 8, 2);
		$month = substr($date, 5, 2);
		$year = substr($date, 0, 4);

		$this->form_validation->set_message('valid_date', '{field} no es una fecha valida');

		return checkdate($month, $day, $year);
	}
}