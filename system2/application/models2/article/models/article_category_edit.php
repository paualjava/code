<?php
class Article_category_edit extends CI_Model {
	private $table_name='article_category';
	private $index_id="id";
	function main()
	{
		$title=$this->input->post("name");
		$sort_order=$this->input->post("sort_order");
		$current=$this->input->post("current");
		$url=$this->input->post("url");
		$id=$this->input->post("id");
		if($title)
		{
			$this->save_info($title,$sort_order,$current,$id,$url);
		}
		else
		{
			return  $this->show_info($this->uri->segment(5),$this->uri->segment(6));
		}
	}
	function show_info($id,$current='')
	{
		$sql="select * from ".$this->db->dbprefix.$this->table_name." where ".$this->index_id."=".$id;
		$result=$this->db->query($sql);
		$row =$result->row();
		return array("current"=>($current==0) ? '' : $current,"this_data"=>$row,"id"=>$id);
	}
	function save_info($title,$sort_order,$current,$id,$url)
	{
		$data=array("name"=>$title,"sort_order"=>$sort_order,"url"=>$url);
		$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_name, $data,$this->index_id."=".$id);
		$this->db->query($insert_str);
		redirect(site_url()."admin_admin/index/manager/success/article_category/".$current);
	}
}
?>
