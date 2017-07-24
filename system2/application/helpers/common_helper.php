<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 输出显示
 *
 * @param unknown_type $name
 * @param unknown_type $data
 */
function show($name,$data='',$ajax=1,$signal=false)
{
	$CI =& get_instance();
	$data=array_merge(get_header_data($data),$data);
	$header="";
	$footer="";
	if($ajax==1)
	$header=$CI->load->view('header',$data,$signal);
	$content=$CI->load->view($name,$data,$signal);
	if($ajax==1)
	$footer=$CI->load->view('footer',$data,$signal);
	if($signal)
	return $header.$content.$footer;
}
function show_wap($name,$data='',$ajax=1,$signal=false)
{
	$CI =& get_instance();
	$data=array_merge(get_header_data($data),$data);
	$header="";
	$footer="";
	if($ajax==1)
	$header=$CI->load->view('wap/header',$data,$signal);
	$content=$CI->load->view('wap/'.$name,$data,$signal);
	if($ajax==1)
	$footer=$CI->load->view('wap/footer',$data,$signal);
	if($signal)
	return $header.$content.$footer;
}
function get_header_data($data='')
{
	$CI =& get_instance();
	$data_url=array("base_url"=>base_url(),"web_url"=>base_url()."site/index/","site_url"=>site_url(),"current_url"=>current_url());
	$setting=get_setting();
	$data['site_name']=$setting[0]->value;
	$data['site_logo']=base_url().$setting[3]->value;
	$data['seo_title']=(@$data['seo']['seo_title']) ? $data['seo']['seo_title']." | " : "";
	$data['seo_title']=(@$data['seo']['seo_title'] && !$CI->uri->segment(1)) ? $data['seo']['seo_title'] : $data['seo_title'];
	$data['seo_keywords']=(@$data['seo']['seo_keywords']) ? msubstr(trim(str_replace("&nbsp;","",$data['seo']['seo_keywords'])),0,100) : $setting[1]->value;
	$data['seo_description']=(@$data['seo']['seo_description']) ? trim(str_replace("&nbsp;","",$data['seo']['seo_description'])) : $setting[2]->value;
	$data['site_user']=get_username();
	$CI->load->database();
	$data['cfg_address']=@$setting[4]->value;
	$data['footer_login']=get_footer_login();
	$login_from="";
	for($i=1;$i<5;$i++)
	{
		if($CI->uri->segment($i))
		{
			$login_from.=$CI->uri->segment($i)."/";
		}
		else
		break;
	}
	$data['login_from']=$login_from;
	$data=array_merge($data_url,$data);
	return $data;
}
function friend_link($limit=30)
{
	$CI =& get_instance();
	$temp=array();
	if($CI->uri->segment(1)=='')
	{
		$CI->load->database();
		$sql="select * from ".$CI->db->dbprefix."friend_link order by sort_order desc,id asc limit ".$limit;
		$result=$CI->db->query($sql);
		foreach ($result->result() as $row)
		{
			$temp[]=$row;
		}
	}
	return $temp;
}
function get_category_parent($id)
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix."product_category where parent_id=?",array($id));
	$row=$result->row();
	return ($row) ? $row : '';
}
function product_site_list($table='',$limit=10)
{
	$CI =& get_instance();
	$CI->load->database();
	$table=($table) ? $table : "product_site";
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table." order by id asc limit 0,$limit");
	$temp=array();
	foreach ($result->result() as $row)
	{
		$temp[]=$row;
	}
	return $temp;
}
function get_count_sale($search_where)
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select count(*) as total from ".$CI->db->dbprefix."sale ".$search_where);
	$row=$result->result();
	return $row[0]->total;
}
function get_username()
{
	$CI =& get_instance();
	$CI->load->library('session');
	return $CI->session->userdata("SITE_USER");
}
/**
 * 对跳转的url进行字符替换
 *
 * @param unknown_type $url
 * @return unknown
 */
function url_replace($url)
{
	$url=str_replace(":","%3A",$url);
	$url=str_replace("/","%2F",$url);
	$url=str_replace("?","%3F",$url);
	$url=str_replace("&","%26",$url);
	$url=str_replace("=","%3D",$url);
	return $url;
}
function get_footer_login()
{
	$string = "0123456789";
	$code='';
	for($i = 0;$i < 15;$i++){
		$str[$i] = $string[rand(0,strlen($string)-1)];
		$code .= $str[$i];
	}
	$code.=get_uid();
	for($i = 0;$i < 9;$i++){
		$str[$i] = $string[rand(0,strlen($string)-1)];
		$code .= $str[$i];
	}
	return $code;
}
function get_uid()
{
	$CI =& get_instance();
	$CI->load->library('session');
	return $CI->session->userdata("SITE_USERID");
}
function get_setting($varname='')
{
	$CI =& get_instance();
	$CI->load->database();
	if($varname=='')
	{
		return json_decode(file_get_contents(FCPATH.APPPATH."views/cache/config_cache.php"));
		/*$sql="select * from ".$CI->db->dbprefix."setting";
		$result=$CI->db->query($sql);
		$setting=array();
		foreach ($result->result() as $row)
		{
		$setting[]=$row;
		}
		return $setting;*/
	}
	else
	{
		$row=$CI->db->get_where($CI->db->dbprefix."setting",array("varname"=>$varname))->row();
		return @$row->value;
	}
}

function get_upload_dir($dir2='')
{
	$upload_dir="upload";
	$dir2=($dir2=="") ? date("Ymd",time()) : $dir2;
	$dir=dirname(dirname(dirname(str_replace("\\","/",__FILE__))))."/".$upload_dir."/".$dir2."/";
	@create_dir($dir);
	return $upload_dir."/".$dir2;
}
function create_dir($path) {
	$path_arr = explode("/", $path);
	$p_str = "";

	foreach($path_arr as $key => $val) {
		$p_str .= $val."/";
		if(!@file_exists($p_str)) {
			@mkdir ($p_str, 0777);
		}
	}
}
function get_time($time,$format='Y-m-d H:i:s')
{
	return date($format,$time);
}
function get_table_row($table,$ids,$id="id")
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table." where `".$id."`=?",array($ids));
	$row =$result->row();
	return $row;
}
function get_table_row_value($table,$id,$ids,$column)
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table." where ".$id."=?",array($ids));
	$row =$result->row();
	return @$row->$column;
}
function get_table_list($table='product',$limit="10",$order="desc",$id="id",$where="")
{
	$CI =& get_instance();
	$CI->load->database();
	$where=($table=="product")  ? " where web_id=3" : $where;
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table.$where." order by ".$id." ".$order." limit ".$limit);
	$temp=array();
	foreach ($result->result() as $row)
	{
		$temp[]=$row;
	}
	return $temp;
}
function alert($Text,$url)
{
	echo "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title></title></head><body><script type=\"text/javascript\">alert(\"".$Text."\");window.location.href=\"".$url."\";</script></body></html>";
}
function has_child($id,$table="download_category")
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table." where parent_id=?",array($id));
	$row =$result->row();
	return ($row) ? true : false;
}
function wap_link()
{
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS"))
	{
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		if(!strpos($url,"/wap/"))
		$url=str_replace(base_url(),base_url()."wap/",$url);
		header("Location:".$url);
	}
}