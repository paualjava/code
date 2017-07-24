<?php
class category_add extends CI_Model {
	private $table_name='category';
	function main()
	{
		$this->load->library('Session');
		$this->load->helper("website");
		$form_is_post=$this->input->post('form_is_post');
		if($form_is_post)
		{
			$this->sava_info();
		}
		else 
		{
			$parent_id=$this->session->userdata($this->table_name."_parent_id");
			$parent_id=($parent_id) ? $parent_id : 0;
			return array("parent_id"=>$parent_id,"table_name"=>$this->table_name);
		}
	}
	function sava_info()
	{
		$data = array(
		'name'              =>$this->input->post('name'),//名称
		'parent_id'         =>$this->input->post('parent_id'),//父类
		'url'               =>$this->input->post('url'),//seo_url
		'pic'               =>$this->input->post('pic'),//图片
		'is_show'           =>$this->input->post('is_show'),//审核
		'sort_order'        =>$this->input->post('sort_order'),//排序
		'postdate'          =>time(),//添加时间
		'info'              =>$this->input->post('info')//备注
		);
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		$this->db->query($insert_str);
		$session_info=array($this->table_name."_parent_id"=>$this->input->post('parent_id'));
		$this->session->set_userdata($session_info);
		$arr = array('errno'=>"0", 'error'=>"添加成功！",'url'=>url_admin()."category");
		echo json_encode($arr);die();
	}
}
?>
