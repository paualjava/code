<link rel="stylesheet" href="<?php echo $base_url;?>resource/css/admin_admin/admin.css" type="text/css">
<style type="text/css">
#pic_layer1 {position:absolute;display:none;top:100px;left:200px;width:495px;height:350px;background:url(images/pic_layer1_bg.jpg) no-repeat;;text-align:center;z-index:2;text-align:left;line-height:25px;}
#main{border:solid 1px #fff;margin:5px;}
#main .main1_t{width:3%;}
#main .main1{background-color:#ddd;height:28px;}
#main .main2{background-color:#fff;height:28px;}
#main .main1_0{width:5%;margin:0 0 0 2px;height:25px;}
#main .main1_1{width:50%;}
#main .main1_2{width:10%;}
#main .main1_3{width:10%;}
#main .main1_4{width:20%;}
#main .main1_5{width:10%;}
#main td{text-align:center}
.bg_red{ background-color:#66CC99}
}
</style>
<form name="form2" id="form2" action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main" class="table_main">
<tr>
    <td colspan="6" id="navigate_head"><div class="top_nav">
        <span class="t_button">
          <div class="t-img"><a href="<?php echo $admin_url;?>article_category_add"><img style="width: 24px; height: 24px; background-position: 0pt -316px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>article_category_add">添加分类</a></div>
        </span>
         <span class="t_button">
          <div class="t-img"><a href="javascript:delArc(0,'<?php echo $admin_url;?>article_category/delete')"><img style="width: 24px; height: 24px; background-position: 0pt -757px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="javascript:delArc(0,'<?php echo $admin_url;?>article_category/delete')" style="margin:0 10px 0 0;">删除 &nbsp; </a></div>
        </span>
  
      </div>
     </td>
  </tr>
  <tr class="main2">
   <td class="main1_t"><input type="checkbox" onclick="selAll()" name="goods_id[]" class="sellist"></td>
	<td class="main1_1" style="text-align:left"> &nbsp;名称</td>
	<td class="main1_5" style="text-align:left"> &nbsp;别名</td>
   <td class="main1_2" style="text-align:center"> &nbsp;添加子类</td>
<td class="main1_3"> &nbsp;排序</td>
	<td class="main1_4">操作</td>  
  </tr>

<?php 
$i=1;

foreach ($t_line as $key=>$row)
{
$increment=($i % 2) ? "main1" : "main2";
   //var_dump($row);
?>
  <tr class="<?php echo $increment;?> d_row">
   <td><input type="checkbox" value="<?php echo $t_id[$key];?>" name="arcID" id="arcID" class="sel"/></td>
    <td style="text-align:left"> &nbsp;<?php echo $t_line[$key].$t_array[$key];?></td>
	 <td style="text-align:left"> &nbsp;<?php echo $t_url[$key];?></td>
    <td style="text-align:center"><a href="<?php echo $admin_url;?>article_category_add/<?php echo $t_id[$key];?>"><img border="0" style="width: 16px; height: 16px; background-position: 0pt 0px;" class="imgbundle" alt="添加子分类" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></td>
 <td><input name="sort_order<?php echo $t_id[$key];?>" type="text" value="<?php echo $t_order[$key];?>" style="text-align:center"/></td>
	<td> &nbsp;<a href="<?php echo $web_url;?>admin_admin/index/manager/article_category/delete/<?php echo $t_id[$key];?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/admin_del.gif" border="0" onclick='{if(confirm("该操作不可恢复")){return true;}return false;}'/></a>  <a href="<?php echo $admin_url;?>article_category_edit/<?php echo $t_id[$key];?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/edit.gif" border="0"/></a></td>
  </tr>
 
  <?  $i++; }?>	       
</table>
<div style="text-align:right;margin:0 220px 0 0;"><input type="hidden" name="save_order" value="1"/>
<input type="submit" class="submitBtn" value="保存排序"></div>
</form>
