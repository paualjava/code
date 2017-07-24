<?php
class module extends CI_Model {
	private $table_name='module';
	private $index_id="id";
	private $this_link_url="admin_admin/index/manager/module/";
	private $per_page=200;
	private $num_links=10;
	private $dir_laravel='generate_code/laravel/';
	function main()
	{
		if($this->uri->segment(5)=="delete")
		$this->delete_this($this->uri->segment(6));
		if($this->uri->segment(5)=="create_site")
		{
			$select_program=$this->input->post("select_program");
			if($select_program=="" || $select_program=="yii2")
			$this->create_site_yii2($this->uri->segment(6),$this->uri->segment(7),$this->uri->segment(8));
			if($select_program=="" || $select_program=="laravel")
			$this->create_site_laravel($this->uri->segment(6),$this->uri->segment(7),$this->uri->segment(8));
			if($select_program=="" || $select_program=="codeIgniter")
			$this->create_site($this->uri->segment(6),$this->uri->segment(7),$this->uri->segment(8),$this->input->post("select_program_type"));
			if($select_program=="codeIgniter_code")
			$this->create_site_codeigniter_code($this->uri->segment(6),$this->uri->segment(7),$this->uri->segment(8),$this->input->post("select_program_type"));
			die();
		}
		if($this->uri->segment(5)=="create_sitemap2")
		$this->create_sitemap2();
		if($this->uri->segment(5)=="more_value_ajax")
		$this->more_value_ajax();
		if($this->uri->segment(5)=="more_value_ajax_set_value")
		$this->more_value_ajax_set_value();
		if($this->uri->segment(5)=="ajax_tranlate")
		$this->ajax_tranlate();
		if($this->uri->segment(5)=="ajax_tranlate2")
		$this->ajax_tranlate2();
		$search_where=$this->search_where();
		$current=($this->uri->segment(5) && is_int($this->uri->segment(5))) ? $this->uri->segment(5) : 0;
		return $this->content_list_search($search_where,$this->per_page,$current);
	}
	function create_sitemap2()
	{
		$this->load->helper("admin");
		$admin_dir="admin_admin";
		$this->load->model($admin_dir.'/sitemap', '', TRUE);
		$this->sitemap->create_sitemap();
		die("Site map created!");
	}
	function content_list_search($search_where='',$page_num='',$current='')
	{
		list($search_where,$total_record,$total_page,$current)=$this->get_sql_nav_search($search_where,$current);
		$result=$this->db->query($search_where);
		foreach ($result->result() as $row)
		{
			$temp[]=$row;
		}
		$config['base_url'] = base_url().$this->this_link_url;
		$config['total_rows'] = $total_record;
		$config['per_page'] = $this->per_page;
		$config['num_links'] = $this->num_links;
		$config['cur_page'] = $current;
		$this->pagination->initialize($config);
		$this_page=$this->pagination->create_links();
		$module_admin=get_table_row("module_template","admin","position");
		return array("this_page"=>$this_page,"current"=>($current==0) ? '' : $current,"this_data"=>@$temp,"module_admin"=>@$module_admin,"success_bind"=>$this->uri->segment(5));
	}
	function get_sql_nav_search($search_where,$current)
	{
		$search_where=($search_where=="") ? "" : $search_where."";
		$page_num    =$this->per_page;
		$current     =($current==null)  ? 0 : $current;
		$result=$this->db->query("select count(*) as total from ".$this->db->dbprefix.$this->table_name."  $search_where");
		$row =$result->row();
		$total_record=$row->total;
		$total_page=($total_record % $page_num==0) ? intval($total_record/$page_num) : intval($total_record/$page_num)+1;
		$search_sql="select * from ".$this->db->dbprefix.$this->table_name."  $search_where limit $current,$page_num";
		return array($search_sql,$total_record,$total_page,$current);
	}
	function more_value_ajax()
	{
		$more_value_txt=$this->input->post("more_value_txt");
		$more_value_value=$this->input->post("more_value_value");
		if (count($more_value_value) != count(array_unique($more_value_value)))
		{
			$arr=array("error"=>1,"info"=>'该数组有重复值');
		}
		else
		{
			$array=array_combine($more_value_value,$more_value_txt);
			$arr=array("error"=>0,"info"=>json_encode($array));
		}
		echo json_encode($arr,JSON_UNESCAPED_UNICODE);die();
	}
	function ajax_tranlate()
	{
		$keyword=$this->input->post("keyword");
		$url="http://fanyi.baidu.com/v2transapi?from=en&query=".$keyword."&to=zh";
		$result=file_get_contents($url);
		$result = json_decode( $result, true );
		$return=$result ['trans_result'] ['data'] ['0'] ['dst'];
		$array=array("info"=>$return);
		echo json_encode($array);die();
	}
	function ajax_tranlate2()
	{
		$keyword=$this->input->post("keyword");
		$url="http://fanyi.baidu.com/v2transapi?from=zh&query=".$keyword."&to=en";
		$result=file_get_contents($url);
		$result = json_decode( $result, true );
		$return=$result ['trans_result'] ['data'] ['0'] ['dst'];
		$array=array("info"=>strtolower($return));
		echo json_encode($array);die();
	}
	function more_value_ajax_set_value()
	{
		$more_value=$this->input->post("more_value");
		$more_value=json_decode($more_value,true);
		$str="";
		if(is_array($more_value))
		{
			$i=1;

			foreach($more_value as $key=>$item)
			{
				//<tr class="more_value">
			/*	<td>排序<span class="num_more_value">2</span>：</td>
          <td><input type="text" name="more_value_txt[]" id="txt1" value="" class="input-small"></td>
          <td><input name="more_value_value[]" id="value1" type="text" class="input-small"></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="more_value_del" href="javascript:void(0);">删除</a></p></td>
        </tr>*/
				$str.='<tr class="more_value">
          <td>排序<span class="num_more_value">'.$i.'</span>：</td>
          <td><input type="text" name="more_value_txt[]" id="txt1" value="'.$item.'" class="input-small"></td>
          <td><input name="more_value_value[]" id="value1" type="text" class="input-small" value="'.$key.'"></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="more_value_del" href="javascript:void(0);">删除</a></p></td>
        </tr>';
				$i++;
			}
		}
		$array=array("error"=>0,"info"=>$str);
		echo json_encode($array);die();
	}
	function search_where()
	{
		$sql=" WHERE ".$this->index_id.">0";
		return $sql."  order by id desc";
	}
	function delete_this($id)
	{
		$id=urldecode($id);
		$id_s=explode("`",$id);
		$this->load->helper('file');
		$content_wap=file_get_contents(FCPATH."application/controllers/wap.php");
		foreach ($id_s as $value)
		{
			$m_row=get_table_row($this->table_name,$value);
			//replace wap
			$content_wap=preg_replace("/function ".$m_row->url.".*function/isU","function",$content_wap);
			$content_wap=preg_replace("/function ".$m_row->url.".*\}[^\?]*\?>/isU",$content_wap."\r\n}
?>",$content_wap);

			$table=json_decode($m_row->data,true);
			foreach($table as $t_row)
			{
				if(strpos($t_row[9],"cate")!==false)
				{
					$table_name_cate=$m_row->url."_".$t_row[0];
					$table_url=str_replace("_id","",$table_name_cate)."_cate";
					$array_delete2=array("","_add","_edit");
					foreach($array_delete2 as $v)
					{
						@unlink(FCPATH."application/views/admin_admin/".$table_url.$v.".php");
						@unlink(FCPATH."application/models/admin_admin/".$table_url.$v.".php");
					}
					$sql ="DROP TABLE  IF EXISTS `".$this->db->dbprefix.$table_url."`";
					$this->db->query($sql);

					$sql ="delete from ".$this->db->dbprefix."menu_admin where url='".$table_url."'";
					$this->db->query($sql);
				}
				elseif(strpos($t_row[9],"pic_multiple")!==false)
				{
					$sql ="DROP TABLE  IF EXISTS `".$this->db->dbprefix.$m_row->url."_pic_multiple`";
					$this->db->query($sql);
				}
			}
			$array_delete=array("","_add","_edit");
			foreach($array_delete as $v)
			{
				@unlink(FCPATH."application/views/admin_admin/".$m_row->url.$v.".php");
				@unlink(FCPATH."application/models/admin_admin/".$m_row->url.$v.".php");
			}
			@unlink(FCPATH."application/controllers/".$m_row->url.".php");
			$array_delete2=array("","_show");
			foreach($array_delete2 as $v)
			{
				@unlink(FCPATH."application/views/wap/".$m_row->url.$v.".php");
			}
			//delete other wap.php
			$content_wap=file_get_contents(FCPATH."application/controllers/wap.php");
			if(preg_match("/function ".$m_row->url.".*function/isU",$content_wap))
			$content_wap=preg_replace("/function ".$m_row->url.".*function/isU","function",$content_wap);
			else
			$content_wap=preg_replace("/function ".$m_row->url.".*\}[^\?]*\?>/isU","}
?>",$content_wap);
			file_put_contents(FCPATH."application/controllers/wap.php",$content_wap);
			//delete this wap.php
			$sql ="DROP TABLE  IF EXISTS `".$this->db->dbprefix.$m_row->url."`";
			$this->db->query($sql);
			$sql ="delete from ".$this->db->dbprefix.$this->table_name." where ".$this->index_id."=".$value;
			$this->db->query($sql);
			$sql ="delete from ".$this->db->dbprefix."menu_admin where url='".$m_row->url."'";
			$this->db->query($sql);
		}
		write_file(FCPATH."application/controllers/wap.php", $content_wap,"w");
		$current=$this->uri->segment(7);
		//$this_url=site_url()."admin_admin/index/manager/success/module/".$current;
		redirect(site_url()."admin_admin/index/manager/success/index2_module");
		//redirect($this_url);
	}
	function create_site_laravel($www_root,$database_name,$admin_dir)
	{
		$www_root=str_replace("codeIgniter_","laravel_",$www_root);
		$database_name=str_replace("codeIgniter_","laravel_",$database_name);
		//$this->delete_dir(FCPATH.'yii2/backend');
		//$this->delete_dir(FCPATH.'yii2/frontend');
		if(!is_dir($www_root) || 1==1)
		{
			create_dir($www_root);
			create_dir($www_root."/app/Http");
			create_dir($www_root."/resources/views");
			create_dir($www_root."/database/migrations");
			//$array_file=array("index.php",".htaccess","robots.txt");

			$array_file=array("index.php",".env","artisan","composer.json","gulpfile.js","package.json","phpunit.xml","app/Http/routes.php","database/migrations/migration.php");
			foreach($array_file as $file_name)
			{
				copy("upload/laravel_5_2/".$file_name,$www_root.'/'.$file_name);
			}

			$array_dir=array("app","bootstrap","config","database","public","resources","storage","tests","vendor");
			foreach($array_dir as $dir_name)
			{
				$this->recurse_copy("upload/laravel_5_2/".$dir_name,$www_root."/".$dir_name,$www_root,$admin_dir);
			}
			/*foreach($array_file as $file_name)
			{
			copy($file_name,$www_root.'/'.$file_name);
			}*/
			/*$content=file_get_contents($www_root."/.htaccess");
			file_put_contents($www_root."/.htaccess",str_replace("codeIgniter_system2",$www_root,$content));*/
			$content=file_get_contents(FCPATH.$www_root."/.env");
			file_put_contents(FCPATH.$www_root."/.env",str_replace("laravel",$www_root,$content));
			//copy select module item
			$str_delete=$this->create_site_select_laravel_module($this->input->post("module_item"),$www_root,$admin_dir);
			//create database
			//manager
			$sql="drop TABLE  `".$this->db->dbprefix."manager`;";
			$this->db->query($sql);
			$sql="CREATE TABLE `site_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `truename` varchar(20) NOT NULL COMMENT '姓名',
  `phone` char(20) NOT NULL COMMENT '电话',
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (1, 'admin', '8RA5CwK-ivM4_uUgC34CAt6eyAWmfLN4', '\$2y\$13\$ptEbkX1dFyFTe.5sYELM4uS2Nx81lbaaFzuLhfi/PbJ.RxASz.XTS', NULL, '', '', 'admin@qq.com', 1, 10, 1438910582, 1442452804);";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (2, 'test', 'pvm5q3TUCR9NUDLkfNt_vcxmLVX4c7ql', '\$2y\$13\$U38vd/r9zPV.HGR3Of1vgOckHC2/HLhIfHuc867W5PwSisehiQ7o2', NULL, '', '13918376989', 'test@test.com', 2, 10, 1442282714, 1442452943);";
			$this->db->query($sql);
			$this->load->dbutil();
			$backup =& $this->dbutil->backup();
			$this->load->helper('file');
			create_dir($www_root."/sql_sql");
			write_file($www_root.'/sql_sql/sql_'.$database_name.'_'.date("Y-m-d").'sql.gz', $backup);
			//$content="CREATE DATABASE  `".$database_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;\r\n\r\n\r\n";
			$content="";
			file_put_contents($www_root.'/sql_sql/sql.txt',$content.$str_delete);
			$sql="drop TABLE  `".$this->db->dbprefix."manager`;";
			$this->db->query($sql);
			$sql="CREATE TABLE `site_manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` char(50) NOT NULL COMMENT '用户名',
  `password` char(50) NOT NULL COMMENT '密码',
  `role_id` tinyint(4) DEFAULT '0' COMMENT '所属角色',
  `truename` varchar(20) DEFAULT ' ' COMMENT '姓名',
  `email` varchar(100) DEFAULT ' ' COMMENT '邮箱',
  `phone` varchar(20) DEFAULT ' ' COMMENT '电话',
  `ip` char(200) DEFAULT '' COMMENT '最后登录IP',
  `salt` char(8) DEFAULT NULL,
  `postdate` int(10) NOT NULL,
  `login_time` int(10) NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
			$this->db->query($sql);
			$password=md5("admin123456");
			$sql="INSERT INTO `site_manager` VALUES (1, 'admin', '".$password."', 1, ' ', '512644164@qq.com', ' ', '0.0.0.0', NULL, 0, 1441779860);";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (2, 'user', 'c30807e6587ade285ba7ade9f881b3d7', 3, ' ', '', ' ', '', NULL, 0, 0);";
			$this->db->query($sql);
			echo "创建成功!";
		}
		else
		{
			echo "当前目录已经存在";
		}
	}
	function create_site_yii2($www_root,$database_name,$admin_dir)
	{
		$www_root=str_replace("codeIgniter_","yii2_",$www_root);
		$database_name=str_replace("codeIgniter_","yii2_",$database_name);
		//$this->delete_dir(FCPATH.'yii2/backend');
		//$this->delete_dir(FCPATH.'yii2/frontend');
		if(!is_dir($www_root) || 1==1)
		{
			create_dir($www_root);
			create_dir($www_root."/backend/controllers");
			create_dir($www_root."/backend/models");
			create_dir($www_root."/backend/views");
			//$array_file=array("index.php",".htaccess","robots.txt");
			$array_dir=array("backend","common","console","environments","frontend","tests","vendor");
			foreach($array_dir as $dir_name)
			{
				$this->recurse_copy("upload/yii2/".$dir_name,$www_root."/".$dir_name,$www_root,$admin_dir);
			}
			/*foreach($array_file as $file_name)
			{
			copy($file_name,$www_root.'/'.$file_name);
			}*/
			/*$content=file_get_contents($www_root."/.htaccess");
			file_put_contents($www_root."/.htaccess",str_replace("codeIgniter_system2",$www_root,$content));*/
			$content=file_get_contents($www_root."/common/config/main-local.php");
			file_put_contents($www_root."/common/config/main-local.php",str_replace("yii2",$www_root,$content));
			$content=file_get_contents($www_root."/common/config/bootstrap.php");
			file_put_contents($www_root."/common/config/bootstrap.php",str_replace("yii2",$www_root,$content));
			//copy select module item
			$str_delete=$this->create_site_select_yii2_module($this->input->post("module_item"),$www_root,$admin_dir);
			//create database
			//manager
			$sql="drop TABLE  `".$this->db->dbprefix."manager`;";
			$this->db->query($sql);
			$sql="CREATE TABLE `site_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `truename` varchar(20) NOT NULL COMMENT '姓名',
  `phone` char(20) NOT NULL COMMENT '电话',
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (1, 'admin', '8RA5CwK-ivM4_uUgC34CAt6eyAWmfLN4', '\$2y\$13\$ptEbkX1dFyFTe.5sYELM4uS2Nx81lbaaFzuLhfi/PbJ.RxASz.XTS', NULL, '', '', 'admin@qq.com', 1, 10, 1438910582, 1442452804);";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (2, 'test', 'pvm5q3TUCR9NUDLkfNt_vcxmLVX4c7ql', '\$2y\$13\$U38vd/r9zPV.HGR3Of1vgOckHC2/HLhIfHuc867W5PwSisehiQ7o2', NULL, '', '13918376989', 'test@test.com', 2, 10, 1442282714, 1442452943);";
			$this->db->query($sql);
			$this->load->dbutil();
			$backup =& $this->dbutil->backup();
			$this->load->helper('file');
			create_dir($www_root."/database");
			write_file($www_root.'/database/sql_'.$database_name.'_'.date("Y-m-d").'sql.gz', $backup);
			//$content="CREATE DATABASE  `".$database_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;\r\n\r\n\r\n";
			$content="";
			file_put_contents($www_root.'/database/sql.txt',$content.$str_delete);
			$sql="drop TABLE  `".$this->db->dbprefix."manager`;";
			$this->db->query($sql);
			$sql="CREATE TABLE `site_manager` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` char(50) NOT NULL COMMENT '用户名',
  `password` char(50) NOT NULL COMMENT '密码',
  `role_id` tinyint(4) DEFAULT '0' COMMENT '所属角色',
  `truename` varchar(20) DEFAULT ' ' COMMENT '姓名',
  `email` varchar(100) DEFAULT ' ' COMMENT '邮箱',
  `phone` varchar(20) DEFAULT ' ' COMMENT '电话',
  `ip` char(200) DEFAULT '' COMMENT '最后登录IP',
  `salt` char(8) DEFAULT NULL,
  `postdate` int(10) NOT NULL,
  `login_time` int(10) NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3;";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (1, 'admin', '5c5ca2ca10bd5d843628909e166609fe', 1, ' ', '512644164@qq.com', ' ', '0.0.0.0', NULL, 0, 1441779860);";
			$this->db->query($sql);
			$sql="INSERT INTO `site_manager` VALUES (2, 'user', '5c5ca2ca10bd5d843628909e166609fe', 3, ' ', '', ' ', '', NULL, 0, 0);";
			$this->db->query($sql);
			echo "创建成功!";
		}
		else
		{
			echo "当前目录已经存在";
		}
	}
	function create_site($www_root,$database_name,$admin_dir,$select_program_type)
	{
		$www_root_new=FCPATH.$www_root;

		if(!is_dir($www_root) || 1==1)
		{
			create_dir($www_root);
			$array_dir=array("application","resource","system","upload","sitemap");
			$array_file=array("index.php",".htaccess","robots.txt");
			foreach($array_dir as $dir_name)
			{
				$this->recurse_copy($dir_name,$www_root."/".$dir_name,$www_root,$admin_dir);
			}
			foreach($array_file as $file_name)
			{
				copy($file_name,$www_root.'/'.$file_name);
			}
			$content=file_get_contents($www_root."/.htaccess");
			file_put_contents($www_root."/.htaccess",str_replace("codeIgniter_system2",$www_root,$content));
			$content=file_get_contents($www_root."/application/config/config.php");
			file_put_contents($www_root."/application/config/config.php",str_replace("codeIgniter_system2",$www_root,$content));
			$content=file_get_contents($www_root."/application/config/database.php");
			file_put_contents($www_root."/application/config/database.php",str_replace("codeIgniter_system2",$www_root,$content));
			$array_del=array("application/models2","application/models3","resource/kindeditor-4.1.9/examples","resource/phpexcel/Examples/images","resource/phpexcel/Examples/templates","resource/phpexcel/Examples","resource/phpexcel/Documentation","resource/app","upload/temp_zip","upload/url_fetch","upload/site_wap","upload/site_www","upload/sandisk","upload/sandisk2","upload/html5","upload/shandong-liantong","upload/filter_css","upload/yii2","resource/site_yn");
			$array_del_file=array(
			"application/views/admin_admin/module.php",
			"application/views/admin_admin/module_add.php",
			"application/views/admin_admin/module_edit.php",
			"application/views/admin_admin/module_data_match.php",
			"application/views/admin_admin/ajax_module_step.php",
			"application/views/admin_admin/html_to_sql.php",
			"application/views/admin_admin/html_to_sql_edit.php",
			"application/views/admin_admin/html_to_sql_add.php",
			"application/views/admin_admin/html_to_css_pic.php",
			"application/views/admin_admin/module_style.php",
			"application/views/admin_admin/module_data.php",
			"application/views/admin_admin/module_data_add.php",
			"application/views/admin_admin/module_data_edit.php",
			"application/views/admin_admin/module_style_select.php",
			"application/views/admin_admin/module_type.php",
			"application/views/admin_admin/module_type_add.php",
			"application/views/admin_admin/module_type_edit.php",
			"application/views/admin_admin/module_url_fetch.php",
			"application/views/admin_admin/module_zip.php",
			"application/views/admin_admin/html5_app.php",
			"application/views/admin_admin/html5_app_add.php",
			"application/views/admin_admin/html5_app_edit.php",
			"application/views/admin_admin/preg_php.php",
			"application/views/admin_admin/url_fetch.php",
			"application/views/admin_admin/html5_animate.php",
			"application/views/admin_admin/module_style_add.php",
			"application/views/admin_admin/module_style_edit.php",
			"application/views/admin_admin/mban.php",
			"application/views/admin_admin/module_template.php",
			"application/views/admin_admin/module_data_front_style.php",
			"application/views/admin_admin/index2.php",
			"application/views/admin_admin/index_1.php",
			"application/views/admin_admin/index3.php",
			"application/views/admin_admin/index4.php",
			"application/views/admin_admin/login_1.php",
			"application/views/admin_admin/login1.php",
			"application/views/admin_admin/login2.php",
			"application/views/admin_admin/database_backup_2.php",
			"application/views/admin_admin/link2.php",
			"application/views/admin_admin/link3.php",
			"application/views/admin_admin/product_sale2.php",
			"application/views/admin_admin/product_sale2_add.php",
			"application/views/admin_admin/product_sale2_edit.php",
			"application/views/admin_admin/sale_edit.php",
			"application/views/admin_admin/product_city.php",
			"application/views/admin_admin/shop_goods.php",
			"application/views/admin_admin/shop_goods_add.php",
			"application/views/admin_admin/shop_goods_edit.php",
			"application/views/admin_admin/site_edit.php",
			"application/views/admin_admin/start_b.php",
			"application/views/admin_admin/module_data1.php",
			"application/views/admin_admin/product_sale_add.php",
			"application/views/admin_admin/product_sale_caiji.php",
			"application/views/admin_admin/product_menu.php",
			"application/views/admin_admin/product_info_edit.php",
			"application/views/admin_admin/product_info_add.php",
			"application/views/admin_admin/product_info.php",
			"application/views/admin_admin/product_edit.php",
			"application/views/admin_admin/product_city.php",
			"application/views/admin_admin/product_brand.php",
			"application/views/admin_admin/product_brand_add.php",
			"application/views/admin_admin/product_brand_edit.php",
			"application/views/admin_admin/product_category.php",
			"application/views/admin_admin/product_category_add.php",
			"application/views/admin_admin/product_category_edit.php",
			"resource/images/admin_admin/Thumbs.db",
			"resource/images/admin_admin/admin_01.jpg",
			"resource/images/admin_admin/admin_02.jpg",
			"resource/images/admin_admin/admin_03.jpg",
			"resource/images/admin_admin/circle.jpg",
			"resource/images/admin_admin/circle2.jpg",
			"resource/images/admin_admin/loginback.jpg",
			"resource/images/admin_admin/product_02.jpg",
			"resource/images/admin_admin/page_01.jpg",
			"resource/images/admin_admin/header_03.jpg",
			"resource/images/admin_admin/member_01.jpg",
			"resource/images/admin_admin/footer_01.jpg",
			"resource/images/admin_admin/footer_02.jpg",
			"resource/images/admin_admin/header_02.jpg",
			"resource/images/admin_admin/header_04.jpg",
			"resource/images/admin_admin/admin2_b.jpg",
			"resource/app.zip",			
			);
			if($select_program_type=="html5")
			{
				$array_del2=array(
					"resource/kindeditor-4.1.9",
					"resource/phpexcel",
					"resource/uploadify",
					"resource/site_yn",
					"resource/test",
					"resource/wap",
					"resource/css/test",
					"sitemap",
					"upload/yii2",
					"upload/test",
					"upload/slide",
					"upload/site",
					"upload/data_backup",
					"upload/advertise",
					"application/views/wap",
					);
				$array_del_file2=array(
					"upload/css.html",
					"resource/css/member_info.css",
					"resource/css/css.css",
					"resource/css/WdatePicker.css",
					"resource/css/other.css",
					"application/views/footer.php",
					"application/views/header.php",
					"application/controllers/site.php",
					"application/controllers/wap.php",
					);
				$array_del=array_merge($array_del,$array_del2);
				$array_del_file=array_merge($array_del_file,$array_del_file2);
			}
			foreach($array_del as $array_del_name)
			{
				$this->delete_dir($www_root_new."/".$array_del_name);
			}
			foreach($array_del_file as $array_del_file_a)
			{
				@unlink($www_root_new."/".$array_del_file_a);
				$array_del_file_a=str_replace("views","models",$array_del_file_a);
				@unlink($www_root_new."/".$array_del_file_a);
			}
			//copy select module item
			$str_delete=$this->create_site_select_module($this->input->post("module_item"),$www_root,$admin_dir);
			//create database
			$this->load->dbutil();
			$backup =& $this->dbutil->backup();
			$this->load->helper('file');
			create_dir($www_root."/database");
			write_file($www_root.'/database/sql_'.$database_name.'_'.date("Y-m-d").'sql.gz', $backup);
			//$content="CREATE DATABASE  `".$database_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;\r\n\r\n\r\n";
			$content="";
			/*			$drop_table=array("site_module","site_module_data","site_module_style","site_module_template","site_module_type");
			foreach($drop_table as $drop_table_name)
			{
			$content.="DROP TABLE if exists `".$drop_table_name."`;\r\n";
			}*/
			file_put_contents($www_root.'/database/sql.txt',$content.$str_delete);

			echo "创建成功!";
		}
		else
		{
			echo "当前目录已经存在";
		}
	}
	function create_site_codeigniter_code($www_root,$database_name,$admin_dir,$select_program_type)
	{
		$www_root_new=FCPATH.$www_root;
		if(!is_dir($www_root) || 1==1)
		{
			create_dir($www_root);

			//把后台生产的文件copy过来，然后用php_zip打包下载

			$array_table=$this->create_site_select_module_codeigniter_code($this->input->post("module_item"),$www_root,$admin_dir);
			$this->load->library('DbManage');
			$DbManage = new DbManage();
			$DbManage->backup($array_table,FCPATH.$www_root."/");

			$this->load->library('phpzip_new');
			$phpzip_new = new phpzip_new();
			$url=$www_root.'/'.$www_root.'.zip';
			$phpzip_new->Zip(FCPATH.$www_root,$www_root.'/'.$www_root.'.zip');
			$array=array("error"=>0,"url"=>base_url().$url);
			echo json_encode($array);die();
			//http://www.jb51.net/article/49971.htm
			$backup =& $this->dbutil->backup();
			$this->load->helper('file');
			create_dir($www_root."/database");
			write_file($www_root.'/database/sql_'.$database_name.'_'.date("Y-m-d").'sql.gz', $backup);
			//$content="CREATE DATABASE  `".$database_name."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;\r\n\r\n\r\n";
			$content="";
			/*			$drop_table=array("site_module","site_module_data","site_module_style","site_module_template","site_module_type");
			foreach($drop_table as $drop_table_name)
			{
			$content.="DROP TABLE if exists `".$drop_table_name."`;\r\n";
			}*/
			file_put_contents($www_root.'/database/sql.txt',$content.$str_delete);

			$info="创建成功!";
		}
		else
		{
			$info="当前目录已经存在";
		}
		$array=array("error"=>0,"info"=>$info);
		echo json_encode($array);die();
	}
	function create_site_select_laravel_module($module_item,$www_root,$admin_dir)
	{
		$this->load->helper('file');
		$this->load->database();
		$str_admin="";
		$str_admin_left="";
		$module_item=$this->input->post("module_item");
		$module_item=urldecode($module_item);
		$id_s=explode("`",$module_item);
		$sql_role="TRUNCATE TABLE  `".$this->db->dbprefix."manager_role_cate`;";
		$this->db->query($sql_role);
		$role_i=1;
		$role_str_all=array(array());
		$role_str_common=array(array());
		$array_table_list=array("manager","log","manager_role","manager_role_cate","setting","setting_wap","menu_admin","slide_wap");
		$array_wap=array("index");
		$site_nav_li='';
		$routes='';
		foreach ($id_s as $value)
		{
			if(preg_match("/^\d+$/is",$value))
			{
				$m_row=get_table_row($this->table_name,$value);
				array_push($array_wap,$m_row->url);
				$routes.="    Route::resource('".$m_row->url."', '".ucfirst($m_row->url)."Controller');\r\n";
				$module_url=$m_row->url;
				$create_view=$m_row->create_view;
				array_push($array_table_list,$module_url);
				$module_date=json_decode($m_row->data);
				$site_nav_li.=" 			<li><a href=\"{{ url('/".$admin_dir."/".$module_url."') }}\">".$m_row->name."</a></li>\r\n";
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						array_push($array_table_list,$table_name_cate);


						create_dir(FCPATH.$www_root."/backend/views/".$table_name_cate);
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/index.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/index.php");
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/create.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/create.php");
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/update.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/update.php");
						@copy(FCPATH.'upload/yii2/.htaccess',FCPATH.$www_root."/.htaccess");
						@copy(FCPATH.'upload/yii2/index.php',FCPATH.$www_root."/index.php");
						@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($table_name_cate).".php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($table_name_cate).".php");
						@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($table_name_cate)."Search.php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($table_name_cate)."Search.php");
						@copy(FCPATH.'yii2/backend/controllers/'.ucfirst($table_name_cate)."Controller.php",FCPATH.$www_root.'/backend/controllers/'.ucfirst($table_name_cate)."Controller.php");


					}
				}

				//copy admin template
				/* $row_header=get_table_row("module_template","admin","position");
				$css_content=file_get_contents(FCPATH.$www_root."/resource/css/admin_admin/admin_index.css");
				$css_header_temp=file_get_contents(FCPATH."t_model/admin/".$row_header->url."/css/style.css");
				preg_match_all("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is",$css_content,$list);
				if(@$list[0][0])
				$css_content=preg_replace("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is","\r\n".$css_header_temp,$css_content);
				else
				$css_content=$css_content.$css_header_temp;
				$css_content=str_replace("\r\n\r\n","\r\n",$css_content);
				file_put_contents(FCPATH.$www_root."/resource/css/admin_admin/admin_index.css",$css_content);
				/***copy images***/
				/*$dir_images=FCPATH."t_model/admin/".$row_header->url."/images";
				if(is_dir($dir_images))
				{
				$dir = opendir($dir_images);
				while(false !== ( $file = readdir($dir)) ) {
				if ( $file != '.' && $file != '..' ) {
				@copy($dir_images."/".$file,FCPATH.$www_root."/resource/images/admin_admin/".$file);
				}
				}
				closedir($dir);
				}
				$array_member=array("index","login");
				foreach($array_member as $file_name)
				{
				$file2=FCPATH."t_model/admin/".$row_header->url."/view/".$file_name.".php";
				$content=file_get_contents($file2);
				$s=write_file(FCPATH.$www_root."/application/views/admin_admin/".$file_name.".php",$content,"w");
				} */
				//copy admin template
				//insert role
				$array_role=array("","_list","_add","_edit","_del");
				$array_role_zi=array($m_row->name,"查看","添加","编辑","删除");
				foreach($array_role as $key=>$v)
				{
					$parent_id=($v=="") ? 0 : $role_parent_id;
					$create_type=($create_view==1) ? 2 : 1;
					$data=array("name"=>$array_role_zi[$key],"parent_id"=>$parent_id,"key"=>$module_url.$v,"status"=>1,"create_type"=>$create_type);
					$insert_str=$this->db->insert_string($this->db->dbprefix."manager_role_cate", $data);
					$this->db->query($insert_str);
					if($v=="")
					$role_parent_id=$this->db->insert_id();
					else
					{
						array_push($role_str_all[0],$role_i);
					}
					//$role_str_all.=($v=="") ? "" : $role_i.",";
					if($v=="_list")
					array_push($role_str_common[0],$role_i);
					$role_i++;
				}

				$array_item=array("","_add","_edit");
				foreach($array_item as $v)
				{
					//create_dir(FCPATH.$www_root."/backend/views/".$module_url);
					//create_dir(FCPATH.$www_root."/frontend/views/".$module_url);
					create_dir(FCPATH.$www_root."/resources/views/".$admin_dir."/".$module_url);

					@copy(FCPATH.$this->dir_laravel.'resources/views/admin/'.$module_url."/index.blade.php",FCPATH.$www_root.'/resources/views/'.$admin_dir.'/'.$module_url."/index.blade.php");
					@copy(FCPATH.$this->dir_laravel.'resources/views/admin/'.$module_url."/create.blade.php",FCPATH.$www_root.'/resources/views/'.$admin_dir.'/'.$module_url."/create.blade.php");
					@copy(FCPATH.$this->dir_laravel.'resources/views/admin/'.$module_url."/edit.blade.php",FCPATH.$www_root.'/resources/views/'.$admin_dir.'/'.$module_url."/edit.blade.php");

					//@copy(FCPATH.'yii2/frontend/views/'.$module_url."/list.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/list.php");
					//@copy(FCPATH.'yii2/frontend/views/'.$module_url."/view.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/view.php");
					//@copy(FCPATH.'yii2/frontend/views/'.$module_url."/form.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/form.php");

					create_dir(FCPATH.$www_root."/app/Http/Controllers/".$admin_dir);
					copy(FCPATH.$this->dir_laravel.'app/Http/Controllers/Admin/'.ucfirst($module_url)."Controller.php",FCPATH.$www_root.'/app/Http/Controllers/'.$admin_dir.'/'.ucfirst($module_url)."Controller.php");
					//@copy(FCPATH.'yii2/frontend/controllers/'.ucfirst($module_url)."Controller.php",FCPATH.$www_root.'/frontend/controllers/'.ucfirst($module_url)."Controller.php");
					copy(FCPATH.$this->dir_laravel.'app/'.ucfirst($module_url).".php",FCPATH.$www_root.'/app/'.ucfirst($module_url).".php");
					//@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($module_url)."Search.php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($module_url)."Search.php");
					//@copy(FCPATH.'yii2/frontend/models/'.$this->get_yii2_class_name($module_url).".php",FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).".php");
					//@copy(FCPATH.'yii2/frontend/models/'.$this->get_yii2_class_name($module_url)."Search.php",FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url)."Search.php");
					//导航连接
					//$content_layout=file_get_contents(FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).".php");
					//$content_layout=str_replace("    public static function tableName()","    public \$verifyCode;
					//public static function tableName()",$content_layout);
					//file_put_contents(FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).'.php',$content_layout);
				}
				$str_admin.='
				
           <?php if(ManagerRole::is_role(\''.$module_url.'_list\')){?><li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\''.$module_url.'/index\']);?>" class="top_class" target="main"><span>'.$m_row->name.'<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">';
				$str_admin.='
         <li><a href="javascript:" url=\'<?php echo Url::toRoute([\''.$module_url.'/index\']);?>\' target="main">'.$m_row->name.'</a></li>';
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						$str_admin.='
          <li><a href="javascript:" url=\'<?php echo Url::toRoute([\''.$table_name_cate.'/index\']);?>\' target="main">'.str_replace("管理","",$module_date_row[8]).'管理</a></li>';
					}
				}

				$str_admin.='
          </ul>
</li><?php }?>';
				$str_admin='';
				/*$str_admin_left.='
				<?php if(ManagerRole::is_role(\''.$module_url.'_list\')){?><li><a href="javascript:" url="<?php echo Url::toRoute([\''.$module_url.'/index\']);?>" target="main">'.$m_row->name.'</a></li><?php }?>';*/
				$str_admin_left.='
          <li><a href="javascript:" url="{{ url(/'.$admin_dir.'/'.$module_url.') }}" target="main">'.$m_row->name.'</a></li>';
				/*$str_admin_left.='
				<?php if(ManagerRole::is_role(\''.$module_url.'_list\')){?><li><a href="javascript:" url="{{ url(/'.$admin_dir.'/'.$module_url.') }}" target="main">'.$m_row->name.'</a></li><?php }?>';*/

			}
		}
		$this->create_lavaral_migration($id_s,$www_root);
		//copy route.php
		$content_route=file_get_contents(FCPATH.$www_root.'/app/Http/routes.php');
		$content_route=str_replace("<route_code>",$routes,$content_route);
		$content_route=str_replace("<admin_dir_upper>",ucfirst($admin_dir),$content_route);
		$content_route=str_replace("<admin_dir>",$admin_dir,$content_route);
		file_put_contents(FCPATH.$www_root.'/app/Http/routes.php',$content_route);
		//导航连接
		//$content_layout=file_get_contents(FCPATH.$www_root.'/frontend/views/layouts/main.php');
		//$content_layout=str_replace("<site_nav_li>",$site_nav_li,$content_layout);
		//file_put_contents(FCPATH.$www_root.'/frontend/views/layouts/main.php',$content_layout);
		//导航连接
		$data=array("role"=>serialize($role_str_all));
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=1");
		$this->db->query($sql_role);
		$data=array("role"=>serialize($role_str_common));
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=2 or id=3");
		$this->db->query($sql_role);
		//update manager
		$str_admin.='<?php if($role==1){?><li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\'manager/index\']);?>" class="top_class" target="main"><span>管理员设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
    <li><a href="javascript:" url="manager" target="main">管理员设置</a></li>
    <li><a href="javascript:" url="manager_role" target="main">角色管理</a></li>
  </ul>
</li>

<li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\'setting_wap/index\']);?>" class="top_class" target="main"><span>手机设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
  <li><a href="javascript:" url="setting_wap" target="main">手机设置</a></li>
    <li><a href="javascript:" url="slide_wap" target="main">首页滑动图</a></li>
  </ul>
</li><?php }?>';
		//copy other
		$array_copy_other_file=array();
		foreach($array_copy_other_file as $v_file)
		{
			//copy(FCPATH.$v_file,FCPATH.$www_root."/".$v_file);
		}
		//copy admin
		$module_template=get_table_row("module_template","admin","position");
		$module_template->url=="admin2";
		if($module_template->url!="admin3")
		$this->delete_dir(FCPATH.$www_root."/resource/assets");
		if($module_template->url=="admin2")
		{
			//$content=file_get_contents(FCPATH.$www_root.'/backend/views/site/index.php');
			//$content=preg_replace("/<!--menu_top-->.*<!--menu_top_end-->/is","<!--menu_top-->".$str_admin."<!--menu_top_end-->",$content);
			//$content=preg_replace("/<!--menu_left-->.*<!--menu_left_end-->/is","<!--menu_left-->".$str_admin_left."<!--menu_left_end-->",$content);
			//file_put_contents(FCPATH.$www_root.'/backend/views/site/index.php',$content);
		}

		//repair admin_admin
		if($admin_dir!="admin_admin")
		{
		}
		//delete table;
		$str_delete="";
		$query = $this->db->query("SHOW TABLE STATUS LIKE '" . $this->db->dbprefix . "%'");
		$tables = $query->result();
		foreach($tables as $table_row)
		{
			$name=str_replace($this->db->dbprefix,"",$table_row->Name);
			if(!in_array($name,$array_table_list))
			{
				$str_delete.="DROP TABLE if exists `".$this->db->dbprefix.$name."`;\r\n";
			}
		}
		return $str_delete;
	}
	function create_lavaral_migration($id_s,$www_root)
	{
		$up='';
		$down='';
		foreach ($id_s as $value)
		{
			if(preg_match("/^\d+$/is",$value))
			{
				$m_row=get_table_row($this->table_name,$value);
				$up.="        Schema::create('".$m_row->url."', function (Blueprint \$table) {\r\n";
				$column_data=json_decode($m_row->data);
				foreach($column_data as $column_name)
				{
					if($column_name[7]=="auto_increment")
					$up.="            \$table->increments('".$column_name[0]."');\r\n";
					else
					{
						$data_null=($column_name[5]=="NULL") ? "->nullable()" : "";
						$data_length=($column_name[2]>0) ? ", ".$column_name[2] : "";
						if(strpos($column_name[1],"char")!==false)
						$up.="            \$table->string('".$column_name[0]."'".$data_length.")".$data_null.";\r\n";
						elseif(strpos($column_name[1],"text")!==false)
						$up.="            \$table->text('".$column_name[0]."')".$data_null.";\r\n";
						elseif(strpos($column_name[1],"int")!==false)
						$up.="            \$table->integer('".$column_name[0]."')".$data_null.";\r\n";
					}
				}
				if($m_row->laravel_timestamp==1)
				$up.="            \$table->timestamps();\r\n";
				$up.="        });\r\n";
				$down.="    	Schema::drop('".$m_row->url."');\r\n";
			}
		}
		$content=file_get_contents(FCPATH.$www_root.'/database/migrations/migration.php');
		$content=str_replace("<up>",$up,$content);
		$content=str_replace("<down>",$down,$content);
		file_put_contents(FCPATH.$www_root.'/database/migrations/migration.php',$content);
	}
	function create_site_select_yii2_module($module_item,$www_root,$admin_dir)
	{
		$this->load->helper('file');
		$this->load->database();
		$str_admin="";
		$str_admin_left="";
		$module_item=$this->input->post("module_item");
		$module_item=urldecode($module_item);
		$id_s=explode("`",$module_item);
		$sql_role="TRUNCATE TABLE  `".$this->db->dbprefix."manager_role_cate`;";
		$this->db->query($sql_role);
		$role_i=1;
		$role_str_all=array(array());
		$role_str_common=array(array());
		$array_table_list=array("manager","log","manager_role","manager_role_cate","setting","setting_wap","menu_admin","slide_wap");
		$array_wap=array("index");
		$site_nav_li='';
		foreach ($id_s as $value)
		{
			if(preg_match("/^\d+$/is",$value))
			{
				$m_row=get_table_row($this->table_name,$value);
				array_push($array_wap,$m_row->url);
				$module_url=$m_row->url;
				$create_view=$m_row->create_view;
				array_push($array_table_list,$module_url);
				$module_date=json_decode($m_row->data);
				$site_nav_li.=" 			<li><a href=\"<?php echo Url::toRoute(['".$module_url."/index'],true);?>\">".$m_row->name."</a></li>\r\n";
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						array_push($array_table_list,$table_name_cate);


						create_dir(FCPATH.$www_root."/backend/views/".$table_name_cate);
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/index.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/index.php");
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/create.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/create.php");
						@copy(FCPATH.'yii2/backend/views/'.$table_name_cate."/update.php",FCPATH.$www_root.'/backend/views/'.$table_name_cate."/update.php");
						@copy(FCPATH.'upload/yii2/.htaccess',FCPATH.$www_root."/.htaccess");
						@copy(FCPATH.'upload/yii2/index.php',FCPATH.$www_root."/index.php");
						@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($table_name_cate).".php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($table_name_cate).".php");
						@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($table_name_cate)."Search.php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($table_name_cate)."Search.php");
						@copy(FCPATH.'yii2/backend/controllers/'.ucfirst($table_name_cate)."Controller.php",FCPATH.$www_root.'/backend/controllers/'.ucfirst($table_name_cate)."Controller.php");



					}
				}

				//copy admin template
				/* $row_header=get_table_row("module_template","admin","position");
				$css_content=file_get_contents(FCPATH.$www_root."/resource/css/admin_admin/admin_index.css");
				$css_header_temp=file_get_contents(FCPATH."t_model/admin/".$row_header->url."/css/style.css");
				preg_match_all("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is",$css_content,$list);
				if(@$list[0][0])
				$css_content=preg_replace("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is","\r\n".$css_header_temp,$css_content);
				else
				$css_content=$css_content.$css_header_temp;
				$css_content=str_replace("\r\n\r\n","\r\n",$css_content);
				file_put_contents(FCPATH.$www_root."/resource/css/admin_admin/admin_index.css",$css_content);
				/***copy images***/
				/*$dir_images=FCPATH."t_model/admin/".$row_header->url."/images";
				if(is_dir($dir_images))
				{
				$dir = opendir($dir_images);
				while(false !== ( $file = readdir($dir)) ) {
				if ( $file != '.' && $file != '..' ) {
				@copy($dir_images."/".$file,FCPATH.$www_root."/resource/images/admin_admin/".$file);
				}
				}
				closedir($dir);
				}
				$array_member=array("index","login");
				foreach($array_member as $file_name)
				{
				$file2=FCPATH."t_model/admin/".$row_header->url."/view/".$file_name.".php";
				$content=file_get_contents($file2);
				$s=write_file(FCPATH.$www_root."/application/views/admin_admin/".$file_name.".php",$content,"w");
				} */
				//copy admin template
				//insert role
				$array_role=array("","_list","_add","_edit","_del");
				$array_role_zi=array($m_row->name,"查看","添加","编辑","删除");
				foreach($array_role as $key=>$v)
				{
					$parent_id=($v=="") ? 0 : $role_parent_id;
					$create_type=($create_view==1) ? 2 : 1;
					$data=array("name"=>$array_role_zi[$key],"parent_id"=>$parent_id,"key"=>$module_url.$v,"status"=>1,"create_type"=>$create_type);
					$insert_str=$this->db->insert_string($this->db->dbprefix."manager_role_cate", $data);
					$this->db->query($insert_str);
					if($v=="")
					$role_parent_id=$this->db->insert_id();
					else
					{
						array_push($role_str_all[0],$role_i);
					}
					//$role_str_all.=($v=="") ? "" : $role_i.",";
					if($v=="_list")
					array_push($role_str_common[0],$role_i);
					$role_i++;
				}
				$array_item=array("","_add","_edit");
				create_dir(FCPATH.$www_root."/backend/views/site");
				foreach($array_item as $v)
				{
					create_dir(FCPATH.$www_root."/backend/views/".$module_url);
					create_dir(FCPATH.$www_root."/frontend/views/".$module_url);
					@copy(FCPATH.'yii2/backend/views/'.$module_url."/index.php",FCPATH.$www_root.'/backend/views/'.$module_url."/index.php");
					@copy(FCPATH.'yii2/backend/views/'.$module_url."/create.php",FCPATH.$www_root.'/backend/views/'.$module_url."/create.php");
					@copy(FCPATH.'yii2/backend/views/'.$module_url."/update.php",FCPATH.$www_root.'/backend/views/'.$module_url."/update.php");

					@copy(FCPATH.'yii2/frontend/views/'.$module_url."/list.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/list.php");
					@copy(FCPATH.'yii2/frontend/views/'.$module_url."/view.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/view.php");
					@copy(FCPATH.'yii2/frontend/views/'.$module_url."/form.php",FCPATH.$www_root.'/frontend/views/'.$module_url."/form.php");

					@copy(FCPATH.'yii2/backend/controllers/'.ucfirst($module_url)."Controller.php",FCPATH.$www_root.'/backend/controllers/'.ucfirst($module_url)."Controller.php");
					@copy(FCPATH.'yii2/frontend/controllers/'.ucfirst($module_url)."Controller.php",FCPATH.$www_root.'/frontend/controllers/'.ucfirst($module_url)."Controller.php");
					@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($module_url).".php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($module_url).".php");
					@copy(FCPATH.'yii2/backend/models/'.$this->get_yii2_class_name($module_url)."Search.php",FCPATH.$www_root.'/backend/models/'.$this->get_yii2_class_name($module_url)."Search.php");
					@copy(FCPATH.'yii2/frontend/models/'.$this->get_yii2_class_name($module_url).".php",FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).".php");
					@copy(FCPATH.'yii2/frontend/models/'.$this->get_yii2_class_name($module_url)."Search.php",FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url)."Search.php");
					//导航连接
					$content_layout=file_get_contents(FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).".php");
					$content_layout=str_replace("    public static function tableName()","    public \$verifyCode;
    public static function tableName()",$content_layout);
					file_put_contents(FCPATH.$www_root.'/frontend/models/'.$this->get_yii2_class_name($module_url).'.php',$content_layout);
				}
				$str_admin.='
				
           <?php if(ManagerRole::is_role(\''.$module_url.'_list\')){?><li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\''.$module_url.'/index\']);?>" class="top_class" target="main"><span>'.$m_row->name.'<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">';
				$str_admin.='
         <li><a href="javascript:" url=\'<?php echo Url::toRoute([\''.$module_url.'/index\']);?>\' target="main">'.$m_row->name.'</a></li>';
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						$str_admin.='
          <li><a href="javascript:" url=\'<?php echo Url::toRoute([\''.$table_name_cate.'/index\']);?>\' target="main">'.str_replace("管理","",$module_date_row[8]).'管理</a></li>';
					}
				}

				$str_admin.='
          </ul>
