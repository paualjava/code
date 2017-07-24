<?php
class module_data_match extends CI_Model {
	private $table_name='module_data';
	private $index_id="id";
	function main()
	{
		$id=$this->uri->segment(5);
		$data=array();
		$data_count=$this->input->post("data_count");
		if(preg_match("/^\d+$/is",$id))
		{
			$module_row=get_table_row("module",$id);
			$module_row2=get_table_row("module","module_data","url");
			$data['modele_data']=json_decode($module_row->data,true);
			$data['modele_data2']=json_decode($module_row2->data,true);
			if($data_count)
			{
				$this->save_data($data_count,$module_row,$data['modele_data'],$module_row->leixing,$module_row);
				$data['input']=$this->input->post();
			}
			else
			{

			}
		}
		$data['data_count']=($data_count) ? $data_count : 100;
		return array("list"=>$data);
	}
	function get_class()
	{
		get_browser();
	}
	function save_data($count,$module_row,$info2,$leixing)
	{
		$table_name=$module_row->url;
		$id=$module_row->id;
		$rand=$id % 3;
		$cate_array=array(0=>array("手机","笔记本","平板电脑","台式机","数码相机","服务器"),1=>array("女装","男装","内衣","箱包","鞋子","配饰"),2=>array("文艺","生活","科技","教育培训","回顾历史","人文管理"));
		$cate_array=$cate_array[$rand];
		if($leixing=="page")
		{
			$sql="select * from ".$this->db->dbprefix."module_data where leixing=? limit 0,100";
			$result=$this->db->query($sql,$leixing);
			foreach ($result->result_array() as $row)
			{
				$data=array();
				foreach($info2 as $key2=>$row2)
				{
					$key=$this->input->post($key2);

					if($key)
					{
						if($key=="cate")
						$data[$key2]=$this->get_cate_id($table_name,$key2,$cate_array);
						else
						$data[$key2]=$row[$key];
					}
				}
				$insert_str=$this->db->insert_string($this->db->dbprefix.$table_name, $data);
				//var_dump($insert_str);die("adf");
				$this->db->query($insert_str);
			}
		}
		else
		{
			$sql="select count(*) as total from ".$this->db->dbprefix."module_data where leixing!='page' limit 0,100";
			$result=$this->db->query($sql);
			$row=$result->result();
			$count=(intval($row[0]->total)>$count) ? $count : intval($row[0]->total)-1;
			$tmp=array();

			while(count($tmp)<$count){
				$tmp[]=mt_rand(0,$count);
				$tmp=array_unique($tmp);
			}
			$tmp=array_values($tmp);
			foreach($tmp as $value)
			{
				$sql="select * from ".$this->db->dbprefix."module_data where leixing!='page' order by id desc limit $value,1";
				$result=$this->db->query($sql);
				$row=$result->result_array();
				$row=$row[0];
				$data=array();
				foreach($info2 as $key2=>$row2)
				{
					$key=$this->input->post($key2);
					$key=($key2=="is_show") ? 1 : $key;
					if($key)
					{
						if($key=="cate")
						$data[$key2]=$this->get_cate_id($table_name,$key2,$cate_array);
						elseif($key2=="is_show")
						$data[$key2]=1;
						else
						$data[$key2]=$row[$key];
					}
				}
				$insert_str=$this->db->insert_string($this->db->dbprefix.$table_name, $data);
				//var_dump($insert_str);die("mmmmdddddddd");
				$this->db->query($insert_str);
				//$insert_sql();
			}
		}

	}
	function get_cate_id($table_name,$column_name,$cate_array)
	{
		$cate_name=$cate_array[mt_rand(0,count($cate_array)-1)];
		$table_name_cate=$table_name."_".$column_name;
		$table_name=str_replace("_id","",$table_name_cate)."_cate";
		$cate_row=$this->db->get_where($this->db->dbprefix.$table_name,array("name"=>$cate_name))->row();
		if($cate_row)
		{
			$cate_id=$cate_row->id;
		}
		else
		{
			$insert_str=$this->db->insert_string($this->db->dbprefix.$table_name, array("name"=>$cate_name,"postdate"=>time()));
			$this->db->query($insert_str);
			$cate_id=$this->db->insert_id();
		}
		return $cate_id;
	}
}
?>
