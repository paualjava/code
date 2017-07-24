<?php
class friend_link extends CI_Model {
	private $table_name='friend_link';
	private $index_id="<column_name_first>";
	private $per_page=20;
	private $num_links=10;
	function main()
	{
		role_check('friend_link_view');
		$current=get_current();
		if($this->uri->segment(5)=="delete")
		delete($this->table_name,$this->uri->segment(6),$this->uri->segment(7),$this->index_id);
		if($this->uri->segment(5)=="delete_all")
		delete_all($this->uri->segment(6),$this->table_name,$this->uri->segment(7),$this->index_id);
		if($this->uri->segment(5)=="export_excel")
		$this->export_excel();
		<save_pic_multiple>
		<save_is_show>
		<save_sort_order>
		$c_url=get_current_url();
		$keyword=$this->input->get("keyword");
		$search_where="";
		if($keyword<search_cate_column>)
		{
			$search_where.="  where ".$this->index_id.">0 ";
			if($keyword)
			$search_where.=" <search_like>";
<search_like_more>
			$search_where.="  order by <sort_order_paixu> <column_name_first> desc";
		}
		else
		$search_where=" where ".$this->index_id.">0 order by <sort_order_paixu> <column_name_first> desc";
		$content_list_search=list_search($this->table_name,$search_where,$this->per_page,$this->num_links,$c_url,$current);
		if($_SERVER["QUERY_STRING"])
		$content_list_search['this_page']=preg_replace("/href=\"([^\"]*)\"/isU","href=\"\$1?".$_SERVER["QUERY_STRING"]."\"",$content_list_search['this_page']);
		$data=array("list"=>$content_list_search,"per_page"=>$this->per_page,"current"=>$current);
		$data['search_keyword']=$keyword;
<search_cate_column_module>
		$data['query_string']=($_SERVER["QUERY_STRING"]) ? "?".$_SERVER["QUERY_STRING"] : "";
		<search_cate>
		return $data;
	}
	<save_is_show_function>
	<save_sort_order_function>
	<save_pic_multiple_function>
	
	//导出Excel
	function export_excel()
	{
		$data=array();
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name);
		foreach ($result->result_array() as $row)
		{
<export_excel_category>		
<export_excel_time>
			$data[]=$row;

		}
		$data_colunm=array(<export_excel_array>);
		create_excel_file($data,$data_colunm,"<table_name_zh>");
		die();
	}
}
?>
