<?php
class Article_add extends CI_Model {
	private $table_name='article';
	private $table_cate_name='article_category';
	private $table_cate_order='sort_order';
	private $table_content_name='article_content';
	function main()
	{
		$subject=$this->input->post("title");
		$content=$this->input->post("page_text");
		$category=$this->input->post("category");
		if($subject && $content)
		{
			$this->sava_this_page($subject,$content,$category);
		}
		else
		{
			$this->load->library('session');
			$category_id=$this->session->userdata('category_id');
			return $this->show_category($category_id);
		}
	}
	function sava_this_page($subject,$content,$category)
	{
		$data=array("subject"=>$subject,"category_id"=>$category,"postdate"=>time());
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		$this->db->query($insert_str);
		$insert_id=$this->db->insert_id();
		$data=array("id"=>$insert_id,"content"=>$content);
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_content_name, $data);
		$this->db->query($insert_str);

		$category_info=array(
		"category_id"=>$category);
		$this->session->set_userdata($category_info);
		redirect(base_url()."admin_admin/index/manager/success/article");
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
			$select=($category_id==$t_id[$c_key] && $category_id) ? "selected=\"selected\"" : "";
			$str.='<option value="'.$t_id[$c_key].'" '.$select.'>'.$t_line[$c_key].$c_value.'</option>';
		}
		return array("category"=>$str,"current"=>$this->uri->segment(5));
	}
}
?>
