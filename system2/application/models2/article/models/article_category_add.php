<?php
class Article_category_add extends CI_Model {
	private $table_name='article_category';
	function main()
	{
		$name=$this->input->post('name');
		$url=$this->input->post('url');
		$sort_order=$this->input->post('sort_order');
		if($name)
		{
			$this->sava_this_page($name,$sort_order,$url);
		}
	}
	function sava_this_page($name,$sort_order,$url)
	{
		$data=array("name"=>$name,"sort_order"=>$sort_order,"url"=>$url,"postdate"=>time(),"parent_id"=>$this->uri->segment(5));
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		$this->db->query($insert_str);
		redirect(site_url()."admin_admin/index/manager/success/article_category/");
	}
}
?>
