<?php
class friend_link extends CI_Controller {
	private $table_name="friend_link";
	function index()
	{
		$data=array();
		$page_left=array();
		$this->load->database();
		$sql="select id,title from ".$this->db->dbprefix.$this->table_name." where id>? order by id asc limit 0,10000";
		$result=$this->db->query($sql,array(0));
		$id_first=1;
		$i=1;
		foreach ($result->result() as $row)
		{
			$page_left[]=$row;
			$first=($i==1) ? $row->id : $id_first;
			$iid_first;
		}
		$id=$this->uri->segment(3);
		$id=($id) ? $id : $id_first;
		if(preg_match("/^\d+$/is",$id))
		$page_info=$this->db->get_where($this->db->dbprefix.$this->table_name,array("id"=>$id))->row();
		/*else
		$page_info=$this->db->get_where($this->db->dbprefix.$this->table_name,array("url"=>$id))->row();*/
		$data['page_left']=$page_left;
		$data['current']=$id;
		$data['page_info']=$page_info;
		show("friend_link",$data);
	}
}