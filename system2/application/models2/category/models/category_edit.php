<?php
class category_edit extends CI_Model {
	private $table_name='category';
	private $index_id="id";
	function main()
	{
		$this->load->helper("website");
		$form_is_post=$this->input->post('form_is_post');
		if($form_is_post)
		{
			$this->save_info($this->uri->segment(5),$this->uri->segment(6));
		}
		else
		{
			$id=$this->uri->segment(5);
			$current=$this->uri->segment(6);
			$row=$this->db->get_where($this->db->dbprefix.$this->table_name,array($this->index_id=>$id))->row();
			return array("current"=>($current==0) ? '' : $current,"this_data"=>$row,"table_name"=>$this->table_name,"id"=>$id);
		}
	}
	function save_info($id,$current=0)
	{
		$data = array(
		'name'              =>$this->input->post('name'),//名称
		'parent_id'         =>$this->input->post('parent_id'),//父类
		'url'               =>$this->input->post('url'),//seo_url
		'pic'               =>$this->input->post('pic'),//图片
		'is_show'           =>$this->input->post('is_show'),//审核
		'sort_order'        =>$this->input->post('sort_order'),//排序
		'info'              =>$this->input->post('info')//备注
		);
		$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, $data,$this->index_id."=".$id);
		$this->db->query($update_str);
		$current=($current) ? "/".$current : "";
		$arr = array('errno'=>"0", 'error'=>"编辑成功！",'url'=>url_admin()."category".$current);
		echo json_encode($arr);die();
	}
}
?>
