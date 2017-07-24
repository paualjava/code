<?php
class article_category extends CI_Model {
	private $table_name='article_category';
	private $index_id="id";
	private $this_link_url="admin_admin/index/manager/article_category/";
	function main()
	{
		if($this->uri->segment(5)=="delete")
		$this->delete_this($this->uri->segment(6));
		if($this->input->post("save_order"))
		$this->save_order();
		$search_where=$this->search_where();
		return $this->content_list_search($search_where);
	}
	function save_order()
	{
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name);
		foreach ($result->result() as $row)
		{
			$order=$this->input->post("sort_order".$row->id);
			if($order)
			{
				$s_array=array("sort_order"=>$order);
				$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_name, $s_array,$this->index_id."=".$row->id);
				$this->db->query($insert_str);
			}
		}
	}
	function content_list_search($search_where)
	{
		$search_where="select * from ".$this->db->dbprefix.$this->table_name."  $search_where";
		$result=$this->db->query($search_where);
		$this->load->library('tree');
		foreach ($result->result() as $row)
		{
			$this->tree->setNode($row->id, $row->parent_id,$row->name,$row->url,0,$row->sort_order);
		}
		$category = $this->tree->getChilds();
		$t_line=array();
		$t_array=array();
		$t_id=array();
		$t_url=array();
		$t_order=array();
		foreach ($category as $key=>$id){
			array_push($t_line,$this->tree->getLayer($id, '|------'));
			array_push($t_array,$this->tree->getValue($id));
			array_push($t_id,$this->tree->getId($id));
			array_push($t_url,$this->tree->getUrl($id));
			array_push($t_order,$this->tree->getOrder($id));
			
		}
			return array("t_line"=>$t_line,"t_array"=>$t_array,"t_id"=>$t_id,"t_order"=>$t_order,"t_url"=>$t_url);
	}
	function search_where()
	{
		$sql=" WHERE ".$this->index_id.">0";
		return $sql."  order by sort_order desc";
	}
	function delete_this($id)
	{
		$id=urldecode($id);
		$id_s=explode("`",$id);
		foreach ($id_s as $value)
		{
			$sql ="delete from ".$this->db->dbprefix.$this->table_name." where ".$this->index_id."=".$value;
			$this->db->query($sql);
		}
		$current=$this->uri->segment(7);
		$this_url=site_url()."admin_admin/index/manager/success/article_category/".$current;
		redirect($this_url);
	}
}
?>
