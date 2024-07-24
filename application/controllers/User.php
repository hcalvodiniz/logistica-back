<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('User_model', 'user');
	}

	public function index() {
		$users = $this->user->all();
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type('application/json')
			->set_output(json_encode($users));
	}

	public function store() {
		$this->load->library('form_validation');
		$_POST = json_decode(file_get_contents("php://input"), true);

		$this->form_validation->set_rules('noempl', 'No. Empleado', 'required|numeric');
		$this->form_validation->set_rules('name', 'Nombre', 'required');
		$this->form_validation->set_rules('email', 'Correo', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Contraseña', 'required');
		$this->form_validation->set_rules('password_confirmation', 'Confirmar Contraseña', 'required|matches[password]');

		if($this->form_validation->run() === FALSE) {
			$response = [
				'msg' => validation_errors(),
				'success' => FALSE,
				'data' => null
			];

			 $this->output
			 	->set_header("Access-Control-Allow-Origin: *")
			 	->set_header("Access-Control-Allow-Headers: *")
			 	->set_content_type("application/json")
			 	->set_output(json_encode($response));
		} else {
			$data = $this->input->post();
			$data['username'] = $data['noempl'];
			$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
			unset($data['password_confirmation']);
			$model = $this->user->insert($data);

			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type("application/json")
				->set_output(json_encode([
					'success' => TRUE,
					'data' => $model,
					'msg' => '¡Se ha dado de alta el usuario exitosamente!'
				]));
		}
	}

	public function show($id) {
		$user = $this->user->find($id);
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type("application/json")
			->set_output(json_encode([
				'success' => TRUE,
				'msg' => '',
				'data' => $user
			]));
	}

	public function update() {
		$this->load->library('form_validation');
		$_POST = json_decode(file_get_contents("php://input"), true);


		$this->form_validation->set_rules('noempl', 'No. Empleado', 'required|numeric');
		$this->form_validation->set_rules('name', 'Nombre', 'required');
		$this->form_validation->set_rules('email', 'Correo', 'required|valid_email');

		if($this->form_validation->run() === FALSE) {
			$response = [
				'msg' => validation_errors(),
				'success' => FALSE,
				'data' => null
			];
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type("application/json")
				->set_output(json_encode($response));
		} else {
			$data = $this->input->post();
			if (!isset($data['id'])) {
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type("application/json")
					->set_output(json_encode([
						'success' => FALSE,
						'data' => null,
						'msg' => 'No hay ID presente, no se puede actualizar!'
					]));
			} else {
				$id = $data['id'];
				unset($data['id']);
				$data['username'] = $data['noempl'];
				if(!empty($data['password'])) {
					if($data['password'] !== $data['password_confirmation']) {
						$this->output
							->set_header("Access-Control-Allow-Origin: *")
							->set_header("Access-Control-Allow-Headers: *")
							->set_content_type("application/json")
							->set_output(json_encode([
								'success' => FALSE,
								'data' => null,
								'msg' => 'Las contraseñas no coinciden!'
							]));
					} else {
						$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
						unset($data['password_confirmation']);
					}
				} else {
					unset($data['password']);
					unset($data['password_confirmation']);
				}
				$model = $this->user->update($data, $id);
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type("application/json")
					->set_output(json_encode([
						'success' => TRUE,
						'data' => $model,
						'msg' => '¡Se ha actualizado correctamente el usuario!'
					]));
			}
		}
	}

	public function delete($id) {
		if ($_SERVER['REQUEST_METHOD'] === 'DELETE' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			$this->user->delete($id);
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_header("Access-Control-Allow-Methods: DELETE, OPTIONS")
				->set_content_type("application/json")
				->set_output(json_encode([
					'success' => TRUE,
					'data' => null,
					'msg' => '¡Se ha eliminado correctamente el usuario!'
				]));
		} else {
			show_error('Not allowed '.$_SERVER['REQUEST_METHOD'].' method', 400);
		}
	}

	public function password_reset($id) {
		if($_SERVER['REQUEST_METHOD'] === 'PATCH' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
			$temp_password = password_hash("123456", PASSWORD_BCRYPT);
			$model = $this->user->update(['password' => $temp_password], $id);
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_header("Access-Control-Allow-Methods: PATCH, PUT, OPTIONS")
				->set_content_type("application/json")
				->set_output(json_encode([
					'success' => TRUE,
					'data' => $model,
					'msg' => 'Se ha actualizado la contraseña del usuario.'
				]));
		} else {
			show_error('Not allowed '.$_SERVER['REQUEST_METHOD'].' method', 400);
		}
	}
}