<?php
class friend_link extends CI_Controller {
	private $table_name='friend_link';
	private $index_id="<column_name_first>";
	private $num_links=6;
	private $per_page=10;
	private $page_num=10;
	function index()
	{
		$this->load->database();
		$search_where=$this->search_where();
		$current=get_current();
		$c_url=get_current_url("index");
		$content_list_search=list_search($this->table_name,$search_where,$this->per_page,$this->num_links,$c_url,$current);
		$keyword=trim($this->input->get("keyword"));
		if($keyword)
		{
			$content_list_search['this_page']=preg_replace("/href=\"([^\"]*)\"/isU","href=\"\$1?keyword=".$keyword."\"",$content_list_search['this_page']);
			$data['keyword']=$keyword;
		}
		$data['list']=$content_list_search;
		$data['menu_index']="on";
		<index_category>
		/****最新文章****/
		$rank_list=array();
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name." order by rand() limit 0,6");
		foreach ($result->result() as $row)
		{
			$rank_list[]=$row;
		}
		$data['rank_list']=$rank_list;
		show("friend_link",$data);
	}
	function search_where()
	{
		$sql="where <column_name_first>>0";
		$keyword=trim($this->input->get("keyword"));
		if($keyword)
		{
			$sql.=" and title like '%".$keyword."%'";
		}
		else
		{
			<search_where_pre>
			<search_where>
			<search_where_pre_end>
		}
		$sql.=" order by <column_name_first> desc";
		return $sql;
	}
	function show()
	{
		$this->load->database();
		if(preg_match("/^\d+$/is",$this->uri->segment(3)))
		{
			$id=$this->uri->segment(3);
			$page_info=$this->db->get_where($this->db->dbprefix.$this->table_name,array("<column_name_first>"=>$id))->row();
			$data['page_info']=$page_info;
			$data['seo']['seo_title']=$page_info->title;

			/***分类**/
			$cate_list=array();
			$result=$this->db->query("select * from ".$this->db->dbprefix."friend_link_category_cate where is_show=? order by sort_order desc,<column_name_first> asc",array(1));
			foreach ($result->result() as $row)
			{
				$cate_list[]=$row;
			}
			$data['cate_list']=$cate_list;
			/****最新文章****/
			$rank=array();
			$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name." order by rand() limit 0,6");
			foreach ($result->result() as $row)
			{
				$rank[]=$row;
			}
			$data['rank']=$rank;
			show("friend_link_show",$data);
		}
		else
		show_404();
	}
	function friend_link_add()
	{
		$data=array();
		show("friend_link_add",$data,0);
	}
	function ajax_friend_link_add()
	{
		$this->load->database();
		$data = array(
<ajax_save>
		);
		$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		$this->db->query($insert_str);
		$insert_id=$this->db->insert_id();
		$arr = array('errno'=>"0", 'error'=>"添加成功！",'url'=>site_url()."friend_link/index/".$insert_id);
		echo json_encode($arr);die();
	}
}
