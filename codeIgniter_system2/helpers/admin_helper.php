<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 */	
function get_log_type($id=0)
{
	//$progress=array("5"=>"问题url","3"=>"新增站点","6"=>"站点删除","8"=>"无产品内容","9"=>"站点地图","20"=>"产品图片失败","21"=>"促销图片失败","30"=>"产品不够40个","91"=>"特卖没名字","92"=>"其他","7"=>"用户登陆");
	$progress=array("92"=>"其他","7"=>"用户登陆","9"=>"站点地图");
	return ($id) ? $progress["$id"] : $progress;
}
function get_new_file_name($big_pic,$max)
{
	$max=($max<=0) ? 1: $max;
	$page_num=1000;
	$page_num2=1000000;
	$total_page=($max % $page_num==0) ? intval($max/$page_num) : intval($max/$page_num)+1;
	$total_page2=($max % $page_num2==0) ? intval($max/$page_num2) : intval($max/$page_num2)+1;
	$sec =explode(" ",microtime());
	$filename=str_replace("0.","",$sec[1].$sec[0]);
	$new_pic=get_upload_dir($total_page2."/".$total_page)."/".$filename.substr($big_pic,strrpos($big_pic,"."));
	return $new_pic;
}
function curl_get_content($url)
{
	$browsers = array(
	"standard" => array (
	"user_agent" => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)",
	"language" => "en-us,en;q=0.5"
	),
	"iphone" => array (
	"user_agent" => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A537a Safari/419.3",
	"language" => "en"
	),
	"french" => array (
	"user_agent" => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; GTB6; .NET CLR 2.0.50727)",
	"language" => "fr,fr-FR;q=0.5"
	)
	);
	foreach ($browsers as $test_name => $browser) {
		$ch = curl_init();
		// 设置 url
		curl_setopt($ch, CURLOPT_URL, $url);
		// 设置浏览器的特定header
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"User-Agent: {$browser['user_agent']}",
		"Accept-Language: {$browser['language']}"
		));
		//curl_setopt($ch, CURLOPT_NOBODY, 1);
		// 只需返回HTTP header
		//curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}
function getUrl($url)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	// 设置浏览器的特定header
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)", "Accept-Language: en-us,en;q=0.5"));
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	// 有重定向的HTTP头信息吗?
	if (preg_match("!Location: (.*)!", $output, $matches)) {
		return $matches[1];
	} else {
		return false;
	}
}
function get_v($name)
{
	return (trim(@$_GET[$name])) ? trim(@$_GET[$name]) : trim(@$_POST[$name]);
}
function get_surface($id,$title,$style,$input,$pic,$title_name='标题',$show_url=1)
{
	$CI =& get_instance();
	$data['id']=$id;
	$data['title']=$title;
	$data['style']=$style;
	$data['input']=$input;
	$data['show_url']=$show_url;
	$data['pic']=$pic;
	$data['title_name']=$title_name;
	$data['info']=get_table_row("surface",$id,"id");
	echo $CI->load->view('admin_admin/get_surface',$data, true);
}
function get_module_type($id=0)
{
	$progress=array("common"=>"通用管理","article"=>"文章管理","product"=>"产品管理","friend_link"=>"友情链接","page"=>"页面管理","table_structure"=>"表结构");
	return ($id) ? $progress["$id"] : $progress;
}
function show_categroy($cate_table,$category='')
{
	$CI =& get_instance();
	$CI->load->database();
	if($category=="")
	{
		$str="";
		$result=$CI->db->query("select * from ".$CI->db->dbprefix.$cate_table." order by sort_order desc");
		$temp=array();
		foreach ($result->result() as $row)
		{
			$temp[]=$row;
		}
		return $temp;
	}
	else
	{
		$category=substr($category,1,strlen($category)-1);
		$category=explode(",",$category);
		$str="";
		$result=$CI->db->query("select * from ".$CI->db->dbprefix.$cate_table." order by sort_order desc");
		$temp=array();
		foreach ($result->result() as $row)
		{
			if(in_array($row->id,$category))
			$row->yes=1;
			else
			$row->yes=2;
			$temp[]=$row;
		}
		return $temp;
	}
}
function ajax_show_tag($table_name,$category_str)
{
	$category_str=urldecode($category_str);
	$category_str=explode(",",$category_str);
	$str="";
	foreach($category_str as $value)
	{
		if($value)
		$str.=get_table_row_value($table_name,"id",$value,"name")." ";
	}
	return $str;
}
function role($key)
{
	$CI =& get_instance();
	$role=get_table_row("manager_role_cate",$key,"key");
	if($role)
	{
		$CI->load->library('session');
		$admin_id=$CI->session->userdata("ADMIN_ID");
		$info=get_table_row("manager",$admin_id);
		$manager_role=get_table_row("manager_role",$info->role_id);
		return (strpos($manager_role->role,",".$role->id.",")!==false) ? true : false;
	}
	return true;
}
function role_check($key)
{
	if(!role($key))
	{
		alert("对不起，权限不足!",site_url()."/admin_admin/index/manager/start");
	}
}