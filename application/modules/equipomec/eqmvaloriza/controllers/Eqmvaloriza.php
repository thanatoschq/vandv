<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eqmvaloriza extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_eqmvaloriza','mv');
	}

	public function index(){
		$data['rpt_server'] = $this->config->item('rpt_server');
		$this->load->view('v_valorizacion',$data);
	}

	public function getAnios(){
		$rows = $this->mv->get_anios();
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getObras(){
		$anio = $this->input->get('anio');
		$rows = $this->mv->get_obras($anio);
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function getFrente(){
		$anio = $this->input->get('anio');
		$obra = $this->input->get('obra');
		$rows = $this->mv->get_frente($anio,$obra);
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getMeses(){
		$anio = $this->input->get('anio');
		$rows = $this->mv->get_meses(date('Y'));
		echo '{"data":'.json_encode($rows->result()).'}';	
	}

	public function getValorizaciones(){
		$anio = $this->input->get('anio');
		$obra = $this->input->get('obra');
		$rows = $this->mv->get_valorizaciones($anio,$obra);
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function regNuevaVal(){
		$anio = $this->input->post('anio');
		$obra = $this->input->post('obra');
		$periodo = $this->input->post('periodo');

		$result = $this->mv->reg_nueva_valorizacion($anio,$obra,$periodo);

		if($result == "true"){
  			echo '{"success":"'.$result.'","msg":"Se registro el proceso"}';
  		}else{
  			echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  		}
	}

	public function getValMaquina(){
		$anio = $this->input->get('anio');
		$obra = $this->input->get('obra');
		$val = $this->input->get('val');
		$rows = $this->mv->get_val_maquinaria($anio,$obra,$val);
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function getMaquinariaEquipo(){
		$q = $this->input->get('query');
		$rows = $this->mv->get_maquina_equipo($q);
		echo '{"data":'.json_encode($rows->result()).'}';		
	}

	public function getTrabajador(){
		$rows = $this->mv->get_trabajador();
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function regNuevaMaquina(){
		$anio = $this->input->post('anio');
		$cod_maqequi = $this->input->post('cod_maqequi');
		$cod_trabajador = $this->input->post('cod_trabajador');
		$emaq_det_maquina = $this->input->post('emq_det_maquina');
		$obra = $this->input->post('obra');
		$obs_det_maquina = $this->input->post('obs_det_maquina');
		$real_det_maquina = $this->input->post('real_det_maquina');
		$val = $this->input->post('val');
		$id = $this->input->post('id_det_valoriza');
		$opt = $this->input->post('opt');
		$plani = $this->input->post('plani_det_maquina');
		$repmant = $this->input->post('repmant_det_maquina');

		if($opt == 'n'){
			$result = $this->mv->reg_nueva_maquina($anio,$cod_maqequi,$cod_trabajador,$emaq_det_maquina,$obra,$obs_det_maquina,$real_det_maquina,$plani,$repmant,$val);
		}else if($opt == 'e'){
			$result = $this->mv->reg_edita_maquina($anio,$cod_maqequi,$cod_trabajador,$emaq_det_maquina,$obra,$obs_det_maquina,$real_det_maquina,$plani,$repmant,$val,$id);
		}
		

		if($result == "true"){
  			echo '{"success":"'.$result.'","msg":"Se registro el proceso"}';
  		}else{
  			echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  		}
	}

	public function getPrecios(){
		$anio = $this->input->post('anio');
		$obra = $this->input->post('obra');
		$maq = $this->input->post('maq');
		$unid = $this->input->post('unid');
		$rows = $this->mv->get_precios($anio,$obra,$maq);
		$row = $rows->row();
		if($unid == 'D'){
			$precio = $row->dia_precio;
		}else if($unid == 'H'){
			$precio = $row->hr_precio;
		}
		echo '{"data":{"precio_maq":'.$precio.',"precio_combus":'.$row->comb_precio.'}}';
	}

	public function regNuevoDetalle(){
		$anio = $this->input->post('anio');
		$cant_comb = $this->input->post('cant_combustible');
		$cant_maq_val = $this->input->post('cant_maquina_val');
		$fech_maq_val = $this->input->post('fech_maquina_val');
		$id_detalle = $this->input->post('iddet');
		$nro_parte = $this->input->post('nro_parte_maquina_val');
		$cod_obra = $this->input->post('obra');
		$obs_maq = $this->input->post('observ_maquina_val');
		$precio_maq_val = $this->input->post('precio_maquina_val');
		$pu_combus = $this->input->post('pu_combustible');
		$tot_combus = $this->input->post('tot_combustible');
		$tot_maq_val = $this->input->post('tot_maquina_val');
		$unid_maq_val = $this->input->post('unid_maquina_val');
		$id_val = $this->input->post('val');
		$opt = $this->input->post('opt');
		$id_maq_val = $this->input->post('id_maquina_val');

		if($opt == 'n'){
			$result = $this->mv->reg_nuevo_detalle($anio,$cant_comb,$cant_maq_val,$fech_maq_val,$id_detalle,$nro_parte,$cod_obra,$obs_maq,$precio_maq_val,$pu_combus,$tot_combus,$tot_maq_val, $unid_maq_val,$id_val);
		}else if($opt == 'e'){
			$result = $this->mv->reg_edita_detalle($anio,$cant_comb,$cant_maq_val,$fech_maq_val,$id_detalle,$nro_parte,$cod_obra,$obs_maq,$precio_maq_val,$pu_combus,$tot_combus,$tot_maq_val, $unid_maq_val,$id_val,$id_maq_val);
		}
		
		if($result == "true"){
  			echo '{"success":"'.$result.'","msg":"Se registro el proceso"}';
  		}else{
  			echo '{"failed":"'.$result.'","msg":"Error al registrar el proceso"}';
  		}

	}

	public function getMaquiValoriza(){
		$anio = $this->input->get('anio');
		$obra = $this->input->get('obra');
		$val = $this->input->get('val');
		$id_maq = $this->input->get('maq');
		$rows = $this->mv->get_maqui_valoriza($anio,$obra,$val,$id_maq);
		echo '{"data":'.json_encode($rows->result()).'}';
	}

	public function printValoriza(){
		/*** usuario acceso ****/
		$anio = $this->input->post('a');
		$obra = $this->input->post('o');
		$val = $this->input->post('n');
		$id = $this->input->post('i');
		echo $this->config->item('rpt_server')."/rpt_valorizacion_pdf.jsp?a=".$anio."&o=".$obra."&n=".$val."&i=".$id;
	}

	public function printResumen(){
		$anio = $this->input->post('a');
		$obra = $this->input->post('o');
		$val = $this->input->post('v');
		echo $this->config->item('rpt_server')."/rpt_resumen_val_pdf.jsp?a=".$anio."&o=".$obra."&v=".$val;
	}
}

?>