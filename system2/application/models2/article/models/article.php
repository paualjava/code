<?php
class Article extends CI_Model {
	private $table_name='article';
	private $table_content_name='article_content';
	private $index_id="id";
	private $this_link_url="admin_admin/index/manager/article/";
	private $per_page=22;
	private $num_links=10;
	function main()
	{
		if($this->uri->segment(5)=="delete")
		$this->delete_this($this->uri->segment(6));
		if($this->uri->segment(5)=="export_excel")
		$this->export_excel();
		$current=($this->uri->segment(5) && is_int($this->uri->segment(5))) ? $this->uri->segment(5) : 0;
		$search_where=$this->search_where();
		return $this->content_list_search($search_where,$this->per_page,$this->uri->segment(5));
	}
	function get_category($id)
	{
		$result=$this->db->query("select * from ".$this->db->dbprefix."article_category where id=".$id);
		$row =$result->row();
		return $row->name;
	}
	function content_list_search($search_where='',$page_num='',$current='')
	{
		$current=($this->input->post("search")) ? 0 : $current;
		list($search_where,$total_record,$total_page,$current)=$this->get_sql_nav_search($search_where,$current);
		$result=$this->db->query($search_where,$this->search_band());
		$cate_array=array();
		$temp=array();
		foreach ($result->result() as $row)
		{
			$row->cate_name=$this->get_cate_name($row->category_id);
			$temp[]=$row;
		}
		$config['base_url'] = site_url().$this->this_link_url."";
		$config['total_rows'] = $total_record;
		$config['per_page'] = $this->per_page;
		$config['num_links'] = $this->num_links;
		$config['cur_page'] = $current;
		$this->pagination->initialize($config);
		$this_page=$this->pagination->create_links();
		return array("this_page"=>$this_page,"total_record"=>$total_record,"current"=>($current==0) ? '' : $current,"this_data"=>$temp);
	}
	function get_cate_name($id)
	{
		$catename="";
		$result=$this->db->query("select * from ".$this->db->dbprefix."article_category where id=?",array($id));
		$row=$result->result();
		if($row)
		{
			$row=$row[0];
			$catename=@$row->name;
			$result=$this->db->query("select * from ".$this->db->dbprefix."article_category where id=?",array($row->parent_id));
			$row=$result->result();
			if($row)
			$catename2=$row[0]->name."-->";
		}
		return $catename2.$catename;
	}
	function get_sql_nav_search($search_where,$current,$type='')
	{
		$search_where=($search_where=="") ? "" : $search_where."";
		$page_num    =$this->per_page;
		$current     =($current==null)  ? 0 : $current;
		$result=$this->db->query("select count(*) as total from ".$this->db->dbprefix.$this->table_name."  $search_where",$this->search_band());
		$row =$result->row();
		$total_record=$row->total;
		$total_page=($total_record % $page_num==0) ? intval($total_record/$page_num) : intval($total_record/$page_num)+1;
		if($type=="export_excel")
		{
			$search_sql="select * from ".$this->db->dbprefix.$this->table_name.$search_where;
			return $search_sql;
		}
		else
		{
			$search_sql="select * from ".$this->db->dbprefix.$this->table_name."  $search_where limit $current,$page_num";
			return array($search_sql,$total_record,$total_page,$current);
		}
	}
	function search_where()
	{
		$sql=" WHERE ".$this->index_id.">?";
		if($this->input->post("category"))
		{
			$sql.=" and category_id=?";
		}
		if($this->input->post("title"))
		{
			$sql.=" and subject like ?";
		}
		return $sql."  order by ".$this->index_id." desc";
	}
	function search_band()
	{
		$search_s=array("0");
		if($this->input->post("category"))
		{
			array_push($search_s,$this->input->post("category"));
		}
		if(trim($this->input->post("title")))
		{
			array_push($search_s,"%".trim($this->input->post("title"))."%");
		}
		return $search_s;
	}
	function delete_this($id)
	{
		$id=urldecode($id);
		$id_s=explode("`",$id);
		foreach ($id_s as $value)
		{
			$sql ="delete from ".$this->db->dbprefix.$this->table_name." where ".$this->index_id."=".$value;
			$this->db->query($sql);
			$sql ="delete from ".$this->db->dbprefix.$this->table_content_name." where ".$this->index_id."=".$value;
			$this->db->query($sql);
		}
		$current=$this->uri->segment(7);
		$this_url=site_url()."admin_admin/index/manager/success/article/".$current;
		redirect($this_url);
	}
}
?>
