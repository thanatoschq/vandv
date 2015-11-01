<?php
class M_menu extends CI_Model{
	
	public function __construct(){
		parent::__construct();
	}

	public function get_acceso($user,$parent){
		$this->db->where('id_padre',$parent);
		$this->db->where('cod_usuario',$user);
		$result = $this->db->get('sys.v_accesos');
		return $result;
	}
}
?>