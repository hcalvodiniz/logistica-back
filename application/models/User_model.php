<?php 

class User_model extends CI_Model {
	public $table = 'users';

	function __construct() {
		parent::__construct();
		$this->load->database('default');
	}

	public function login($credentials) {
		extract($credentials);
		$model = $this->where(compact('username'));
		if(sizeof($model) == 0) {
			return [
				'success' => FALSE,
				'msg' => 'El Usuario no se encuentra registrado!',
				'data' => null
			];
		}
		if(password_verify($password, $model[0]['password'])) {
			return [
				'success' => TRUE,
				'msg' => 'Ha iniciado sesión correctamente!',
				'data' => $model[0]
			];
		} else {
			return [
				'success' => FALSE,
				'msg' => 'La contraseña no es valida!',
				'data' => null
			];
		}
	}
}