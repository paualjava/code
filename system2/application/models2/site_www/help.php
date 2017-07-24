<?php
class help extends CI_Controller {
	private $table_name="help";
	function index()
	{
		wap_link();
		$data=array();
		$page_left=array();
		$this->load->database();
		$sql="select id,<field_title> from ".$this->db->dbprefix.$this->table_name." where id>? order by id asc limit 0,10000";
		$result=$this->db->query($sql,array(0));
		$id_first=1;
		$i=1;
		foreach ($result->result() as $row)
		{
			$page_left[]=$row;
			$id_first=($i==1) ? $row->id : $id_first;
		}
		$id=$this->uri->segment(3);
		$id=($id) ? $id : $id_first;
		if(preg_match("/^\d+$/is",$id))
		$page_info=$this->db->get_where($this->db->dbprefix.$this->table_name,array("id"=>$id))->row();
		/*else
		$page_info=$this->db->get_where($this->db->dbprefix."help",array("url"=>$id))->row();*/
		if(!$page_info)
		show_404();
		$data['page_left']=$page_left;
		$data['current']=$id;
		$data['page_info']=$page_info;
		show("help",$data);
	}
}