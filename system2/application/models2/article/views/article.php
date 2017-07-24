<style type="text/css">
#pic_layer1 {
	position:absolute;
	display:none;
	top:100px;
	left:200px;
	width:495px;
	height:350px;
	background:url(images/pic_layer1_bg.jpg) no-repeat;
	;
	text-align:center;
	z-index:2;
	text-align:left;
	line-height:25px;
}
#main {
	border:solid 1px #fff;
	text-align:left;
	margin:5px;
}
#main .main1 {
	background-color:#eeeeee;
	height:28px;
}
#main .main2 {
	background-color:#fff;
	height:28px;
}
#main .main1_0 {
	width:3%;
	margin:0 0 0 2px;
	height:25px;
}
#main .main1_1 {
	width:40%;
}
#main .main1_2 {
	width:15%;
}
#main .main1_3 {
	width:15%;
}
#main .main1_4 {
	width:15%;
}
#main td{text-align:center}
#main .main3 {background-color:#ddd;}
</style>
<script type="text/javascript">
$(function ()
{
  $(".d_row").each(function ()
    {
  		$(this).mouseover(function ()
			{
				$(this).addClass("main3");
			}),

		$(this).mouseout(function ()
		{
			$(this).removeClass("main3");
		})
	})
})
</script>
<form name="form2" method="post" action="">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main" class="table_main">
    <tr>
      <td colspan="5" id="navigate_head"><div class="top_nav"> <span class="t_button">
          <div class="t-img"><a href="<?php echo $admin_url;?>article_add/<?php echo $current;?>"><img style="width: 24px; height: 24px; background-position: 0pt -316px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>article_add/<?php echo $current;?>">添加文章</a></div>
          </span> <span class="t_button">
          <div class="t-img"><a href="javascript:delArc(0,'<?php echo $admin_url;?>article/delete')"><img style="width: 24px; height: 24px; background-position: 0pt -757px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="javascript:delArc(0,'<?php echo $admin_url;?>article/delete')" style="margin:0 10px 0 0;">删除 &nbsp; </a></div>
          </span> <!--<span class="t_button" >
          <div class="t-img"><a href="<?php echo $admin_url;?>article/export_excel" target="_blank"><img style="width: 24px; height: 24px; background-position: 0pt -484px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>article/export_excel" target="_blank">导出</a></div>
          </span>--> <span class="t_button_r" >
          <table width="600" border="0" cellpadding="0" cellspacing="0" style="margin:8px 0 0 0;">
            <tr>
              
              <td>关键字：<input type="text" style="width: 200px; height: 20px;padding:2px 0 0 2px;height:17px" class="input1" id="title" name="title">
              <input type="hidden" name="search" id="search" value="search" /></td>
              <td><input type="submit" class="submitBtn" value="搜索"></td>
            </tr>
          </table>
          </span> </div></td>
    </tr>
    <tr class="main2">
      <td class="main1_0"><input type="checkbox" onclick="selAll()" name="goods_id[]" class="sellist"></td>
      <td class="main1_1" style="text-align:left">&nbsp;标题</td>
    <td class="main1_2">分类</td>
      <td class="main1_3">时间</td>
      <td class="main1_4">操作</td>
    </tr>
    <?php 
$i=1;
foreach ($this_data as $key=>$row)
{
$increment=($i % 2) ? "main1" : "main2";
   //var_dump($row);
?>
    <tr class="<?php echo $increment;?> d_row">
      <td><input type="checkbox" value="<?php echo $row->id;?>" name="arcID" id="arcID" class="sel"/></td>
      <td style="text-align:left"><a href="<?php echo $web_url;?>news/show/<?php echo $row->id;?>" target="_blank"><?php echo $row->subject;?></a> &nbsp; </td>
     <td><?php echo $row->cate_name;?></td>
      <td><?php echo date("Y-m-d H:i:s",$row->postdate);?></td>
      <td>&nbsp;<a href="<?php echo $web_url;?>admin_admin/index/manager/article/delete/<?php echo $row->id;?>/<?php echo $current;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/admin_del.gif" border="0" onclick='{if(confirm("该操作不可恢复")){return true;}return false;}'/></a> &nbsp; <a href="<?php echo $web_url;?>admin_admin/index/manager/article_edit/<?php echo $row->id;?>/<?php echo $current;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/edit.gif" border="0"/></a> &nbsp; </td>
    </tr>
    <?  $i++; }?>
  </table>
</form>
 <div style="clear:both;" class="pagenation2"><p style="float:left;">共<?php echo $total_record;?>条 &nbsp; </p><p style="float:left"><?php echo $this_page;?></p></div>
