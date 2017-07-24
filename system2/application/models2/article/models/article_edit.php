<?php
class Article_edit extends CI_Model {
	private $table_name='article';
	private $table_cate_name='article_category';
	private $table_cate_order='sort_order';
	private $table_content_name='article_content';
	private $index_id="id";
	function main()
	{
		$title=$this->input->post("title");
		$current=$this->input->post("current");
		$page_text=$this->input->post("page_text");
		//var_dump($page_text);die();
		$category=$this->input->post("category");
		$id=$this->input->post("id");
		if($title)
		{
			$this->save_info($title,$page_text,$category,$current,$id);
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
		$category_id=$row->category_id;
		$category_this=$this->show_category($category_id);
		$sql="select * from ".$this->db->dbprefix.$this->table_content_name." where ".$this->index_id."=".$id;
		$result=$this->db->query($sql);
		$row_content =$result->row();
		return array("current"=>($current==0) ? '' : $current,"this_data"=>$row,"id"=>$id,"page_text"=>(@$row_content->content) ? @$row_content->content : "","category"=>$category_this);
	}
	function save_info($title,$page_text,$category,$current,$id)
	{
		$data=array("subject"=>$title,"category_id"=>$category);
		$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_name, $data,$this->index_id."=".$id);
		$this->db->query($insert_str);
		$data=array("content"=>$page_text);
		$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_content_name, $data,$this->index_id."=".$id);
		$this->db->query($insert_str);
		redirect(site_url()."admin_admin/index/manager/success/article/".$current);
	}
	function show_category($category_id)
	{
		$str="";
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_cate_name." order by ".$this->table_cate_order." desc");
		$this->load->library('tree');
		foreach ($result->result() as $row)
		{
			$this->tree->setNode($row->id, $row->parent_id,$row->name,$row->url,$row->sort_order);
		}
		$category = $this->tree->getChilds();
		$t_line=array();
		$t_array=array();
		$t_id=array();
		foreach ($category as $key=>$id){
			array_push($t_line,$this->tree->getLayer($id, '|------'));
			array_push($t_array,$this->tree->getValue($id));
			array_push($t_id,$this->tree->getId($id));
		}
		foreach ($t_array as $c_key=>$c_value)
		{
			$select=($category_id==$t_id[$c_key]) ? "selected=\"selected\"" : "";
			$str.='<option value="'.$t_id[$c_key].'" '.$select.'>'.$t_line[$c_key].$c_value.'</option>';
		}
		return $str;
	}
}
?>
