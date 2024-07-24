<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Travel extends CI_Controller {
	
	public $diniz;
	public $default;

	public function __construct() {
		parent::__construct();
		$this->diniz = $this->load->database('diniz', TRUE);
		$this->default = $this->load->database('default', TRUE);
	}

	public function get_locales() {
		$this->diniz->from('locales');
		$this->diniz->where('descripcion is NOT NULL');
		$this->diniz->where('tipo_cef', '1');
		$this->diniz->order_by('descripcion', 'ASC');
		$result = $this->diniz->get();
		$result = $this->pluck($result->result_array(), 'descripcion', 'local');

		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type("application/json")
			->set_output(json_encode($result));
	}

	public function store() {
		$this->load->library('form_validation');
		$_POST = json_decode(file_get_contents("php://input"), TRUE);

		$this->form_validation->set_rules('departure_date', 'Fecha de Salida', 'callback_valid_date');
		$this->form_validation->set_rules('departure_time', 'Hora de Salida', 'required');
		$this->form_validation->set_rules('origin', 'Origen', 'required');
		$this->form_validation->set_rules('destination', 'Destino', 'required');
		$this->form_validation->set_rules('driver_id', 'Conductor', 'required');
		$this->form_validation->set_rules('truck_id', 'Camión / Vehiculo', 'required');

		if($this->form_validation->run() === FALSE){
			$response = [
				'success' => FALSE,
				'msg' => validation_errors(),
				'data' => null
			];

			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type("application/json")
				->set_output(json_encode($response));
		} else {
			$data = $this->input->post();
			$mileage = explode(',', $data['initial_mileage']);
			$data['initial_mileage'] = intval(implode('', $mileage));
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type("application/json")
				->set_output(json_encode([
					"success" => TRUE,
					"msg" => "Se ha guardado datos del viaje!",
					"data" => $data
				]));
		}
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