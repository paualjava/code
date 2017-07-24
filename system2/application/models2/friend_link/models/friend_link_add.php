<?php
class friend_link_add extends CI_Model {
	private $table_name='friend_link';
	function main()
	{
		role_check('friend_link_add');
		$this->load->helper("website");
		$form_is_post=$this->input->post('form_is_post');
		if($form_is_post)
		{
			$this->sava_info();
		}
		<model_add_session_pre>
	}
	function sava_info()
	{
		<model_add_array>
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		$this->db->query($insert_str);
		<model_add_session>
		<model_add_pic_multiple>
		$arr = array('errno'=>"0", 'error'=>"添加成功！",'url'=>url_admin()."friend_link");
		echo json_encode($arr);die();
	}
}
?>
