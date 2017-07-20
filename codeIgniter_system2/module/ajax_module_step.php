<?php
class ajax_module_step extends CI_Model {

	function main()
	{
		$array_url=$this->get_table_column();
		$data['array_url']=$array_url['array_url'];
		$data['array_url_v']=$array_url['array_url_v'];
		$data['info']=$this->input->post();
		$data['info2']=get_table_row("module",$this->input->post("url6"));
		$data['template_wap']=(@$data['info2']->template_wap) ? @$data['info2']->template_wap : "article:1";
		$data['template_site']=(@$data['info2']->template_site) ? @$data['info2']->template_site : "article:1";
		if($this->input->post("url6"))
		{
			$module_row=get_table_row("module",$this->input->post("url6"));
			$data['modele_data']=json_decode($module_row->data,true);
		}
		return $data;
	}
	function get_table_column()
	{
		$array_zh=array();
		$array_url=array();
		$array_url_v=array();
		$array_type=array();
		$array_cate_column=array();
		$array_cate_column_zh=array();
		$data_array="\$table=array(";
		$str='';
		if($this->input->post("field_1_1"))
		{
			$table_name=trim($this->input->post("table_name"));
			/***category***/
			//$str='CREATE TABLE IF NOT EXISTS `site_'.$table_name.'` (';
			$data_array_i=0;
			for($i=1;$i<=1000;$i++)
			{
				if($this->input->post("field_".$i."_1"))
				$data_array.=$data_array_i."=>array(";
				for($j=1;$j<=11;$j++)
				{
					$str_bian=($j==2) ? "" : " ";
					$field_input=trim($this->input->post("field_".$i."_".$j));
					//$field_input=($j==4 && $field_input) ? "character set utf8 COLLATE ".$field_input : $field_input;
					$field_input=($j==4 && $field_input) ? "" : $field_input;
					$field_input=($j==6 && $field_input=="NULL") ? ((strpos(trim($this->input->post("field_".$i."_2")),"text")===false) ? "default " : "") : $field_input;
					$field_input=($j==7 && $field_input) ? ((strpos(trim($this->input->post("field_".$i."_2")),"text")===false) ? "'".$field_input."'" : "")  : $field_input;
					$field_input=($j==7 && $field_input) ? ((stripos(trim($this->input->post("field_".$i."_2")),"int")!==false) ? intval($field_input) : "")  : $field_input;
					$field_input=($j==9 && $field_input) ? "COMMENT '".$field_input."'" : $field_input;
					if($j==3 && $field_input && !strpos(trim($this->input->post("field_".$i."_2")),"text"))
					$str.="(".trim($field_input).")".$str_bian;
					elseif($j==3 && $field_input && strpos(trim($this->input->post("field_".$i."_2")),"text"))
					$str.=$str_bian;
					elseif($j!=10)
					$str.=$field_input.$str_bian;
					elseif($j==10 && strpos(trim($this->input->post("field_".$i."_10")),"cate")!==false)
					{
						array_push($array_cate_column,trim($this->input->post("field_".$i."_1")));
						array_push($array_cate_column_zh,trim($this->input->post("field_".$i."_9")));
					}
					if($this->input->post("field_".$i."_1"))
					{
						$field_input=($j==9 && $this->input->post("field_".$i."_".$j)) ? $this->input->post("field_".$i."_".$j) : $this->input->post("field_".$i."_".$j);
						$data_array.="\"".$field_input."\",";
					}
				}
				$data_array=(substr($data_array,-1)==",") ? substr($data_array,0,strlen($data_array)-1) : $data_array;
				if($this->input->post("field_".$i."_1"))
				$data_array.="),";
				if(!$this->input->post("field_".$i."_1"))
				break;
				else
				{
					array_push($array_zh,trim($this->input->post("field_".$i."_9")));
					array_push($array_url,trim($this->input->post("field_".$i."_1")));
					$url=$this->input->post("field_".$i."_1");
					array_push($array_url_v,trim($this->input->post("column_name_".$url."_1")));
					array_push($array_type,trim($this->input->post("field_".$i."_2")));
					$str.=",\n\r";
				}
				$data_array_i++;
			}
			$data_array=(substr($data_array,-1)==",") ? substr($data_array,0,strlen($data_array)-1) : $data_array;
			$data_array.=");";
			$str.='  PRIMARY KEY  (`'.$this->input->post("field_1_1").'`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';

			//$this->db->query($str);
		}
		elseif($this->input->post("table_structure"))
		{
			/*CREATE TABLE IF NOT EXISTS `site_module_type` (
			`id` int(10) unsigned NOT NULL auto_increment COMMENT 'ID',
			`name` varchar(20) NOT NULL COMMENT '标题',
			`type` varchar(100) NOT NULL COMMENT '类型',
			`data` mediumtext COMMENT '数据',
			`info_control` mediumtext NOT NULL COMMENT '控制�?',
			`info_view` mediumtext NOT NULL COMMENT '视图',
			`postdate` int(10) NOT NULL COMMENT '时间',
			PRIMARY KEY  (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;*/
			$table_structure=str_replace("`","",$this->input->post("table_structure"));
			$table_name=preg_replace("/CREATE TABLE IF NOT EXISTS([^\(]*)\(.*/is","\$1",$table_structure);
			$table_name=trim(str_replace("site_","",$table_name));
			$table_structure=substr($table_structure,strpos($table_structure,"(")+1,strrpos($table_structure,",")-strpos($table_structure,"(")-1);
			$temp_array=explode(",",$table_structure);
			$k=1;
			foreach ($temp_array as $temp_v)
			{
				if(stripos($temp_v,"KEY ")===false)
				{
					$temp_v=trim($temp_v);
					$temp_v=str_replace("NOT NULL","NOTNULL",$temp_v);
					$temp_v2=explode(" ",$temp_v);
					$temp_row2=$temp_v2[1];
					$temp_row2=preg_replace("/\(\d+\)/","",$temp_v2[1]);
					$temp_zh=(strpos($temp_v,"COMMENT")) ? preg_replace("/.*COMMENT '([^']*).*'/","\$1",$temp_v) : $temp_v2[0];
					array_push($array_url,$temp_v2[0]);
					array_push($array_type,$temp_row2);
					array_push($array_zh,$temp_zh);
					$k++;
				}
			}
			//$data_array=$this->get_structure_data($temp_array);
			//$this->db->query($this->input->post("table_structure"));
		}
		return array("array_url"=>$array_url,"array_url_v"=>$array_url_v);
	}
}
?>
