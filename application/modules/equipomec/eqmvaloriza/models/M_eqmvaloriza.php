<?php
class M_eqmvaloriza extends CI_Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function get_anios(){
		//$this->db->where('est_anio','A');
		$result = $this->db->get('sys.anio');
		return $result;
	}

	public function get_obras($anio){
		$this->db->where('est_obra','A');
		$this->db->where('anio_eje',$anio);
		$result = $this->db->get('log.obra');
		return $result;
	}

	public function get_frente($anio,$obra){
		$this->db->where('est_frente','A');
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_obra',$obra);
		$result = $this->db->get('log.frente');
		return $result;
	}

	public function get_meses($anio){
		$this->db->where('anio_eje',$anio);
		$result = $this->db->get('sys.meses');
		return $result;
	}

	public function get_valorizaciones($anio,$obra){
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_obra',$obra);
		$this->db->order_by('nro_valoriza','DESC');
		$result = $this->db->get('eqm.valorizacion');
		return $result;
	}

	public function reg_nueva_valorizacion($anio,$obra,$periodo){
		$num = $this->sig_valoriza($anio,$obra);
		$data = array(
			"nro_valoriza" => $num,
  			"cod_obra" => $obra,
  			"anio_eje" => $anio,
  			"mes_id" => $periodo,
		);

		$this->db->trans_begin();
		
		$this->db->insert('eqm.valorizacion',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function get_val_maquinaria($anio,$obra,$val){
		$sql = "select * from eqm.str_valorizacion_det(?,?,?)";
		$where = array($anio,$obra,$val);
		$result = $this->db->query($sql,$where);
		return $result;

	}

	public function get_maquina_equipo($q){
		$q = strtoupper($q);
		if($q != ''){
			$this->db->like('desc_maqequi',$q);
			$this->db->or_like('cod_maqequi',$q);
		}
		$this->db->select(array('cod_maqequi','desc_maqequi','serie_maqequi'));
		$result = $this->db->get('eqm.v_maquinas');
		return $result;
	}

	public function get_trabajador(){
		$result = $this->db->get('eqm.v_trabajador');
		return $result;
	}

	private function sig_valoriza($anio_eje,$obra){
		$num = "";
		$this->db->where('cod_obra',$obra);
		$this->db->where('anio_eje',$anio_eje);
		$this->db->select('max(nro_valoriza) nro_valoriza');
		$result = $this->db->get('eqm.valorizacion');
		$num = $result->row();
		$num = str_pad($num->nro_valoriza+1,3,'0',STR_PAD_LEFT);
		return $num;
	}

	public function reg_nueva_maquina($anio,$cod_maqequi,$cod_trabajador,$emaq_det_maquina,$obra,$obs_det_maquina,$real_det_maquina,$plani,$repmant,$val){
		$data  = array(
			"nro_valoriza" => $val,
  			"cod_obra" =>  $obra,
  			"anio_eje" => $anio,
  			"cod_maqequi" => $cod_maqequi,
  			"cod_trabajador" => $cod_trabajador,
  			"emaq_det_maquina" => $emaq_det_maquina,
  			"real_det_maquina" =>  $real_det_maquina,
  			"obs_det_maquina" => $obs_det_maquina,
  			"plani_det_maquina" => $plani,
  			"repmant_det_maquina" => $repmant
		);

		$this->db->trans_begin();
		
		$this->db->insert('eqm.det_maquinaria',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function reg_edita_maquina($anio,$cod_maqequi,$cod_trabajador,$emaq_det_maquina,$obra,$obs_det_maquina,$real_det_maquina,$plani,$repmant,$val,$id){
		$data  = array(
  			"cod_maqequi" => $cod_maqequi,
  			"cod_trabajador" => $cod_trabajador,
  			"emaq_det_maquina" => $emaq_det_maquina,
  			"real_det_maquina" =>  $real_det_maquina,
  			"obs_det_maquina" => $obs_det_maquina,
  			"plani_det_maquina" => $plani,
  			"repmant_det_maquina" => $repmant
		);

		$where = array(
			"id_det_valoriza" => $id,
			"nro_valoriza" => $val,
  			"cod_obra" =>  $obra,
  			"anio_eje" => $anio
		);

		$this->db->trans_begin();
		
		$this->db->update('eqm.det_maquinaria',$data,$where);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function get_precios($anio,$obra,$maq){
		$this->db->where('cod_obra',$obra);
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_maqequi',$maq);
		$result = $this->db->get('eqm.costo_maq_obra');
		return $result;
	}

	public function reg_nuevo_detalle($anio,$cant_comb,$cant_maq_val,$fech_maq_val,$id_detalle,$nro_parte,$cod_obra,$obs_maq,$precio_maq_val,$pu_combus,$tot_combus,$tot_maq_val, $unid_maq_val,$id_val){
		$data = array(
			"anio_eje" => $anio,
			"cant_combustible" => $cant_comb,
			"cant_maquina_val" => $cant_maq_val,
			"fech_maquina_val" => $fech_maq_val,
			"id_det_valoriza" => $id_detalle,
			"nro_parte_maquina_val" => $nro_parte,
			"cod_obra" => $cod_obra,
			"observ_maquina_val" => $obs_maq,
			"precio_maquina_val" => $precio_maq_val,
			"pu_combustible" => $pu_combus,
			"tot_combustible" => $tot_combus,
			"tot_maquina_val" => $tot_maq_val,
			"unid_maquina_val" => $unid_maq_val,
			"nro_valoriza" => $id_val	
		);

		$this->db->trans_begin();
		
		$this->db->insert('eqm.maquina_val',$data);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function reg_edita_detalle($anio,$cant_comb,$cant_maq_val,$fech_maq_val,$id_detalle,$nro_parte,$cod_obra,$obs_maq,$precio_maq_val,$pu_combus,$tot_combus,$tot_maq_val, $unid_maq_val,$id_val,$id_maq_val){
		$data = array(
			
			"cant_combustible" => $cant_comb,
			"cant_maquina_val" => $cant_maq_val,
			"fech_maquina_val" => $fech_maq_val,
			"nro_parte_maquina_val" => $nro_parte,
			"observ_maquina_val" => $obs_maq,
			"precio_maquina_val" => $precio_maq_val,
			"pu_combustible" => $pu_combus,
			"tot_combustible" => $tot_combus,
			"tot_maquina_val" => $tot_maq_val,
			"unid_maquina_val" => $unid_maq_val,
			
		);

		$where = array(
			"nro_valoriza" => $id_val,
			"anio_eje" => $anio,
			"cod_obra" => $cod_obra,
			"id_det_valoriza" => $id_detalle,
			"id_maquina_val" => $id_maq_val
		);

		$this->db->trans_begin();
		
		$this->db->update('eqm.maquina_val',$data,$where);

		if ($this->db->trans_status() === FALSE){
        	$this->db->trans_rollback();
        	return "false";
		}else{
        	$this->db->trans_commit();
        	return "true";
		}
	}

	public function get_maqui_valoriza($anio,$obra,$val,$id_maq){
		$this->db->where('anio_eje',$anio);
		$this->db->where('cod_obra',$obra);
		$this->db->where('nro_valoriza',$val);
		$this->db->where('id_det_valoriza',$id_maq);
		$result = $this->db->get('eqm.maquina_val');
		//echo $this->db->last_query();
		return $result;
	}
}
?>