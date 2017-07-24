<?php
class module_add extends CI_Model {
	private $table_name='module';
	private $dir_laravel='generate_code/laravel/';
	function __construct()
	{
	}
	function main()
	{

		if($this->uri->segment(5)=="ajax_valid")
		$this->ajax_valid();
		if($this->uri->segment(5)=="ajax_valid2")
		$this->ajax_valid2();
		$type=$this->uri->segment(5);
		$table='';
		$table_url2="";
		$table_name2="";
		if($this->input->post("module"))
		{
			$module=$this->input->post("module");
			redirect(site_url()."admin_admin/index/manager/module_add/".$module);
		}
		if(preg_match("/^module_.*$/is",$type))
		{
			/*`name` varchar(20) NOT NULL,
			`url` varchar(100) character set gbk NOT NULL,
			`position` char(15) default 'index',
			`sort_order` int(3) default '0',
			`postdate` int(10) unsigned default '0',
			`status` tinyint(1) NOT NULL default '1',
			PRIMARY KEY  (`id`),*/
			$leixing=str_replace("module_","",$type);
			$leixing2=$leixing;
			$leixing=($leixing=="table_structure") ? "friend_link" : $leixing;

			if(preg_match("/^\d+$/is",$this->uri->segment(6)))
			{
				$mod_row=get_table_row("module",$this->uri->segment(6),"id");
				$table_url2=$mod_row->url;
				$table_name2=$mod_row->name;
			}
			else
			$mod_row=get_table_row("module_type",$leixing,$id="type");

			if($mod_row)
			{
				$mod_row->data=str_replace("&gt;",">",$mod_row->data);
				$mod_row->data=str_replace("&lt;","<",$mod_row->data);
			}
			if(array_key_exists($leixing,get_module_type()) && $leixing!="table_structure")
			//if($leixing=="friend_link" || $leixing=="common" || $leixing=="page")
			{
				//var_dump($mod_row);die();<br>
				//eval($mod_row->data);
				$table=json_decode($mod_row->data);
				/*$table[0]=array("id","int",10,'',"unsigned","NOT NULL","","auto_increment","ID");
				$table[1]=array("name","varchar",20,'utf8_general_ci',"","NOT NULL","","","标题");
				$table[2]=array("url","varchar",100,'utf8_general_ci',"","NOT NULL","","","URL");
				$table[3]=array("position","char",15,'utf8_general_ci',"","NULL","index","","位置");*/
				//$table=array(0=>array("id","int",10,'',"unsigned","NOT NULL","","auto_increment","ID"),1=>array("name","varchar",20,'utf8_general_ci',"","NOT NULL","","","标题"),2=>array("url","varchar",100,'utf8_general_ci',"","NOT NULL","","","URL"),3=>array("position","char",15,'utf8_general_ci',"","NULL","index","","位置"));

			}
			elseif($leixing=="category")
			{
				$table[0]=array("id","int",10,'',"unsigned","NOT NULL","","auto_increment","ID");
				$table[1]=array("name","varchar",20,'utf8_general_ci',"","NOT NULL","","","名称");
				$table[2]=array("url","char",40,'utf8_general_ci',"","NOT NULL","","","别名");
				$table[3]=array("parent_id","int",6,'',"","NULL","0","","父类");
				$table[4]=array("sort_order","int",6,'',"","NULL","0","","排序");
			}
			elseif($leixing=="table_structure")
			{
				$table=array();
			}
			if($this->input->post("field_1_1") || $this->input->post("table_structure"))
			{
				$array_zh=array();
				$array_url=array();
				$array_type=array();
				$array_type_new=array();
				$array_cate_column=array();
				$array_cate_column_zh=array();
				$table_name_zh=trim($this->input->post("table_name_zh"));
				$data_array="\$table=array(";
				if($this->input->post("field_1_1"))
				{
					$table_name=trim($this->input->post("table_name"));
					/***category***/
					$str='CREATE TABLE IF NOT EXISTS `site_'.$table_name.'` (';
					$data_array_i=0;
					for($i=1;$i<=1000;$i++)
					{
						if($this->input->post("field_".$i."_1"))
						$data_array.=$data_array_i."=>array(";
						for($j=1;$j<=10;$j++)
						{
							$str_bian=($j==2) ? "" : " ";
							$field_input=$this->input->post("field_".$i."_".$j);
							//$field_input=($j==4 && $field_input) ? "character set utf8 COLLATE ".$field_input : $field_input;
							$field_input=($j==4 && $field_input) ? "" : $field_input;
							$field_input=($j==6 && $field_input=="NULL") ? ((strpos($this->input->post("field_".$i."_2"),"text")===false) ? "default " : "") : $field_input;
							if($j==7)
							{
								if(!$field_input && $this->input->post("field_".$i."_6")=="NULL")
								{
									if(stripos($this->input->post("field_".$i."_2"),"int")!==false)
									$field_input=0;
									elseif(stripos($this->input->post("field_".$i."_2"),"char")!==false)
									$field_input="' '";
									elseif(stripos($this->input->post("field_".$i."_2"),"text")!==false)
									$field_input="";
								}

								elseif(($field_input===0 || $field_input==="0" || $field_input!=="" || $field_input>0) && $this->input->post("field_".$i."_6")=="NOT NULL")
								{
									$field_input=" DEFAULT '".$field_input."' ";
								}
								elseif($field_input  && $this->input->post("field_".$i."_6")=="NULL")
								{
									if($field_input=="null" && stripos($this->input->post("field_".$i."_2"),"int")!==false)
									$field_input=0;
								}
								//var_dump($field_input."****************".$this->input->post("field_".$i."_1"));
							}
							$field_input=($j==9 && $field_input) ? "COMMENT '".$field_input."'" : $field_input;
							if($j==3 && $field_input && !strpos($this->input->post("field_".$i."_2"),"text"))
							$str.="(".trim($field_input).")".$str_bian;
							elseif($j==3 && $field_input && strpos($this->input->post("field_".$i."_2"),"text"))
							$str.=$str_bian;
							elseif($j!=10)
							$str.=$field_input.$str_bian;
							elseif($j==10 && strpos($this->input->post("field_".$i."_10"),"cate")!==false)
							{
								array_push($array_cate_column,$this->input->post("field_".$i."_1"));
								array_push($array_cate_column_zh,$this->input->post("field_".$i."_9"));
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
							array_push($array_zh,$this->input->post("field_".$i."_9"));
							array_push($array_url,$this->input->post("field_".$i."_1"));
							array_push($array_type,$this->input->post("field_".$i."_2"));
							array_push($array_type_new,$this->input->post("field_".$i."_10"));
							$str.=",\n\r";
						}
						$data_array_i++;
					}
					$data_array=(substr($data_array,-1)==",") ? substr($data_array,0,strlen($data_array)-1) : $data_array;
					$data_array.=");";
					$str.='  PRIMARY KEY  (`'.$this->input->post("field_1_1").'`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
					@$this->db->query("DROP TABLE IF EXISTS `site_".$table_name."`");

					$this->db->query($str);
					$create_view=$this->input->post("create_type");
					$laravel_timestamp=$this->input->post("laravel_timestamp");
					$input_data=$this->get_input_data($array_url,$this->input->post());

					$this->insert_module($table_name_zh,$table_name,$leixing2,$mod_row,$input_data,$create_view,$laravel_timestamp);
					//var_dump($create_view);die();

					if($create_view=="create_view")
					{
						//插入一行
						$create_view_current=current($input_data);
						$insert_str=$this->db->insert_string($this->db->dbprefix.$table_name,array($create_view_current[0]=>1));
						$this->db->query($insert_str);

						//$this->write_file_view($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_view_edit($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						//$this->write_file_module($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_module_edit($table_name,$array_url,$input_data,$array_type_new,$create_view);
						$this->write_file_yii2_view_update($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_yii2_models($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_yii2_controller($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
					}
					else
					{
						$this->write_file_view($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_view_add($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_view_edit($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_module($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_module_add($table_name,$array_url,$input_data,$array_type_new);
						$this->write_file_module_edit($table_name,$array_url,$input_data,$array_type_new,$create_view);
						//laravel
						$this->write_file_laravel_view_index($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_laravel_view_create($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_laravel_view_update($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_view_add($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_view_edit($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_laravel_controller($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$laravel_timestamp);
						//yii2
						$this->write_file_yii2_view($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_yii2_view_create($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_yii2_view_update($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_yii2_models($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						//$this->write_file_view_add($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						//$this->write_file_view_edit($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
						$this->write_file_yii2_controller($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$this->write_file_yii2_view_front($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
						$template_wap=$this->insert_template_name($table_name,$this->input->post("template_wap"),"wap");
						if($template_wap[0]=="help")//帮助中心
						{
							$this->write_file_front_controller_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
							$this->write_file_front_view_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
							$this->write_file_wap_controller_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
							$this->write_file_wap_front_view_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
						}
						elseif($template_wap[0]=="article")//文章列表
						{
							$this->write_file_front_controller_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
							$this->write_file_front_view_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
							$this->write_file_wap_controller_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
							$this->write_file_wap_front_view_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
						}
						elseif($template_wap[0]=="photo")//图片列表
						{
							$this->write_file_front_controller_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view);
							$this->write_file_front_view_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
							$this->write_file_wap_controller_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh);
							$this->write_file_wap_front_view_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_wap[1]);
						}
						else
						$this->write_file_front_controller($table_name,$array_url,$input_data,$array_type_new);
						$this->write_file_front_view_add($table_name,$array_url,$input_data,$array_type_new);
						$this->write_file_front_view($table_name,$array_url,$input_data,$array_type_new);
					}

					//$pic_multiple_table=$this->get_pic_multiple_table($table_name);
					//@$this->db->query("TRUNCATE TABLE `site_".$pic_multiple_table."`");
					//var_dump($input_data);die();
					//update is_first
					/*$data=array("is_first"=>1);
					$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_name,$data,"id=".$info2->id);*/
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
					$i=0;
					if(preg_match("/CREATE TABLE IF NOT EXISTS([^\(]*)\(.*/is",$table_structure))
					{
						$table_name=preg_replace("/CREATE TABLE IF NOT EXISTS([^\(]*)\(.*/is","\$1",$table_structure);
						$i=1;
					}
					elseif(preg_match("/CREATE TABLE ([^\(]*)\(.*/is",$table_structure))
					{
						$table_name=preg_replace("/CREATE TABLE ([^\(]*)\(.*/is","\$1",$table_structure);
						$i=1;
					}
					if($i==1)
					{
						$table_name=trim(str_replace("site_","",$table_name));
						$table_structure=substr($table_structure,strpos($table_structure,"(")+1,strrpos($table_structure,",")-strpos($table_structure,"(")-1);
						$table_structure=preg_replace("/(\d+),(\d+)/is","\$1**\$2",$table_structure);
						$temp_array=explode(",",$table_structure);
						$k=1;
						//var_dump($temp_array);die();
						/*		foreach ($temp_array as $temp_v)
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
                        }*/
						//$data_array=$this->get_structure_data($temp_array,$array_type,$array_zh);
						list($data_array,$array_url)=$this->get_structure_data($temp_array);
						//$this->db->query($this->input->post("table_structure"));
						//var_dump($data_array);die();
						$create_view=(@$create_view) ? $create_view : "";
						$table_structure_insert_id=$this->insert_module($table_name_zh,$table_name,$leixing2,$mod_row,$data_array,$create_view);
					}
				}
				//if($this->input->post("category"))
				//$this->menu_insert($this->input->post("category"),$table_name,$table_name_zh,$array_cate_column,$array_cate_column_zh,$table_name);
				$this->insert_menu_or_update($table_name,$table_name_zh,$array_cate_column,$array_cate_column_zh,$create_view);
				if(array_key_exists($leixing,get_module_type()))
				//if($leixing=="friend_link" || $leixing=="common" || $leixing=="page" || $leixing=="table_structure")
				{
					//$leixing=($leixing=="table_structure") ? "friend_link" : $leixing;
					//$leixing=($leixing=="common") ? "friend_link" : $leixing;
					//	$leixing=($leixing=="page") ? "friend_link" : $leixing;
					//$leixing=($leixing=="article") ? "friend_link" : $leixing;
					/*$leixing="friend_link";
					$this->load->helper('file');
					/****view******/
					/*$file=FCPATH."application/models2/".$leixing."/views/".$leixing.".php";
					$content=file_get_contents($file);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/views/admin_admin/".$table_name.".php";
					$s=write_file($file2, $content,"w");*/

					/****view_add******/
					/*$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing."_add.php";
					$content=file_get_contents($file_add);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/views/admin_admin/".$table_name."_add.php";
					$s=write_file($file2, $content,"w");*/
					/****view_edit******/
					/*$file_edit=FCPATH."application/models2/".$leixing."/views/".$leixing."_edit.php";
					$content=file_get_contents($file_edit);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/views/admin_admin/".$table_name."_edit.php";
					$s=write_file($file2, $content,"w");*/
					/****module******/
					/*$this->load->helper('file');
					$leixing="friend_link";
					$file=FCPATH."application/models2/".$leixing."/models/".$leixing.".php";
					$content=file_get_contents($file);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/models/admin_admin/".$table_name.".php";
					$s=write_file($file2, $content,"w");*/
					/****module_add******/
					/*$file_add=FCPATH."application/models2/".$leixing."/models/".$leixing."_add.php";
					$content=file_get_contents($file_add);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/models/admin_admin/".$table_name."_add.php";
					$s=write_file($file2, $content,"w");*/
					/****module_edit******/
					/*$file_edit=FCPATH."application/models2/".$leixing."/models/".$leixing."_edit.php";
					$content=file_get_contents($file_edit);
					$content=str_replace($leixing,$table_name,$content);
					$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
					$file2=FCPATH."application/models/admin_admin/".$table_name."_edit.php";
					$s=write_file($file2, $content,"w");*/
					/*x
					/*$file_add=FCPATH."application/models2/".$leixing."/views/friend_link_add.php";
					$content=file_get_contents($file_add);
					$fp=fopen($file_add,'w');
					fwrite($fp,$content);
					fclose($fp);
					$file_edit=FCPATH."application/models2/".$leixing."/views/friend_link_edit.php";
					$content=file_get_contents($file_add);
					file_put_contents(FCPATH."application/view/admin_admin/".$table_name."_edit.php",$content);*/
				}
				/***insert into module table*****/
				if($this->input->post("table_structure"))
				redirect(site_url()."admin_admin/index/manager/module_add/module_common/".$table_structure_insert_id);
				else
				{
					$arr = array('errno'=>"0", 'error'=>"添加成功！",'url'=>url_admin()."module/success_bind");
					echo json_encode($arr);die();
				}

			}
		}
		$data_enum='';
		if(preg_match("/^\d+$/is",$this->uri->segment(6)))
		$data_enum=(@$mod_row) ? json_decode($mod_row->data_enum,true) :"";
		//var_dump($table);
		//var_dump($data_enum);die();
		$url6=(preg_match("/^\d+$/is",$this->uri->segment(6))) ? $this->uri->segment(6) : "";
		return array("type"=>$type,"table"=>$table,"data_enum"=>$data_enum,"show_category"=>$this->show_category(),"url6"=>$url6,"table_url2"=>$table_url2,"table_name2"=>$table_name2,"mod_row"=>@$mod_row);
	}
	function get_input_data($array_url,$post)
	{
		$array_f=array();
		$i=1;
		for($i=1;$i<=1000;$i++)
		{
			if(!$this->input->post("field_".$i."_1"))
			break;
			$array_key=$i-1;
			$url=trim($array_url[$array_key]);
			$temp=array();
			for($j=1;$j<=10;$j++)
			{
				if($this->input->post("field_".$i."_1"))
				{
					$field_input=trim($this->input->post("field_".$i."_".$j));
					if($j==7)
					{
						$field_input=trim($this->input->post("field_".$i."_7"));
						if(!$field_input && $this->input->post("field_".$i."_6")=="NULL")
						{
							if(stripos($this->input->post("field_".$i."_2"),"int")!==false)
							$field_input=0;
							elseif(stripos($this->input->post("field_".$i."_2"),"char")!==false)
							$field_input="' '";
							elseif(stripos($this->input->post("field_".$i."_2"),"text")!==false)
							$field_input="";
						}
						elseif(($field_input===0 || $field_input==="0" || $field_input!=="" || $field_input>0) && $this->input->post("field_".$i."_6")=="NOT NULL")
						{
							$field_input=" DEFAULT \"".$field_input."\" ";
						}
						elseif($field_input  && $this->input->post("field_".$i."_6")=="NULL")
						{
							if($field_input=="null" && stripos($this->input->post("field_".$i."_2"),"int")!==false)
							$field_input=0;
						}
					}
					elseif($j==3)
					{
						if(stripos($this->input->post("field_".$i."_2"),"text")!==false)
						$field_input="";
					}
					array_push($temp,$field_input);
				}
			}
			for($j=2;$j<=8;$j++)
			{
				if($this->input->post("field_".$i."_1"))
				{
					array_push($temp,$this->input->post("column_name_".$url."_".$j));
				}
			}

			$array_f[$url]=$temp;
		}
		return $array_f;
	}
	function get_input_data_more_value()
	{
		$array_f=array();
		$i=1;
		for($i=1;$i<=1000;$i++)
		{
			if(!$this->input->post("field_".$i."_1"))
				break;
			if($this->input->post("field_".$i."_10")=="more_value")
			{
				$column=$this->input->post("field_".$i."_1");
				$array_f[$column]=$this->input->post("field_".$i."_11");
			}
		}
		return $array_f;
	}
	function write_file_yii2_view_update($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view='')
	{
		$str="";
		$str_last_edit="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$model_column_first="";
		$i=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		$class_name=$this->get_yii2_class_name($table_name);
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$class_name.'['.$value[0].']" type="text" value="<?php echo $model->'.$value[0].';?>" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></a></span>
          <?php $'.$value[0].'=(substr($model->'.$value[0].',0,4)=="http" || substr($model->'.$value[0].',0,1)=="/") ? $model->'.$value[0].' : (($model->'.$value[0].') ? Yii::getAlias("@base_url")."/".$model->'.$value[0].' : "");?>
          <div><img src="<?php echo $'.$value[0].';?>" style="max-width:360px;margin:10px 0 2px 0;"></div>'.$require.'<span class="help-inline">'.$value[12].'</span> </div>
      </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$table_name.'-'.$value[0].'" name="'.$class_name.'['.$value[0].']" placeholder="'.str_replace("请输入","",$value[12]).'" value="<?php echo $model->'.$value[0].';?>" '.$input_rule.'>
          <span class="input-group-btn">
<button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button>
</span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$table_name.'-'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate" || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$style=($value[13]=="input-xxlarge") ? 'style="width:545px"' : "";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$style.' style="width:544px">
		<option value="">请选择</option>
		 <?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$model->'.$value[0].') ? \'selected="selected"\' : "";?>
<option value="<?php echo $row->id;?>" <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?>
        </select> &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="hide_'.$value[0].'" id="hide_'.$value[0].'" type="text"  class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" value="<?= '.$this->get_yii2_class_name($table_name).'::getCategory'.$value[0].'($model->'.$value[0].');?>" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <input name="'.$class_name.'['.$value[0].']" type="hidden" id="'.$table_name.'-'.$value[0].'" value="<?php echo $model->'.$value[0].';?>"/>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last_edit.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#'.$table_name.'-'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'\']);?>\',\''.$cate_table.'\');
            });	
            $("#'.$cate_table.'_content .cate_ajax").live("click",function ()
			{
				column="'.$table_name.'-'.$value[0].'";
				tag_str=$("#"+column).val();
				id=$(this).attr("attr_id");
				if(($(this).hasClass("cate_correct")))
				{		
					tag_str=tag_str.replace(id+",","");
					$(this).removeClass("cate_correct");
				}
				else
				{
					$(this).addClass("cate_correct");	
					if(tag_str=="")
					tag_str=",";
					tag_str=tag_str+id+",";
				}
				$("#"+column).val(tag_str);
				csrf=$(".csrf").attr("_csrf");
				url="<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'-click\']);?>";
				$.post(url,{"_csrf": csrf,"tag_str":tag_str},function(data){	
					$("#hide_'.$value[0].'").val(data);						 
				})
			 })
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" '.$input_rule.'><?php echo $model->'.$value[0].';?></textarea>
          '.$require.'<span class="help-inline">请输入<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> </div>
      </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$table_name.'-'.$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>名称</th>
          <th>内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
      
<?php 
$i=1;
if($model->'.$value[0].')
{
$'.$value[0].'=json_decode($model->'.$value[0].');
$'.$value[0].'_txt=$'.$value[0].'->txt;
$'.$value[0].'_value=$'.$value[0].'->value;
foreach(@$'.$value[0].'_txt as $key=>$edu_txt)
{
if($edu_txt)
{
?>   
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'"><?php echo $i;?></span>：</td>
          <td><input type="'.$value[0].'_txt" name="'.$value[0].'_txt[]" id="txt1" value="<?php echo $edu_txt;?>"  class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" value="<?php echo @$'.$value[0].'_value[$key];?>"></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php $i++;}}}?> 
<?php if($i==1){?>
<tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1"   class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php }?>       
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}

				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <?php
           $check1=($model->'.$value[0].'==1) ? "checked=\"checked\"" : "";
           $check2=($model->'.$value[0].'==2) ? "checked=\"checked\"" : "";
          ?>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="1" <?php echo $check1;?> style="margin-top:0"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="2" <?php echo $check2;?> style="margin-top:0"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.='<div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="102265">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
              <?php
		  if(@$pic_list){
		  foreach($pic_list as $p_row)
		  {
			  $pic=(substr($p_row->pic,0,4)=="http" || substr($p_row->pic,0,1)=="/") ? $p_row->pic : (($p_row->pic) ? $p_row->pic : $base_url."resource/images/avatar.gif");?>
              <li class="imgbox" data-post-id="<?php echo $p_row->id;?>" data-url="<?php echo $p_row->pic;?>"> <a class="item_close" href="javascript:void(0)" title="删除"></a>
                <input type="hidden" value="<?php echo $p_row->id;?>" name="phout_list[]">
                <input type="hidden" value="<?php echo $pic;?>" name="phout_url[<?php echo $p_row->id;?>][]">
                <span class="item_box"><img src="<?php echo $pic;?>"></span> <span class="item_input">
                <textarea name="imagestexts[<?php echo $p_row->id;?>][]" class="bewrite" cols="3" rows="4" style="resize: none" data-rule-maxlength="150"><?php echo $p_row->description;?></textarea>
                <i class="shadow hc"></i> </span> </li>
              <?php }}?>
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js=' <script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				
				\'utype\':"102"
			},
			url: "<?php echo $admin_url;?>'.$table_name.'/upimg",
			del_url: "<?php echo $admin_url;?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		G.logic.uploadify.init(op);	
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$str.='<div class="control-group">
                                                <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
                                                <div class="controls">
                                                     <textarea class="'.$value[13].'" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" style="height:80px;"><?php echo $model->'.$value[0].';?></textarea>'.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
                                            </div>';
				}
				else
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input type="text" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="<?php echo $model->'.$value[0].';?>" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">请输入<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
			}
			$model_column_first=($i==1) ? $value[0] : $model_column_first;
			$i++;
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';

		}

		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}

		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file=FCPATH."application/models2/yii2/views/slide/update_create_view.php";
		else
		$file=FCPATH."application/models2/yii2/views/slide/update.php";
		$content=file_get_contents($file);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$content=str_replace("<model_column_first>",$model_column_first,$content);
		$content=str_replace("<table_name_zh_create_view>",$table_name_zh,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_edit>",$str,$content);
		$content=str_replace("<str_last_edit>",$str_last_edit,$content);
		$content=str_replace("<Slide>",$class_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=$this->replace_blank_row($content);

		$file2=FCPATH."yii2/backend/views/".$table_name."/";
		if($create_view=="create_view")
		@create_dir($file2);
		if($create_view=="create_view")
		$file2=FCPATH."yii2/backend/views/".$table_name."/index.php";
		else
		$file2=FCPATH."yii2/backend/views/".$table_name."/update.php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_view_edit($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view='')
	{
		$str="";
		$str_last_edit="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$model_column_first="";
		$i=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$value[0].'" type="text" value="<?php echo $this_data->'.$value[0].';?>" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传图片</a></span>
          <?php $'.$value[0].'=(substr($this_data->'.$value[0].',0,4)=="http" || substr($this_data->'.$value[0].',0,1)=="/") ? $this_data->'.$value[0].' : (($this_data->'.$value[0].') ? base_url().$this_data->'.$value[0].' : "");?>
          <div><img src="<?php echo $'.$value[0].';?>" style="max-width:360px;margin:10px 0 2px 0;"></div>'.$require.'<span class="help-inline">'.$value[12].'</span> </div>
      </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$value[0].'" name="'.$value[0].'" placeholder="'.str_replace("请输入","",$value[12]).'" value="<?php echo $this_data->'.$value[0].';?>" '.$input_rule.'>
          <span class="input-group-btn">
<button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button>
</span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate" || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$value[0].'" id="'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" style="width:544px">
		<option value="">请选择</option>
		<?php show_class_select(\''.$cate_table.'\',0,$this_data->id,$this_data->'.$value[0].');?>
        </select> &nbsp; &nbsp; <a href="<?php echo $admin_url;?>'.$cate_table.'" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="'.$value[0].'" id="'.$value[0].'" type="text"  class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" value="<?php echo ajax_show_tag("'.$cate_table.'",$this_data->'.$value[0].');?>" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo $admin_url;?>'.$cate_table.'" target="_blank">管理分类</a>
          <input name="hide_'.$value[0].'" type="hidden" id="hide_'.$value[0].'" value="<?php echo $this_data->'.$value[0].';?>"/>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last_edit.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#hide_'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo $admin_url;?>\',\''.$cate_table.'\',\''.$value[0].'\');
            });	
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" '.$input_rule.'><?php echo $this_data->'.$value[0].';?></textarea>
          '.$require.'<span class="help-inline">'.$value[12].'</span> </div>
      </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>名称</th>
          <th>内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
      
<?php 
$i=1;
if($this_data->'.$value[0].')
{
$'.$value[0].'=json_decode($this_data->'.$value[0].');
$'.$value[0].'_txt=$'.$value[0].'->txt;
$'.$value[0].'_value=$'.$value[0].'->value;
foreach(@$'.$value[0].'_txt as $key=>$edu_txt)
{
if($edu_txt)
{
?>   
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'"><?php echo $i;?></span>：</td>
          <td><input type="'.$value[0].'_txt" name="'.$value[0].'_txt[]" id="txt1" value="<?php echo $edu_txt;?>"  class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" value="<?php echo @$'.$value[0].'_value[$key];?>"></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php $i++;}}}?> 
<?php if($i==1){?>
<tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1"   class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php }?>       
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}

				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <?php
           $check1=($this_data->'.$value[0].'==1) ? "checked=\"checked\"" : "";
           $check2=($this_data->'.$value[0].'==0) ? "checked=\"checked\"" : "";
          ?>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="1" <?php echo $check1;?> style="margin-top:0px"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="2" <?php echo $check2;?> style="margin-top:0px"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="more_value")
				{
					if($this->input->post("table_name"))
						$mod_row2=get_table_row("module",$this->input->post("table_name"),"name");
					else
					$mod_row2=get_table_row("module",$this->uri->segment(6),"id");
					$data_enum=$mod_row2->data_enum;
					$data_enum=json_decode($data_enum,true);
					$column=$value[0];
					$data_enum=$data_enum[$column];
					$data_enum=json_decode($data_enum);
					$data_enum_str='';
					$data_enum_str='<?php
		$enum_'.$value[0].'=array(';
					$data_enum_str.="\r\n";
					foreach($data_enum as $enum_key=>$enum_value)
					{
						$data_enum_str.="			'".$enum_key."'=>'".$enum_value."',\r\n";
					}
					$data_enum_str.='		);?>
    <?php
	$data_enum_i=1;
	foreach($enum_'.$value[0].' as $eunm_key=>$enum_value){
	$check=($data_enum_i==$this_data->'.$value[0].') ? \'checked="checked"\' : "";?>
    <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="<?php echo $eunm_key;?>"  style="margin-top:0px" <?php echo $check;?>/><?php echo $enum_value;?></label>
    <?php $data_enum_i++;}?>';
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
         '.$data_enum_str.'
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.='<div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="102265">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
              <?php
		  if(@$pic_list){
		  foreach($pic_list as $p_row)
		  {
			  $pic=(substr($p_row->pic,0,4)=="http" || substr($p_row->pic,0,1)=="/") ? $p_row->pic : (($p_row->pic) ? base_url().$p_row->pic : $base_url."resource/images/avatar.gif");?>
              <li class="imgbox" data-post-id="<?php echo $p_row->id;?>" data-url="<?php echo $p_row->pic;?>"> <a class="item_close" href="javascript:void(0)" title="删除"></a>
                <input type="hidden" value="<?php echo $p_row->id;?>" name="phout_list[]">
                <input type="hidden" value="<?php echo $pic;?>" name="phout_url[<?php echo $p_row->id;?>][]">
                <span class="item_box"><img src="<?php echo $pic;?>"></span> <span class="item_input">
                <textarea name="imagestexts[<?php echo $p_row->id;?>][]" class="bewrite" cols="3" rows="4" style="resize: none" data-rule-maxlength="150"><?php echo $p_row->description;?></textarea>
                <i class="shadow hc"></i> </span> </li>
              <?php }}?>
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js=' <script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				
				\'utype\':"102"
			},
			url: "<?php echo $admin_url;?>'.$table_name.'/upimg",
			del_url: "<?php echo $admin_url;?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		G.logic.uploadify.init(op);	
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$str.='<div class="control-group">
                                                <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
                                                <div class="controls">
                                                     <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" style="height:80px;"><?php echo $this_data->'.$value[0].';?></textarea>'.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
                                            </div>';
				}
				else
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" name="'.$value[0].'" id="'.$value[0].'" value="<?php echo $this_data->'.$value[0].';?>" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
			}
			$model_column_first=($i==1) ? $value[0] : $model_column_first;
			$i++;
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';

		}

		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}

		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing."_edit_create_view.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing."_edit.php";
		$content=file_get_contents($file_add);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$content=str_replace("<model_column_first>",$model_column_first,$content);
		$content=str_replace("<table_name_zh_create_view>",$table_name_zh,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_edit>",$str,$content);
		$content=str_replace("<str_last_edit>",$str_last_edit,$content);
		$content=$this->replace_blank_row($content);
		if($create_view=="create_view")
		$file2=FCPATH."application/views/admin_admin/".$table_name.".php";
		else
		$file2=FCPATH."application/views/admin_admin/".$table_name."_edit.php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_yii2_controller($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$is_show="is_show";
		$sort_order="sort_order";
		$controller_get_cate="";
		$getCategoryMore="";
		$search_cate="";
		$search_cate_js="";
		$search_cate_js2="";
		$behaviors="";
		foreach($input_data as $key=>$value)
		{
			if($value[15]=="1" && $value[14]!="1")//列显示
			{
				if($value[9]=="is_show")
				{
					$is_show=$value[0];
					$search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$search_cate_js.='  || $'.$value[0].'!=""';
					$search_cate_js2.=',"'.$value[0].'"=>$'.$value[0];
					$behaviors.=",'ajax-save-is-show'";
				}
				elseif($value[9]=="sort_order")
				{
					$sort_order=$value[0];
					$behaviors.=",'ajax-save-sort-order'";
				}
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$search_cate.="\r\n";
					$search_cate_js.='  || $'.$value[0].'!=""';
					$search_cate_js2.=',"'.$value[0].'"=>$'.$value[0];
					$search_cate.="\r\n";
					$controller_get_cate.="		'category_list_".$value[0]."' => ".$this->get_yii2_class_name($table_name)."::getCategoryList".ucfirst($value[0])."(),\r\n";
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$search_cate.="\r\n";
					$search_cate_js.='  || $'.$value[0].'!=""';
					$search_cate_js2.=',"'.$value[0].'"=>$'.$value[0];
					$controller_get_cate.="		'category_list_".$value[0]."' => ".$this->get_yii2_class_name($table_name)."::getCategoryList".ucfirst($value[0])."(),\r\n";
					//多分类
					$getCategory_content=file_get_contents(FCPATH."application/models2/yii2/getCategoryMore.php");
					$getCategory_content=str_replace("MoreCategory","More".ucfirst($value[0]),$getCategory_content);
					$getCategory_content=str_replace("ListCategory","List".ucfirst($value[0]),$getCategory_content);
					$getCategory_content=str_replace("getCategoryCategory","getCategory".ucfirst($value[0]),$getCategory_content);
					$getCategory_content=str_replace("Slide",$this->get_yii2_class_name($table_name),$getCategory_content);
					$getCategoryMore.=$getCategory_content;
					$behaviors.=",'category-more-".str_replace("_","-",$value[0])."'";
					$behaviors.=",'category-more-".str_replace("_","-",$value[0])."-click'";
				}
			}
		}
		$this->load->helper('file');
		if($create_view=="create_view")
		$file=FCPATH."application/models2/yii2/controllers/Slide_create_viewController.php";
		else
		$file=FCPATH."application/models2/yii2/controllers/SlideController.php";
		$content=file_get_contents($file);
		$class_name=$this->get_yii2_class_name($table_name);
		$content=str_replace("SlideController",ucfirst($table_name)."Controller",$content);
		$content=str_replace("Slide",$class_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<is_show>",$is_show,$content);
		$content=str_replace("<sort_order>",$sort_order,$content);
		$content=str_replace("<table_name>",$table_name,$content);
		$content=str_replace("<controller_get_cate>",$controller_get_cate,$content);
		$content=str_replace("<getCategoryMore>",$getCategoryMore,$content);
		$content=str_replace("<search_cate>",$search_cate,$content);
		$content=str_replace("<search_cate_js2>",$search_cate_js2,$content);
		$content=str_replace("<search_cate_js>",$search_cate_js,$content);
		$content=str_replace("<behaviors>",$behaviors,$content);
		$file_name=ucfirst($table_name)."Controller";
		$file2=FCPATH."yii2/backend/controllers/".$file_name.".php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		//front
		if($create_view!="create_view")
		{
			$file=FCPATH."application/models2/yii2/controllers/FrontController.php";
			$content=file_get_contents($file);
			$content=str_replace("FrontController",ucfirst($table_name)."Controller",$content);
			$content=str_replace("<table_name>",$table_name,$content);
			$content=str_replace("<Front>",$class_name,$content);
			$file_name=ucfirst($table_name)."Controller";
			$file2=FCPATH."yii2/frontend/controllers/".$file_name.".php";
			$content=$this->replace_blank_row($content);
			$s=write_file($file2, $content,"w");
		}
	}
	function write_file_yii2_view_front($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$this->load->helper('file');
		$file2=FCPATH."yii2/frontend/views/".$table_name."/";
		@create_dir($file2);
		/****view_list******/
		$file=FCPATH."application/models2/yii2/views/front/list.php";
		$content=file_get_contents($file);
		$content=str_replace("<front>",$table_name,$content);
		$content=str_replace("<field_title>",$this->get_table_field($input_data,"char"),$content);
		$content=str_replace("<field_id>",$this->get_table_field($input_data,"int"),$content);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."list.php", $content,"w");
		/****view_view******/
		$file=FCPATH."application/models2/yii2/views/front/view.php";
		$content=file_get_contents($file);
		$content=str_replace("<front>",$table_name,$content);
		$content=str_replace("<field_title>",$this->get_table_field($input_data,"char"),$content);
		$content=str_replace("<field_content>",$this->get_table_field($input_data,"text"),$content);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."view.php", $content,"w");
		/****view_form******/
		$str="";
		foreach($input_data as $key=>$value)
		{
			if($value[15]=="1" && $value[0]!="id" && ($value[9]=="" || $value[9]=="editor"))
			{
				if($value[9]=="editor")
				$str.='                <?= $form->field($model, \''.$value[0].'\')->textArea([\'rows\' => 6]) ?>';
				else
				$str.='                <?= $form->field($model, \''.$value[0].'\') ?>';
			}
		}
		$file=FCPATH."application/models2/yii2/views/front/form.php";
		$content=file_get_contents($file);
		$content=str_replace("<input_data>",$str,$content);
		$content=str_replace("<field_title>",$this->get_table_field($input_data,"char"),$content);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."form.php", $content,"w");
	}
	function write_file_yii2_models($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$table_col="";
		$str_view_list="";
		$is_show="";
		$sort_order="";
		$search_cate="";
		$search_cate_column_view="";
		$before_sort_order="";
		$before_is_show="";
		$before_postdate="";
		$before_click="";
		$sort_order="";
		$rules_required="";
		$getCategory="";
		$kind=1;
		$search_like="";
		$search_like_i=1;
		$module_get_cate="";
		$module_search_cate="";
		$module_search_cate2="";
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$table_col.="            '".$value[0]."' => '".$value[10]."',\r\n";
			if($value[15]=="1" && $value[14]!="1")//列显示
			{
				$rules_required.=(strpos($input_rule,"required")!==false) ? "'".$value[0]."'," : "";
				if((strpos($value[1],"char")!==false || strpos($value[1],"text")!==false) && strpos($value[9],"cate")===false && strpos($value[9],"pic")===false)
				{
					//$search_like.='`'.$value[0].'` like \'%".$keyword."%\' or ';
					if($search_like_i==1)
					$search_like.="			\$data->andFilterWhere(['like', '".$value[0]."', \$keyword])\r\n";
					else
					$search_like.="			->orFilterWhere(['like', '".$value[0]."', \$keyword])\r\n";
					$search_like_i++;
				}
				if($value[9]=="pic")
				{
				}
				elseif($value[9]=="pic_multiple")
				{
				}
				elseif($value[9]=="click")
				{
					$before_click.="            \$this->".$value[0]." = 0;\r\n";
				}
				elseif($value[9]=="postdate")
				{
					$before_postdate.="            \$this->".$value[0]." = time();\r\n";
				}
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$module_search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$module_search_cate.="\r\n";
					$module_search_cate2.='		if($'.$value[0].')
		$data->andwhere([\''.$value[0].'\'=>$'.$value[0].']);';
					$module_search_cate2.="\r\n";
					$search_cate_column_view.=' || @$search_'.$value[0];
					$getCategory_content=file_get_contents(FCPATH."application/models2/yii2/getCategory.php");
					$getCategory_content=str_replace("getCategory","getCategory".ucfirst($value[0]),$getCategory_content);
					$getCategory_content=str_replace("CategoryTable",$this->get_yii2_class_name($cate_table),$getCategory_content);
					$getCategory.=$getCategory_content;
					$module_get_cate.='	/**
     * @inheritdoc
     */
	public static function getCategoryList'.ucfirst($value[0]).'()
	{
		$data = '.$this->get_yii2_class_name($cate_table).'::find()->where([\'is_show\'=>1])->all();
		return $data;
	}';
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$module_search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$module_search_cate.="\r\n";
					$module_search_cate2.='		if($'.$value[0].')
		$data->andFilterWhere([\'like\', \''.$value[0].'\', ",".$'.$value[0].'.","]);';
					$module_search_cate2.="\r\n";
					$str_view_list.="            <td><?php echo ajax_show_tag('".$cate_table."',\$row->".$value[0].");?></td>\r\n";
					$search_cate_column_view.=' || @$search_'.$value[0];
					$getCategory_content=file_get_contents(FCPATH."application/models2/yii2/getCategory.php");
					$getCategory_content=str_replace("getCategory","getCategory".ucfirst($value[0]),$getCategory_content);
					$getCategory_content=str_replace("CategoryTable",$this->get_yii2_class_name($cate_table),$getCategory_content);
					$getCategory.=$getCategory_content;
					$module_get_cate.='	/**
     * @inheritdoc
     */
	public static function getCategoryList'.ucfirst($value[0]).'()
	{
		$data = '.$this->get_yii2_class_name($cate_table).'::find()->where([\'is_show\'=>1])->all();
		return $data;
	}';
				}
				elseif($value[9]=="is_show")
				{
					$before_is_show.="            \$this->".$value[0]." = 1;\r\n";
					$module_search_cate.='		$'.$value[0].' =trim(Yii::$app->request->get("'.$value[0].'"));';
					$module_search_cate2.='		if($'.$value[0].')
		$data->andwhere([\''.$value[0].'\'=>$'.$value[0].']);';
				}
				elseif($value[9]=="sort_order")
				{
					$before_sort_order.="            \$this->".$value[0]." = 0;\r\n";
					$sort_order=$value[0]." desc,";
				}
				elseif($value[9]=="value_multiple")
				{
					$str_view_list.="            <td></td>\r\n";
				}
				else
				$str_view_list.="            <td><?= Html::encode(\$val->".$value[0].");?></td>\r\n";
			}
		}
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file=FCPATH."application/models2/yii2/models/Slide.php";
		else
		$file=FCPATH."application/models2/yii2/models/Slide.php";
		$class_name=$this->get_yii2_class_name($table_name);
		$content=file_get_contents($file);
		$rules_required=(strlen($rules_required)>0) ? "			[[".substr($rules_required,0,strlen($rules_required)-1)."], 'required']," : "";
		$content=str_replace("<table_name>",$table_name,$content);
		$content=str_replace("Slide",$class_name,$content);
		$content=str_replace("<table_col>",$table_col,$content);
		$content=str_replace("<rules_required>",$rules_required,$content);
		$content=str_replace("<rules>",$this->get_yii2_rule($table_name),$content);
		$content=str_replace("<before_sort_order>",$before_sort_order,$content);
		$content=str_replace("<before_is_show>",$before_is_show,$content);
		$content=str_replace("<before_postdate>",$before_postdate,$content);
		$content=str_replace("<before_click>",$before_click,$content);
		$content=str_replace("<getCategory>",$getCategory,$content);
		$content=str_replace("<module_get_cate>",$module_get_cate,$content);
		$file2=FCPATH."yii2/backend/models/";
		@create_dir($file2);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."/".$class_name.".php", $content,"w");
		//add front module
		$s=write_file(FCPATH."yii2/frontend/models/".$class_name.".php", $content,"w");
		//replace ModelSearch
		$file=FCPATH."application/models2/yii2/models/SlideSearch.php";
		$content=file_get_contents($file);
		$content=str_replace("Slide",$class_name,$content);
		$content=str_replace("<sort_order>",$sort_order,$content);
		$search_like=(!empty($search_like)) ? substr($search_like,0,strlen($search_like)-2).";" : "";
		$content=str_replace("<search_like>",$search_like,$content);
		$content=str_replace("<module_search_cate2>",$module_search_cate2,$content);
		$content=str_replace("<module_search_cate>",$module_search_cate,$content);
		$content=$this->replace_blank_row($content);
		if($create_view!="create_view")
		{
			$s=write_file(FCPATH."yii2/backend/models/".$class_name."Search.php", $content,"w");
			//add front module
			$s=write_file(FCPATH."yii2/frontend/models/".$class_name."Search.php", $content,"w");
		}
	}
	function write_file_yii2_view($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$table_col="";
		$str_view_list="";
		$is_show="";
		$sort_order="";
		$search_cate="";
		$search_cate_column_view="";
		$search_is_show="";//审核显示
		$search_cate_js="";
		$search_cate_js2="";
		$search_cate_search_keywrod="";
		$kind=1;
		foreach($input_data as $key=>$value)
		{
			if($value[15]=="1" && $value[14]!="1")//列显示
			{
				$table_col.="            <th>".$value[10]."</th>\r\n";
				if($value[9]=="pic")
				{
					$str_view_list.="<?php \$pic=@\$val->".$value[0]."; \$pic=(substr(@\$pic,0,4)=='http' || substr(@\$pic,0,1)=='/') ? @\$pic : ((@\$pic) ? Yii::getAlias(\"@base_url\").\"/\".@\$pic : Yii::getAlias(\"@web\").\"/\".'resource/images/white.gif');?>"
					. "<td><?=Html::img(\$pic,['border'=>0,'style'=>'padding:1px;height:23px;border:solid 1px #ddd;']);?></td>\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$str_view_list.="<?php \$pic=@\$val->".$value[0]."; \$pic=(substr(@\$pic,0,4)=='http' || substr(@\$pic,0,1)=='/') ? @\$pic : ((@\$pic) ? Yii::getAlias(\"@base_url\").\"/\".@\$pic : '@web/'.'resource/images/none_small.png');?>"
					. "<td><?=Html::img(\$pic,['border'=>0,'style'=>'padding:1px;height:23px']);?></td>\r\n";
				}
				elseif($value[9]=="postdate")
				$str_view_list.="            <td><?= Html::encode(date(\"Y-m-d H:i:s\",\$val->".$value[0]."));?></td>\r\n";
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?= \$val->getCategory".ucfirst($value[0])."(\$val->".$value[0].");?></td>\r\n";
					$search_cate.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span><select name="'.$value[0].'" id="'.$value[0].'" style="width:120px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>"  <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate.="\r\n";
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js.="\r\n";
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?= \$val->getCategory".ucfirst($value[0])."(\$val->".$value[0].");?></td>\r\n";
					$search_cate.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span><select name="'.$value[0].'" id="'.$value[0].'" style="width:120px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>" <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate.="\r\n";
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js.="\r\n";
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="is_show")
				{
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_is_show.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span> <select name="'.$value[0].'" id="'.$value[0].'" style="width:70px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php 
$category_is_show=array("1"=>"显示","2"=>"隐藏");
foreach ($category_is_show  as $key=>$value){
$select=($key==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $key;?>" <?php echo $select;?>><?php echo $value;?></option>
<?php }?>
</select>';
					$str_view_list.="            <td><?php echo (\$val->is_show==1) ? '<span class=\"label label-satgreen is_show\" style=\"cursor:pointer\">显示</span>' : '<span class=\"label is_show\" style=\"cursor:pointer\">隐藏</span>';?></td>\r\n";
					$is_show='<script type="text/javascript">
$(".is_show").each(function ()
{
	$(this).live("click",function ()
	{
		sort_id=$(this).parent().parent().find("td").eq(1).html();
		this_url="<?php echo Url::toRoute(["'.$table_name.'/ajax-save-is-show"]);?>";
                var a=$(this);
                $.post(this_url,{"id":sort_id,"_csrf": "<?php echo $CsrfToken;?>"},function (result)
                {
                    if(result==1)
                    {

                            $(a).attr("class","label label-satgreen is_show");
                            $(a).html("显示");
                    }
                    else
                    {
                            $(a).attr("class","label is_show");
                            $(a).html("隐藏");
                    }
                })
})
})

</script>';
				}
				elseif($value[9]=="sort_order")
				{
					$str_view_list.="            <td><input name=\"sort_order\" type=\"text\" value=\"<?= Html::encode(\$val->".$value[0].");?>\" style=\"width:50px;\" class=\"sort_order\"></td>\r\n";
					$sort_order='<script type="text/javascript">
$(".sort_order").focusin(function() {
		$(this).attr("v", $(this).val());
	}).focusout(function() {
		var orderby = $(this).val();
		var old_orderby = $(this).attr("v");
		if(orderby == old_orderby) {return;}
		sort_id=$(this).parent().parent().find("td").eq(1).html();
		this_url="<?php echo Url::toRoute(["'.$table_name.'/ajax-save-sort-order"]);?>";
		$.post(this_url,{id:sort_id, orderby:orderby,"_csrf": "<?php echo $CsrfToken;?>"}, function(data){
			//if(data.err==0) //get_data();
		});
	});
</script>';
				}
				elseif($value[9]=="value_multiple")
				{
					$str_view_list.="            <td></td>\r\n";
				}
				else
				$str_view_list.="            <td><?= Html::encode(\$val->".$value[0].");?></td>\r\n";
			}
		}
		$this->load->helper('file');
		/****view_add******/
		$search_cate_search_keywrod=($search_cate_js!="") ? "请输入搜索条件" : "请输入关键词";
		if($create_view=="create_view")
		$file=FCPATH."application/models2/yii2/views/slide/index.php";
		else
		$file=FCPATH."application/models2/yii2/views/slide/index.php";
		$content=file_get_contents($file);
		$content=str_replace("<str_view_list>",$str_view_list,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<table_col>",$table_col,$content);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<table_name_zh_add>",str_replace("管理","",$table_name_zh),$content);
		$content=str_replace("<is_show>",$is_show,$content);
		$content=str_replace("<search_is_show>",$search_is_show,$content);
		$content=str_replace("<sort_order>",$sort_order,$content);
		$content=str_replace("<search_cate>",$search_cate,$content);
		$content=str_replace("<search_cate_column_view>",$search_cate_column_view,$content);
		$content=str_replace("<search_cate_js2>",$search_cate_js2,$content);
		$content=str_replace("<search_cate_js>",$search_cate_js,$content);
		$content=str_replace("<search_cate_search_keywrod>",$search_cate_search_keywrod,$content);
		//$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
		$file2=FCPATH."yii2/backend/views/".$table_name."/";
		@create_dir($file2);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."index.php", $content,"w");
	}
	function write_file_laravel_view_update($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view='')
	{
		$str="";
		$str_last_edit="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$model_column_first="";
		$i=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		$class_name=$table_name;
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$value[0].'" type="text" value="<?php echo $model->'.$value[0].';?>" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传图片</a></span>
          <?php $'.$value[0].'=(substr($model->'.$value[0].',0,4)=="http" || substr($model->'.$value[0].',0,1)=="/") ? $model->'.$value[0].' : (($model->'.$value[0].') ? Yii::getAlias("@base_url")."/".$model->'.$value[0].' : "");?>
          <div><img src="<?php echo $'.$value[0].';?>" style="max-width:360px;margin:10px 0 2px 0;"></div>'.$require.'<span class="help-inline">'.$value[12].'</span> </div>
      </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$value[0].'" name="'.$value[0].'" placeholder="'.str_replace("请输入","",$value[12]).'" value="<?php echo $model->'.$value[0].';?>" '.$input_rule.'>
          <span class="input-group-btn">
<button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button>
</span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate" || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$style=($value[13]=="input-xxlarge") ? 'style="width:545px"' : "";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$value[0].'" id="'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$style.' style="width:544px">
		<option value="">请选择</option>
		 <?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$model->'.$value[0].') ? \'selected="selected"\' : "";?>
<option value="<?php echo $row->id;?>" <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?>
        </select> &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="hide_'.$value[0].'" id="hide_'.$value[0].'" type="text"  class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" value="<?= '.$this->get_yii2_class_name($table_name).'::getCategory'.$value[0].'($model->'.$value[0].');?>" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <input name="'.$value[0].'" type="hidden" id="'.$value[0].'" value="<?php echo $model->'.$value[0].';?>"/>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last_edit.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'\']);?>\',\''.$cate_table.'\');
            });	
            $("#'.$cate_table.'_content .cate_ajax").live("click",function ()
			{
				column="'.$value[0].'";
				tag_str=$("#"+column).val();
				id=$(this).attr("attr_id");
				if(($(this).hasClass("cate_correct")))
				{		
					tag_str=tag_str.replace(id+",","");
					$(this).removeClass("cate_correct");
				}
				else
				{
					$(this).addClass("cate_correct");	
					if(tag_str=="")
					tag_str=",";
					tag_str=tag_str+id+",";
				}
				$("#"+column).val(tag_str);
				csrf=$(".csrf").attr("_csrf");
				url="<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'-click\']);?>";
				$.post(url,{"_csrf": csrf,"tag_str":tag_str},function(data){	
					$("#hide_'.$value[0].'").val(data);						 
				})
			 })
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" '.$input_rule.'><?php echo $model->'.$value[0].';?></textarea>
          '.$require.'<span class="help-inline">请输入'.$value[12].'</span> </div>
      </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$table_name.'-'.$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>名称</th>
          <th>内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
      
<?php 
$i=1;
if($model->'.$value[0].')
{
$'.$value[0].'=json_decode($model->'.$value[0].');
$'.$value[0].'_txt=$'.$value[0].'->txt;
$'.$value[0].'_value=$'.$value[0].'->value;
foreach(@$'.$value[0].'_txt as $key=>$edu_txt)
{
if($edu_txt)
{
?>   
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'"><?php echo $i;?></span>：</td>
          <td><input type="'.$value[0].'_txt" name="'.$value[0].'_txt[]" id="txt1" value="<?php echo $edu_txt;?>"  class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" value="<?php echo @$'.$value[0].'_value[$key];?>"></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php $i++;}}}?> 
<?php if($i==1){?>
<tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1"   class="input-medium" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge" ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
<?php }?>       
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}

				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <?php
           $check1=($model->'.$value[0].'==1) ? "checked=\"checked\"" : "";
           $check2=($model->'.$value[0].'==2) ? "checked=\"checked\"" : "";
          ?>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="1" <?php echo $check1;?> style="margin-top:0"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="2" <?php echo $check2;?> style="margin-top:0"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.='<div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="102265">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
              <?php
		  if(@$pic_list){
		  foreach($pic_list as $p_row)
		  {
			  $pic=(substr($p_row->pic,0,4)=="http" || substr($p_row->pic,0,1)=="/") ? $p_row->pic : (($p_row->pic) ? $p_row->pic : $base_url."resource/images/avatar.gif");?>
              <li class="imgbox" data-post-id="<?php echo $p_row->id;?>" data-url="<?php echo $p_row->pic;?>"> <a class="item_close" href="javascript:void(0)" title="删除"></a>
                <input type="hidden" value="<?php echo $p_row->id;?>" name="phout_list[]">
                <input type="hidden" value="<?php echo $pic;?>" name="phout_url[<?php echo $p_row->id;?>][]">
                <span class="item_box"><img src="<?php echo $pic;?>"></span> <span class="item_input">
                <textarea name="imagestexts[<?php echo $p_row->id;?>][]" class="bewrite" cols="3" rows="4" style="resize: none" data-rule-maxlength="150"><?php echo $p_row->description;?></textarea>
                <i class="shadow hc"></i> </span> </li>
              <?php }}?>
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js=' <script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				
				\'utype\':"102"
			},
			url: "<?php echo $admin_url;?>'.$table_name.'/upimg",
			del_url: "<?php echo $admin_url;?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		G.logic.uploadify.init(op);	
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$str.='<div class="control-group">
                                                <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
                                                <div class="controls">
                                                     <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" style="height:80px;"><?php echo $model->'.$value[0].';?></textarea>'.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
                                            </div>';
				}
				else
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" name="'.$value[0].'" id="'.$value[0].'" value="{{$info->'.$value[0].'}}" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
			}
			$model_column_first=($i==1) ? $value[0] : $model_column_first;
			$i++;
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';

		}

		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}

		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file=FCPATH."application/models2/laravel/views/slide/edit.blade.php";
		else
		$file=FCPATH."application/models2/laravel/views/slide/edit.blade.php";
		$content=file_get_contents($file);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$content=str_replace("<model_column_first>",$model_column_first,$content);
		$content=str_replace("<table_name_zh_create_view>",$table_name_zh,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_edit>",$str,$content);
		$content=str_replace("<str_last_edit>",$str_last_edit,$content);
		$content=str_replace("<Slide>",$class_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=$this->replace_blank_row($content);

		$file2=FCPATH.$this->dir_laravel."resources/views/admin/".$table_name;
		if($create_view=="create_view")
		@create_dir($file2);
		if($create_view=="create_view")
		$file2=FCPATH.$this->dir_laravel."resources/views/admin/".$table_name;
		else
		$file2=FCPATH.$this->dir_laravel."resources/views/admin/".$table_name;
		$s=write_file($file2."/edit.blade.php", $content,"w");
	}
	function write_file_laravel_view_create($table_name,$array_url,$input_data,$array_type_new,$table_name_zh)
	{
		$str="";
		$str_last="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		$class_name=$table_name;
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$value[0].'" type="text" value="" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></a></span>
          <div><?= Html::img(\'@web/resource/images/white.gif\',[\'style\'=>\'max-width:360px;margin:10px 0 2px 0;\']);?></div>'.$require.'<span class="help-inline">请上传<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> </div>
       </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$value[0].'" name="'.$value[0].'" placeholder="'.str_replace("请输入","",$value[12]).'" value="" '.$input_rule.'>
          <span class="input-group-btn">
		  <button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button></span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate"  || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$style=($value[13]=="input-xxlarge") ? 'style="width:545px"' : "";
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$value[0].'" id="'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$style.' style="width:544px">
		<option value="">请选择</option>
		<?php foreach ($category_list_'.$value[0].'  as $row){?>
<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
<?php }?>
        </select> &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="hide_'.$value[0].'" id="hide_'.$value[0].'" type="text" class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <input name="'.$value[0].'" type="hidden" id="'.$value[0].'" />
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'\']);?>\',\''.$cate_table.'\');
            });	
            $("#'.$cate_table.'_content .cate_ajax").live("click",function ()
	{
		column="'.$value[0].'";
		tag_str=$("#"+column).val();
		id=$(this).attr("attr_id");
		if(($(this).hasClass("cate_correct")))
		{		
			tag_str=tag_str.replace(id+",","");
			$(this).removeClass("cate_correct");
		}
		else
		{
			$(this).addClass("cate_correct");	
			if(tag_str=="")
			tag_str=",";
			tag_str=tag_str+id+",";
		}
		$("#"+column).val(tag_str);
		csrf=$(".csrf").attr("_csrf");
		url="<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'-click\']);?>";
		$.post(url,{"_csrf": csrf,"tag_str":tag_str},function(data){	
			$("#hide_'.$value[0].'").val(data);						 
		})
	 })
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" '.$input_rule.'></textarea>
          '.$require.'<span class="help-inline">请输入<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> </div>
       </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$table_name.'-'.$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.="\r\n";
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>字段名称</th>
          <th>初始内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1" value=""  class="input-xlarge" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge"  ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}
				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="1" checked="checked" style="margin-top:0px"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="2" style="margin-top:0px"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.="\r\n";
					$str.='     <div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="0">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js='<script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				\'aid\':"",
				\'utype\':"",
				\'uid\':"",
				\'uu\':""
			},
			url: "<?php echo $admin_url?>'.$table_name.'/upimg/",
			del_url: "<?php echo $admin_url?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		
		G.logic.uploadify.init(op);
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" style="height:80px"></textarea>
          '.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				else
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$value_default=($value[9]=="click") ? 0 : $value_default;
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" name="'.$value[0].'" id="'.$value[0].'" value="'.$value_default.'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
       </div>';
				}
			}
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';

		}
		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}
		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		$file=FCPATH."application/models2/laravel/views/slide/create.blade.php";
		$content=file_get_contents($file);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_add>",$str,$content);
		$content=str_replace("<Slide>",$class_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<str_last>",$str_last,$content);
		
		$file2=FCPATH.$this->dir_laravel."resources/views/admin/".$table_name;
		$content=$this->replace_blank_row($content);
		@create_dir($file2);
		$s=write_file($file2."/create.blade.php", $content,"w");
	}
	function write_file_laravel_view_index($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$table_col="";
		$str_view_list="";
		$is_show="";
		$sort_order="";
		$search_cate="";
		$search_cate_column_view="";
		$search_is_show="";//审核显示
		$search_cate_js="";
		$search_cate_js2="";
		$search_cate_search_keywrod="";
		$kind=1;
		foreach($input_data as $key=>$value)
		{
			if($value[15]=="1" && $value[14]!="1")//列显示
			{
				$table_col.="            <th>".$value[10]."</th>\r\n";
				if($value[9]=="pic")
				{
					$str_view_list.="            <td><img src=\"<?php echo (substr(@\$row->".$value[0].",0,4)=='http' || substr(@\$row->".$value[0].",0,1)=='/') ? @\$row->".$value[0]." : ((@\$row->".$value[0].") ? \$base_url.@\$row->".$value[0]." : \$base_url.\"resource/images/none_small.png\");?>\" border=\"0\" style=\"padding:1px;height:23px\"/></td>\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$str_view_list.='            <td><img src="<?php echo (substr(@$row->'.$value[0].',0,4)=="http" || substr(@$row->'.$value[0].',0,1)=="/") ? @$row->'.$value[0].' : $base_url.@$row->'.$value[0].';?>" border="0" style="padding:1px;height:23px"/></td>\r\n';
				}
				elseif($value[9]=="postdate")
				$str_view_list.="            <td>{{\$item->created_at}}</td>\r\n";
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?php \$cate_table=get_table_row('".$cate_table."',\$row->".$value[0].");  echo @\$cate_table->name;?></td>\r\n";
					$search_cate_column_view.=' || @$search_'.$value[0];
					$search_cate.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span><select name="'.$value[0].'" id="'.$value[0].'" style="width:120px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>"  <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate.="\r\n";
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js.="\r\n";
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?php echo ajax_show_tag('".$cate_table."',\$row->".$value[0].");?></td>\r\n";
					$search_cate.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span><select name="'.$value[0].'" id="'.$value[0].'" style="width:120px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($category_list_'.$value[0].'  as $row){
$select=($row->id==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>" <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate.="\r\n";
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js.="\r\n";
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="is_show")
				{
					$str_view_list.="            <td>
            @if(\$item->".$value[0]."==1)
            <span class='label label-satgreen is_show' style='cursor:pointer'>显示</span>
            @else
            <span class=\"label is_show\" style=\"cursor:pointer\">隐藏</span>
            @endif
          </td>\r\n";
					$is_show='<script type="text/javascript">
$(".is_show").each(function ()
{
	$(this).live("click",function ()
	{
		sort_id=$(this).parent().parent().find("td").eq(1).html();
		var a=$(this);
		$.ajax({  
			type: "PUT",  
			dataType:"json",
			url: "{{url(\'/admin/'.$table_name.'/\')}}/"+sort_id,  
			data: {action:"is_show"},
			success: function(result){  
				if(result.is_show==1)
				{
					$(a).attr("class","label label-satgreen is_show");
					$(a).html("显示");
				}
				else
				{
					$(a).attr("class","label is_show");
					$(a).html("隐藏");
				}
			},  
			error: function(result){   
			}  
		});
	})
})
</script>
';
					$search_cate_js.='		'.$value[0].'=$("#'.$value[0].'").val();';
					$search_cate_js2.=' && '.$value[0].'==""';
					$search_is_show.='<span  style="color:#808080;">&nbsp; '.$value[10].':</span> <select name="'.$value[0].'" id="'.$value[0].'" style="width:70px;height:28px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php 
$category_is_show=array("1"=>"显示","2"=>"隐藏");
foreach ($category_is_show  as $key=>$value){
$select=($key==@$search_value[\''.$value[0].'\']) ? \'selected="selected"\' : "";?>
 <option value="<?php echo $key;?>" <?php echo $select;?>><?php echo $value;?></option>
<?php }?>
</select>';
				}
				elseif($value[9]=="sort_order")
				{
					$str_view_list.="            <td><input name=\"".$value[0]."\" type=\"text\" value=\"{{ \$item->".$value[0]." }}\" style=\"width:50px;\" class=\"sort_order\"></td>\r\n";
					$sort_order='<script type="text/javascript">
$(".sort_order").focusin(function() {
		$(this).attr("v", $(this).val());
	}).focusout(function() {
		var orderby = $(this).val();
		var old_orderby = $(this).attr("v");
		if(orderby == old_orderby) {return;}
		sort_id=$(this).parent().parent().find("td").eq(1).html();		
		$.ajax({  
			type: "PUT",  
			dataType:"json",
			url: "{{url(\'/admin/'.$table_name.'/\')}}/"+sort_id,  
			data: {action:"sort_order",orderby:orderby},
			success: function(result){  	
			},  
			error: function(result){   
			}  
		});	
	});
</script>';
				}
				elseif($value[9]=="value_multiple")
				{
					$str_view_list.="            <td></td>\r\n";
				}
				else
				$str_view_list.="            <td>{{ \$item->".$value[0]." }}</td>\r\n";
			}
		}
		$search_cate_search_keywrod=($search_cate_js!="") ? "请输入搜索条件" : "请输入关键词";
		if($create_view=="create_view")
		$file=FCPATH."application/models2/laravel/views/slide/index.blade.php";
		else
		$file=FCPATH."application/models2/laravel/views/slide/index.blade.php";
		$content=file_get_contents($file);
		$content=str_replace("<str_view_list>",$str_view_list,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<table_col>",$table_col,$content);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<table_name_zh_add>",str_replace("管理","",$table_name_zh),$content);
		$content=str_replace("<is_show>",$is_show,$content);
		$content=str_replace("<sort_order>",$sort_order,$content);
		$content=str_replace("<search_is_show>",$search_is_show,$content);
		$content=str_replace("<search_cate>",$search_cate,$content);
		$content=str_replace("<search_cate_column_view>",$search_cate_column_view,$content);
		$content=str_replace("<search_cate_js2>",$search_cate_js2,$content);
		$content=str_replace("<search_cate_js>",$search_cate_js,$content);
		$content=str_replace("<search_cate_search_keywrod>",$search_cate_search_keywrod,$content);
		$file2=FCPATH.$this->dir_laravel."resources/views/admin/".$table_name;
		$content=$this->replace_blank_row($content);
		@create_dir($file2);
		$s=write_file($file2."/index.blade.php", $content,"w");
	}
	function write_file_laravel_controller($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="",$laravel_timestamp=1)
	{
		$model_add_array="\$data = array(\r\n";
		$model_add_pic_multiple="";
		$model_add_session="";
		$model_add_session_pre="";
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			if($value[7]!="auto_increment" && $value[9]!="click")
			{
				if($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$model_add_array.="		".$value2."=>trim(\$this->input->post('hide_".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$model_add_session.="\$session_info=array(\"parent_".$value[0]."\"=>\$this->input->post('".$value[0]."'));
		\$this->session->set_userdata(\$session_info);\r\n";
					$model_add_session_pre.="\$parent_".$value[0]."=\$this->session->userdata(\"parent_".$value[0]."\");
			\$parent_".$value[0]."=(\$parent_".$value[0].") ? \$parent_".$value[0]." : 0;
			return array(\"parent_".$value[0]."\"=>\$parent_".$value[0].");\r\n";
					$model_add_array.="		".$value2."=>trim(\$this->input->post('".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="postdate")
				{
					$model_add_array.="		".$value2."=>time()".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="ip_address")
				{
					$model_add_array.="		".$value2."=>\$this->input->ip_address()".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="value_multiple")
				{
					$model_add_array.="		".$value2."=>json_encode(array(\"txt\"=>\$this->input->post(\"".$value[0]."_txt\"),\"value\"=>\$this->input->post(\"".$value[0]."_value\")))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$pic_multiple_table=$this->get_pic_multiple_table($table_name);
					@$this->db->query("TRUNCATE TABLE `site_".$pic_multiple_table."`");
					$model_add_pic_multiple.='/**多图**/
		$pid=$this->db->insert_id();
		$phout_list=$this->input->post("phout_list");
		$phout_url=$this->input->post("phout_url");
		$imagestexts=$this->input->post("imagestexts");
		$j=10000;
		if($phout_list)
		{
			foreach($phout_list as $images_key=>$images_v)
			{
				if($images_v)
				{
					$data=array("pid"=>$pid,"sort_order"=>$j,"status"=>1,"description"=>@$imagestexts[$images_v][0]);
					$update_str=$this->db->update_string($this->db->dbprefix."'.$pic_multiple_table.'", $data,"id=?");
					$this->db->query($update_str,array($images_v));
					$j--;
				}
			}
			$pic=current($phout_url);
			$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("'.$value[0].'"=>@$pic[0]),"id=?");
			$this->db->query($update_str,array($pid));
		}';
				}
				else
				{
					//$model_add_array.="\"".$value[0]."\"=>\$".$value[0].",";
					$model_add_array.="		\$data->".$value[0]." = \$request->input('".$value[0]."');\r\n";
				}
			}
			$i++;
		}
		$model_add_array.="		);";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/laravel/controllers/SlideController.php";
		$content=file_get_contents($file_add);
		$content=str_replace("Slide",$table_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<model_add_array>",$model_add_array,$content);
		$content=str_replace("<model_add_pic_multiple>",$model_add_pic_multiple,$content);
		$content=str_replace("<model_add_session>",$model_add_session,$content);
		$model_add_session_pre=($model_add_session_pre) ? "else\r\n		{\r\n			".$model_add_session_pre."\r\n		}" : "";
		$content=str_replace("<model_add_session_pre>",$model_add_session_pre,$content);
		$file_name=ucfirst($table_name)."Controller";
		$file2=FCPATH.$this->dir_laravel."app/Http/Controllers/Admin/".$file_name.".php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		//model
		$file=FCPATH."application/models2/laravel/models/Slide.php";
		$content=file_get_contents($file);
		$content=str_replace("Slide",ucfirst($table_name),$content);
		$timestamp=($laravel_timestamp==1) ? '' : "public \$timestamps = false;";
		$content=str_replace("<laravel_timestamps>",$timestamp,$content);
		$content=$this->replace_blank_row($content);
		$file_name=FCPATH.$this->dir_laravel."app/".ucfirst($table_name).".php";
		$s=write_file($file_name, $content,"w");
	}
	function write_file_view($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$table_col="";
		$str_view_list="";
		$is_show="";
		$sort_order="";
		$search_cate="";
		$search_cate_column_view="";
		$kind=1;
		foreach($input_data as $key=>$value)
		{
			if($value[15]=="1" && $value[14]!="1")//列显示
			{
				$table_col.="            <th>".$value[10]."</th>\r\n";
				if($value[9]=="pic")
				{
					$str_view_list.="            <td><img src=\"<?php echo (substr(@\$row->".$value[0].",0,4)=='http' || substr(@\$row->".$value[0].",0,1)=='/') ? @\$row->".$value[0]." : ((@\$row->".$value[0].") ? \$base_url.@\$row->".$value[0]." : \$base_url.\"resource/images/none_small.png\");?>\" border=\"0\" style=\"padding:1px;height:23px\"/></td>\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$str_view_list.='            <td><img src="<?php echo (substr(@$row->'.$value[0].',0,4)=="http" || substr(@$row->'.$value[0].',0,1)=="/") ? @$row->'.$value[0].' : $base_url.@$row->'.$value[0].';?>" border="0" style="padding:1px;height:23px"/></td>';
					$str_view_list.="\r\n";
				}
				elseif($value[9]=="postdate")
				$str_view_list.="            <td><?php echo get_time(\$row->".$value[0].",\"Y-m-d H:i:s\");?></td>\r\n";
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?php \$cate_table=get_table_row('".$cate_table."',\$row->".$value[0].");  echo @\$cate_table->name;?></td>\r\n";
					$search_cate.='<select name="'.$value[0].'" style="width:120px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($'.$cate_table.'_list  as $row){
$select=($row->id==$search_'.$value[0].') ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>"  <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$str_view_list.="            <td><?php echo ajax_show_tag('".$cate_table."',\$row->".$value[0].");?></td>\r\n";
					$search_cate.='<select name="'.$value[0].'" style="width:120px;" class="wizard-ignore ui-wizard-content ui-helper-reset ui-state-default">
<option value="">全部</option>
<?php foreach ($'.$cate_table.'_list  as $row){
$select=($row->id==$search_'.$value[0].') ? \'selected="selected"\' : "";?>
 <option value="<?php echo $row->id;?>" <?php echo $select;?>><?php echo $row->name;?></option>
<?php }?></select> ';
					$search_cate_column_view.=' || @$search_'.$value[0];
				}
				elseif($value[9]=="is_show")
				{
					$str_view_list.="            <td><?php echo (\$row->".$value[0]."==1) ? '<span class=\"label label-satgreen is_show\" style=\"cursor:pointer\">显示</span>' : '<span class=\"label is_show\" style=\"cursor:pointer\">隐藏</span>';?></td>\r\n";
					$is_show='<script type="text/javascript">
$(".is_show").each(function ()
{
	$(this).live("click",function ()
	{
		sort_id=$(this).parent().parent().find("td").eq(1).html();
		this_url="<?php echo $admin_url?>'.$table_name.'/ajax_save_is_show/"+sort_id+"/+?rand=<?php echo mt_rand(1,999);?>";
		var a=$(this);
		$.post(this_url,function (result)
		{
			if(result==1)
			{
	
				$(a).attr("class","label label-satgreen is_show");
				$(a).html("显示");
			}	
			else
			{
				$(a).attr("class","label is_show");
				$(a).html("隐藏");	
			}	
		})
	})
})

</script>';
				}
				elseif($value[9]=="more_value")
				{

					if($this->input->post("table_name"))
						$mod_row2=get_table_row("module",$this->input->post("table_name"),"name");
					else
						$mod_row2=get_table_row("module",$this->uri->segment(6),"id");

					$data_enum=$mod_row2->data_enum;
					$data_enum=json_decode($data_enum,true);
					$column=$value[0];
					$data_enum=$data_enum[$column];
					$data_enum=json_decode($data_enum);
					$data_enum_str='';
					$data_enum_str='<?php
		$enum_'.$value[0].'=array(';
					$data_enum_str.="\r\n";
					foreach($data_enum as $enum_key=>$enum_value)
					{
						$data_enum_str.="			'".$enum_key."'=>'".$enum_value."',\r\n";
					}
					$data_enum_str.='		);?>
					';
					$str_view_list.=$data_enum_str."            <td><?php echo @\$enum_".$value[0]."[\$row->".$value[0]."];?></td>\r\n";
				}
				elseif($value[9]=="sort_order")
				{
					$str_view_list.="            <td><input name=\"sort_order\" type=\"text\" value=\"<?php echo \$row->".$value[0].";?>\" style=\"width:50px;\" class=\"sort_order\"></td>\r\n";
					$sort_order='<script type="text/javascript">
$(".sort_order").focusin(function() {
		$(this).attr("v", $(this).val());
	}).focusout(function() {
		var orderby = $(this).val();
		var old_orderby = $(this).attr("v");
		if(orderby == old_orderby) {return;}
		sort_id=$(this).parent().parent().find("td").eq(1).html();
		this_url="<?php echo $admin_url?>'.$table_name.'/ajax_save_sort_order";
		$.post(this_url,{id:sort_id, orderby:orderby}, function(data){
			//if(data.err==0) //get_data();
		});
	});
</script>';
				}
				elseif($value[9]=="value_multiple")
				{
					$str_view_list.="            <td></td>\r\n";
				}
				else
				$str_view_list.="            <td><?php echo \$row->".$value[0].";?></td>\r\n";
			}
		}
		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing."_view.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing.".php";
		$content=file_get_contents($file_add);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<table_col>",$table_col,$content);
		$content=str_replace("<str_view_list>",$str_view_list,$content);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<table_name_zh_add>",str_replace("管理","",$table_name_zh),$content);
		$content=str_replace("<is_show>",$is_show,$content);
		$content=str_replace("<sort_order>",$sort_order,$content);
		$content=str_replace("<search_cate>",$search_cate,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		$content=str_replace("<search_cate_column_view>",$search_cate_column_view,$content);
		//$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
		$file2=FCPATH."application/views/admin_admin/".$table_name.".php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
	}
	function write_file_wap_controller_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$leixing="site_wap";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/help.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/help.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		$content=str_replace("help",$table_name,$content);
		$content_wap=file_get_contents(FCPATH."application/controllers/wap.php");
		if(preg_match("/function ".$table_name."()/is",$content_wap))
		{
			if(preg_match("/function ".$table_name.".*function/isU",$content))
			$content_wap=preg_replace("/function ".$table_name.".*function/isU",$content."function",$content_wap);
			else
			$content_wap=preg_replace("/function ".$table_name.".*\}[^\?]*\?>/isU",$content."\r\n}
?>",$content_wap);
		}
		else
		{
			$content_wap=str_replace("}
?>","	".$content."\r\n}
?>",$content_wap);
		}
		write_file(FCPATH."application/controllers/wap.php", $content_wap,"w");
	}
	function write_file_wap_front_view_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="",$template_val)
	{
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_wap/views/help_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_wap/views/help_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("help",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/wap/".$table_name.".php", $content,"w");
	}
	function write_file_wap_controller_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$leixing="site_wap";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/photo.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/photo.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_cate=$this->get_table_field($input_data,"cate");
		if($field_cate)
		{
			$table_name_cate=$table_name."_".$field_cate;
			$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
		}
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_cate>",$field_cate,$content);
		$content=str_replace("<table_name_cate>",$table_name_cate,$content);
		$content=str_replace("photo",$table_name,$content);
		$content_wap=file_get_contents(FCPATH."application/controllers/wap.php");
		if(preg_match("/function ".$table_name."()/is",$content_wap))
		{
			if(preg_match("/function ".$table_name.".*function/isU",$content))
			$content_wap=preg_replace("/function ".$table_name.".*function/isU",$content."function",$content_wap);
			else
			$content_wap=preg_replace("/function ".$table_name.".*\}[^\?]*\?>/isU",$content."\r\n}
?>",$content_wap);
		}
		else
		{
			$content_wap=str_replace("}
?>","	".$content."\r\n}
?>",$content_wap);
		}
		write_file(FCPATH."application/controllers/wap.php", $content_wap,"w");
	}
	function write_file_wap_front_view_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="",$template_val)
	{
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_wap/views/photo_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_wap/views/photo_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("photo",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_pic=$this->get_table_field($input_data,"pic");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/wap/".$table_name.".php", $content,"w");
		//show
		$file_add=FCPATH."application/models2/site_wap/views/photo_show_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("photo",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/wap/".$table_name."_show.php", $content,"w");
	}
	function write_file_wap_controller_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="")
	{
		$leixing="site_wap";
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/article.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/article.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_cate=$this->get_table_field($input_data,"cate");
		if($field_cate)
		{
			$table_name_cate=$table_name."_".$field_cate;
			$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
		}
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_cate>",$field_cate,$content);
		$content=str_replace("article",$table_name,$content);
		$content=str_replace("<table_name_cate>",$table_name_cate,$content);
		$content_wap=file_get_contents(FCPATH."application/controllers/wap.php");
		if(preg_match("/function ".$table_name."()/is",$content_wap))
		{
			if(preg_match("/function ".$table_name.".*function/isU",$content))
			$content_wap=preg_replace("/function ".$table_name.".*function/isU",$content."function",$content_wap);
			else
			$content_wap=preg_replace("/function ".$table_name.".*\}[^\?]*\?>/isU",$content."\r\n}
?>",$content_wap);
		}
		else
		{
			$content_wap=str_replace("}
?>","	".$content."\r\n}
?>",$content_wap);
		}
		write_file(FCPATH."application/controllers/wap.php", $content_wap,"w");
	}
	function write_file_wap_front_view_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view="",$template_val)
	{
		$this->load->helper('file');
		/****view_add******/
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_wap/views/article_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_wap/views/article_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("article",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_pic=$this->get_table_field($input_data,"pic");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/wap/".$table_name.".php", $content,"w");
		//show
		$file_add=FCPATH."application/models2/site_wap/views/article_show_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("article",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/wap/".$table_name."_show.php", $content,"w");
	}
	function write_file_yii2_view_create($table_name,$array_url,$input_data,$array_type_new,$table_name_zh)
	{
		$str="";
		$str_last="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		$class_name=$this->get_yii2_class_name($table_name);
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$class_name.'['.$value[0].']" type="text" value="" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></a></span>
          <div><?= Html::img(\'@web/resource/images/white.gif\',[\'style\'=>\'max-width:360px;margin:10px 0 2px 0;\']);?></div>'.$require.'<span class="help-inline">请上传<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> </div>
       </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$value[0].'" name="'.$class_name.'['.$value[0].']" placeholder="'.str_replace("请输入","",$value[12]).'" value="" '.$input_rule.'>
          <span class="input-group-btn">
		  <button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button></span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$table_name.'-'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate"  || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$style=($value[13]=="input-xxlarge") ? 'style="width:545px"' : "";
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$style.' style="width:544px">
		<option value="">请选择</option>
		<?php foreach ($category_list_'.$value[0].'  as $row){?>
<option value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
<?php }?>
        </select> &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="hide_'.$value[0].'" id="hide_'.$value[0].'" type="text" class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo Url::toRoute([\''.$cate_table.'/index\']);?>" target="_blank">管理分类</a>
          <input name="'.$class_name.'['.$value[0].']" type="hidden" id="'.$table_name.'-'.$value[0].'" />
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#'.$table_name.'-'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'\']);?>\',\''.$cate_table.'\');
            });	
            $("#'.$cate_table.'_content .cate_ajax").live("click",function ()
	{
		column="'.$table_name.'-'.$value[0].'";
		tag_str=$("#"+column).val();
		id=$(this).attr("attr_id");
		if(($(this).hasClass("cate_correct")))
		{		
			tag_str=tag_str.replace(id+",","");
			$(this).removeClass("cate_correct");
		}
		else
		{
			$(this).addClass("cate_correct");	
			if(tag_str=="")
			tag_str=",";
			tag_str=tag_str+id+",";
		}
		$("#"+column).val(tag_str);
		csrf=$(".csrf").attr("_csrf");
		url="<?php echo Url::toRoute([\''.$table_name.'/category-more-'.$value[0].'-click\']);?>";
		$.post(url,{"_csrf": csrf,"tag_str":tag_str},function(data){	
			$("#hide_'.$value[0].'").val(data);						 
		})
	 })
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" '.$input_rule.'></textarea>
          '.$require.'<span class="help-inline">请输入<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> </div>
       </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$table_name.'-'.$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.="\r\n";
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>字段名称</th>
          <th>初始内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1" value=""  class="input-xlarge" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge"  ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}
				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="1" checked="checked" style="margin-top:0px"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="2" style="margin-top:0px"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.="\r\n";
					$str.='     <div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="0">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js='<script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				\'aid\':"",
				\'utype\':"",
				\'uid\':"",
				\'uu\':""
			},
			url: "<?php echo $admin_url?>'.$table_name.'/upimg/",
			del_url: "<?php echo $admin_url?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		
		G.logic.uploadify.init(op);
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" style="height:80px"></textarea>
          '.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				else
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$value_default=($value[9]=="click") ? 0 : $value_default;
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label"><?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?>：</label>
        <div class="controls">
          <input type="text" name="'.$class_name.'['.$value[0].']" id="'.$table_name.'-'.$value[0].'" value="'.$value_default.'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">请输入<?php echo strip_tags(HTML::activeLabel($model,"'.$value[0].'")); ?></span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
       </div>';
				}
			}
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';

		}
		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<?=Html::cssFile(\'@web/resource/kindeditor-4.1.9/themes/default/default.css\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/kindeditor-min.js\')?>
<?=Html::jsFile(\'@web/resource/kindeditor-4.1.9/lang/zh_CN.js\')?>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}
		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		$file=FCPATH."application/models2/yii2/views/slide/create.php";
		$content=file_get_contents($file);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_add>",$str,$content);
		$content=str_replace("<Slide>",$class_name,$content);
		$content=str_replace("slide",$table_name,$content);
		$content=str_replace("<str_last>",$str_last,$content);
		$file2=FCPATH."yii2/backend/views/".$table_name."/create.php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
	}
	function write_file_view_add($table_name,$array_url,$input_data,$array_type_new,$table_name_zh)
	{
		$str="";
		$str_last="";
		$str_kindEditor="";
		$kindeditor_js="";
		$kindeditor_str="";
		$kind=1;
		$pic_multiple_include='';
		$kindeditor_file_upload_js='';
		$pic_multiple_js='';
		$value_multiple_js='';
		foreach($input_data as $key=>$value)
		{
			$input_rule=$this->get_input_rule($value);
			$require=(@in_array("require",@$value[11])) ? '<span class="maroon">*</span>' : '';
			if($value[16]=="1" && $value[14]!="1")
			{
				if($value[9]=="pic")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="txt" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" name="'.$value[0].'" type="text" value="" '.$input_rule.'>
          <span class="help-inline"><a class="btn insertimage">上传图片</a></span>
          <div><img src="<?php echo $base_url;?>resource/images/white.gif" style="max-width:360px;margin:10px 0 2px 0;"></div>'.$require.'<span class="help-inline">'.$value[12].'</span> </div>
       </div>';
				}
				elseif($value[9]=="file_upload")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="url" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" class="form-control '.$value[13].'" id="'.$value[0].'" name="'.$value[0].'" placeholder="'.str_replace("请输入","",$value[12]).'" value="" '.$input_rule.'>
          <span class="input-group-btn">
		  <button class="btn '.$value[0].' file-upload-image btn-default" type="button" data-urlinput="'.$value[0].'" data-dir="file" data-lang="zh"><i class="icon-upload"></i> '.str_replace("请输入","",$value[12]).'</button></span> '.$require.'<span for="url" class="help-block error valid"></span></div>
      </div>';
					$kindeditor_file_upload_js.='
			<script type="text/javascript">
			$(document).ready(function() {
	$(".'.$value[0].'").live("click",
	function() {
		var $this = $(this);
		var urlinput = $this.data("urlinput");
		var dir = $this.data("dir");
		if (dir == undefined || dir == null || dir.length == 0) dir = "file";
		var editor = KindEditor.editor({
			//langType: ($this.data("lang")) ? $this.data("lang") : "zh",
		/*	uploadJson: upload.uploadJson + "?dir=" + dir,*/
			allowFileManager: false
		});
		editor.loadPlugin("insertfile",
		function() {
			editor.plugin.fileDialog({
				fileUrl: KindEditor("#" + urlinput).val(),
				clickFn: function(url, title) {
					$("#" + urlinput).val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
</script>';
				}
				elseif($value[9]==="cate"  || $value[9]==="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].':</label>
        <div class="controls">
         <select name="'.$value[0].'" id="'.$value[0].'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" style="width:544px">
		<option value="">请选择</option>
		<?php show_class_select(\''.$cate_table.'\',0,0,$parent_'.$value[0].');?>
        </select> &nbsp; &nbsp; <a href="<?php echo $admin_url;?>'.$cate_table.'" target="_blank">管理分类</a>
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				elseif($value[9]==="cate_more" || $value[9]==="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					///******//$table_name_cate2
					$str.="\r\n";
					$str.='<div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'</label>
        <div class="controls">
          <input name="'.$value[0].'" id="'.$value[0].'" type="text" class="'.$cate_table.'_layer '.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" readonly="" '.$input_rule.'/>
          &nbsp; &nbsp; <a href="<?php echo $admin_url;?>'.$cate_table.'" target="_blank">管理分类</a>
          <input name="hide_'.$value[0].'" type="hidden" id="hide_'.$value[0].'" />
          <span class="maroon">'.$require.'</span><span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';

					$str_last.='<script type="text/javascript">
        $(function () {
            $(".controls").delegate(".'.$cate_table.'_layer", "click", function () {
            tag_str=$("#hide_'.$value[0].'").val();
			tag_str=tag_str.replace(/,/gi,"_");
			show_tag_div(tag_str,\'<?php echo $admin_url;?>\',\''.$cate_table.'\',\''.$value[0].'\');
            });	
        });
    </script>
<div id="'.$cate_table.'_layer" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-361px -48px;width:20px;height:20px;"></i>选择分类</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <div id="'.$cate_table.'_content"></div>
          </div>
        </div>
        <div class="modal-footer" style="text-align:left">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</button>
        </div>
      </form>
    </div>
  </div>
</div>';
				}
				elseif($value[9]=="editor")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" '.$input_rule.'></textarea>
          '.$require.'<span class="help-inline">'.$value[12].'</span> </div>
       </div>';
					$str_kindEditor.="var editor".$kind." = K.create('#".$value[0]."', seting);\r\n";
					$kind++;
				}
				elseif($value[9]=="value_multiple")
				{
					$str.="\r\n";
					$str.='<div class="control-group">
  <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
  <div class="controls"> <span class="help-inline">	</span>
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>字段名称</th>
          <th>初始内容</th>
          <th>操作</th>
          
        </tr>
      </thead>
      <tbody class="singlebody_'.$value[0].'">
        <tr class="'.$value[0].'" >
          <td>'.$value[10].'<span class="num_'.$value[0].'">1</span>：</td>
          <td><input type="text" name="'.$value[0].'_txt[]" id="txt1" value=""  class="input-xlarge" ></td>
          <td><input name="'.$value[0].'_value[]" id="value1" type="text" class="input-xlarge"  ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="'.$value[0].'_del" href="javascript:void(0);">删除</a></p></td>
          
        </tr>
      </tbody>
     
    </table>
  </div>
</div>';
					$value_multiple_js.='<script type="text/javascript">
    $(function () {
    $(".'.$value[0].'").each(function(){
        $(this).find(\'.btnGrayS\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            //读取当前单项文本的数量
            var num = $(".'.$value[0].'").size();
            if (num < 10) {
                newsingle = $('.$value[0].').clone(true);
                newsingle.appendTo(".singlebody_'.$value[0].'");
                //清空文本
                $(newsingle).find(\'input\').val(\'\');
                hanghao("'.$value[0].'");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find(\'.'.$value[0].'_del\').click(function(){
            var '.$value[0].' = $(this).parents(".'.$value[0].'");
            var num = $(".'.$value[0].'").size();
            if (num == 1) {
                //清空值
                $('.$value[0].').find(\'input\').val(\'\');
            } else {
                $('.$value[0].').remove();
                hanghao("'.$value[0].'");
            }
        });
    });
	})
</script>';
				}
				elseif($value[9]=="is_show")
				{
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="1" checked="checked" style="margin-top:0px"/>是</label>
          <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="2" style="margin-top:0px"/>否</label>
        </div>
      </div>';
				}
				elseif($value[9]=="more_value")
				{
					if($this->input->post("table_name"))
						$mod_row2=get_table_row("module",$this->input->post("table_name"),"name");
					else
						$mod_row2=get_table_row("module",$this->uri->segment(6),"id");
					$data_enum=$mod_row2->data_enum;
					$data_enum=json_decode($data_enum,true);
					$column=$value[0];
					$data_enum=$data_enum[$column];
					$data_enum=json_decode($data_enum);
					$data_enum_str='';
					$data_enum_str='<?php
		$enum_'.$value[0].'=array(';
					$data_enum_str.="\r\n";
					foreach($data_enum as $enum_key=>$enum_value)
					{
						$data_enum_str.="			'".$enum_key."'=>'".$enum_value."',\r\n";
					}
					$data_enum_str.='		);?>
    <?php
	$data_enum_i=1;
	foreach($enum_'.$value[0].' as $eunm_key=>$enum_value){
	$check=($data_enum_i==1) ? \'checked="checked"\' : "";?>
    <label class="radio inline" style="padding-top:2px"><input type="radio" name="'.$value[0].'" id="'.$value[0].'" value="<?php echo $eunm_key;?>"  style="margin-top:0px" <?php echo $check;?>/><?php echo $enum_value;?></label>
    <?php $data_enum_i++;}?>';
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
         '.$data_enum_str.'
        </div>
      </div>';
				}
				elseif($value[9]=="pic_multiple")
				{
					$str.="\r\n";
					$str.='     <div class="control-group">
        <label for="prices" class="control-label">图片上传：</label>
        <div class="controls">
          <input type="hidden" name="abid" id="abid" value="0">
          <div id="upimg_main">
            <input id="file_upload" type="file" />
            <ul class="ipost-list ui-sortable" id="fileList">
            </ul>
            <div id="file_upload_queue" class="uploadifyQueue"> </div>
          </div>
        </div>
      </div>';
					$pic_multiple_include='<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/uploadify/uploadify_t.css" media="all">
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_ui_custom.js?2014-03-07-1"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jquery_uploadify.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/uploadify/jupload.js"></script>';
					$pic_multiple_js='<script type="text/javascript">
	$(function () {
		var op = {
			el: $("#file_upload"),
			count: 50,
			data: {
				\'type_id\': 3,
				\'abid\':"0",
				\'aid\':"",
				\'utype\':"",
				\'uid\':"",
				\'uu\':""
			},
			url: "<?php echo $admin_url?>'.$table_name.'/upimg/",
			del_url: "<?php echo $admin_url?>'.$table_name.'/Delimg/",
			swf: "<?php echo $base_url;?>resource/uploadify/uploadify.swf"
		}
		
		G.logic.uploadify.init(op);
		var flag=true;
		G.logic.uploadify.Callback=function(data){
			if (flag && op.data.abid==0) {
				var urlstr = data.url.split(\'_\');
				var strs = urlstr[1].split(\'/\');
				var abid=strs[0];
				op.data.abid = abid;
				$("#abid").val(abid);
				op.el.uploadify(\'settings\',\'formData\',op.data);
				flag=false;
			};
		}
		/*$("#bsubmit").click(function () {
			if (!$("li.imgbox").length) {
				G.ui.tips.info("请上传图片");
				return false;
			};

		})*/
		$("#file_upload").append(\'<span class="maroon"></span><span class="help-inline">图片大小不超过300K</span>\');
	})
</script>';
				}
				elseif($value[9]=="input_text")
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <textarea class="'.$value[13].'" name="'.$value[0].'" id="'.$value[0].'" style="height:80px"></textarea>
          '.$require.'<span class="help-inline">'.$value[12].'</span>
                                                <span for="'.$value[0].'" class="help-block error valid"></span></div>
      </div>';
				}
				else
				{
					$value_default=($value[9]=="sort_order") ? 0 : "";
					$str.="\r\n";
					$str.='      <div class="control-group">
        <label for="'.$value[0].'" class="control-label">'.$value[10].'：</label>
        <div class="controls">
          <input type="text" name="'.$value[0].'" id="'.$value[0].'" value="'.$value_default.'" class="'.$value[13].' ui-wizard-content ui-helper-reset ui-state-default valid" '.$input_rule.'>
          '.$require.'<span class="help-inline">'.$value[12].'</span> <span for="'.$value[0].'" class="help-block error valid"></span></div>
       </div>';
				}
			}
		}
		if(in_array("file_upload",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';

		}
		//kindeditor
		if(in_array("editor",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
		}
		elseif(in_array("pic",$array_type_new))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';
			$kindeditor_js='	<script type="text/javascript">
                                                    KindEditor.ready(function (K) {
                                                        var editor = K.editor({
                                                            themeType: "simple",
                                                            allowFileManager: true
                                                        });
                                                        $(\'a.insertimage\').add("button.insertimage").click(function (e) {
                                                            editor.loadPlugin(\'smimage\', function () {
																var $input = $(e.target).parent().prev();
                                                                editor.plugin.imageDialog({
                                                                    imageUrl: $input ? $input.val() : "",
                                                                    clickFn: function (url, title, width, height, border, align) {
                                                                        if ($input) {
                                                                            $input.val(url);
                                                                            var rel = $(e.target).attr("rel")
																			$(e.target).parent().prev().val(url);
																			 $(e.target).parent().next().find("img").attr("src",url);
                                                                        }
                                                                        editor.hideDialog();
                                                                    }
                                                                });
                                                            });
                                                        })

                                                    });
                                                    
                                                </script>
';
		}
		$leixing="friend_link";
		$this->load->helper('file');
		/****view_add******/
		$file_add=FCPATH."application/models2/".$leixing."/views/".$leixing."_add.php";
		$content=file_get_contents($file_add);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<pic_multiple_include>",$pic_multiple_include,$content);
		$content=str_replace("<pic_multiple_js>",$pic_multiple_js,$content);
		$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
		$content=str_replace("<kindeditor_file_upload_js>",$kindeditor_file_upload_js,$content);
		$content=str_replace("<value_multiple_js>",$value_multiple_js,$content);
		$table_name_zh=str_replace("管理","",$table_name_zh);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<view_add>",$str,$content);
		$content=str_replace("<str_last>",$str_last,$content);

		//$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
		$file2=FCPATH."application/views/admin_admin/".$table_name."_add.php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		/****view_edit******/
		/*$file_edit=FCPATH."application/models2/".$leixing."/views/".$leixing."_edit.php";
		$content=file_get_contents($file_edit);
		$content=str_replace($leixing,$table_name,$content);
		$content=$this->replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type);
		$file2=FCPATH."application/views/admin_admin/".$table_name."_edit.php";
		$s=write_file($file2, $content,"w");*/
		//var_dump($str);die();
	}
	function write_file_module($table_name,$array_url,$input_data,$array_type_new,$table_name_zh)
	{
		$save_is_show="";
		$save_is_show_function="";
		$save_sort_order="";
		$save_sort_order_function="";
		$sort_order_paixu="";
		$save_pic_multiple="";
		$save_pic_multiple_function="";
		$search_like="and (";
		$export_excel_array="";
		$export_excel_time="";
		$export_excel_category="";
		$search_cate="";
		$search_cate_column="";
		$search_like_more="";
		$search_cate_column_module="";
		$search_cate_column2="";
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			$export_excel_array.="\"".$value[0]."\"=>\"".$value[8]."\",";
			if($value[7]!="auto_increment")
			{
				if((strpos($value[1],"char")!==false || strpos($value[1],"text")!==false) && strpos($value[9],"cate")===false && strpos($value[9],"pic")===false)
				{
					$search_like.='`'.$value[0].'` like \'%".$keyword."%\' or ';
				}
				if($value[9]=="is_show")
				{
					$save_is_show.="if(\$this->uri->segment(5)==\"ajax_save_is_show\")
		\$this->ajax_save_is_show(\$this->uri->segment(6));";
					$save_is_show_function='function ajax_save_is_show($id)
	{
		$is_show=0;
		if(preg_match("/^\d+$/is",$id))
		{
			$info=get_table_row($this->table_name,$id);
			if($info)
			{
				$is_show=$info->'.$value[0].';
				$is_show=($is_show==1) ? 0 : 1;
				$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("'.$value[0].'"=>$is_show),"id=?");
				$this->db->query($update_str,$id);
			}
		}
		echo $is_show;die();
	}';
				}
				if($value[9]=="pic_multiple")
				{
					$pic_multiple_table=$this->get_pic_multiple_table($table_name);
					$save_pic_multiple.='if($this->uri->segment(5)=="upimg")
		$this->upimg();
		if($this->uri->segment(5)=="Delimg")
		$this->Delimg($this->uri->segment(6));';
					$save_pic_multiple_function='function upimg()
	{

		//$verifyToken = md5("unique_salt" . $_POST["timestamp"]);
		//if (!empty($_FILES) && $_POST["token"] == $verifyToken) {
		if (!empty($_FILES)) {
			$targetFolder = "/uploads"; // Relative to the root
			//$verifyToken = md5("unique_salt" . $_POST["timestamp"]);
			//if (!empty($_FILES) && $_POST["token"] == $verifyToken) {
			if (!empty($_FILES)) {
				$tempFile = $_FILES["Filedata"]["tmp_name"];
				$sec =explode(" ",microtime());
				$filename=str_replace("0.","",$sec[1].$sec[0]);
				$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
				$vcode="";
				for($i = 0;$i < 4;$i++){
					$vcode .= $string[rand(0,35)];
				}
				$file_name_old=$_FILES["Filedata"]["name"];
				$targetFile = get_upload_dir()."/".$vcode.$filename.substr($file_name_old,strrpos($file_name_old,"."));
				$targetFile_thumb = get_upload_dir()."/thm_".$vcode.$filename.substr($file_name_old,strrpos($file_name_old,"."));
				//create_crop_pic($targetFile,$targetFile_thumb);
				//$targetFile = rtrim($targetPath,"/") . "/" . $file_name_old;

				// Validate the file type
				$fileTypes = array("jpg","jpeg","gif","png"); // File extensions
				$fileParts = pathinfo($file_name_old);
				$title=substr($file_name_old,0,strrpos($file_name_old,"."));
				if (in_array($fileParts["extension"],$fileTypes)) {
					move_uploaded_file($tempFile,$targetFile);

					$data=array("pic"=>$targetFile,"title"=>$title,"description"=>"","postdate"=>time());
					$insert_str=$this->db->insert_string($this->db->dbprefix."'.$pic_multiple_table.'", $data);
					$this->db->query($insert_str);
					$insert_id=$this->db->insert_id();
					$arr = array("result"=>"SUCCESS", "image"=>array("id"=>$insert_id,"url"=>base_url().$targetFile,"title"=>$title,"content"=>"","thm_url"=>base_url().$targetFile));
					echo json_encode($arr);
				} else {
					$arr = array("message"=>"文件格式不正确","image"=>array("id"=>3,"url"=>base_url().$targetFile,"title"=>$title,"content"=>"","thm_url"=>base_url().$targetFile));
				}
			}
			die();
		}
		die();
	}
	function Delimg($photo_id)
	{
		$table_pic="'.$pic_multiple_table.'";
		$id=$this->input->post("id");
		$insert_str=$this->db->update_string($this->db->dbprefix.$table_pic, array("status"=>0),$this->index_id."=".$id);
		$this->db->query($insert_str);
		//删除图片
		$info=get_table_row($table_pic,$id);
		@unlink(FCPATH.@$info->pic);
		die();
		//删除图片end
		//$arr = array("errno"=>"0", "error"=>"操作成功！","url"=>url_admin()."product_add/".$photo_id);
		//echo json_encode($arr);die();
	}';
				}
				elseif($value[9]=="sort_order")
				{
					$save_sort_order.="if(\$this->uri->segment(5)==\"ajax_save_sort_order\")
		\$this->ajax_save_sort_order();";
					$sort_order_paixu=$value[0]." desc,";
					$save_sort_order_function='function ajax_save_sort_order()
	{
		$id=$this->input->post("id");
		$order=$this->input->post("orderby");
		if(preg_match("/^\d+$/is",$id) && preg_match("/^\d+$/is",$order))
		{
			$info=get_table_row($this->table_name,$id);
			if($info)
			{
				$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("'.$value[0].'"=>$order),"id=?");
				$this->db->query($update_str,$id);
			}
		}
		echo $order;die();
	}';
				}
				elseif($value[9]=="postdate")
				{
					$export_excel_time.="			\$row['".$value[0]."']=(\$row['".$value[0]."']>0) ? get_time(\$row['".$value[0]."']) : \"\";\r\n";
				}
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$export_excel_category.="            \$cate_table=get_table_row('".$cate_table."',\$row['".$value[0]."']);\r\n            \$row['".$value[0]."']=@\$cate_table->name;\r\n";
					$search_cate.="\r\n		//".$cate_table."\r\n";
					$search_cate.='		$'.$cate_table.'_list=array();
		$sql="select * from ".$this->db->dbprefix."'.$cate_table.' where id>0 order by id desc";
		$result=$this->db->query($sql,array(1));
		foreach ($result->result() as $row)
		{
			$'.$cate_table.'_list[]=$row;
		}
		$data["'.$cate_table.'_list"]=$'.$cate_table.'_list;';
					$search_cate_column.=' || $this->input->get("'.$value[0].'")';
					$search_like_more.=' 			if($this->input->get("'.$value[0].'"))
			$search_where.=" and `'.$value[0].'`=".$this->input->get("'.$value[0].'");';
					$search_like_more.="\r\n";
					$search_cate_column_module.='		$data[\'search_'.$value[0].'\']=$this->input->get("'.$value[0].'");';
					$search_cate_column_module.="\r\n";
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_table=$this->get_cate_table($value[0],$value[10],$table_name,$value[9]);
					$export_excel_category.="            \$row['".$value[0]."']=ajax_show_tag('".$cate_table."',\$row['".$value[0]."']);\r\n";
					$search_cate.="\r\n		//".$cate_table."\r\n";
					$search_cate.='		$'.$cate_table.'_list=array();
		$sql="select * from ".$this->db->dbprefix."'.$cate_table.' where id>0 order by id desc";
		$result=$this->db->query($sql,array(1));
		foreach ($result->result() as $row)
		{
			$'.$cate_table.'_list[]=$row;
		}
		$data["'.$cate_table.'_list"]=$'.$cate_table.'_list;';
					$search_cate_column.=' || $this->input->get("'.$value[0].'")';
					$search_like_more.=' 			if($this->input->get("'.$value[0].'"))
			$search_where.=" and `'.$value[0].'` like \'%,".$this->input->get("'.$value[0].'").",%\'";';
					$search_like_more.="\r\n";
					$search_cate_column_module.='		$data[\'search_'.$value[0].'\']=$this->input->get("'.$value[0].'");';
					$search_cate_column_module.="\r\n";
				}
			}
			$i++;

		}
		$search_like=($search_like=="and (") ? "" : substr($search_like,0,strlen($search_like)-4).")";
		$export_excel_array=(substr($export_excel_array,-1)==",") ? substr($export_excel_array,0,strlen($export_excel_array)-1) : $export_excel_array;
		$leixing="friend_link";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/".$leixing."/models/".$leixing.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("<search_like>",$search_like,$content);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<save_is_show>",$save_is_show,$content);
		$content=str_replace("<save_is_show_function>",$save_is_show_function,$content);
		$content=str_replace("<save_sort_order>",$save_sort_order,$content);
		$content=str_replace("<save_sort_order_function>",$save_sort_order_function,$content);
		$content=str_replace("<sort_order_paixu>",$sort_order_paixu,$content);
		$content=str_replace("<save_pic_multiple>",$save_pic_multiple,$content);
		$content=str_replace("<save_pic_multiple_function>",$save_pic_multiple_function,$content);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<export_excel_array>",$export_excel_array,$content);
		$content=str_replace("<export_excel_time>",$export_excel_time,$content);
		$content=str_replace("<export_excel_category>",$export_excel_category,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		$content=str_replace("<search_cate>",$search_cate,$content);
		$content=str_replace("<search_cate_column>",$search_cate_column,$content);
		$content=str_replace("<search_like_more>",$search_like_more,$content);
		$content=str_replace("<search_cate_column_module>",$search_cate_column_module,$content);
		$content=$this->replace_blank_row($content);
		$file2=FCPATH."application/models/admin_admin/".$table_name.".php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_module_add($table_name,$array_url,$input_data,$array_type_new)
	{
		$model_add_array="\$data = array(\r\n";
		$model_add_pic_multiple="";
		$model_add_session="";
		$model_add_session_pre="";
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			if($value[7]!="auto_increment" && $value[9]!="click")
			{
				if($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$model_add_array.="		".$value2."=>trim(\$this->input->post('hide_".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$model_add_session.="\$session_info=array(\"parent_".$value[0]."\"=>\$this->input->post('".$value[0]."'));
		\$this->session->set_userdata(\$session_info);\r\n";
					$model_add_session_pre.="\$parent_".$value[0]."=\$this->session->userdata(\"parent_".$value[0]."\");
			\$parent_".$value[0]."=(\$parent_".$value[0].") ? \$parent_".$value[0]." : 0;
			return array(\"parent_".$value[0]."\"=>\$parent_".$value[0].");\r\n";
					$model_add_array.="		".$value2."=>trim(\$this->input->post('".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="postdate")
				{
					$model_add_array.="		".$value2."=>time()".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="ip_address")
				{
					$model_add_array.="		".$value2."=>\$this->input->ip_address()".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="value_multiple")
				{
					$model_add_array.="		".$value2."=>json_encode(array(\"txt\"=>\$this->input->post(\"".$value[0]."_txt\"),\"value\"=>\$this->input->post(\"".$value[0]."_value\")))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$pic_multiple_table=$this->get_pic_multiple_table($table_name);
					@$this->db->query("TRUNCATE TABLE `site_".$pic_multiple_table."`");
					$model_add_pic_multiple.='/**多图**/
		$pid=$this->db->insert_id();
		$phout_list=$this->input->post("phout_list");
		$phout_url=$this->input->post("phout_url");
		$imagestexts=$this->input->post("imagestexts");
		$j=10000;
		if($phout_list)
		{
			foreach($phout_list as $images_key=>$images_v)
			{
				if($images_v)
				{
					$data=array("pid"=>$pid,"sort_order"=>$j,"status"=>1,"description"=>@$imagestexts[$images_v][0]);
					$update_str=$this->db->update_string($this->db->dbprefix."'.$pic_multiple_table.'", $data,"id=?");
					$this->db->query($update_str,array($images_v));
					$j--;
				}
			}
			$pic=current($phout_url);
			$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("'.$value[0].'"=>@$pic[0]),"id=?");
			$this->db->query($update_str,array($pid));
		}';
				}
				else
				{
					//$model_add_array.="\"".$value[0]."\"=>\$".$value[0].",";
					$model_add_array.="		".$value2."=>trim(\$this->input->post('".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
			}
			$i++;
		}
		$model_add_array.="		);";
		$leixing="friend_link";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/".$leixing."/models/".$leixing."_add.php";
		$content=file_get_contents($file_add);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<model_add_array>",$model_add_array,$content);
		$content=str_replace("<model_add_pic_multiple>",$model_add_pic_multiple,$content);
		$content=str_replace("<model_add_session>",$model_add_session,$content);
		$model_add_session_pre=($model_add_session_pre) ? "else\r\n		{\r\n			".$model_add_session_pre."\r\n		}" : "";
		$content=str_replace("<model_add_session_pre>",$model_add_session_pre,$content);
		$content=$this->replace_blank_row($content);
		$file2=FCPATH."application/models/admin_admin/".$table_name."_add.php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_module_edit($table_name,$array_url,$input_data,$array_type_new,$create_view)
	{
		$model_column_first="";
		$model_edit_array="\$data = array(\r\n";
		$model_edit_pic_multiple="";
		$model_edit_pic_multiple_show_info="";
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			if($value[9]!="postdate" && $value[9]!="ip_address" && $value[9]!="click" && $value[7]!="auto_increment")
			{
				if($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$model_edit_array.="		".$value2."=>trim(\$this->input->post('hide_".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
				elseif($value[9]=="pic_multiple")
				{
					$pic_multiple_table=$this->get_pic_multiple_table($table_name);
					@$this->db->query("TRUNCATE TABLE `site_".$pic_multiple_table."`");
					$model_edit_pic_multiple.='/**多图**/
		$phout_list=$this->input->post("phout_list");
		$phout_url=$this->input->post("phout_url");
		$imagestexts=$this->input->post("imagestexts");
		$j=10000;
			if($phout_list)
		{
			foreach($phout_list as $images_key=>$images_v)
			{
				if($images_v)
				{
					$data=array("pid"=>$id,"pic"=>@$phout_url[$images_v][0],"sort_order"=>$j,"status"=>1,"description"=>@$imagestexts[$images_v][0]);
					$update_str=$this->db->update_string($this->db->dbprefix."'.$pic_multiple_table.'", $data,"id=?");
					$this->db->query($update_str,array($images_v));
					$j--;
				}
			}
			$pic=current($phout_url);
			$update_str=$this->db->update_string($this->db->dbprefix.$this->table_name, array("'.$value[0].'"=>@$pic[0]),"id=?");
			$this->db->query($update_str,array($id));
		}
		/**多图结束**/';
					$model_edit_pic_multiple_show_info='$pic_list=array();
		$result=$this->db->query("select * from ".$this->db->dbprefix."'.$pic_multiple_table.' where pid=? and status=? order by sort_order desc",array($id,1));
		foreach ($result->result() as $row)
		{
			$pic_list[]=$row;
		}
		$data["pic_list"]=$pic_list;';
				}
				elseif($value[9]=="value_multiple")
				{
					$model_edit_array.="		".$value2."=>json_encode(array(\"txt\"=>\$this->input->post(\"".$value[0]."_txt\"),\"value\"=>\$this->input->post(\"".$value[0]."_value\")))".$symbol."//".@$value[8]."\r\n";
				}
				else
				{
					$model_edit_array.="		".$value2."=>trim(\$this->input->post('".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
			}
			$model_column_first=($i==1) ? $value[0] : $model_column_first;
			$i++;
		}
		$model_edit_array.="		);";
		//$model_edit_array=(substr($model_edit_array,-1)==",") ? substr($model_edit_array,0,strlen($model_edit_array)-1) : $model_edit_array;
		//$model_edit_array="\$data=array(".$model_edit_array.");";
		$leixing="friend_link";
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/models/".$leixing."_edit_create_view.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/models/".$leixing."_edit.php";
		$content=file_get_contents($file_add);//var_dump($content);
		$content=str_replace($leixing,$table_name,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		$content=str_replace("<model_edit_pic_multiple_show_info>",$model_edit_pic_multiple_show_info,$content);
		$content=str_replace("<model_edit_pic_multiple>",$model_edit_pic_multiple,$content);
		$content=str_replace("<model_edit_array>",$model_edit_array,$content);
		$content=str_replace("<model_column_first>",$model_column_first,$content);
		//var_dump($content);die();
		$content=$this->replace_blank_row($content);
		if($create_view=="create_view")
		$file2=FCPATH."application/models/admin_admin/".$table_name.".php";
		else
		$file2=FCPATH."application/models/admin_admin/".$table_name."_edit.php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_front_controller($table_name,$array_url,$input_data,$array_type_new)
	{
		$i=1;
		$cate_i=0;
		$search_where='';
		$search_where_pre='';
		$search_where_pre_end='';
		$index_category='';
		$ajax_save='';
		foreach($input_data as $key=>$value)
		{
			if($value[9]!="postdate" && $value[7]!="auto_increment")
			{
				if($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$search_where_if=($cate_i==0) ? "if" : "		elseif";
					$search_where.="	".$search_where_if.'(preg_match("/^'.$value[0].'_\d+$/is",$url))
			{
				$val=str_replace("'.$value[0].'_","",$url);
				$sql.=" and '.$value[0].'=".$val;
			}';
					$search_where.="\r\n";
					$cate_table=$this->get_cate_table($value[0],$value[8],$table_name,$value[9]);
					$index_category.='		/***'.@$value[8].'**/
		$'.$value[0].'_list=array();
		$result=$this->db->query("select * from ".$this->db->dbprefix."'.$cate_table.' where is_show=? order by sort_order desc,id asc",array(1));
		foreach ($result->result() as $row)
		{
			$'.$value[0].'_list[]=$row;
		}
		$data[\''.$value[0].'_list\']=$'.$value[0].'_list;';
					$index_category.="\r\n";
					$cate_i++;
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$search_where_if=($cate_i==0) ? "if" : "		elseif";
					$search_where.="	".$search_where_if.'(preg_match("/^'.$value[0].'_\d+$/is",$url))
			{
				$val=str_replace("'.$value[0].'_","",$url);
				$sql.=" and `'.$value[0].'` like \'%".$val."%\'";
			}';
					$search_where.="\r\n";
					$cate_table=$this->get_cate_table($value[0],$value[8],$table_name,$value[9]);
					$index_category.='		/***'.@$value[8].'**/
		$'.$value[0].'_list=array();
		$result=$this->db->query("select * from ".$this->db->dbprefix."'.$cate_table.' where is_show=? order by sort_order desc,id asc",array(1));
		foreach ($result->result() as $row)
		{
			$'.$value[0].'_list[]=$row;
		}
		$data[\''.$value[0].'_list\']=$'.$value[0].'_list;';
					$index_category.="\r\n";
					$cate_i++;
				}
			}
			$model_column_first=($i==1) ? $value[0] : $model_column_first;
			$i++;
		}
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			if($value[7]!="auto_increment" && $value[9]!="click")
			{
				if($value[9]=="postdate")
				{
					$ajax_save.="		".$value2."=>time()".$symbol."//".@$value[8]."\r\n";
				}
				else
				{
					//$model_add_array.="\"".$value[0]."\"=>\$".$value[0].",";
					$ajax_save.="		".$value2."=>trim(\$this->input->post('".$value[0]."'))".$symbol."//".@$value[8]."\r\n";
				}
			}
			$i++;
		}

		if($cate_i>0)
		{
			$search_where_pre.='for($i=1;$i<=20;$i++)
		{
			$url=$this->uri->segment($i);';
			$search_where_pre_end.='}';
		}
		$leixing="friend_link";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/".$leixing."/views/front_controller.php";
		$content=file_get_contents($file_add);//var_dump($content);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<search_where>",$search_where,$content);
		$content=str_replace("<search_where_pre>",$search_where_pre,$content);
		$content=str_replace("<search_where_pre_end>",$search_where_pre_end,$content);
		$content=str_replace("<ajax_save>",$ajax_save,$content);
		$content=str_replace("<index_category>",$index_category,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		$file2=FCPATH."application/controllers/".$table_name.".php";
		$content=$this->replace_blank_row($content);
		//if(!file_exists($file2))
		$s=write_file($file2, $content,"w");
	}

	function write_file_front_controller_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view)
	{
		$leixing="site_www";
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/article.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/article.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_cate=$this->get_table_field($input_data,"cate");
		if($field_cate)
		{
			$table_name_cate=$table_name."_".$field_cate;
			$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
		}
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_cate>",$field_cate,$content);
		$content=str_replace("article",$table_name,$content);
		$content=str_replace("<table_name_cate>",$table_name_cate,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		write_file(FCPATH."application/controllers/".$table_name.".php", $content,"w");
	}
	function write_file_front_controller_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view)
	{
		$leixing="site_www";
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/photo.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/photo.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_cate=$this->get_table_field($input_data,"cate");
		if($field_cate)
		{
			$table_name_cate=$table_name."_".$field_cate;
			$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
		}
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_cate>",$field_cate,$content);
		$content=str_replace("<table_name_cate>",$table_name_cate,$content);
		$content=str_replace("photo",$table_name,$content);
		write_file(FCPATH."application/controllers/".$table_name.".php", $content,"w");
	}
	function write_file_front_view_photo($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_val)
	{
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_www/views/photo_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_www/views/photo_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("photo",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_pic=$this->get_table_field($input_data,"pic");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		write_file(FCPATH."application/views/".$table_name.".php", $content,"w");
		//show
		$file_add=FCPATH."application/models2/site_www/views/photo_show_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("photo",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_pic=$this->get_table_field($input_data,"pic");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/".$table_name."_show.php", $content,"w");
	}
	function write_file_front_view_article($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_val)
	{
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_www/views/article_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_www/views/article_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("article",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_pic=$this->get_table_field($input_data,"pic");
		$field_click=$this->get_table_field($input_data,"click");
		$field_postdate=$this->get_table_field($input_data,"postdate");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_click>",$field_click,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		$content=str_replace("<field_postdate>",$field_postdate,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		write_file(FCPATH."application/views/".$table_name.".php", $content,"w");
		//show
		$file_add=FCPATH."application/models2/site_www/views/article_show_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("article",$table_name,$content);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_pic>",$field_pic,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		$content=str_replace("<field_click>",$field_click,$content);
		$content=str_replace("<field_postdate>",$field_postdate,$content);
		$column_name_first=$this->get_table_locate_column($table_name,"int");
		$content=str_replace("<column_name_first>",$column_name_first,$content);
		write_file(FCPATH."application/views/".$table_name."_show.php", $content,"w");
	}

	function write_file_front_controller_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view)
	{
		$leixing="site_www";
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/".$leixing."/help.php";
		else
		$file_add=FCPATH."application/models2/".$leixing."/help.php";
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=file_get_contents($file_add);
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		$content=str_replace("help",$table_name,$content);
		write_file(FCPATH."application/controllers/".$table_name.".php", $content,"w");
	}
	function write_file_front_view_help($table_name,$array_url,$input_data,$array_type_new,$table_name_zh,$create_view,$template_val)
	{
		$this->load->helper('file');
		if($create_view=="create_view")
		$file_add=FCPATH."application/models2/site_www/views/help_view_".$template_val.".php";
		else
		$file_add=FCPATH."application/models2/site_www/views/help_view_".$template_val.".php";
		$content=file_get_contents($file_add);
		$content=str_replace("help",$table_name,$content);
		$field_title=$this->get_table_field($input_data,"char");
		$field_content=$this->get_table_field($input_data,"text");
		$content=str_replace("<field_title>",$field_title,$content);
		$content=str_replace("<field_content>",$field_content,$content);
		write_file(FCPATH."application/views/".$table_name.".php", $content,"w");
	}
	function write_file_front_view_add($table_name,$array_url,$input_data,$array_type_new)
	{
		$view_add='';
		$js_add_value="";
		$js_add="";
		$i=1;
		foreach($input_data as $key=>$value)
		{
			$symbol=($i==count($input_data)) ? "" : ",";
			$value2=substr("'".$value[0]."'".str_repeat(" ",19),0,20);
			$value2=(str_replace(array(" ","'"),"",$value2)==$value[0]) ? $value2 : "'".$value[0]."'";
			if($value[7]!="auto_increment" && $value[9]!="click" && $value[9]!="postdate")
			{
				$view_add.='<dl>
              <dt>'.@$value[8].'： </dt>
              <dd>
                <input type="text" name="'.$value[0].'" id="'.$value[0].'" placeholder="请输入'.@$value[8].'" maxlength="30" class="input">
              </dd>
            </dl>';
				if(strpos($value[0],"email")!==false)
				{
					$js_add.='var reg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;		if(obj.input_'.$value[0].'.length==0){
			alert("请输入'.@$value[8].'", 1500);
			return;
		}
		if(!reg.test(obj.input_'.$value[0].')){
			alert("请输入有效的邮箱!", 1500);
			return;
		}';
				}
				else if((strpos($value[0],"phone")!==false) || (strpos($value[0],"tel")!==false))
				{
					$js_add.='var mobile=/^((13[0-9]{1})|15|13|17|18|19|12)+\d{9}$/;
					if(obj.input_'.$value[0].'.length==0){
			alert("请输入'.@$value[8].'", 1500);
			return;
		}
		if(!mobile.test(obj.input_'.$value[0].')){
			alert("请输入有效的手机号码!", 1500);
			return;
		}';
				}

				else
				$js_add.='		if(obj.input_'.$value[0].'.length==0){
			alert("请输入'.@$value[8].'", 1500);
			return;
		}';
				$js_add_value.="			input_".$value[0].": form.".$value[0].".value".$symbol."\r\n";
			}
			$i++;
		}
		$leixing="friend_link";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/".$leixing."/views/front_view_add.php";

		$content=file_get_contents($file_add);//var_dump($content);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<view_add>",$view_add,$content);
		$content=str_replace("<js_add>",$js_add,$content);
		$content=str_replace("<js_add_value>",$js_add_value,$content);
		$content=$this->replace_blank_row($content);
		$file2=FCPATH."application/views/".$table_name."_add.php";
		$s=write_file($file2, $content,"w");
	}
	function write_file_front_view($table_name,$array_url,$input_data,$array_type_new)
	{
		$cate_list='';
		foreach($input_data as $key=>$value)
		{
			if($value[9]!="postdate" && $value[7]!="auto_increment")
			{
				if($value[9]=="cate" || $value[9]=="cate_simple")
				{
					$cate_title=(strpos($value[8],"分类")) ? $value[8] : $value[8]."分类";
					$cate_list.='<div class="block side-bar">
        <h4>'.$value[8].'</h4>
        <ul class="unstyled list nav-pills">
        <?php  $class_on=(!preg_match("/[^\w]?major_\d+?/is",$_SERVER["REQUEST_URI"])) ? \' class="active"\' : "";?>
          <li<?php echo $class_on;?>><a href="<?php echo $site_url;?><?php echo get_cate_url_all("'.$value[0].'");?>">全部</a></li>
          <?php foreach($'.$value[0].'_list as $'.$value[0].'_list_row){
          $class_on=(get_url_color_on("'.$value[0].'_".$'.$value[0].'_list_row->id)) ? \' class="active"\' : "";?>
          <li<?php echo $class_on;?>><a href="<?php echo $site_url;?><?php echo get_cate_url("'.$value[0].'",$'.$value[0].'_list_row->id,"'.$table_name.'");?>"><?php echo $'.$value[0].'_list_row->name;?></a></li>
          <?php }?>
        </ul>
      </div>';
				}
				elseif($value[9]=="cate_more" || $value[9]=="cate_more_simple")
				{
					$cate_title=(strpos($value[8],"分类")) ? $value[8] : $value[8]."分类";
					$cate_list.='<div class="block side-bar">
        <h4>'.$cate_title.'</h4>
        <ul class="unstyled list nav-pills">
        <?php  $class_on=(!preg_match("/[^\w]?major_\d+?/is",$_SERVER["REQUEST_URI"])) ? \' class="active"\' : "";?>
         <li<?php echo $class_on;?>><a href="<?php echo $site_url;?><?php echo get_cate_url_all("'.$value[0].'");?>">全部</a></li>
          <?php foreach($'.$value[0].'_list as $'.$value[0].'_list_row){
           $class_on=(get_url_color_on("'.$value[0].'_".$'.$value[0].'_list_row->id)) ? \' class="active"\' : "";?>
          <li<?php echo $class_on;?>><a href="<?php echo $site_url;?><?php echo get_cate_url("'.$value[0].'",$'.$value[0].'_list_row->id,"'.$table_name.'");?>"><?php echo $'.$value[0].'_list_row->name;?></a></li>
          <?php }?>
        </ul>
      </div>';
				}
			}
		}
		$leixing="friend_link";
		$this->load->helper('file');
		$file_add=FCPATH."application/models2/".$leixing."/views/front_view_index.php";

		$content=file_get_contents($file_add);//var_dump($content);
		$content=str_replace($leixing,$table_name,$content);
		$content=str_replace("<cate_list>",$cate_list,$content);
		$content=$this->replace_blank_row($content);
		$file2=FCPATH."application/views/".$table_name.".php";
		if(!file_exists($file2))
		$s=write_file($file2, $content,"w");
	}
	function column_exist($column_name,$input_data)
	{
		$signal=false;
		foreach($input_data as $key=>$value)
		{
			if($value[9]!="postdate" && $value[7]!="auto_increment")
			{
				if($value[9]=="cate")
				{

				}
			}
		}
		foreach($array_url as $key=>$value)
		{
			if($column_name==$value)
			{
				$signal=true;
				break;
			}
		}
		return $signal;
	}
	function get_yii2_rule($table_name)
	{
		$sql="show columns from ".$this->db->dbprefix.$table_name;
		$result=$this->db->query($sql);
		$str_all="";
		if($result)
		{
			$table_info=$result->result();
			$string=array();
			$int=array();
			foreach($table_info as $row)
			{
				$type=$row->Type;
				$extra=$row->Extra;
				$type_len=preg_replace("/.*\((\d+)\).*/is", "\$1", $type);
				if(strpos($type,"varchar")!==false || strpos($type,"char")!==false)
				{
					if(!isset($string[$type_len]))
					$string[$type_len]=array();
					array_push($string[$type_len],$row->Field);
				}
				elseif(strpos($type,"int")!==false && $extra!="auto_increment")
				{
					if(!isset($int[$type_len]))
					$int[$type_len]=array();
					array_push($int[$type_len],$row->Field);
				}
				elseif(strpos($type,"text")!==false)
				{
					if(!isset($string["all"]))
					$string["all"]=array();
					array_push($string["all"],$row->Field);
				}
			}
			foreach($string as $key=>$string_row)
			{
				$str="";
				foreach($string_row as $str2)
				{
					$str.="'".$str2."',";
				}
				$str=(strlen($str)>0) ? substr($str,0,strlen($str)-1) : "";
				if($key=="all")
				$str_all.="			[[".$str."], 'string'],\r\n";
				else
				$str_all.="			[[".$str."], 'string', 'max' => ".$key."],\r\n";
			}
			foreach($int as $key=>$string_row)
			{
				$str="";
				foreach($string_row as $str2)
				{
					$str.="'".$str2."',";
				}
				$str=(strlen($str)>0) ? substr($str,0,strlen($str)-1) : "";
				$str_all.="			[[".$str."], 'integer'],\r\n";
			}
		}
		
		
		return $str_all;
	}
	function get_input_rule($rule_value)
	{
		$str="";
		$rule_array=$rule_value[11];
		$count=$rule_value[2];
		if($rule_array)
		{
			foreach($rule_array as $value)
			{
				$str.=($value=="require") ? 'data-rule-required="true" ' : "";
				$str.=($value=="url") ? 'data-rule-url="true" ' : "";
				$str.=($value=="email") ? 'data-rule-email="true" ' : "";
				$str.=($value=="phone") ? 'data-rule-phone="true" ' : "";
				$str.=($value=="digits") ? 'data-rule-digits="true" ' : "";
				$str.=($value=="number") ? 'data-rule-number="true" ' : "";
				$str.=($value=="minlength") ? 'data-rule-minlength="4" ' : "";
				$str.=($value=="maxlength") ? 'data-rule-maxlength="'.$count.'" ' : "";
				$str.=($value=="range") ? 'data-rule-range="[0,100]" ' : "";
				$str.=($value=="min") ? 'data-rule-min="1" ' : "";
				$str.=($value=="max") ? 'data-rule-max="'.$count.'" ' : "";
				$str.=($value=="money") ? 'data-rule-ismoney="true" ' : "";
				$str.=($value=="readonly") ? 'readonly="readonly" ' : "";
				$str.=($value=="disabled") ? 'disabled="disabled" ' : "";
			}
		}
		return $str;
	}
	function insert_module($table_name_zh,$table_name,$leixing,$mod_row,$data_array,$create_view,$laravel_timestamp='')
	{
		$input_data_more_value=$this->get_input_data_more_value();
		$data_enum=json_encode($input_data_more_value);
		$data_array=json_encode($data_array);
		$info_view=str_replace($leixing,$table_name,$mod_row->info_view);
		$result=$this->db->query("select * from ".$this->db->dbprefix.$this->table_name." where url=?",array($table_name));
		$info=$result->num_rows();
		$info2=$result->row();
		$create_view=$this->input->post("create_type");
		$create_view=($create_view=="create_view") ? 1 : 0;
		//info_control
		$info_control="";
		$info_view="";
		$module_info=get_table_row($this->table_name,$table_name,"url");
		$url_array=array();
		$url_array_zh=array();
		array_push($url_array,$table_name);
		array_push($url_array_zh,$table_name_zh);
		$data=json_decode($data_array);
		$pic_col='';
		$array_col=array();
		foreach($data as $data_row)
		{
			if(strpos($data_row[9],"cate")!==false)
			{
				$table_name_cate2=$table_name."_".$data_row[0];
				$table_name2=str_replace("_id","",$table_name_cate2)."_cate";
				array_push($url_array,$table_name2);
				array_push($url_array_zh,$data_row[8]);
			}
			if(strpos($data_row[9],"pic")!==false)
			{
				$pic_col=$data_row[0];
			}
			$array_col[$data_row[0]]=$data_row[9];

		}
		$i=1;
		foreach($url_array as $key=>$url_name)
		{
			$br_control=($i>1) ? "<br/><br/>" : "";
			$info_control.=$br_control."
		/***".$url_array_zh[$key]."**/<br/>";
			$info_control.="\$".$url_name."_list=array();<br/>
		\$sql=\"select * from \".\$this->db->dbprefix.\"".$url_name." where id>0 order by id desc\";<br/>
		\$result=\$this->db->query(\$sql,array(1));<br/>
		foreach (\$result->result() as \$row)<br/>
		{<br/>
						\$".$url_name."_list[]=\$row;<br/>
		}<br/>
		\$data['".$url_name."_list']=\$".$url_name."_list;";
			$br_view=($i>1) ? "\r\n\r\n" : "";
			$pic_col=($pic_col && $i==1) ? "<a href=\"<?php echo \$site_url;?>article/detail/<?php echo \$row->id;?>\" target=\"_blank\"><img alt=\"<?php echo \$row->title;;?>\" src=\"<?php echo (@\$row->".$pic_col.") ? ((substr(@\$row->".$pic_col.",0,4)=='http' || substr(@\$row->".$pic_col.",0,1)=='/') ? @\$row->".$pic_col." : \$base_url.@\$row->".$pic_col.")  : \"\";?>\" width=\"120\" height=\"75\" /></a>\r\n" : "";
			$info_view.=$br_view."<!--".$url_array_zh[$key]."-->\r\n";
			if($i==1)
			{
				foreach($array_col as $key=>$value)
				{
					if($value=="pic")
					$info_view.="<img alt=\"<?php echo \$row->title;;?>\" src=\"<?php echo (@\$row->".$key.") ? ((substr(@\$row->".$key.",0,4)=='http' || substr(@\$row->".$key.",0,1)=='/') ? @\$row->".$key." : \$base_url.@\$row->".$key.")  : \"\";?>\" width=\"120\" height=\"75\" />\r\n";
					elseif($value=="postdate")
					$info_view.="<?php echo get_time(\$row->".$key.",\"Y-m-d H:i:s\");?>\r\n";
					elseif($value=="cate" || $value=="cate_simple")
					{
						$table_name_cate8=$table_name."_".$key;
						$table_name8=str_replace("_id","",$table_name_cate8)."_cate";
						$info_view.="<?php \$cate_table=get_table_row('".$table_name8."',\$row->".$key.");  echo @\$cate_table->name;?>\r\n";
					}

					else
					$info_view.="<?php echo \$row->".$key.";?>\r\n";
				}
			}
			$column_name=($i==1) ? "title" : "name";
			$info_view.="\r\n<?php foreach (\$".$url_name."_list  as \$row){?>
<li><a href=\"<?php echo \$site_url;?><?php echo \$row->id;?>\"><?php echo \$row->".$column_name.";?></a></li>
".$pic_col."<?php }?>";
			$i++;

		}
		if(!$info)
		{
			$data=array("name"=>$table_name_zh,"url"=>$table_name,"leixing"=>$leixing,"data"=>$data_array,"data_enum"=>$data_enum,"info_control"=>$info_control,"info_view"=>base64_encode($info_view),"create_view"=>$create_view,"laravel_timestamp"=>$laravel_timestamp,"postdate"=>time());
			$insert_str=$this->db->insert_string($this->db->dbprefix.$this->table_name, $data);
		}
		else
		{
			$data=array("name"=>$table_name_zh,"url"=>$table_name,"data"=>$data_array,"data_enum"=>$data_enum,"info_control"=>$info_control,"info_view"=>base64_encode($info_view),"create_view"=>$create_view,"laravel_timestamp"=>$laravel_timestamp);
			$insert_str=$this->db->update_string($this->db->dbprefix.$this->table_name,$data,"id=".$info2->id);
		}
		$this->db->query($insert_str);
		return $this->db->insert_id();
	}
	function replace_file_view($content,$array_zh,$array_url,$table_name,$table_name_zh,$array_type)
	{
		$str_view="";
		$str_view_list="";
		$str_view_css="";
		$str_view_add="";
		$str_view_edit="";
		$str_kindEditor="";
		$model_add="";
		$model_add_str="";
		$model_add_function="";
		$model_add_array="";
		$model_edit="";
		$model_edit_str="";
		$model_edit_function="";
		$model_edit_array="";
		$kind=1;
		$edit_pic="";//上传图片编辑模块
		$i=0;
		$i_pic=1;
		$i_pic_edit=1;
		$array_type_str=implode(' ',$array_type); //table leixing int,varchar...
		$array_name_str=implode(' ',$array_url);//table colume name
		//var_dump($array_zh);die();
		foreach($array_zh as $key=>$zh)
		{
			$table_name_cate2=$table_name."_".trim($this->input->post("field_".($key+1)."_1"));
			$table_name_cate2=str_replace("_id","",$table_name_cate2)."_cate";
			if(strpos($array_type[$key],"text")===false)
			{
				if($i<11)
				$str_view.="<th class=\"main1_".$i."\">".$zh."</th>\r\n	  ";
			}

			/*$str_view_list.=($array_url[$key]=="postdate") ? "<td><?php echo get_time(\$row->".$array_url[$key].",\"Y-m-d H:i:s\");?></td>\r\n	  " : "<td><?php echo \$row->".$array_url[$key].";?></td>\r\n	  ";*/
			$str_view_css.="#main .main1_".$i."{width:9%;}\r\n";
			$str_view_add_pre=($i==1) ? ' &nbsp; <a href="javascript:window.history.go(-1)"><img src="<?php echo $base_url;?>resource/images/admin_admin/return.gif" border="0" align="absmiddle"/> 返回</a>' : "";
			//var_dump($this->input->post("field_".($key+1)."_10"));die();
			if($i>0 && $this->input->post("field_".($key+1)."_10")==="pic")
			{
				$str_view_add.="      <tr>
        <td>".$zh."</td>
        <td><input type=\"file\" name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" />".$str_view_add_pre."</td>
      </tr>\r\n";
				$str_view_list.="<td><img src=\"<?php echo \$base_url.@\$row->".$array_url[$key].";?>\" border=\"0\" height=\"23\" style=\"padding:1px;\"/></td>";
			}
			elseif(strpos($array_type[$key],"text")!==false)
			{
				$str_view_add.="      <tr>
        <td>".$zh."</td>
        <td><textarea name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" data-rule-required=\"true\"></textarea></td>
      </tr>\r\n";
				$str_kindEditor.="var editor".$kind." = K.create('#".$array_url[$key]."', seting);\r\n";
				$kind++;
			}
			elseif($this->input->post("field_".($key+1)."_10")==="cate"  || $this->input->post("field_".($key+1)."_10")==="cate_simple")
			{
				$cate_table=$this->get_cate_table($this->input->post("field_".($key+1)."_1"),$this->input->post("field_".($key+1)."_9"),$table_name,$this->input->post("field_".($key+1)."_10"));
				///******
				$str_view_add.="      <tr>
        <td>".$zh."</td>
        <td> <select name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" style=\"width:400px\" onclick=\"javascript:select_cate_load('<?php echo \$admin_url;?>','".$cate_table."','".$array_url[$key]."','post_form');\">
		<option value=\"\">请选择</option>
		<?php \$".$cate_table."=get_table_all_data('".$cate_table."',\"order by sort_order desc,id asc\");
		
		foreach(\$".$cate_table." as \$key=>\$value){
	    ?>
		<option value=\"<?php echo \$value->id;?>\"><?php echo \$value->name;?></option>
		<?php }?>
       
        </select> &nbsp; &nbsp; <a href='<?php echo \$admin_url;?>".$table_name_cate2."' target='_blank'>管理分类</a></td>
      </tr>";
				//$str_view_list.="<td><?php echo \$row->".$array_url[$key].";";
				if($i<11)
				$str_view_list.="<td><?php \$cate_table=get_table_row('".$cate_table."',\$row->".$array_url[$key].");  echo @\$cate_table->name;?></td>";

			}
			elseif($this->input->post("field_".($key+1)."_10")==="cate_more" || $this->input->post("field_".($key+1)."_10")==="cate_more_simple")
			{
				$cate_table=$this->get_cate_table($this->input->post("field_".($key+1)."_1"),$this->input->post("field_".($key+1)."_9"),$table_name,$this->input->post("field_".($key+1)."_10"));
				///******
				$str_view_add.="      <tr><td>".$zh."</td>
         <td><div style=\" position:relative\">
            <input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\"/> &nbsp; &nbsp; <a href='<?php echo \$admin_url;?>".$table_name_cate2."' target='_blank'>管理分类</a>
            <input name=\"hide_".$array_url[$key]."\" type=\"hidden\" id=\"hide_".$array_url[$key]."\" />
          </div>
          <div style=\"position:absolute;top:40px;left:40px;width:800px;height:400px; background:url('<?php echo \$base_url;?>resource/images/admin_admin/div_bg.gif');z-index:1000;display:none;\" class=\"".$array_url[$key]."_cate_list\" id=\"".$array_url[$key]."_cate_list\">
            <table  border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"table_class2\" style=\"width:750px;margin:10px 0 0 20px;\">
              <tr>
                <td colspan=\"7\" style=\"color:#8B8B8B; background:none;\"><div style=\"float:left;\">选择分类</div>
                  <div style=\"float:right;\"><img src=\"<?php echo \$base_url;?>resource/images/admin_admin/close.gif\" align=\"absmiddle\" alt=\"关闭\" onclick=\"close_div('".$array_url[$key]."_cate_list')\" style=\"cursor:pointer;\"/> </div></td>
              </tr>
              <tr>
                <td colspan=\"7\"></td>
              </tr>
              <td colspan=\"7\" class=\"y_select\" style=\"color:#FF0000\">&nbsp;</td>
              </tr>
              <tr>
                <td  colspan=\"7\"><div style=\"height:250px; overflow-y:scroll\"  id=\"".$array_url[$key]."_cate_list_content\">
                    <div style=\"margin:50px auto; text-align:center\"><img src=\"<?php echo \$base_url;?>resource/images/admin_admin/loadding.gif\" align=\"absmiddle\"/></div>
                  </div></td>
              </tr>
              <tr>
                <td colspan=\"7\" style=\"color:#8B8B8B;padding:10px 0 0 0;\" class=\"confirm_button\"><input type=\"button\" value=\"确定\" class=\"submitBtn\" onclick=\"javascript:close_div('".$array_url[$key]."_cate_list');\"/></td>
              </tr>
            </table>
          </div>
          <script type=\"text/javascript\">
\$(function ()
{
		$(\"#".$array_url[$key]."\").bind(\"click\",function ()
		{
			show_tag_div('','".$array_url[$key]."_cate_list','<?php echo \$admin_url;?>','".$cate_table."',\"".$array_url[$key]."\");
		})
})
</script></td>
      </tr>";
				//$str_view_list.="<td><?php echo \$row->".$array_url[$key].";";
				if($i<11)
				$str_view_list.="<td><?php echo ajax_show_tag('".$cate_table."',\$row->".$array_url[$key].");?></td>";

			}
			elseif($i>0 && strpos($array_url[$key],"postdate")===false)
			{
				$str_view_add.="      <tr>
        <td>".$zh."</td>
        <td><input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\"/>".$str_view_add_pre."</td>
      </tr>";
				if($i<11)
				$str_view_list.="<td><?php echo \$row->".$array_url[$key].";?></td>";
			}
			elseif($this->input->post("field_".($key+1)."_10")==="postdate")
			{
				if($i<11)
				$str_view_list.="<td><?php echo get_time(\$row->".$array_url[$key].",\"Y-m-d H:i:s\");?></td>";
			}
			elseif($this->input->post("field_".($key+1)."_10")!=="postdate")
			{
				if($i<11)
				$str_view_list.="<td><?php echo \$row->".$array_url[$key].";?></td>";
			}
			/*if($i>0 && strpos($array_type[$key],"text")===false && strpos($array_url[$key],"postdate")===false)
			$str_view_add.="<tr>
			<td>".$zh."</td>
			<td><input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\"/>".$str_view_add_pre."</td>\r\n
			</tr>";
			elseif($i>0 && strpos($array_type[$key],"text")!==false && strpos($array_url[$key],"postdate")===false)
			{
			$str_view_add.="<tr>
			<td>".$zh."</td>
			<td><textarea name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" data-rule-required=\"true\"></textarea></td>\r\n
			</tr>";
			$str_kindEditor.="var editor".$kind." = K.create('#".$array_url[$key]."', seting)\r\n";
			$kind++;
			}*/
			$str_view_edit_pre=($i==1) ? ' &nbsp; <a href="javascript:window.history.go(-1)"><img src="<?php echo $base_url;?>resource/images/admin_admin/return.gif" border="0" align="absmiddle"/> 返回</a><input name="current" type="hidden" id="current"  value="<?php echo $current;?>"/><input name="id" type="hidden" id="id"  value="<?php echo $id;?>"/>' : "";
			/*$str_view_edit.=($i>0) ? "<tr>
			<td>".$zh."</td>
			<td><input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\" value=\"<?php echo \$this_data->".$array_url[$key]."; ?>\"/>".$str_view_edit_pre."</td>
			</tr>\r\n" : "";*/
			if($i>0 && $this->input->post("field_".($key+1)."_10")==="pic")
			{
				$str_view_edit.="<tr>
        <td>".$zh."</td>
        <td><?php if(@\$this_data->".$array_url[$key].") {?>
		<div class=\"images_display\">
		<img src=\"<?php echo \$base_url.\$this_data->".$array_url[$key].";?>?=<?php echo microtime();?>\"   style=\"max-width:700px;max-height:1200px;\" border=\"0\"/>   &nbsp;	</div>
		<div  class=\"images_upload\" style=\"display:block;\">
          <input type=\"file\" name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" />
		  
		  </div>
		<?php }else {?>
		
          <input type=\"file\" name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" />
		  
		  <?php }?>".$str_view_edit_pre."</td>\r\n
      </tr>";
			}
			elseif($this->input->post("field_".($key+1)."_10")==="cate" || $this->input->post("field_".($key+1)."_10")==="cate_simple")
			{
				$cate_table=$this->get_cate_table($this->input->post("field_".($key+1)."_1"),$this->input->post("field_".($key+1)."_9"),$table_name,$this->input->post("field_".($key+1)."_10"));
				///******
				$str_view_edit.="  <tr>
        <td>".$zh."</td>
        <td> <select name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" style=\"width:400px\" onclick=\"javascript:select_cate_load('<?php echo \$admin_url;?>','".$cate_table."','".$array_url[$key]."','post_form');\">
        <option value=\"\">请选择</option>
		<?php \$".$cate_table."=get_table_all_data('".$cate_table."',\"order by sort_order desc,id asc\");
		foreach(\$".$cate_table." as \$key=>\$value){
		\$select=(\$value->id==\$this_data->".$this->input->post("field_".($key+1)."_1").") ? 'selected=\"selected\"' : '';
	    ?>
		<option value=\"<?php echo \$value->id;?>\" <?php echo \$select;?>><?php echo \$value->name;?> </option>
		<?php }?>
        </select> &nbsp; &nbsp; <a href='<?php echo \$admin_url;?>".$table_name_cate2."' target='_blank'>管理分类</a></td>
      </tr>";

			}
			elseif($this->input->post("field_".($key+1)."_10")==="cate_more" || $this->input->post("field_".($key+1)."_10")==="cate_more_simple")
			{
				$cate_table=$this->get_cate_table($this->input->post("field_".($key+1)."_1"),$this->input->post("field_".($key+1)."_9"),$table_name,$this->input->post("field_".($key+1)."_10"));
				///******
				$str_view_edit.="  <tr><td>".$zh."</td>
         <td><div style=\" position:relative\">
            <input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\" value=\"<?php echo ajax_show_tag(\"".$cate_table."\",\$this_data->".$array_url[$key].");?>\"/> &nbsp; &nbsp; <a href='<?php echo \$admin_url;?>".$table_name_cate2."' target='_blank'>管理分类</a>
            <input name=\"hide_".$array_url[$key]."\" type=\"hidden\" id=\"hide_".$array_url[$key]."\" value=\"<?php echo \$this_data->".$array_url[$key].";?>\" />
          </div>
          <div style=\"position:absolute;top:40px;left:40px;width:800px;height:400px; background:url('<?php echo \$base_url;?>resource/images/admin_admin/div_bg.gif');z-index:1000;display:none;\" class=\"".$array_url[$key]."_cate_list\" id=\"".$array_url[$key]."_cate_list\">
            <table  border=\"0\" cellpadding=\"1\" cellspacing=\"1\" class=\"table_class2\" style=\"width:750px;margin:10px 0 0 20px;\">
              <tr>
                <td colspan=\"7\" style=\"color:#8B8B8B; background:none;\"><div style=\"float:left;\">选择分类</div>
                  <div style=\"float:right;\"><img src=\"<?php echo \$base_url;?>resource/images/admin_admin/close.gif\" align=\"absmiddle\" alt=\"关闭\" onclick=\"close_div('".$array_url[$key]."_cate_list')\" style=\"cursor:pointer;\"/> </div></td>
              </tr>
              <tr>
                <td colspan=\"7\"></td>
              </tr>
              <td colspan=\"7\" class=\"y_select\" style=\"color:#FF0000\">&nbsp;</td>
              </tr>
              <tr>
                <td  colspan=\"7\"><div style=\"height:250px; overflow-y:scroll\"  id=\"".$array_url[$key]."_cate_list_content\">
                    <div style=\"margin:50px auto; text-align:center\"><img src=\"<?php echo \$base_url;?>resource/images/admin_admin/loadding.gif\" align=\"absmiddle\"/></div>
                  </div></td>
              </tr>
              <tr>
                <td colspan=\"7\" style=\"color:#8B8B8B;padding:10px 0 0 0;\" class=\"confirm_button\"><input type=\"button\" value=\"确定\" class=\"submitBtn\" onclick=\"javascript:close_div('".$array_url[$key]."_cate_list');\"/></td>
              </tr>
            </table>
          </div>
          <script type=\"text/javascript\">
\$(function ()
{
		\$(\"#".$array_url[$key]."\").bind(\"click\",function ()
		{
			show_tag_div('<?php echo str_replace(\",\",\"_\",\$this_data->".$array_url[$key].");?>','".$array_url[$key]."_cate_list','<?php echo \$admin_url;?>','".$cate_table."',\"".$array_url[$key]."\");
		})
})
</script></td>
      </tr>";

			}
			elseif($i>0 && strpos($array_type[$key],"text")!==false)
			{
				$str_view_edit.="<tr>
        <td>".$zh."</td>
        <td><textarea name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" data-rule-required=\"true\"><?php echo \$this_data->".$array_url[$key]." ?></textarea>".$str_view_edit_pre."</td>\r\n
      </tr>";
			}
			elseif($i>0 && strpos($array_url[$key],"postdate")===false)
			{
				$str_view_edit.="<tr>
        <td>".$zh."</td>
        <td><input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\" value=\"<?php echo \$this_data->".$array_url[$key]."; ?>\"/>".$str_view_edit_pre."</td>\r\n
      </tr>";
			}

			/*if($i>0 && strpos($array_type[$key],"text")===false && strpos($array_url[$key],"postdate")===false)
			$str_view_edit.="<tr>
			<td>".$zh."</td>
			<td><input name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" type=\"text\" class=\"input1\" style=\"width:400px;height:20px;\" value=\"<?php echo \$this_data->".$array_url[$key]."; ?>\"/>".$str_view_edit_pre."</td>\r\n
			</tr>";
			elseif($i>0 && strpos($array_type[$key],"text")!==false && strpos($array_url[$key],"postdate")===false)
			$str_view_edit.="<tr>
			<td>".$zh."</td>
			<td><textarea name=\"".$array_url[$key]."\" id=\"".$array_url[$key]."\" data-rule-required=\"true\"><?php echo \$this_data->".$array_url[$key]." ?></textarea></td>\r\n
			</tr>";*/
			/***model_add***/
			if($i>0 && $this->input->post("field_".($key+1)."_10")==="pic")
			{
				$model_add.="		/**上传图片***/
		\$".$array_url[$key]."='';
		\$".$array_url[$key]."_small='';
		\$new_crop".$i_pic."='';
		if(@\$_FILES['".$array_url[$key]."']['name'])
		{
			\$new_crop".$i_pic."=get_upload_dir().\"/\".create_pic(\"".$array_url[$key]."\");
			\$pic_list = @getimagesize(\$new_crop".$i_pic.");
			\$width=\$pic_list[0];
			\$height=\$pic_list[1];
			\$this->load->library(\"image_moo\");
			if(\$width>350 || \$height>350)
			{
				\$".$array_url[$key]."=substr(\$new_crop".$i_pic.",0,strrpos(\$new_crop".$i_pic.",\".\")).\"e\".substr(\$new_crop".$i_pic.",strrpos(\$new_crop".$i_pic.",\".\"));
				\$sing=\$this->image_moo->load(\$new_crop".$i_pic.")->resize(350,350)->save(\$".$array_url[$key].",true);
			}
			else
			\$".$array_url[$key]."=\$new_crop".$i_pic.";
			/***生成小图***/
			//\$pic_small=substr(\$".$array_url[$key].",0,strrpos(\$".$array_url[$key].",\".\")).\"s\".substr(\$".$array_url[$key].",strrpos(\$".$array_url[$key].",\".\"));
			//\$sing=\$this->image_moo->load(\$".$array_url[$key].")->resize(100,100)->save(\$".$array_url[$key]."_small,true);
			//@unlink(\$".$array_url[$key].");
		}\r\n";
				$model_add_str.="\$".$array_url[$key].",";
				//"name"=>$name,
				$model_add_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
				$i_pic++;
			}

			elseif($i>0 && ($this->input->post("field_".($key+1)."_10")=='cate_more' || $this->input->post("field_".($key+1)."_10")=='cate_more_simple'))
			{
				$model_add.="		\$".$array_url[$key]."=\$this->input->post('hide_".$array_url[$key]."');\r\n";
				$model_add_str.="\$".$array_url[$key].",";
				//"name"=>$name,
				$model_add_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
				$model_edit_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
			}
			elseif($i>0 && strpos($array_url[$key],"postdate")===false)
			{
				$model_add.="		\$".$array_url[$key]."=\$this->input->post('".$array_url[$key]."');\r\n";
				$model_add_str.="\$".$array_url[$key].",";
				//"name"=>$name,
				$model_add_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
				$model_edit_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
			}
			/*		if($i>0 && strpos($array_url[$key],"postdate")===false)
			{
			$model_add.="		\$".$array_url[$key]."=\$this->input->post('".$array_url[$key]."');\r\n";
			$model_add_str.="\$".$array_url[$key].",";
			//"name"=>$name,
			$model_add_array.="\"".$array_url[$key]."\"=>\$".$array_url[$key].",";
			}*/
			if($i>0 && $this->input->post("field_".($key+1)."_10")==="pic")
			{
				$edit_pic.="/**上传图片***/
		\$".$array_url[$key]."='';
		\$pic_small".$i_pic_edit."='';
		\$new_crop".$i_pic_edit."='';
		\$i_array=array();
		if(\$_FILES['".$array_url[$key]."']['name'])
		{
			\$new_crop".$i_pic_edit."=get_upload_dir().\"/\".create_pic(\"".$array_url[$key]."\");
			\$pic_list = @getimagesize(\$new_crop".$i_pic_edit.");
			\$width=\$pic_list[0];
			\$height=\$pic_list[1];
			\$this->load->library(\"image_moo\");
			if(\$width>350 || \$height>350)
			{
				\$".$array_url[$key]."=substr(\$new_crop".$i_pic_edit.",0,strrpos(\$new_crop".$i_pic_edit.",\".\")).\"e\".substr(\$new_crop".$i_pic_edit.",strrpos(\$new_crop".$i_pic_edit.",\".\"));
				\$sing=\$this->image_moo->load(\$new_crop".$i_pic_edit.")->resize(350,350)->save(\$".$array_url[$key].",true);
			}
			else
			{
				\$".$array_url[$key]."=\$new_crop".$i_pic_edit.";
			}
			/***生成小图***/
			//\$pic_small=substr(\$".$array_url[$key].",0,strrpos(\$".$array_url[$key].",\".\")).\"s\".substr(\$".$array_url[$key].",strrpos(\$".$array_url[$key].",\".\"));
			//\$sing=\$this->image_moo->load(\$".$array_url[$key].")->resize(100,100)->save(\$pic_small".$i_pic_edit.",true);
			//@unlink(\$pic);
			\$i_array=array(\"".$array_url[$key]."\"=>\$".$array_url[$key].");
			//\$i_array=array(\"".$array_url[$key]."\"=>\$new_crop".$i_pic_edit.",\"pic_small\"=>\$pic_small".$i_pic_edit.");
			\$data=array_merge(\$data,\$i_array);
		}\r\n";
				$i_pic_edit++;
			}
			elseif($i>0 && ($this->input->post("field_".($key+1)."_10")=='cate_more' || $this->input->post("field_".($key+1)."_10")=='cate_more_simple'))
			{
				$model_edit.="		\$".$array_url[$key]."=\$this->input->post('hide_".$array_url[$key]."');\r\n";
				$model_edit_str.="\$".$array_url[$key].",";
			}
			elseif(strpos($array_url[$key],"postdate")===false)
			{
				$model_edit.="		\$".$array_url[$key]."=\$this->input->post('".$array_url[$key]."');\r\n";
				$model_edit_str.="\$".$array_url[$key].",";
				//"name"=>$name,
			}
			/*if(strpos($array_url[$key],"postdate")===false)
			{
			$model_edit.="		\$".$array_url[$key]."=\$this->input->post('".$array_url[$key]."');\r\n";
			$model_edit_str.="\$".$array_url[$key].",";
			//"name"=>$name,
			}*/
			$i++;
		}
		$model_edit.="		\$current=\$this->input->post('current');\r\n";
		$model_add_str=(substr($model_add_str,-1)==",") ? substr($model_add_str,0,strlen($model_add_str)-1) : $model_add_str;
		$model_edit_str=(substr($model_edit_str,-1)==",") ? substr($model_edit_str,0,strlen($model_edit_str)-1) : $model_edit_str;
		$model_add_array=(substr($model_add_array,-1)==",") ? substr($model_add_array,0,strlen($model_add_array)-1) : $model_add_array;
		$model_edit_array=(substr($model_edit_array,-1)==",") ? substr($model_edit_array,0,strlen($model_edit_array)-1) : $model_edit_array;
		//$model_edit_array=$model_add_array;
		$model_add_array=strpos($array_name_str,"postdate") ? $model_add_array.",\"postdate\"=>time()" : $model_add_array;
		$model_add.="		if($".$array_url[1].")
		{
			\$this->sava_this_page(".$model_add_str.");
		}";
		$model_edit.="		if($".$array_url[0].")
		{
			\$this->save_info(".$model_edit_str.",\$current);
		}";
		$model_add_function="sava_this_page(".$model_add_str.")";
		$model_add_array="\$data=array(".$model_add_array.");";
		$model_edit_function="save_info(".$model_edit_str.",\$current)";
		$model_edit_array="\$data=array(".$model_edit_array.");";
		//kindeditor
		if(strpos($array_type_str,"text"))
		{
			$kindeditor_str='<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>';
			$kindeditor_js='<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        \'source\', \'undo\', \'redo\', \'plainpaste\', \'plainpaste\', \'wordpaste\', \'clearhtml\', \'quickformat\', \'selectall\', \'fullscreen\', \'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\', \'hr\',
        \'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
        \'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\', \'unlink\', \'baidumap\'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 330,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               '.$str_kindEditor.'
                              
                                K(\'a.insertimage\').click(function (e) {
                                    editor1.loadPlugin(\'smimage\', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                                $(e.target).parent().prev().val(url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script>';
			$content=str_replace("<kindeditor_js>",$kindeditor_js,$content);
			$content=str_replace("<kindeditor>",$kindeditor_str,$content);
		}

		/***model_edit***/
		$content=str_replace("<table_col>",$str_view,$content);
		$content=str_replace("<table_css>",$str_view_css,$content);
		$content=str_replace("<table_name_zh>",$table_name_zh,$content);
		$content=str_replace("<table_name_zh_add>",str_replace("管理","",$table_name_zh),$content);
		$content=str_replace("<view_add>",$str_view_add,$content);
		$content=str_replace("<view_edit>",$str_view_edit,$content);
		$content=str_replace("<table_view_list>",$str_view_list,$content);
		$content=str_replace("<model_add>",$model_add,$content);
		$content=str_replace("<model_add_function>",$model_add_function,$content);
		$content=str_replace("<model_add_array>",$model_add_array,$content);
		$content=str_replace("<model_edit>",$model_edit,$content);
		$content=str_replace("<model_edit_function>",$model_edit_function,$content);
		$content=str_replace("<model_edit_array>",$model_edit_array,$content);
		$content=str_replace("<model_edit_pic>",$edit_pic,$content);
		return $content;
	}
	function show_category()
	{
		/*	$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin");
		foreach ($result->result() as $row)
		{
		$data=array("sort_order"=>$row->id);
		$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$row->id);
		$this->db->query($insert_str);
		}*/
		$str="";
		$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin order by sort_order desc");
		$this->load->library('tree');
		foreach ($result->result() as $row)
		{
			$this->tree->setNode($row->id, $row->parent_id,$row->name,$row->url,$row->sort_order);
		}
		$category = $this->tree->getChilds();
		$t_line=array();
		$t_array=array();
		$t_id=array();
		foreach ($category as $key=>$id){
			array_push($t_line,$this->tree->getLayer($id, '|------'));
			array_push($t_array,$this->tree->getValue($id));
			array_push($t_id,$this->tree->getId($id));
		}
		foreach ($t_array as $c_key=>$c_value)
		{
			$str.='<option value="'.$t_id[$c_key].'">'.$t_line[$c_key].$c_value.'</option>';
		}
		return $str;
	}
	function menu_insert_cate($insert_id,$array_cate_column,$array_cate_column_zh,$table_name)
	{
		$menu_row=get_table_row("menu_admin",$insert_id,"id");
		//var_dump($array_cate_column_zh);die();
		$parent_id=($menu_row->parent_id==0) ? $menu_row->id : $menu_row->parent_id;
		$sort_order=$menu_row->sort_order;
		$i=1;
		foreach($array_cate_column as $key=>$value)
		{
			$sort_order--;
			$table_name_cate=$table_name."_".$value;
			$table_url=str_replace("_id","",$table_name_cate)."_cate";
			$menu_row_exist=get_table_row("menu_admin",$table_url,"url");
			if($menu_row_exist)
			{
				$array_cate_column_zh[$key]=str_replace("管理","",$array_cate_column_zh[$key])."管理";
				$data=array("name"=>$array_cate_column_zh[$key],"parent_id"=>$parent_id);
				$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$insert_id);
				$this->db->query($insert_str);
			}
			else
			{
				$array_cate_column_zh[$key]=str_replace("管理","",$array_cate_column_zh[$key])."管理";
				$data=array("name"=>$array_cate_column_zh[$key],"url"=>$table_url,"parent_id"=>$parent_id,"sort_order"=>$sort_order,"postdate"=>time());
				$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
				$this->db->query($insert_str);
			}
			$i++;
		}
	}
	function insert_menu_or_update($table_name,$table_name_zh,$array_cate_column,$array_cate_column_zh,$create_view)
	{
		//$menu_row_exist=get_table_row("menu_admin",$table_url,"url");
		$category=$this->input->post("category");
		if($category)
		{
			$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where id=?",array($category));
			$row =$result->row();
			$parent_id=$row->parent_id;
		}
		$parent_id=($category) ? $parent_id : 0;
		//var_dump($parent_id);die();
		$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where url=?",array($table_name));
		$info=$result->num_rows();
		$sort_order=1000;
		if(!$info)
		{
			$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where parent_id=0 order by sort_order asc limit 0,1");
			$row =$result->row();
			$sort_order=$row->sort_order;
			$sort_order=$sort_order-10;
			$table_name_zh2=($create_view!="create_view") ? str_replace("管理","",$table_name_zh)."管理" : $table_name_zh;
			$data=array("name"=>$table_name_zh2,"sort_order"=>$sort_order,"url"=>$table_name,"postdate"=>time(),"parent_id"=>$parent_id,"status"=>2);
			$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
			//echo $insert_str."<BR>";
			$this->db->query($insert_str);
			$parent_id=$this->db->insert_id();
			$sort_order=$sort_order-10;
			$table_name_zh3=($create_view!="create_view") ? str_replace("管理","",$table_name_zh)."管理" : $table_name_zh;
			$data=array("name"=>$table_name_zh3,"sort_order"=>$sort_order,"url"=>$table_name,"postdate"=>time(),"parent_id"=>$parent_id,"status"=>3);
			$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
			$this->db->query($insert_str);
		}
		else
		{
			$table_name_zh4=($create_view!="create_view") ? str_replace("管理","",$table_name_zh)."管理" : $table_name_zh;
			$data=array("name"=>$table_name_zh4);
			$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"url='".$table_name."'");
			$this->db->query($insert_str);
		}
		foreach($array_cate_column as $key=>$value)
		{
			$sort_order++;
			$table_name_cate=$table_name."_".$value;
			$table_url=str_replace("_id","",$table_name_cate)."_cate";
			$menu_row_exist=get_table_row("menu_admin",$table_url,"url");
			$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where parent_id=0 and url='".$table_name."' order by sort_order asc limit 0,1");
			$row =$result->row();
			$parent_id=$row->id;
			if($menu_row_exist)
			{
				$array_cate_column_zh[$key]=($create_view!="create_view") ? str_replace("管理","",$array_cate_column_zh[$key])."管理" : $table_name_zh;
				$data=array("name"=>$array_cate_column_zh[$key],"parent_id"=>$parent_id);
				$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$menu_row_exist->id);
				$this->db->query($insert_str);
			}
			else
			{
				$sort_order=$sort_order-10;
				$sort_order=($sort_order>=0) ? $sort_order : 0;
				$array_cate_column_zh[$key]=($create_view!="create_view") ? str_replace("管理","",$array_cate_column_zh[$key])."管理" : $table_name_zh;
				$data=array("name"=>$array_cate_column_zh[$key],"url"=>$table_url,"parent_id"=>$parent_id,"sort_order"=>$sort_order,"postdate"=>time(),"status"=>100);
				$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
				$this->db->query($insert_str);
			}
		}
		//$this->db->query($insert_str);
		//die();
	}
	function menu_insert($category,$table_name,$table_name_zh,$array_cate_column,$array_cate_column_zh,$table_name)
	{
		$category=$this->input->post("category");
		if($category)
		{
			$category_row=get_table_row("menu_admin",$category);
			$sort_order=$category_row->sort_order;
			$parent_id=$category_row->parent_id;
		}
		else
		{
			$sort_order=0;
			$parent_id=0;
		}
		if($this->input->post("url6"))
		{
			$mod_row=get_table_row("module",$this->input->post("url6"),"id");
			$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where url=? order by id desc limit 0,1",array($mod_row->url));
			$row=$result->result();
			if($row)
			{
				$table_name_zh2=str_replace("管理","",$table_name_zh)."管理";
				$data=array("name"=>$table_name_zh2);
				$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$row[0]->id);
				$this->db->query($insert_str);
				$this->menu_insert_cate($row[0]->id,$array_cate_column,$array_cate_column_zh,$table_name);
			}
		}
		elseif(!$this->input->post("url6") || !$row)
		{
			if($parent_id==0 || $category=="")
			{
				$category=($category=="") ? 0 : $category;
				$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where id>".$category);
				foreach ($result->result() as $row)
				{
					$data=array("sort_order"=>intval($row->sort_order)+2);
					$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$row->id);
					$this->db->query($insert_str);
				}
				$sort_order=($category=="") ? 1000 : $sort_order+1;
				$table_name_zh2=str_replace("管理","",$table_name_zh)."管理";
				$data=array("name"=>$table_name_zh2,"sort_order"=>$sort_order,"url"=>$table_name,"postdate"=>time(),"parent_id"=>0);
				$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
				$this->db->query($insert_str);
				$insert_id=$this->db->insert_id();
				$sort_order=($category=="") ? 0 : $sort_order+2;
				$table_name_zh2=str_replace("管理","",$table_name_zh)."管理";
				$data=array("name"=>$table_name_zh2,"sort_order"=>$sort_order,"url"=>$table_name,"postdate"=>time(),"parent_id"=>$insert_id);
				$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
				$this->db->query($insert_str);
				$insert_id=$this->db->insert_id();
				$this->menu_insert_cate($insert_id,$array_cate_column,$array_cate_column_zh,$table_name);
			}
			elseif($parent_id>0)
			{
				$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where id>".$category);
				foreach ($result->result() as $row)
				{
					$data=array("sort_order"=>intval($row->sort_order)+1);
					$insert_str=$this->db->update_string($this->db->dbprefix."menu_admin",$data,"id=".$row->id);
					$this->db->query($insert_str);
				}
				$table_name_zh2=str_replace("管理","",$table_name_zh)."管理";
				$data=array("name"=>$table_name_zh2,"sort_order"=>$sort_order+1,"url"=>$table_name,"postdate"=>time(),"parent_id"=>$parent_id);
				$insert_str=$this->db->insert_string($this->db->dbprefix."menu_admin", $data);
				$this->db->query($insert_str);
				$insert_id=$this->db->insert_id();
				$this->menu_insert_cate($insert_id,$array_cate_column,$array_cate_column_zh);
			}
		}
	}
	function ajax_valid2()
	{
		$table_structure=str_replace("`","",trim($this->input->post("table_structure")));
		if(preg_match("/CREATE TABLE IF NOT EXISTS([^\(]*)\(.*/is",$table_structure))
		{
			$table_name=preg_replace("/CREATE TABLE IF NOT EXISTS([^\(]*)\(.*/is","\$1",$table_structure);
		}
		elseif(preg_match("/CREATE TABLE ([^\(]*)\(.*/is",$table_structure))
		{
			$table_name=preg_replace("/CREATE TABLE ([^\(]*)\(.*/is","\$1",$table_structure);
		}
		$table_name=trim(str_replace("site_","",$table_name));
		$info='';
		if($table_name)
		{
			$result=$this->db->query("select * from ".$this->db->dbprefix."module where url =?",array($table_name));
			$info=$result->num_rows();
			/*if(strpos($table_structure,"COMMENT")===false)
			{
			$info="db_no_comment";
			}*/
			if($info<=0)
			{
				$check=$this->db->query($this->input->post("table_structure"));
				var_dump($check);
				if(!$check)
				{
					$info="db_error";
				}
			}
			echo $info;die();
		}
		else
		echo "db_null";die();
	}
	function ajax_valid()
	{
		$table_name=trim($this->input->post("table_name"));
		$info='';
		if(!preg_match("/^[_a-zA-Z]+[_0-9a-zA-Z-]{1,100}$/i",$table_name))
		{
			$info="bad";echo $info;die();
		}
		else
		{
			$result=$this->db->query("select * from ".$this->db->dbprefix."module where url =?",array($table_name));
			$info=$result->num_rows();
			$url6=$this->input->post("url6");
			if($url6)
			{
				$mod_row=get_table_row("module",$url6,"id");
				if($mod_row->url==$table_name)
				{
					@$this->db->query("DROP TABLE IF EXISTS `site_".$table_name."`");
					$info=0;
					$result=$this->db->query("select * from ".$this->db->dbprefix."menu_admin where parent_id=0 and url='".$table_name."' order by sort_order asc limit 0,1");
					$row =$result->row();
					$parent_id=$row->id;
					$sql ="delete from ".$this->db->dbprefix."menu_admin where parent_id=".$parent_id." and url!='".$table_name."'";
					$this->db->query($sql);
				}
			}
			if($info<=0)
			{
				$str='CREATE TABLE IF NOT EXISTS `site_'.$table_name.'` (';

				for($i=1;$i<=1000;$i++)
				{
					for($j=1;$j<=9;$j++)
					{
						$str_bian=($j==2) ? "" : " ";
						$field_input=trim($this->input->post("field_".$i."_".$j));
						//$field_input=($j==4 && $field_input) ? "character set utf8 COLLATE ".$field_input : $field_input;
						$field_input=($j==4 && $field_input) ? "" : $field_input;
						$field_input=($j==6 && $field_input=="NULL") ? ((strpos($this->input->post("field_".$i."_2"),"text")===false) ? "default " : "") : $field_input;
						if($j==7)
						{
							if(!$field_input && $this->input->post("field_".$i."_6")=="NULL")
							{
								if(stripos($this->input->post("field_".$i."_2"),"int")!==false)
								$field_input=0;
								elseif(stripos($this->input->post("field_".$i."_2"),"char")!==false)
								$field_input="' '";
								elseif(stripos($this->input->post("field_".$i."_2"),"text")!==false)
								$field_input="";
							}
							elseif(($field_input===0 || $field_input==="0" || $field_input!=="" || $field_input>0) && $this->input->post("field_".$i."_6")=="NOT NULL")
							{
								$field_input=" DEFAULT '".$field_input."' ";
							}
							elseif($field_input  && $this->input->post("field_".$i."_6")=="NULL")
							{
								if($field_input=="null" && stripos($this->input->post("field_".$i."_2"),"int")!==false)
								$field_input=0;
							}
						}
						/*		if($j==7 && $field_input)
						{
						if(stripos($this->input->post("field_".$i."_2"),"text")===false)
						{
						$field_input="'".$field_input."'";
						}
						elseif(stripos($this->input->post("field_".$i."_2"),"int")!==false)
						{
						var_dump($field_input."****************".$this->input->post("field_".$i."_1"));
						$field_input=intval($field_input);

						}
						else
						$field_input="";
						}*/
						//$field_input=($j==7 && $field_input) ? ((strpos($this->input->post("field_".$i."_2"),"text")===false) ? "'".$field_input."'" : "")  : $field_input;
						//$field_input=($j==7 && $field_input) ? ((stripos($this->input->post("field_".$i."_2"),"int")!==false) ? intval($field_input) : "")  : $field_input;
						$field_input=($j==9 && $field_input) ? "COMMENT '".$field_input."'" : $field_input;
						if($j==3 && $field_input && !strpos($this->input->post("field_".$i."_2"),"text"))
						$str.="(".trim($field_input).")".$str_bian;
						elseif($j==3 && $field_input && strpos($this->input->post("field_".$i."_2"),"text"))
						$str.=$str_bian;
						else
						$str.=$field_input.$str_bian;
					}
					if(!$this->input->post("field_".$i."_1"))
					break;
					else
					{
						$str.=",\n\r";
					}

				}
				$str.='  PRIMARY KEY  (`'.$this->input->post("field_1_1").'`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
				echo $str;die();
				$check=$this->db->query($str);
				if(!$check)
				{
					$info="db_error";
				}
				echo $info;die();
			}
			else
			echo $info;die();
		}
	}
	function get_yii2_class_name($table_name)
	{
		$str=explode("_",$table_name);
		$str_line="";
		foreach($str as $str_v)
		{
			$str_line.=ucfirst($str_v);
		}
		return $str_line;
	}
	function replace_blank_row($content)
	{
		$content=preg_replace("/\s+$/m","",$content);
		return $content;
	}
	function get_structure_data($temp_array)
	{
		$array_f=array();
		$array_url=array();
		//foreach($temp_array as $)
		$i=1;
		foreach($temp_array as $row)
		{
			$row=strtolower(trim($row));
			$row=str_replace("`","",$row);
			if(stripos($row,"KEY ")===false)
			{
				$str1=preg_replace("/^([^ ]*) .*$/is","\$1",$row);
				$str2_temp=preg_replace("/^[^ ]* ([^ ]*) .*$/is","\$1",$row);
				$str2_temp_ex=explode(" ",$str2_temp);
				if(strpos($str2_temp,")"))
				$str2=preg_replace("/^([^(]*)\(.*$/is","\$1",$str2_temp);
				elseif(count($str2_temp_ex)==2)
				$str2=$str2_temp_ex[1];
				else
				$str2=(strpos($str2_temp," ")) ? $str2_temp_ex[1] : $str2_temp;
				//$str2=(strpos($str2_temp,")")) ? preg_replace("/^([^(]*)\(.*$/is","\$1",$str2_temp) : preg_replace("/^[^ ]* ([^ ]*) .*$/is","\$1",$str2_temp);
				$str3=(stripos($row,"(")) ? preg_replace("/^.*\((\d+)\).*$/is","\$1",$str2_temp) : "";
				$str3=preg_replace("/^[^\d]+(\d+)\*\*(\d+)[^\d]+$/is","$1,$2",$str3);

				$str4=(stripos($row,"utf8_general_ci")) ? 'utf8_general_ci' : "";
				$str5=(stripos($row,"unsigned")) ? 'unsigned' : "";
				$str6=(stripos($row,"NOT NULL")) ? 'NOT NULL' : "NULL";
				$str6=(count($str2_temp_ex)==2) ? "NOT NULL" : $str6;
				$str7=($str6=="NULL") ? preg_replace("/^.*default[^ ]* ([^ ]*).*$/is","\$1",$row) : "";
				$str7=str_replace(array("'","\""),"",$str7);
				$str7=(count($str2_temp_ex)>1) ? "" : $str7;
				$str7=($str2==$str2_temp) ? "" : $str7;


				$str8=(stripos($row,"auto_increment")) ? 'auto_increment' : "";
				$str9=(stripos($row,"COMMENT")) ? preg_replace("/^.*COMMENT[^ ]* ([^ ]*).*$/is","\$1",$row) : $str1;
				$str9=str_replace(array("'","\""),"",$str9);
				array_push($array_url,$str1);
				/*echo "str1 ".$str1."<BR>";
				echo "str2 ".$str2."<BR>";
				echo "str3 ".$str3."<BR>";
				echo "str4 ".$str4."<BR>";
				echo "str5 ".$str5."<BR>";
				echo "str6 ".$str6."<BR>";
				echo "str7 ".$str7."<BR>";
				echo "str8 ".$str8."<BR>";
				echo "str9 ".$str9."<BR>";
				echo "**********************<BR>";*/
				$str_last=($str1=="info") ? 'editor' : "";
				$str_last=($str1=="postdate") ? 'postdate' : $str_last;
				$str_last=($str1=="cate") ? 'cate' : $str_last;
				$str_last=($str1=="pic") ? 'pic' : $str_last;
				$str10=$str9;
				$str11=($i==2) ? array("require") : "";
				$str12=($i!=1 && $str_last!="postdate") ? "请输入".$str10 : "";
				$str13="input-xlarge";
				$str14=2;
				$str15=($str_last=="editoer" || $i>11) ? 2 : 1;
				$str16=($str_last=="postdate") ? 2 : 1;
				$array_f[$str1]=array($str1,$str2,$str3,$str4,$str5,$str6,$str7,$str8,$str9,$str_last,$str10,$str11,$str12,$str13,$str14,$str15,$str16);
				$i++;
			}
			else
			break;

		}
		return array($array_f,$array_url);
	}
	function get_table_field($input_data,$type)
	{
		$column="";
		$i=1;
		$category=array("cate","pic","postdate","click");
		if(in_array($type,$category))
		{
			foreach($input_data as $key=>$value)
			{
				if(strpos($value[9],$type)!==false)
				{
					$column=$value[0];
					break;
				}
				elseif($i==1)
				$column=$value[1];
				$i++;
			}
		}
		else
		{
			foreach($input_data as $key=>$value)
			{
				if(strpos($value[1],$type)!==false)
				{
					$column=$value[0];
					break;
				}
				elseif($i==1)
				$column=$value[0];
				$i++;
			}
		}
		return $column;
	}
	function insert_template_name($table_name,$value,$type="wap")
	{
		if($type=="wap")
		{
			$insert_str=$this->db->update_string($this->db->dbprefix."module",array("template_wap"=>$value),"url=?");
			$this->db->query($insert_str,array($table_name));
		}
		else
		{
			$insert_str=$this->db->update_string($this->db->dbprefix."module",array("template_site"=>$value),"url=?");
			$this->db->query($insert_str,array($table_name));
		}
		$value=explode(":",$value);
		return array(@$value[0],@$value[1]);
	}
	/**获取指定的列**/
	function get_table_locate_column($table_name,$type="int",$index=0)
	{
		$result=$this->db->query("select * from ".$this->db->dbprefix."module where url='".$table_name."'");
		$row =$result->row();
		$data=json_decode($row->data);
		$i=0;
		$column_first="";
		foreach($data as $key=>$value)
		{
			$column_name=$value[0];
			$column_type=$value[1];
			$column_first=($i==0) ? $column_name : $column_first;
			if(strpos($column_type,$type)!==false)
			{
				if($index==$i)
				{
					return $column_name;
				}
				$i++;
			}
		}
		return $column_first;
	}
	function get_pic_multiple_table($table_name2)
	{
		$table_name=$table_name2."_pic_multiple";
		$result=$this->db->query("SHOW TABLES LIKE '".$this->db->dbprefix.$table_name."'");
		$info=$result->num_rows();
		if(!$info)
		{
			$sql="CREATE TABLE IF NOT EXISTS `site_".$table_name."` (
		     `id` int(11) NOT NULL auto_increment COMMENT 'id',
		     `pid` int(10) NOT NULL COMMENT '产品Id',
		     `pic` varchar(300) NOT NULL COMMENT '图片地址',
		     `title` varchar(300) NOT NULL COMMENT '图片标题',
		     `description` varchar(300) NOT NULL COMMENT '图片描述',
		     `sort_order` int(10) NOT NULL default '0' COMMENT '排序',
		     `status` tinyint(1) NOT NULL default '0' COMMENT '0为上传没保存，1为成功保存',
		     `postdate` varchar(200) NOT NULL COMMENT '前台是否显示',
			  PRIMARY KEY  (`id`),
			  KEY `pid` (`pid`),
			  KEY `sort_order` (`sort_order`),
			  KEY `status` (`status`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
			$this->db->query($sql);
		}
		return $table_name;
	}
	function get_cate_table($column_name,$column_name_zh,$table_name2,$type)
	{
		$table_name_cate=$table_name2."_".$column_name;
		$table_name=str_replace("_id","",$table_name_cate)."_cate";
		$this->db->query("drop TABLE '".$this->db->dbprefix.$table_name."'");
		$result=$this->db->query("SHOW TABLES LIKE '".$this->db->dbprefix.$table_name."'");
		$info=$result->num_rows();
		/*	if(!$info)
		{*/
		if($type=="cate_simple" || $type=="cate_more_simple")
		{
			$sql="CREATE TABLE IF NOT EXISTS `site_".$table_name."` (
  			`id` int(11) NOT NULL auto_increment COMMENT 'id',
  			`name` varchar(20) NOT NULL COMMENT '名称',
  			`parent_id` int(10) default '0' COMMENT '父类',
  			`is_show` tinyint(1) NOT NULL default '1' COMMENT '审核',
  			`sort_order` int(3) default '0' COMMENT '排序',
  			`postdate` int(10) unsigned default '0' COMMENT '添加时间',
  			`info` varchar(255) NOT NULL COMMENT '备注',
  			PRIMARY KEY  (`id`)
  			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			$this->db->query($sql);
			$leixing="category_simple";
		}
		else
		{
			$sql="CREATE TABLE IF NOT EXISTS `site_".$table_name."` (
  			`id` int(11) NOT NULL auto_increment COMMENT 'id',
  			`name` varchar(20) NOT NULL COMMENT '名称',
  			`parent_id` int(10) default '0' COMMENT '父类',
  			`url` varchar(200) NOT NULL COMMENT 'seo_url',
  			`pic` varchar(200) NOT NULL COMMENT '图片',
  			`is_show` tinyint(1) NOT NULL default '1' COMMENT '审核',
  			`sort_order` int(3) default '0' COMMENT '排序',
  			`postdate` int(10) unsigned default '0' COMMENT '添加时间',
  			`info` varchar(255) NOT NULL COMMENT '备注',
  			PRIMARY KEY  (`id`)
  			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
			$this->db->query($sql);
			$leixing="category";
		}

		$column_name_zh=str_replace("管理","",$column_name_zh);
		/****view******/
		$this->load->helper('file');
		$file=FCPATH."application/models2/".$leixing."/views/category.php";
		$content=file_get_contents($file);
		$content=str_replace("category",$table_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."application/views/admin_admin/".$table_name.".php";
		$s=write_file($file2, $content,"w");

		/****view_add******/
		$file_add=FCPATH."application/models2/".$leixing."/views/category_add.php";
		$content=file_get_contents($file_add);
		$content=str_replace("category",$table_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."application/views/admin_admin/".$table_name."_add.php";
		$s=write_file($file2, $content,"w");
		/****view_edit******/
		$file_edit=FCPATH."application/models2/".$leixing."/views/category_edit.php";
		$content=file_get_contents($file_edit);
		$content=str_replace("category",$table_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."application/views/admin_admin/".$table_name."_edit.php";
		$s=write_file($file2, $content,"w");
		/****module******/
		$file=FCPATH."application/models2/".$leixing."/models/category.php";
		$content=file_get_contents($file);
		$content=str_replace("category",$table_name,$content);
		$file2=FCPATH."application/models/admin_admin/".$table_name.".php";
		$s=write_file($file2, $content,"w");
		/****module_add******/
		$file_add=FCPATH."application/models2/".$leixing."/models/category_add.php";
		$content=file_get_contents($file_add);
		$content=str_replace("category",$table_name,$content);
		$file2=FCPATH."application/models/admin_admin/".$table_name."_add.php";
		$s=write_file($file2, $content,"w");
		/****module_edit******/
		$file_edit=FCPATH."application/models2/".$leixing."/models/category_edit.php";
		$content=file_get_contents($file_edit);
		$content=str_replace("category",$table_name,$content);
		$file2=FCPATH."application/models/admin_admin/".$table_name."_edit.php";
		$s=write_file($file2, $content,"w");
		/***yii2 controller****/
		$file=FCPATH."application/models2/yii2/controllers/CategoryControlle.php";
		$content=file_get_contents($file);
		$class_name=$this->get_yii2_class_name($table_name);
		$content=str_replace("<CategoryController>",ucfirst($table_name)."Controller",$content);
		$content=str_replace("<Category>",$class_name,$content);
		$content=str_replace("<category>",$table_name,$content);
		$file_name=ucfirst($table_name)."Controller";
		$file2=FCPATH."yii2/backend/controllers/".$file_name.".php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		/***yii2 modules****/
		$file=FCPATH."application/models2/yii2/models/Category.php";
		$content=file_get_contents($file);
		$content=str_replace("Category",$class_name,$content);
		$content=str_replace("<table_name>",$table_name,$content);
		$content=$this->replace_blank_row($content);
		$s=write_file(FCPATH."yii2/backend/models/".$class_name.".php", $content,"w");
		/***yii2 modules search****/
		$file=FCPATH."application/models2/yii2/models/CategorySearch.php";
		$content=file_get_contents($file);
		$content=str_replace("Category",$class_name,$content);
		$content=$this->replace_blank_row($content);
		$s=write_file(FCPATH."yii2/backend/models/".$class_name."Search.php", $content,"w");
		/***yii2 view index****/
		$file=FCPATH."application/models2/yii2/views/category/index.php";
		$content=file_get_contents($file);
		$content=str_replace("<category>",$table_name,$content);
		$content=str_replace("<class_name>",$class_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."yii2/backend/views/".$table_name."/";
		@create_dir($file2);
		$content=$this->replace_blank_row($content);
		$s=write_file($file2."index.php", $content,"w");
		/***yii2 view create****/
		$file=FCPATH."application/models2/yii2/views/category/create.php";
		$content=file_get_contents($file);
		$content=str_replace("category",$table_name,$content);
		$content=str_replace("<TableCategory>",$class_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."yii2/backend/views/".$table_name."/create.php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		/***yii2 view update****/
		$file=FCPATH."application/models2/yii2/views/category/update.php";
		$content=file_get_contents($file);
		$content=str_replace("category",$table_name,$content);
		$content=str_replace("<TableCategory>",$class_name,$content);
		$content=str_replace("<column_name_zh>",$column_name_zh,$content);
		$file2=FCPATH."yii2/backend/views/".$table_name."/update.php";
		$content=$this->replace_blank_row($content);
		$s=write_file($file2, $content,"w");
		return $table_name;
		/*}
		else
		return $table_name;*/
		/*if(mysql_num_rows(mysql_query("SHOW TABLES LIKE '".$table."'")==1) {
		echo "Table exists";
		} else {
		echo "Table does not exist";
		}
		*/
		/*$table_name_cate=$table_name."_".$column_name;
		$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
		//if(table_exist)
		{
		$str="CREATE TABLE IF NOT EXISTS `site_".$table_name_cate."`  (
		`id` int(11) NOT NULL auto_increment COMMENT 'ID',
		`name` varchar(20) NOT NULL COMMENT '名称',
		`url` char(40) NOT NULL COMMENT 'URL',
		`sort_order` int(3) default '0' COMMENT '排序',
		`postdate` int(10) unsigned default '0' COMMENT '添加时间',
		`seo_title` varchar(100) NOT NULL COMMENT 'SEO title',
		PRIMARY KEY  (`id`),
		KEY `postdate` (`postdate`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		//copy modele2 cate;
		}
		//else

		{

		}*/
	}
}
?>
