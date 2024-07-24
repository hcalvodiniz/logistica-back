<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
	}

	public function login() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			$this->load->library('form_validation');
			$_POST = json_decode(file_get_contents("php://input"), TRUE);

			$this->form_validation->set_rules('username', 'Usuario', 'required');
			$this->form_validation->set_rules('password', 'Contraseña', 'required');
			$this->form_validation->set_message('required', '¡El Campo {field} es requerido!');

			if ($this->form_validation->run() == FALSE) {
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

				$userLogin = $this->user->login($data);

				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type("application/json")
					->set_output(json_encode($userLogin));
			}
		} else {
			show_error(' Not allowed '.$_SERVER['REQUEST_METHOD'].' method', 400);
		}
	}
}