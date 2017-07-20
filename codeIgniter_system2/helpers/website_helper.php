<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 */	
function show_class_list($table_name,$type="simple",$p=0,$layer=0)
{
	$CI =& get_instance();
	$CI->load->database();
	$sql="select * from ".$CI->db->dbprefix.$table_name." where parent_id=? order by `sort_order` desc,id asc";
	$result=$CI->db->query($sql,array($p));
	$admin_url=site_url()."admin_admin"."/index/manager/";
	$layer++;
	foreach ($result->result() as $row)
	{
		$classname=$row->name;
		$repeat=(($layer-1)>1) ? str_repeat('<i class="board_z" style="padding-left:33px;"></i>',($layer-2)) : '';
		$first=($row->parent_id>0) ? $repeat.'<i class="board"></i>'.$classname : $classname;
		if($type!="simple")
		{
		$pic=(@$row->pic) ? ((substr(@$row->pic,0,4)=='http' || substr(@$row->pic,0,1)=='/') ? @$row->pic : base_url().@$row->pic)  : "";
		$pic2=($pic) ? '<img src="'.$pic.'" border="0" style="max-height:22px;"/>' : "";
		}
		$is_show=($row->is_show==1) ? "已审核" : "<span style=\"color:#ccc;\">未审核</span>";
		$first=($row->is_show==1) ? $first : "<span style=\"color:#ccc;\">".$first."</span>";
		if($type!="simple")
		{
			echo ' <tr>
		<td>'.$row->id.'</td>
		<td>'.$first.'</td>
		<td>'.$pic2.'</td>
		<td>'.$is_show.'</td>
		<td>'.$row->url.'</td>
		<td>'.$row->sort_order.'</td>
        <td><div style="float:left;margin:0 4px 0 0;"><a href="'.$admin_url.$table_name.'_edit/'.$row->id.'"><img src="'.base_url().'resource/images/admin_admin/edit.gif" /></a></div><div style="float:left"><a href="javascript:delete_confirm(\'您确定要删除吗?\',\''.$admin_url.$table_name.'/delete/'. $row->id.'\');" title="删除"><img src="'.base_url().'resource/images/admin_admin/admin_del.gif" /></a></div></td></tr>';
		}
		else 
		{
			echo ' <tr>
		<td>'.$row->id.'</td>
		<td>'.$first.'</td>
		<td>'.$is_show.'</td>
		<td>'.$row->sort_order.'</td>
        <td><a href="'.$admin_url.$table_name.'_edit/'.$row->id.'"><img src="'.base_url().'resource/images/admin_admin/edit.gif" /></a> &nbsp;<a href="javascript:delete_confirm(\'您确定要删除吗?\',\''.$admin_url.$table_name.'/delete/'. $row->id.'\');" title="删除"><img src="'.base_url().'resource/images/admin_admin/admin_del.gif" /></a></td></tr>';
		}
		
		show_class_list($table_name,$type,$row->id,$layer);
	}
}
function show_class_select($table_name,$p=0,$id=0,$category_id,$layer=0)
{
	$CI =& get_instance();
	$CI->load->database();
	$sql="select * from ".$CI->db->dbprefix.$table_name." where parent_id=? order by `sort_order` desc,id asc";
	$result=$CI->db->query($sql,array($p));
	$layer++;
	foreach ($result->result() as $row)
	{
		$classname=$row->name;
		$select=($category_id==$row->id) ? 'selected="selected"' : "";
		$select2=($id==$row->id && $id>0) ? 'disabled="disabled"' : '';
		echo " <option value='".$row->id."' ".$select." ".$select2.">".str_repeat("&nbsp;",($layer-1)*5).$classname."</option>\r\n";
		show_class_select($table_name,$row->id,$id,$category_id,$layer);
	}
}
function get_nav($type = 'middle', $parent_id = 0, $level = 0, $current_id = '', &$nav = array(), $mark = '-') {
	$CI =& get_instance();
	$CI->load->database();
	$result=$CI->db->query("select * from ".$CI->db->dbprefix."nav order by `sort` asc");
	foreach ($result->result_array() as $value) {
		if ($value['parent_id'] == $parent_id && $value['type'] == $type && $value['id'] != $current_id) {
			/*if ($value['module'] != 'nav') {
				$value['guide'] = $this->rewrite_url($value['module'], $value['guide']);
			}*/
			
			$value['mark'] = str_repeat($mark, $level);
			$nav[] = $value;
			get_nav($type, $value['id'], $level + 1, $current_id, $nav);
		}
	}
	return $nav;
}
 function rewrite_url($module, $id) {
   $id = $id > 0 ? '?id=' . $id : '';
   $url = $module . '.php' . $id;
   echo  site_url() . $url;
}