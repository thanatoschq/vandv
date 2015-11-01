<?php
class M_equima extends CI_Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function get_anio_activo(){
		$this->db->where('est_anio','A');
		$result = $this->db->get('sys.anio');
		$row = $result->row();
		return $row->anio_eje;
	}

	public function get_tipo_maq(){
		$result = $this->db->get('eqm.tipo_maqequi');
		return $result;
	}

	public function get_propietario(){
		$result = $this->db->get('eqm.propietario');
		return $result;	
	}

	public function reg_maquinaria($cma,$pro,$tma,$des,$pla,$mar,$mod,$ser,$mot,$col,$obs,$val,$hrs){
		$data = array(
			'cod_maqequi' => $cma,
  			'id_propietario' => $pro, 
  			'id_maqequi' => $tma,
  			'desc_maqequi' => $des, 
  			'placa_maqequi' => $pla, 
  			'marca_maqequi' => $mar,
  			'modelo_maqequi' => $mod, 
  			'serie_maqequi' => $ser, 
  			'motor_maqequi' => $mot, 
  			'color_maqequi' => $col, 
  			'obs_maqequi' => $obs, 
  			'valor_maqequi' => $val,
  			'horas_maqequi' => $hrs
		);
		$msg = "";
		$this->db->trans_begin();

		$this->db->insert('eqm.maqui_equi',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function edit_maquinaria($cma,$pro,$tma,$des,$pla,$mar,$mod,$ser,$mot,$col,$obs,$val,$hrs){
		$data = array(
  			'id_propietario' => $pro, 
  			'id_maqequi' => $tma,
  			'desc_maqequi' => $des, 
  			'placa_maqequi' => $pla, 
  			'marca_maqequi' => $mar,
  			'modelo_maqequi' => $mod, 
  			'serie_maqequi' => $ser, 
  			'motor_maqequi' => $mot, 
  			'color_maqequi' => $col, 
  			'obs_maqequi' => $obs, 
  			'valor_maqequi' => $val,
  			'horas_maqequi' => $hrs
		);
	
		$this->db->trans_begin();
		$this->db->where('cod_maqequi',$cma);
		$this->db->update('eqm.maqui_equi',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}	

	public function get_maquina(){
		$this->db->order_by('cod_maqequi','ASC');
		$result = $this->db->get('eqm.v_maquinas');
		return $result;
	}

	public function get_anios(){
		$result = $this->db->get('sys.anio');
		return $result;
	}

	public function get_obras($anio){
		$this->db->where('anio_eje',$anio);
		$result = $this->db->get('log.obra');
		return $result;
	}

	public function get_frente($anio,$obra){
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_obra',$obra);
		$result = $this->db->get('log.frente');
		return $result;
	}

	public function get_tipo_doc(){
		$this->db->where('est_tipdoc','A');
		$result = $this->db->get('sys.tip_doc');
		return $result;
	}

	public function reg_mant_equi($cmaq,$ifre,$cobr ,$anio,$nord,$aord,$mont,$ndoc,$fdoc,$mdoc,$itd,$obs){
		$data = array(
  			'cod_maqequi' => $cmaq,
  			'id_frente' => $ifre,
  			'cod_obra' => $cobr,
  			'anio_eje' => $anio,
  			'nord_mant' => $nord,
  			'aord_mant' => $aord,
  			'mont_mant' =>	$mont,
  			'doc_mant' =>	$ndoc,
  			'fdoc_mant' =>	$fdoc,
  			'mdoc_mant' =>	$mdoc,
  			'id_tipdoc' =>	$itd,
  			'obs_mant' =>	$obs
		);

		$this->db->trans_begin();
		
		$this->db->insert('eqm.em_mantenimiento',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function get_mantenimientos($anio,$cod_maqequi){
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_maqequi',$cod_maqequi);
		$result = $this->db->get('eqm.v_mantenimientos');
		return $result;
	}

	public function regImagen($cod,$desc,$filename,$dir){
		$data = array(
			'desc_img' => $desc,
			'path_img' => $dir.$filename,
			'nom_img' => $filename,
			'cod_maqequi' => $cod
		);

		$this->db->trans_begin();
		
		$this->db->insert('eqm.maqequi_imagen',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function get_maq_images($maq){
		$this->db->where('cod_maqequi',$maq);
		$result = $this->db->get('eqm.maqequi_imagen');
		return $result;
	}
}
?>