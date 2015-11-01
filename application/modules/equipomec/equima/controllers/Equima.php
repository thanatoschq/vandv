<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equima extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_equima','me');
	}

	public function index(){
		$data['anio_eje']  = $this->me->get_anio_activo();
		$this->load->view('v_equima',$data);
	}

	public function getTipoMaq(){
		$rows = $this->me->get_tipo_maq();
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function getPropiet(){
		$rows = $this->me->get_propietario();
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function regMaqEqui($opt){
		$cma = $this->input->post('cod_maqequi');
  		$pro = $this->input->post('id_propietario');
  		$tma = $this->input->post('id_maqequi');
  		$des = $this->input->post('desc_maqequi');
  		$pla = $this->input->post('placa_maqequi');
  		$mar = $this->input->post('marca_maqequi');
  		$mod = $this->input->post('modelo_maqequi');
  		$ser = $this->input->post('serie_maqequi');
  		$mot = $this->input->post('motor_maqequi');
  		$col = $this->input->post('color_maqequi');
  		$obs = $this->input->post('obs_maqequi');
  		$val = $this->input->post('valor_maqequi');
  		$hrs = $this->input->post('horas_maqequi');

  		if($opt == 'n'){
  			$result = $this->me->reg_maquinaria($cma,$pro,$tma,$des,$pla,$mar,$mod,$ser,$mot,$col,$obs,$val,$hrs);
  		}else if($opt == 'e'){
  			$result = $this->me->edit_maquinaria($cma,$pro,$tma,$des,$pla,$mar,$mod,$ser,$mot,$col,$obs,$val,$hrs);
  		}

  		if($result == "true"){
  			echo '{"success":"'.$result.'","msg":"Se registro el proceso"}';
  		}else{
  			echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  		}
	}

	public function getMaquina(){
		$rows = $this->me->get_maquina();
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function getAnios(){
		$rows = $this->me->get_anios();
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getObras(){
		$anio = $this->input->get('anio');
		$rows = $this->me->get_obras($anio);
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getFrente(){
		$anio = $this->input->get('anio');
		$obra = $this->input->get('obra');
		$rows = $this->me->get_frente($anio,$obra);
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getTipoDoc(){
		$rows = $this->me->get_tipo_doc();
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function regMantEqui(){
		$idm = $this->input->post('id_em_mantenimiento');
	  	$cmaq = $this->input->post('cod_maqequi');
	  	$ifre = $this->input->post('id_frente');
	  	$cobr = $this->input->post('cod_obra');
	  	$anio = $this->input->post('anio_eje');
	  	$nord = $this->input->post('nord_mant');
	  	$aord = $this->input->post('aord_mant');
	  	$mont = $this->input->post('mont_mant');
	  	$ndoc = $this->input->post('doc_mant');
	  	$fdoc = $this->input->post('fdoc_mant');
	  	$mdoc = $this->input->post('mdoc_mant');
	  	$itd = $this->input->post('id_tipdoc');
	  	$obs = $this->input->post('obs_mant');
	  	$opt = $this->input->post('opt');

	  	if($opt == 'n'){
	  		$result = $this->me->reg_mant_equi($cmaq,$ifre,$cobr ,$anio,$nord,$aord,$mont,$ndoc,$fdoc,$mdoc,$itd,$obs);
	  	}else if($opt == 'e'){
	  		$result = $this->me->edit_mant_equi($idm,$cmaq,$ifre,$cobr ,$anio,$nord,$aord,$mont,$ndoc,$fdoc,$mdoc,$itd,$obs);
	  	}

	  	if($result == "true"){
  			echo '{"success":"'.$result.'","msg":"Se registro el proceso"}';
  		}else{
  			echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  		}
	}

	public function getMantenim(){
		$anio = $this->input->get('anio_eje');
		$cod = $this->input->get('cod_maqequi');
		$rows = $this->me->get_mantenimientos($anio,$cod);
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function uploadImagen(){
		$desc = $this->input->post('desc');
		$cod = $this->input->post('cod_maqequi');
		$config['upload_path']          = './src/maqequi/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		$config['encrypt_name'] = true;

		$this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')){
            $error = array('error' => $this->upload->display_errors());
            $msg = $error['error'];
            echo '{"failed":"false","msg":"'.$msg.'"}';
        }else{
            $data = $this->upload->data();
            $path = $data['full_path'];
            $filename =  $data['file_name'];
            $result = $this->me->regImagen($cod,$desc,$filename,'./src/maqequi/');
            if($result == "true"){
  				echo '{"success":"'.$result.'","msg":"Se subio el archivo con exito"}';
  			}else{
  				echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  			}
            
        }
	}

	public function getMaqImages(){
		$maq = $this->input->get('cod_maqequi');
		$rows = $this->me->get_maq_images($maq);
		echo '{"data":'.json_encode($rows->result()).'}';	
	}
}
?>