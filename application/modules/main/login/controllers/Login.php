<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_login','ml');
	}

	public function index()
	{
		$this->load->view('v_login');
	}

	public function verifica(){
		$u = $this->input->post('u');
		$p = $this->input->post('p');
		$data = $this->ml->verifica_usuario($u,$p);
		if($data->num_rows() > 0){
			$row = $data->row();
			$data  = array(
				"logged" => true,
				"username" => $row->cod_usu
			);
			$this->session->set_userdata($data);
			echo '{"msg":"true"}';
		}else{
			echo '{"msg":"Usuario o Contraseña invalida"}';
		}
	}
}
?>