</li><?php }?>';
				$str_admin_left.='
          <?php if(ManagerRole::is_role(\''.$module_url.'_list\')){?><li><a href="javascript:" url="<?php echo Url::toRoute([\''.$module_url.'/index\']);?>" target="main">'.$m_row->name.'</a></li><?php }?>';
			}
		}
		//导航连接
		$content_layout=file_get_contents(FCPATH.$www_root.'/frontend/views/layouts/main.php');
		$content_layout=str_replace("<site_nav_li>",$site_nav_li,$content_layout);
		file_put_contents(FCPATH.$www_root.'/frontend/views/layouts/main.php',$content_layout);
		//导航连接
		$data=array("role"=>serialize($role_str_all));
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=1");
		$this->db->query($sql_role);
		$data=array("role"=>serialize($role_str_common));
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=2 or id=3");
		$this->db->query($sql_role);
		//update manager
		$str_admin.='<?php if($role==1){?><li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\'manager/index\']);?>" class="top_class" target="main"><span>管理员设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
    <li><a href="javascript:" url="manager" target="main">管理员设置</a></li>
    <li><a href="javascript:" url="manager_role" target="main">角色管理</a></li>
  </ul>
</li>

<li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo Url::toRoute([\'setting_wap/index\']);?>" class="top_class" target="main"><span>手机设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
  <li><a href="javascript:" url="setting_wap" target="main">手机设置</a></li>
    <li><a href="javascript:" url="slide_wap" target="main">首页滑动图</a></li>
  </ul>
</li><?php }?>';
		//copy other
		$array_copy_other_file=array();
		foreach($array_copy_other_file as $v_file)
		{
			copy(FCPATH.$v_file,FCPATH.$www_root."/".$v_file);
		}
		//copy admin
		$module_template=get_table_row("module_template","admin","position");
		$module_template->url=="admin2";
		if($module_template->url!="admin3")
		$this->delete_dir(FCPATH.$www_root."/resource/assets");
		if($module_template->url=="admin2")
		{
			$content=file_get_contents(FCPATH.$www_root.'/backend/views/site/index.php');
			$content=preg_replace("/<!--menu_top-->.*<!--menu_top_end-->/is","<!--menu_top-->".$str_admin."<!--menu_top_end-->",$content);
			$content=preg_replace("/<!--menu_left-->.*<!--menu_left_end-->/is","<!--menu_left-->".$str_admin_left."<!--menu_left_end-->",$content);
			file_put_contents(FCPATH.$www_root.'/backend/views/site/index.php',$content);
		}

		//repair admin_admin
		if($admin_dir!="admin_admin")
		{
		}
		//delete table;
		$str_delete="";
		$query = $this->db->query("SHOW TABLE STATUS LIKE '" . $this->db->dbprefix . "%'");
		$tables = $query->result();
		foreach($tables as $table_row)
		{
			$name=str_replace($this->db->dbprefix,"",$table_row->Name);
			if(!in_array($name,$array_table_list))
			{
				$str_delete.="DROP TABLE if exists `".$this->db->dbprefix.$name."`;\r\n";
			}
		}
		return $str_delete;
	}
	function create_site_select_module($module_item,$www_root,$admin_dir)
	{
		$this->load->helper('file');
		create_dir($www_root."/application/views/".$admin_dir);
		create_dir($www_root."/application/views/cache");
		$this->load->database();
		$str_admin="";
		$str_admin_left="";
		$module_item=$this->input->post("module_item");
		$module_item=urldecode($module_item);
		$id_s=explode("`",$module_item);
		$sql_role="TRUNCATE TABLE  `".$this->db->dbprefix."manager_role_cate`;";
		$this->db->query($sql_role);
		$role_i=1;
		$role_str_all=",";
		$role_str_common=",";
		$array_table_list=array("manager","log","manager_role","manager_role_cate","setting","setting_wap","menu_admin","slide_wap");
		$array_wap=array("index");
		foreach ($id_s as $value)
		{
			if(preg_match("/^\d+$/is",$value))
			{
				$m_row=get_table_row($this->table_name,$value);
				array_push($array_wap,$m_row->url);
				$module_url=$m_row->url;
				$create_view=$m_row->create_view;
				array_push($array_table_list,$module_url);
				$module_date=json_decode($m_row->data);
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						array_push($array_table_list,$table_name_cate);
						$array_item=array("","_add","_edit");
						foreach($array_item as $v)
						{
							@copy(FCPATH.'application/views/admin_admin/'.$table_name_cate.$v.".php",FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$table_name_cate.$v.".php");
							@copy(FCPATH.'application/models/admin_admin/'.$table_name_cate.$v.".php",FCPATH.$www_root.'/application/models/'.$admin_dir.'/'.$table_name_cate.$v.".php");
						}
					}
				}

				//copy admin template
				$row_header=get_table_row("module_template","admin","position");
				$css_content=file_get_contents(FCPATH.$www_root."/resource/css/".$admin_dir."/admin_index.css");
				$css_header_temp=file_get_contents(FCPATH."t_model/admin/".$row_header->url."/css/style.css");
				preg_match_all("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is",$css_content,$list);
				if(@$list[0][0])
				$css_content=preg_replace("/\/\*\*\*admin\d+\*\*\*\/.*\/\*\*\*end admin\d+\*\*\*\//is","\r\n".$css_header_temp,$css_content);
				else
				$css_content=$css_content.$css_header_temp;
				$css_content=str_replace("\r\n\r\n","\r\n",$css_content);
				$css_content=str_replace("admin_admin",$admin_dir,$css_content);
				file_put_contents(FCPATH.$www_root."/resource/css/".$admin_dir."/admin_index.css",$css_content);
				/***copy images***/
				$dir_images=FCPATH."t_model/admin/".$row_header->url."/images";
				if(is_dir($dir_images))
				{
					$dir = opendir($dir_images);
					while(false !== ( $file = readdir($dir)) ) {
						if ( $file != '.' && $file != '..' ) {
							@copy($dir_images."/".$file,FCPATH.$www_root."/resource/images/".$admin_dir."/".$file);
						}
					}
					closedir($dir);
				}
				$array_member=array("index","login");
				foreach($array_member as $file_name)
				{
					$file2=FCPATH."t_model/admin/".$row_header->url."/view/".$file_name.".php";
					$content=file_get_contents($file2);
					$s=write_file(FCPATH.$www_root."/application/views/".$admin_dir."/".$file_name.".php",$content,"w");
				}
				//copy admin template
				//insert role
				$array_role=array("","_list","_add","_edit","_del");
				$array_role_zi=array($m_row->name,"查看","添加","编辑","删除");
				foreach($array_role as $key=>$v)
				{
					$parent_id=($v=="") ? 0 : $role_parent_id;
					$create_type=($create_view==1) ? 2 : 1;
					$data=array("name"=>$array_role_zi[$key],"parent_id"=>$parent_id,"key"=>$module_url.$v,"status"=>1,"create_type"=>$create_type);
					$insert_str=$this->db->insert_string($this->db->dbprefix."manager_role_cate", $data);
					$this->db->query($insert_str);
					if($v=="")
					$role_parent_id=$this->db->insert_id();
					$role_str_all.=($v=="") ? "" : $role_i.",";
					$role_str_common.=($v=="_list") ? $role_i."," : "";
					$role_i++;
				}
				$array_item=array("","_add","_edit");
				foreach($array_item as $v)
				{
					@copy(FCPATH.'application/views/admin_admin/'.$module_url.$v.".php",FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php");
					$con=file_get_contents(FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php");
					$con=str_replace("admin_admin",$admin_dir,$con);
					file_put_contents(FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php",$con);
					@copy(FCPATH.'application/models/admin_admin/'.$module_url.$v.".php",FCPATH.$www_root.'/application/models/'.$admin_dir.'/'.$module_url.$v.".php");
					@copy(FCPATH.'application/controllers/'.$module_url.$v.".php",FCPATH.$www_root.'/application/controllers/'.$module_url.$v.".php");
					@copy(FCPATH.'application/views/'.$module_url.$v.".php",FCPATH.$www_root.'/application/'.$admin_dir.'/'.$module_url.$v.".php");
				}
				$str_admin.='
				<?php if(role("'.$module_url.'_list")){?>
          <li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo \$admin_url;?>'.$module_url.'" class="top_class" target="main"><span>'.$m_row->name.'<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">';
				$str_admin.='
          <li><a href="javascript:" url=\''.$module_url.'\' target="main">'.$m_row->name.'</a></li>';	
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						$str_admin.='
          <li><a href="javascript:" url=\''.$table_name_cate.'\' target="main">'.str_replace("管理","",$module_date_row[8]).'管理</a></li>';		
					}
				}

				$str_admin.='
          </ul>
</li><?php }?>';	
				$str_admin_left.='
          <?php if(role("'.$module_url.'_list")){?><li><a href="javascript:" url="'.$module_url.'" target="main">'.$m_row->name.'</a></li><?php }?>';
			}
		}
		$data=array("role"=>$role_str_all);
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=1");
		$this->db->query($sql_role);
		$data=array("role"=>$role_str_common);
		$sql_role=$this->db->update_string($this->db->dbprefix."manager_role", $data,"id=2 or id=3");
		$this->db->query($sql_role);
		//update manager
		$str_admin.='<?php if($admin_role_id==1){?><li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo \$admin_url;?>manager" class="top_class" target="main"><span>管理员设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
    <li><a href="javascript:" url="manager" target="main">管理员设置</a></li>
    <li><a href="javascript:" url="manager_role" target="main">角色管理</a></li>
  </ul>
