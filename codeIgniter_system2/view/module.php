<link rel="stylesheet" href="<?php echo $base_url;?>resource/css/admin_admin/admin.css" type="text/css">
<style type="text/css">
#pic_layer1 {position:absolute;display:none;top:100px;left:200px;width:495px;height:350px;background:url(images/pic_layer1_bg.jpg) no-repeat;;text-align:center;z-index:2;text-align:left;line-height:25px;}
#main{border:solid 1px #fff;text-align:left;margin:5px;}
#main .main1{background-color:#ddd;height:28px;}
#main .main2{background-color:#fff;height:28px;}
#main .main1_0{width:5%;margin:0 0 0 2px;height:25px;}
#main .main1_1{width:10%;}
#main .main1_2{width:10%;}
#main .main1_3{width:10%;}
#main .main1_4{width:10%;}
#main .main1_5{width:5%;}
#main .main1_6{width:6%;}
#main .main1_7{width:4%;}
#main .main1_8{width:3%;}
#main .main1_t{width:3%;}
.module_select_list li{float:left;width:100px;color:#584ceb;}

}
</style>
<form name="form2" method="post">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main" class="table_main">
    <tr>
      <td colspan="11" id="navigate_head"><div class="top_nav"> <span class="t_button">
          <div class="t-img"><a href="<?php echo $admin_url;?>module_add"><img style="width: 24px; height: 24px; background-position: 0pt -316px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>module_add">添加模块</a></div>
          </span> <span class="t_button">
          <div class="t-img"><a href="javascript:delArc(0,'<?php echo $admin_url;?>module/delete')"><img style="width: 24px; height: 24px; background-position: 0pt -757px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="javascript:delArc(0,'<?php echo $admin_url;?>module/delete')" style="margin:0 10px 0 0;">删除 &nbsp; </a></div>
          </span> <span class="t_button">
          <div class="t-img"><a class="pay" href="javascript:"><img style="width: 24px; height: 24px; background-position: 0pt -757px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a class="pay" href="javascript:" data-codeid="5"style="margin:0 10px 0 0;">导出网站 &nbsp; </a></div>
          </span>
          <!--<span class="t_button" >
          <div class="t-img"><a href="<?php echo $admin_url;?>product/export_excel/<?php echo time();?>" target="_blank"><img style="width: 24px; height: 24px; background-position: 0pt -484px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>product/export_excel" target="_blank">导出excle</a></div>
        </span>-->
        </div></td>
    </tr>
    <tr class="main2">
      <td class="main1_t"><input type="checkbox" onclick="selAll()" name="goods_id[]" class="sellist" style="width:14px;"></td>
      <td class="main1_0">ID</td>
      <td class="main1_1"> 名称</td>
      <td class="main1_2">数据表名</td>
      <td class="main1_3">&nbsp;类型</td>
      <td class="main1_8">&nbsp;排序</td>
      <td class="main1_4">添加时间</td>
      <td class="main1_5">数据</td>
      <td class="main1_6">前台参考</td>
      <td class="main1_7">前台显示</td>
      <td class="main1_3">操作</td>
    </tr>
    <?php 
$arr_red=array("page_info","photo_list","article1","slide","advertisement","friend_link");	
$i=1;
if(@$this_data){
foreach ($this_data as $row)
{
$increment=($i % 2) ? "main1" : "main2";
   //var_dump($row);
?>
    <tr class="<?php echo $increment;?>">
      <td><input type="checkbox" value="<?php echo $row->id;?>" name="arcID" id="arcID_<?php echo $i-1;?>" class="sel" this_name="<?php echo $row->name;?>"/></td>
      <td><?php echo $row->id;?></td>
      <td><?php echo $row->name;?></td>
      <?php $color_url=(in_array($row->url,$arr_red)) ? "f00" : "333";?>
      <td><a href="<?php echo $admin_url;?><?php echo $row->url;?>" target="_blank" style="color:#<?php echo $color_url;?>"><?php echo $row->url;?></a></td>
      <td style="padding:0 0 0 6px;"><?php echo get_module_type($row->leixing);?></td>
      <td style="padding:0 0 0 18px;"><?php echo $row->sort_order;?></td>
      <td><?php echo get_time($row->postdate);?></td>
      <td><a href="<?php echo $admin_url;?>module_data_match/<?php echo $row->id;?>">测试数据</a></td>
      <td><a href="<?php echo $admin_url;?>module_edit/<?php echo $row->id;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/preview.gif" border="0"/></a> <a href="<?php echo $site_url;?><?php echo $row->url;?>/<?php echo $row->url;?>_add" target="_blank">表单</a> <a href="<?php echo $admin_url;?>module_zip/<?php echo $row->id;?>" target="_blank"><img src="<?php echo $base_url;?>resource/images/admin_admin/cps.gif" border="0"/></a></td>
      <td>&nbsp;<a href="<?php echo $site_url;?><?php echo $row->url;?>" target="_blank"><img src="<?php echo $base_url;?>resource/images/admin_admin/icon_3.gif" border="0"/></a> &nbsp;
        <?php if($row->data_front_style){?>
        <a href="<?php echo $site_url;?><?php echo $row->url;?>" target="_blank"><img src="<?php echo $base_url;?>resource/images/admin_admin/correct.gif" border="0"/>
        <?php }?></td>
      <!-- module_<?php echo $row->
      leixing;?>-->
      <td>&nbsp;<a href="<?php echo $admin_url;?>module_add/module_common/<?php echo $row->id;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/edit.gif" border="0"/></a> &nbsp;<a href="<?php echo $admin_url;?>module/delete/<?php echo $row->id;?>/<?php echo $current;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/admin_del.gif" border="0" onclick='{if(confirm("该操作不可恢复")){return true;}return false;}'/></a> </td>
    </tr>
    <?php  $i++; }}?>
  </table>
