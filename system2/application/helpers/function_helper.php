<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter
 */	
if ( ! function_exists('msubstr'))
{
	function msubstr($str, $start, $lenth)
	{
		$len = strlen($str);
		$r = array();
		$n = 0;
		$m = 0;
		for($i = 0; $i < $len; $i++) {
			$x = substr($str, $i, 1);
			$a = base_convert(ord($x), 10, 2);
			$a = substr('00000000'.$a, -8);
			if ($n < $start){
				if (substr($a, 0, 1) == 0) {
				}elseif (substr($a, 0, 3) == 110) {
					$i += 1;
				}elseif (substr($a, 0, 4) == 1110) {
					$i += 2;
				}
				$n++;
			}else{
				if (substr($a, 0, 1) == 0) {
					$r[ ] = substr($str, $i, 1);
				}elseif (substr($a, 0, 3) == 110) {
					$r[ ] = substr($str, $i, 2);
					$i += 1;
				}elseif (substr($a, 0, 4) == 1110) {
					$r[ ] = substr($str, $i, 3);
					$i += 2;
				}else{
					$r[ ] = '';
				}
				if (++$m >= $lenth){
					break;
				}
			}
		}
		return join("",$r);
	}
}
function create_pic($input_value,$dir2='')
{
	$CI =& get_instance();
	$sec =explode(" ",microtime());
	$filename=str_replace("0.","",$sec[1].$sec[0]);
	$string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$vcode='';
	for($i = 0;$i < 4;$i++){
		$vcode .= $string[rand(0,35)];
	}
	$config['upload_path'] = get_upload_dir($dir2);
	$config['file_name'] = $vcode.$filename;
	$config['allowed_types'] = 'gif|jpg|png|jpeg';
	$config['max_size'] = '20000';
	$config['max_width']  = '20000';
	$config['max_height']  = '20000';
	$CI->load->library('upload', $config);
	if(!$CI->upload->do_upload($input_value))
	{
		echo $CI->upload->display_errors();
		die();
	}
	return $CI->upload->file_name;
}
function create_small_pic($source)
{
	$CI =& get_instance();
	$config['image_library'] = 'gd2';
	$config['source_image'] = $source;
	$config['create_thumb'] = TRUE;
	$config['maintain_ratio'] = TRUE;
	$config['width'] = get_setting('cfg_ddimg_width');
	$config['height'] = get_setting('cfg_ddimg_height');
	$CI->load->library('image_lib', $config);
	if ( ! $CI->image_lib->resize())
	{
		echo $CI->image_lib->display_errors();
		die();
	}
	return substr($source,0,strrpos($source,"."))."_thumb".substr($source,strrpos($source,"."));
}
function create_crop_pic($source,$new)
{
	$CI =& get_instance();
	$config['image_library'] = 'gd2';
	$config['source_image'] = $source;
	$config['x_axis'] = '-50';
	$config['y_axis'] = '0';
	//$config['maintain_ratio'] = TRUE;
	$config['quality']='90%';
	$config['new_image'] = $new;//(必须)设置图像的目标名/路径
	$config['width'] = 230;
	$config['height'] = 147;
	$CI->load->library('image_lib', $config);
	$CI->image_lib->initialize($config);
	if ( ! $CI->image_lib->crop())
	{
		echo $CI->image_lib->display_errors();
		die();
	}
}
function mail_send($email_to,$email_subject='',$email_body='')
{
	$CI =& get_instance();
	$user="codeigniter-mail@sohu.com";
	$config = Array(
	'protocol' => 'smtp',
	'smtp_host' => 'smtp.sohu.com',
	'smtp_port' => 25,
	'smtp_user' => $user,
	'smtp_pass' => 'sohu123456',
	'mailtype' => 'html',
	);
	$CI->load->library('email', $config);
	$CI->email->set_newline("\r\n");


	$from_name = '';//sender
	$CI->email->from($user, $from_name);
	$CI->email->to($email_to);
	$CI->email->subject($email_subject);
	$CI->email->message($email_body);
	if (!$CI->email->send())
	{
		return false;
	}
	else
	{
		return true;
	}
}
function generate()
{
	$CI =& get_instance();
	$w = 100; //设置图片宽和高
	$h = 26;
	$str = Array(); //用来存储随机数
	$string = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";//随机挑选4个字符
	$vcode='';
	for($i = 0;$i < 4;$i++){
		$str[$i] = $string[rand(0,strlen($string)-1)];
		$vcode .= $str[$i];
	}
	$vcode=array("vcode"=>$vcode);
	$CI->load->library('session');
	$CI->session->set_userdata($vcode);
	$im = imagecreatetruecolor($w,$h);
	$white = imagecolorallocate($im,255,255,255); //第一次调用设置背景色
	$black = imagecolorallocate($im,200,200,200); //边框颜色
	imagefilledrectangle($im,0,0,$w,$h,$white); //画一矩形填充
	imagerectangle($im,0,0,$w-1,$h-1,$black); //画一矩形�?
	//生成雪花背景
	for($i = 1;$i < 200;$i++){
		$x = mt_rand(1,$w-9);
		$y = mt_rand(1,$h-9);
		$color = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
		imagechar($im,1,$x,$y,"*",$color);
	}
	//将验证码写入图案
	for($i = 0;$i < count($str);$i++){
		$x = 13 + $i * ($w - 15)/4;
		$y = mt_rand(3,$h / 3);
		$color = imagecolorallocate($im,mt_rand(0,225),mt_rand(0,150),mt_rand(0,225));
		imagechar($im,5,$x,$y,$str[$i],$color);
	}
	header("Content-type:image/jpeg"); //以jpeg格式输出，注意上面不能输出任何字符，否则出错
	imagejpeg($im);
	imagedestroy($im);
	die();
}
function get_category_child($category_id)
{
	$CI =& get_instance();
	$CI->load->database();
	$sql="select * from ".$CI->db->dbprefix."product_category where url=?";
	$result2=$CI->db->query($sql,array($category_id));
	$row2=$result2->result();
	$temp='';
	if(($row2[0]->parent_id)==0)
	{
		$parent_id=$row2[0]->id;
		$sql="select * from ".$CI->db->dbprefix."product_category where parent_id=?";
		$result3=$CI->db->query($sql,array($parent_id));
		foreach ($result3->result() as $row3)
		{
			$temp[]=$row3;
		}
	}
	else
	{
		$parent_id=$row2[0]->parent_id;
		$sql="select * from ".$CI->db->dbprefix."product_category where parent_id=?";
		$result=$CI->db->query($sql,array($parent_id));
		$row2=$result2->result();
		foreach ($result->result() as $row)
		{
			$temp[]=$row;
		}
	}
	return $temp;
}
function get_this_time($time,$type="Y")
{
	$time=$time-time();
	$time=($time>0) ? $time : 0;
	$day=intval($time/86400);
	$time=$time-$day*86400;
	$hour=intval($time/3600);
	$time=$time-$hour*3600;
	$min=intval($time/60);
	return ($type=="Y") ? $day : (($type=="h") ? $hour : $min);
}
function get_table_all_data($table,$order=" order by id desc")
{
	$CI =& get_instance();
	$CI->load->database();
	$sql="select * from ".$CI->db->dbprefix.$table." ".$order;
	$sql=str_replace(",,",",",$sql);
	$result=$CI->db->query($sql);
	$temp=array();
	foreach ($result->result() as $row)
	{
		$temp[]=$row;
	}
	return $temp;
}
function left_page()
{
	$CI =& get_instance();
	$data_url=array("base_url"=>base_url(),"web_url"=>base_url()."site/index/","site_url"=>site_url());
	$page_left=$CI->load->view('page_left',$data_url, true);
	return $page_left;
}
function get_advertise($p)
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix."advertise"." where status=1 and time_start<=".time()." and time_end>".time()." and position=? order by sort_order desc,id desc limit 0,1",$p);
	$row =@$result->row();
	return $row;
}
function get_current_url($url2="listing")
{
	$CI =& get_instance();
	$product_url="";
	for($i=1;$i<=20;$i++)
	{
		$url=$CI->uri->segment($i);
		if($url && !preg_match("/^\d+$/is",$url))
		{
			$product_url.=$url."/";
		}
		else
		break;
	}
	$product_url=(!$CI->uri->segment(2)) ? $product_url.$url2 : $product_url;
	$product_url=(substr($product_url,-1)=="/") ? substr($product_url,0,strlen($product_url)-1) : $product_url;
	return $product_url;
}
function get_current()
{
	$CI =& get_instance();
	$current=0;
	for($i=1;$i<=20;$i++)
	{
		$url=$CI->uri->segment($i);
		if($url)
		{
			if(preg_match("/^\d+$/is",$url))
			$current=$url;
		}
		else
		break;
	}
	return $current;
}
function list_search($table_name,$search_where,$page_num,$num_links,$url,$current=0,$column="*")
{
	$CI =& get_instance();
	$CI->load->database();
	$current     =($current==null)  ? 0 : $current;
	$result=$CI->db->query("select count(*) as total from ".$CI->db->dbprefix.$table_name."  $search_where");
	$row =$result->row();
	$total_record=$row->total;
	$total_page=($total_record % $page_num==0) ? intval($total_record/$page_num) : intval($total_record/$page_num)+1;
	$search_sql="select ".$column." from ".$CI->db->dbprefix.$table_name."  $search_where limit $current,$page_num";
	$result=$CI->db->query($search_sql);
	$temp=array();
	foreach ($result->result() as $row)
	{
		$temp[]=$row;
	}
	$config['base_url'] = site_url().$url;
	$config['total_rows'] = $total_record;
	$config['per_page'] = $page_num;
	$config['num_links'] = $num_links;
	$config['cur_page'] = $current;
	$CI->load->library('pagination');
	$CI->pagination->initialize($config);
	$CI_page=$CI->pagination->create_links();
	$CI_page=preg_replace("/\/0\"/is","\"",$CI_page);
	return array("this_page"=>$CI_page,"total_record"=>$total_record,"current"=>($current==0) ? '' : $current,"this_data"=>$temp);
}
function column_not_exist($column,$keyword,$type='',$table="application")
{
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix.$table." where ".$column."=?",array($keyword));
	$num_rows=$result->num_rows();
	$result->free_result();
	$num=($type=="edit") ? 2 : 1;
	return ($num_rows<$num) ? true : false;
}
function url_admin()
{
	$CI =& get_instance();
	return site_url().$CI->router->fetch_class()."/index/manager/";
}
function delete_all($id,$table,$current,$column="id",$url='')
{
	$CI =& get_instance();
	$CI->load->database();
	$id=urldecode($id);
	$id_s=explode("`",$id);
	foreach ($id_s as $value)
	{
		if(preg_match("/^\d+$/is",$value))
		{
			$sql ="delete from ".$CI->db->dbprefix.$table." where ".$column."=?";
			$CI->db->query($sql,array($value));
		}
	}
	echo ($url) ? $url : 1;die();
}
function delete($table,$id,$current,$column="id",$file='')
{
	$CI =& get_instance();
	$CI->load->database();
	if(preg_match("/^\d+$/is",$id))
	{
		$sql ="delete from ".$CI->db->dbprefix.$table." where ".$column."=?";
		$CI->db->query($sql,array($id));
	}
	$file=($file) ? $file : $table;
	$CI =& get_instance();
	$this_url=site_url().$CI->router->fetch_class()."/index/manager/success/".$file."/".$current."/"."?".$_SERVER["QUERY_STRING"];
	redirect($this_url);
}
function insert_log($web_name,$content,$type=0)
{
	$CI =& get_instance();
	$CI->load->database();
	$data=array("web_name"=>$web_name,"content"=>$content,"type"=>$type,"postdate"=>time());
	$insert_str=$CI->db->insert_string($CI->db->dbprefix."log",$data);
	$CI->db->query($insert_str);
}
function get_cate_url($cate_name,$cate_value,$url_index)
{
	$CI =& get_instance();
	$product_url="";
	$s=0;
	for($i=3;$i<=20;$i++)
	{
		$url=$CI->uri->segment($i);
		if($url && !preg_match("/^\d+$/is",$url))
		{
			//die($url);
			if(preg_match("/^".$cate_name."_\d+$/is",$url))
			{
				$product_url.=$cate_name."_".$cate_value."/";
				$s=1;
			}
			else
			$product_url.=$url."/";
		}
		else
		break;
	}
	$product_url=$url_index."/index/".$product_url;
	$product_url=($s==0) ? $product_url."/".$cate_name."_".$cate_value : $product_url;
	$product_url=str_replace("//","/",$product_url);
	$product_url=(substr($product_url,-1)=="/") ? substr($product_url,0,strlen($product_url)-1) : $product_url;
	return $product_url;
}
function get_cate_url_all($cate_name)
{
	$CI =& get_instance();
	$product_url="";
	$s=0;
	for($i=1;$i<=20;$i++)
	{
		$url=$CI->uri->segment($i);
		if($url && !preg_match("/^\d+$/is",$url))
		{
			if(preg_match("/^".$cate_name."_\d+$/is",$url))
			$product_url.="";
			else
			$product_url.=$url."/";
		}
		else
		break;
	}
	$product_url=str_replace("//","/",$product_url);
	$product_url=(substr($product_url,-1)=="/") ? substr($product_url,0,strlen($product_url)-1) : $product_url;
	return $product_url;
}
function create_excel_file($data,$data_colunm,$title,$array_width="")
{
	error_reporting(E_ALL);
	date_default_timezone_set('Asia/Shanghai');
	require_once FCPATH.'resource/phpexcel/Classes/PHPExcel.php';
	$objPHPExcel=new PHPExcel();
	$objPHPExcel->getProperties()->setCreator('')
	->setLastModifiedBy('')
	->setTitle($title)
	->setSubject($title)
	->setDescription('Document for Office 2007 XLSX')
	->setKeywords('')
	->setCategory('');
	$j=0;
	$obj_str="\$objPHPExcel->setActiveSheetIndex(0)";
	$obj_str_2="\$objPHPExcel->setActiveSheetIndex(0)";
	foreach($data_colunm as $key=>$value)
	{
		$chr=chr(65+$j);
		$obj_str.="->setCellValue('".$chr."1','".$value."')\r\n";
		$obj_str_2.="->setCellValue('".$chr."'.\$i,\$v['".$key."'])\r\n";
		$width=($array_width) ? $array_width[$j] : 25;
		$width=($j==0) ? 20 : $width;
		$objPHPExcel->getActiveSheet()->getColumnDimension($chr)->setWidth($width);
		$j++;
	}
	eval($obj_str.";");
	$i=2;
	foreach($data as $k=>$v){
		eval($obj_str_2.";");
		$i++;
	}
	$objPHPExcel->getActiveSheet()->setTitle($title);
	$objPHPExcel->setActiveSheetIndex(0);
	$filename=urlencode($title).'_'.date('Y-m-d_His');
	/*
	*生成xlsx文件*/
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	header('Cache-Control: max-age=0');
	$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
	/*
	*生成xls文件
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	*/
	$objWriter->save('php://output');
	exit;
}