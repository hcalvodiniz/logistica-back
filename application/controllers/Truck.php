<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Truck extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('truck_model');
	}

	/**
	 * Function to return all resources of the model Truck
	 * @return Response Array of resources
	 */
	public function index() {
		$truck = $this->truck_model->all();
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type('application/json')
			->set_output(json_encode($truck));
	}

	/**
	 * Function to store a new resource of the model Truck
	 * @return Response Array with validation errors or Array with message and Object of the new resource
	 */
	public function store() {
		$this->load->library('form_validation');
		$_POST = json_decode(file_get_contents("php://input"), true);
		$this->form_validation->set_rules($this->truck_model->insertRules);

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
			$model = $this->truck_model->insert($data);
			$this->output
				->set_header("Access-Control-Allow-Origin: *")
				->set_header("Access-Control-Allow-Headers: *")
				->set_content_type('application/json')
				->set_output(json_encode([
					'success'=> TRUE,
					'msg' => 'Se ha creado el registro de la camioneta!',
					'data' => $model
				]));
		}
	}

	/**
	 * Function to get the resource of truck filter by id
	 * @param ID - int
	 * @return Response Array with object of the resource
	 */
	public function show($id) {
		$model = $this->truck_model->find($id);
		$this->output
			->set_header("Access-Control-Allow-Origin: *")
			->set_header("Access-Control-Allow-Headers: *")
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => TRUE,
				'msg' => '',
				'data' => $model
			]));
	}

	/**
	 * Function to update a existing resource, in the post variables need to be added the ID of the resource to be updated
	 * @return Response Array of validation errors or Array with message and object of the updated resource
	 */
	public function update() {
		$this->load->library('form_validation');
		$_POST = json_decode(file_get_contents("php://input"), true);
		$this->form_validation->set_rules($this->truck_model->updateRules);

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
			if (!isset($data['id'])) {
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type('application/json')
					->set_output(json_encode([
						'success' => FALSE,
						'data' => null,
						'msg' => "No se ha detectado el ID, no se puede actualizar, intente más tarde!"
					]));
			} else {
				$id = $data['id'];
				unset($data['id']);

				$model = $this->truck_model->update($data, $id);
				$this->output
					->set_header("Access-Control-Allow-Origin: *")
					->set_header("Access-Control-Allow-Headers: *")
					->set_content_type('application/json')
					->set_output(json_encode([
						'success' => TRUE,
						'data' => $model,
						'msg' => 'Se ha actualizado correctamente la camioneta'
					]));
			}
		}
	}

	/**
	 * Function to delete a resource by ID show error if method is not DELETE or OPTIONS
	 * @return Response Array with message and status
	 */
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
					'msg' => '¡Se ha eliminado exitosamente el camión!',
					'data' => null
				]));
		} else {
			show_error('Not allowed '.$_SERVER['REQUEST_METHOD'].' method', 400);
		}
	}
}