<?php
class friend_link extends CI_Model {
	private $table_name='friend_link';
	private $index_id="<model_column_first>";
	function main()
	{
		//role_check('friend_link_edit');
		$this->load->helper("website");
		$form_is_post=$this->input->post('form_is_post');
		if($form_is_post)
		{
			$this->save_info($this->uri->segment(5),$this->uri->segment(6));
		}
		else
		{
			return  $this->show_info();
		}
	}
	function show_info()
	{
		$data=array();
		<model_edit_pic_multiple_show_info>
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name." limit 0,1");
		$row=$result->row();
		$data["this_data"]=$row;
		$data["id"]=$row->id;
		return $data;
	}
	function save_info($id,$current=0)
	{
		<model_edit_array>
		$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, $data,$this->index_id.">0");
		$this->db->query($update_str);
		<model_edit_pic_multiple>
		$current=($current) ? "/".$current : "";
		$query_string=($_SERVER["QUERY_STRING"]) ? "?".$_SERVER["QUERY_STRING"] : "";
		$arr = array('errno'=>"0", 'error'=>"修改成功！",'url'=>url_admin()."friend_link".$current.$query_string);
		echo json_encode($arr);die();
	}
}
?>
