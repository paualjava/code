<?php
class category extends CI_Model {
	private $table_name='category';
	function main()
	{
		$this->load->helper("website");
		if($this->uri->segment(5)=="ajax_save_is_show")
		$this->ajax_save_is_show($this->uri->segment(6));
		if($this->uri->segment(5)=="ajax_save_sort_order")
		$this->ajax_save_sort_order();
		$current=get_current();
		if($this->uri->segment(5)=="delete")
		{
			if(preg_match("/^\d+$/is",$this->uri->segment(6)))
			{
				$brand_row=$this->db->select("id,parent_id")->get_where($this->db->dbprefix.$this->table_name,array("parent_id"=>$this->uri->segment(6)))->result();
				if($brand_row)
				{
					alert("请先删除此分类下面的子分类!",url_admin()."category");die();
				}
				delete($this->table_name,$this->uri->segment(6),$this->uri->segment(7));
			}
		}
		return array("table_name"=>$this->table_name);
	}
	function ajax_save_is_show($id)
	{
		$is_show=0;
		if(preg_match("/^\d+$/is",$id))
		{
			$info=get_table_row($this->table_name,$id);
			if($info)
			{
				$is_show=$info->is_show;
				$is_show=($is_show==1) ? 0 : 1;
				$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("is_show"=>$is_show),"id=?");
				$this->db->query($update_str,$id);
			}
		}
		echo $is_show;die();
	}
	function ajax_save_sort_order()
	{
		$id=$this->input->post("id");
		$order=$this->input->post("orderby");
		if(preg_match("/^\d+$/is",$id) && preg_match("/^\d+$/is",$order))
		{
			$info=get_table_row($this->table_name,$id);
			if($info)
			{
				$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("sort_order"=>$order),"id=?");
				$this->db->query($update_str,$id);
			}
		}
		echo $order;die();
	}
}
?>