</li>
<?php }?>
<li>
  <iframe frameborder=0></iframe>
  <a href="<?php echo \$admin_url;?>setting_wap" class="top_class" target="main"><span>手机设置<font class="enarr">&nbsp;</font></span></a>
  <ul class="dmenu">
  <li><a href="javascript:" url="setting_wap" target="main">手机设置</a></li>
    <li><a href="javascript:" url="slide_wap" target="main">首页滑动图</a></li>
  </ul>
</li>';
		//copy other
		$array_copy_other_file=array(

		"application/controllers/index.html",
		"application/controllers/site.php",
		"application/controllers/html5.php",
		"application/controllers/admin_admin.php",
		"application/controllers/member.php",
		"application/controllers/wap.php",
		"application/views/cache/config_cache.php",
		"application/views/index.html",
		"application/views/index.php",
		"application/views/header.php",
		"application/views/footer.php",
		"application/views/html5.php",
		"application/views/admin_admin/index.html",
		"application/views/admin_admin/header.php",
		"application/views/admin_admin/start.php",
		"application/views/admin_admin/manager.php",
		"application/views/admin_admin/manager_add.php",
		"application/views/admin_admin/manager_edit.php",
		"application/views/admin_admin/manager_role.php",
		"application/views/admin_admin/manager_role_add.php",
		"application/views/admin_admin/manager_role_edit.php",
		"application/views/admin_admin/success.php",
		"application/views/admin_admin/slide_wap.php",
		"application/views/admin_admin/setting_wap.php",
		"application/views/admin_admin/slide_wap_add.php",
		"application/views/admin_admin/slide_wap_edit.php",
		"application/models/admin_admin/success.php",
		"application/models/admin_admin/slide_wap.php",
		"application/models/admin_admin/slide_wap_add.php",
		"application/models/admin_admin/slide_wap_edit.php",
		"application/models/admin_admin/login.php",
		"application/models/admin_admin/logout.php",
		"application/models/admin_admin/index.php",
		"application/models/admin_admin/start.php",
		"application/models/admin_admin/manager.php",
		"application/models/admin_admin/manager_add.php",
		"application/models/admin_admin/manager_edit.php",
		"application/models/admin_admin/manager_role.php",
		"application/models/admin_admin/manager_role_add.php",
		"application/models/admin_admin/manager_role_edit.php",
		"application/models/admin_admin/ajxa_common.php",
		"application/models/admin_admin/setting_wap.php",
		);
		foreach($array_copy_other_file as $v_file)
		{
			$v_file2=str_replace("admin_admin",$admin_dir, $v_file);
			copy(FCPATH.$v_file,FCPATH.$www_root."/".$v_file2);
			$con=file_get_contents(FCPATH.$www_root."/".$v_file2);
			$con=str_replace("admin_admin",$admin_dir,$con);
			file_put_contents(FCPATH.$www_root."/".$v_file2,$con);
		}
		//delete other wap.php
		$content_wap=file_get_contents(FCPATH.$www_root."/application/controllers/wap.php");
		preg_match_all("/function ([^(]*)()/is",$content_wap,$list);
		foreach($list[1] as $wap_v)
		{
			$wap_v=trim($wap_v);
			if(!in_array($wap_v,$array_wap))
			{
				if(preg_match("/function ".$wap_v.".*function/isU",$content_wap))
				$content_wap=preg_replace("/function ".$wap_v.".*function/isU","function",$content_wap);
				else
				$content_wap=preg_replace("/function ".$wap_v.".*\}[^\?]*\?>/isU","}
?>",$content_wap);
			}
		}
		file_put_contents(FCPATH.$www_root."/application/controllers/wap.php",$content_wap);
		//end
		//copy admin
		$module_template=get_table_row("module_template","admin","position");
		$module_template->url=="admin2";
		if($module_template->url!="admin3")
		$this->delete_dir(FCPATH.$www_root."/resource/assets");
		if($module_template->url=="admin2")
		{
			$content=file_get_contents(FCPATH.'t_model/admin/'.$module_template->url.'/view/index.php');
			$content=preg_replace("/<!--menu_top-->.*<!--menu_top_end-->/is","<!--menu_top-->".$str_admin."<!--menu_top_end-->",$content);
			$content=preg_replace("/<!--menu_left-->.*<!--menu_left_end-->/is","<!--menu_left-->".$str_admin_left."<!--menu_left_end-->",$content);
			$content=str_replace("admin_admin",$admin_dir,$content);
			file_put_contents(FCPATH.$www_root.'/application/views/'.$admin_dir.'/index.php',$content);
		}

		//copy  login php
		$array_file=array("application/models/admin_admin/login.php","application/models/admin_admin/logout.php","application/helpers/admin_helper.php","application/controllers/admin_admin.php");
		foreach($array_file as $file_name)
		{
			$file_name=str_replace("admin_admin",$admin_dir,$file_name);
			$content=file_get_contents(FCPATH.$www_root.'/'.$file_name);
			if(strpos($content,"\"ADMIN_ID\""))
			{
				$content=str_replace("\"ADMIN_ID\"","\"ADMIN_ID_".strtoupper($www_root)."\"",$content);
				file_put_contents(FCPATH.$www_root.'/'.$file_name,$content);
			}
			if(strpos($content,"\"ADMIN_USER\""))
			{
				$content=str_replace("\"ADMIN_USER\"","\"ADMIN_USER_".strtoupper($www_root)."\"",$content);
				file_put_contents(FCPATH.$www_root.'/'.$file_name,$content);
			}
			if(strpos($content,"\"ADMIN_TYPE\""))
			{
				$content=str_replace("\"ADMIN_TYPE\"","\"ADMIN_TYPE_".strtoupper($www_root)."\"",$content);
				file_put_contents(FCPATH.$www_root.'/'.$file_name,$content);
			}
			if(strpos($content,"\"ADMIN_ROLE_ID\""))
			{
				$content=str_replace("\"ADMIN_ROLE_ID\"","\"ADMIN_ROLE_ID_".strtoupper($www_root)."\"",$content);
				file_put_contents(FCPATH.$www_root.'/'.$file_name,$content);
			}

		}
		//repair admin_admin
		if($admin_dir!="admin_admin")
		{
			@copy(FCPATH.$www_root.'/application/controllers/admin_admin.php',FCPATH.$www_root.'/application/controllers/'.$admin_dir.'.php');
			@unlink(FCPATH.$www_root."/application/controllers/admin_admin.php");
			$content_admin=file_get_contents(FCPATH.$www_root.'/application/controllers/'.$admin_dir.'.php');
			$content_admin=str_replace("admin_admin/index/manager/",$admin_dir."/index/manager/",$content_admin);
			$content_admin=str_replace("class Admin_admin","class ".$admin_dir,$content_admin);
			$content_admin=str_replace("admin_admin",$admin_dir,$content_admin);
			file_put_contents(FCPATH.$www_root.'/application/controllers/'.$admin_dir.'.php',$content_admin);
			//loginout
			/*			$content_admin=file_get_contents(FCPATH.$www_root.'/application/models/admin_admin/logout.php');
			$content_admin=str_replace("admin_admin",$admin_dir,$content_admin);
			file_put_contents(FCPATH.$www_root.'/application/models/admin_admin/logout.php',$content_admin);
			$content_admin=file_get_contents(FCPATH.$www_root.'/application/models/admin_admin/login.php');
			$content_admin=str_replace("admin_admin",$admin_dir,$content_admin);
			file_put_contents(FCPATH.$www_root.'/application/models/admin_admin/login.php',$content_admin);
			$content_admin=file_get_contents(FCPATH.$www_root.'/application/helpers/function_helper.php');
			$content_admin=str_replace("admin_admin",$admin_dir,$content_admin);
			file_put_contents(FCPATH.$www_root.'/application/helpers/function_helper.php',$content_admin);
			$content_admin=file_get_contents(FCPATH.$www_root.'/application/models/admin_admin/success.php');
			$content_admin=str_replace("admin_admin",$admin_dir,$content_admin);
			file_put_contents(FCPATH.$www_root.'/application/models/admin_admin/success.php',$content_admin);*/
		}
		//delete table;
		$str_delete="";
		$query = $this->db->query("SHOW TABLE STATUS LIKE '" . $this->db->dbprefix . "%'");
		$tables = $query->result();
		foreach($tables as $table_row)
		{
			$name=str_replace($this->db->dbprefix,"",$table_row->Name);
			if(!in_array($name,$array_table_list))
			{
				$str_delete.="DROP TABLE if exists `".$this->db->dbprefix.$name."`;\r\n";
			}
		}
		return $str_delete;
	}
	function create_site_select_module_codeigniter_code($module_item,$www_root,$admin_dir)
	{
		$this->load->helper('file');
		create_dir($www_root."/application/views/".$admin_dir);
		create_dir($www_root."/application/models/".$admin_dir);
		$this->load->database();
		$module_item=$this->input->post("module_item");
		$module_item=urldecode($module_item);
		$id_s=explode("`",$module_item);
		$array_table_list=array();
		foreach ($id_s as $value)
		{
			if(preg_match("/^\d+$/is",$value))
			{
				$m_row=get_table_row($this->table_name,$value);
				$module_url=$m_row->url;
				$create_view=$m_row->create_view;
				array_push($array_table_list,$module_url);
				$module_date=json_decode($m_row->data);
				foreach($module_date as $module_date_row)
				{
					if($module_date_row[9]=="cate_simple" || $module_date_row[9]=="cate_more_simple" || $module_date_row[9]=="cate" || $module_date_row[9]=="cate_more")
					{
						$table_name_cate=$module_url."_".$module_date_row[0];
						$table_name_cate=str_replace("_id","",$table_name_cate)."_cate";
						array_push($array_table_list,$table_name_cate);
						$array_item=array("","_add","_edit");
						foreach($array_item as $v)
						{
							@copy(FCPATH.'application/views/admin_admin/'.$table_name_cate.$v.".php",FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$table_name_cate.$v.".php");
							@copy(FCPATH.'application/models/admin_admin/'.$table_name_cate.$v.".php",FCPATH.$www_root.'/application/models/'.$admin_dir.'/'.$table_name_cate.$v.".php");
						}
					}
				}
				$array_item=array("","_add","_edit");
				foreach($array_item as $v)
				{
					@copy(FCPATH.'application/views/admin_admin/'.$module_url.$v.".php",FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php");
					$con=file_get_contents(FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php");
					$con=str_replace("admin_admin",$admin_dir,$con);
					file_put_contents(FCPATH.$www_root.'/application/views/'.$admin_dir.'/'.$module_url.$v.".php",$con);
					@copy(FCPATH.'application/models/admin_admin/'.$module_url.$v.".php",FCPATH.$www_root.'/application/models/'.$admin_dir.'/'.$module_url.$v.".php");
				}
			}
		}
		return $array_table_list;
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
	function recurse_copy($src,$dst,$www_root,$admin_dir) {  // 原目录，复制到的目录
		$array_not_in=array(
		"application/models/admin_admin",
		"application/views/admin_admin",
		"application/views",
		"application/controllers",
		);
		$dir = opendir($src);
		$dst=str_replace("admin_admin",$admin_dir,$dst);
		@mkdir($dst);
		while(false!== ($file=readdir($dir))) {
			if ($file!= '.' && $file!= '..' && $file!=".svn" && $file!=".DS_Store" && $file!=".gitkeep") {
				if (is_dir($src. '/'.$file)){
					$this->recurse_copy($src.'/'.$file,$dst.'/'.$file,$www_root,$admin_dir);
				}
				else {
					if(!in_array($src,$array_not_in))
					{
						$content=file_get_contents($src.'/'.$file);
						$content=str_replace("admin_admin",$admin_dir,$content);
						if(strpos($content,"\"ADMIN_ID\""))
						{
							$content=str_replace("\"ADMIN_ID\"","\"ADMIN_ID_".strtoupper($www_root)."\"",$content);
							file_put_contents($dst.'/'.$file,$content);
						}
						if(strpos($content,"\"ADMIN_USER\""))
						{
							$content=str_replace("\"ADMIN_USER\"","\"ADMIN_USER_".strtoupper($www_root)."\"",$content);
							file_put_contents($dst.'/'.$file,$content);
						}
						else
						{
							copy($src.'/'.$file,$dst.'/'.$file);
							$con=file_get_contents($dst.'/'.$file);
							$con=str_replace("admin_admin",$admin_dir,$con);
							file_put_contents($dst.'/'.$file,$con);
						}
						
					}
				}
			}
		}
		closedir($dir);
	}
	function delete_dir($dirName){
		if(is_dir($dirName)){
			if ($handle = opendir("$dirName")){
				while (false!==($item = readdir($handle))){
					if ($item!="." && $item!="..") {
						if (is_dir("$dirName/$item")) {
							$this->delete_dir("$dirName/$item");
						} else {
							unlink("$dirName/$item");
						}
					}
				}
				closedir($handle);
				@rmdir($dirName);

			}
		}
	}
}
?>