</form>
<div class="pagenation2" style="float:left;"><?php echo $this_page;?></div>
<div id="myModal2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="myModal2_form" action="<?php echo $admin_url;?>module/create_site" method="post" class="form-horizontal form-validate form-modal">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-2px 2px;width:20px;height:20px;"></i>导出网站</h4>
        </div>
        <div class="modal-body">
          <div class="control-group">
            <label class="control-label" for="price">导出目录</label>
            <div class="controls">
              <div class="input-append">
                <input type="text" placeholder="目录名称" name="www_root" id="www_root" class="input-large" value="codeIgniter_1" data-rule-required="true"/>
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="price">导出数据库名称</label>
            <div class="controls">
              <input type="text" placeholder="数据库名称" name="database_name" id="database_name" value="codeIgniter_1" class="input-large" data-rule-required="true"/><input name="module_item" id="module_item" type="hidden" value="" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="price">后台地址</label>
            <div class="controls">
              <input type="text" placeholder="后台地址" name="admin_dir" id="admin_dir" class="input-large" value="admin_admin" data-rule-required="true" style="width:100px;"/>
              <select name="select_program_type" id="select_program_type" style="width:100px;height:26px;">
              <option>全部</option>
                <option value="html5">导出html5</option>
                 
               </select>
              <select name="select_program" id="select_program" style="width:120px;height:26px;">
                <option value="">导出所有</option>
                 <option value="yii2">Yii2</option>
                 <option value="codeIgniter">CodeIgniter</option>
                 <option value="codeIgniter_code">CodeIgniter_代码</option>
               </select>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="price">选择的模块</label>
          <ul style="clear:both;margin-left:30px;" class="module_select_list">
          </ul>
          </div>
          <div class="control-group">
            <label class="control-label" for="price">选择的后台模板</label>
          <ul style="clear:both;margin-left:30px;">
          <li><?php echo $module_admin->url;?></li>
          <li><img src="<?php echo $base_url;?>resource/images/admin_admin/admin_0<?php echo str_replace("admin","",$module_admin->url);?>.jpg" style="width:500px;"></li>
          </ul>
          </div>
        </div>
        <div class="modal-footer">
        <div style="float:left;width:160px;text-align:center;" class="create_site_left"> </div>
         <div style="float:left;"><button type="button" class="btn btn-primary" onclick="javascript:create_site();">提交</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">取消</button></div>
          
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<?php if($success_bind=="success_bind"){?>
<!--<script type="text/javascript">top.window.location.href="<?php echo $admin_url;?>";</script>-->
<?php }?>
<script type="text/javascript">
    $(function () {
    $(".more_value").each(function(){
		
        $(this).find('.btnGrayS').click(function(){
            var more_value = $(this).parents(".more_value");
            //读取当前单项文本的数量
            var num = $(".more_value").size();
            if (num < 10) {
                newsingle = $(more_value).clone(true);
                newsingle.appendTo(".singlebody_more_value");
                //清空文本
                $(newsingle).find('input').val('');
                hanghao("more_value");
            } else {
                alert("最多能添加10项");
            }
        });
        //删除
        $(this).find('.more_value_del').click(function(){
            var more_value = $(this).parents(".more_value");
            var num = $(".more_value").size();
            if (num == 1) {
                //清空值
                $(more_value).find('input').val('');
            } else {
                $(more_value).remove();
                hanghao("more_value");
            }
        });
    });
	})
</script>
<script type="text/javascript">
$("#www_root").focusin(function() {
    $(this).attr("v", $(this).val());
  }).focusout(function() {
    var this_v = $(this).val();
    var old_this_v = $(this).attr("v");
    if(this_v == old_this_v) {return;}
    $("#database_name").val(this_v);
    admin_dir="admin_"+this_v.replace(/codeIgniter_/,"");
    $("#admin_dir").val(admin_dir);
  });
        $(function () {
			//$("#myModal_enum").modal("show");
		
            $("tr").delegate(".pay", "click", function () {
                $("#price").val("");
                $("#codeid").val($(this).attr("data-codeid"));
				//select item
				var allSel="";
				if(document.form2.arcID.value) return document.form2.arcID.value;
				for(i=0;i<document.form2.arcID.length;i++)
				{
					if(document.form2.arcID[i].checked)
					{
						if(allSel=="")
							allSel="<li>"+$("#arcID_"+i).attr("this_name")+"</li>";
						else
							allSel=allSel+"<li>"+$("#arcID_"+i).attr("this_name")+"</li>";
					}
				}
				allSel=(allSel) ? allSel : "<li style='color:#f00;width:400px;text-align:center;'>您没有选择任何模块!</li>";
				$(".module_select_list").html(allSel);
                $("#myModal2").modal("show");

            });
	})
function create_more_value()
{
    more_value=$("#form_more_value").serialize();
    url="<?php echo $admin_url;?>module/more_value_ajax";
    $.post(url,more_value,function (res)
    {

    })
}        
function create_site()
{
	var qstr=getCheckboxItem();
	$("#module_item").val(qstr);
	$(".create_site_left").html("<img src='<?php echo $base_url;?>resource/images/admin_admin/loadding.gif' />");
	$.ajax({
	type: "POST",
	dataType: "json",
	url:"<?php echo $admin_url;?>module/create_site/"+$("#www_root").val()+"/"+$("#database_name").val()+"/"+$("#admin_dir").val(),
	data:$('#myModal2_form').serialize(),
	success: function(result) {
		$(".create_site_left").html('');
		$("#myModal2").modal("hide");
		if(result.url)
		{
			location.href=result.url;
		}
		else
		{
			alert(result.info);
		}
		 
	}
	})
}	
</script>
