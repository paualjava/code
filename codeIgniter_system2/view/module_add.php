<script type="text/javascript" src="<?php echo $base_url;?>resource/js/<?php echo $admin_dir;?>/jquery.multiSelect.js"></script>		
<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/css/<?php echo $admin_dir;?>/jquery.multiSelect.css">
<style type="text/css">
.proa_table_class{background:#ddd}
.proa_table_class td{background:#fff;padding:4px;}
.require{color:#f00;}
body{margin:0;}
.controls>.radio:first-child{padding-top:0px}
.radio.inline{padding-top:0}
</style>
<script type="text/javascript">
$(document).ready( function() {
				$("#control_1").multiSelect();				
			});
			
</script>
<script type="text/javascript">

function add_submit_struct()
{
		var table_name_zh= $.trim($("#table_name_zh").val());
		var table_structure= $.trim($("#table_structure").val());
		$("#table_name_zh").css("border","solid 1px #ccc");
		$("#table_structure").css("border","solid 1px #ccc");
		$(".error_info2").html("");
		if(table_name_zh=="")
		{
		  $("#table_name_zh").css("border","solid 1px #f00");
		  $("#table_name_zh").focus();	 
		  $(".error_info2").html("请输入中文名称");
		}
		else if(table_structure=="")
		{
		  $("#table_structure").css("border","solid 1px #f00");
		  //$("#table_structure").focus();	 
		  $(".error_info2").html("请输入表结构");
		}
		else
		{
	$.ajax({
	type: "POST",
	url:"<?php echo $admin_url;?>module_add/ajax_valid2/",
	data:$('#post_form2').serialize(),
	success: function(result) {

				if(result>0)
				{
					alert(result);
					$(".error_info2").html("数据表名重复,请修改");
					return false;	
				}
				else if(result=="db_no_comment")
				{
					$("#table_structure").css("border","solid 1px #f00");
					$(".error_info2").html("数据表的列要有COMMENT注释");
					$("#table_structure").focus();	
				}
				else if(result=="db_null")
				{
					$("#table_name_zh").css("border","solid 1px #f00");
					$(".error_info2").html("数据表结构不正确");
					$("#table_name_zh").focus();	
				}
				else if(result=="db_error")
				{
					$("#table_structure").css("border","solid 1px #f00");
					$(".error_info2").html("数据表结构不正确");
					$("#table_structure").focus();	
				}
				else
				{
				$("#table_name_zh").css("border","solid 1px #ccc");
				$('#post_form2').submit();
				}
			
	},
	error:function (XMLHttpRequest, textStatus, errorThrown)
	{
		$("#table_structure").css("border","solid 1px #f00");
		var reCat = /.*<body>(.*)<\/body>[^<]*<\/html>/gi;
		text2=XMLHttpRequest.responseText;
		text3=text2.replace(reCat,"$1");
		$(".error_info2").html(text3);
		$("#table_structure").focus();
	}
 });
 
}return true;}
function add_submit()
{
		var table_name= $("#table_name").val();
		var table_name_zh= $("#table_name_zh").val();
		$("#table_name").css("border","solid 1px #ccc");
		$("#table_name_zh").css("border","solid 1px #ccc");
		$(".error_info").html("");
		if(table_name=="")
		{
		  $(".error_info").html("请输入数据表名");
		  $("#table_name").focus();	 		
		}
		else if(table_name_zh=="")
		{
		  $("#table_name_zh").css("border","solid 1px #f00");
		  $("#table_name_zh").focus();	 
		  $(".error_info").html("请输入中文名称");
		}
		else
		{
	$.ajax({
	type: "POST",
	url:"<?php echo $admin_url;?>module_add/ajax_valid/",
	data:$('#post_form3').serialize(),
	success: function(result) {
				if(result=="bad")
				{
					$("#table_name").css("border","solid 1px #f00");
					$(".error_info").html("数据表名格式不对,字母或者下划线开头");
					$("#table_name").focus();	
				}			
				else if(result>0)
				{
					$("#table_name").css("border","solid 1px #f00");
					$(".error_info").html("数据表名重复,请重新填写");
					$("#table_name").focus();	
				}
				else if(result=="db_error")
				{
					$(".error_info").html("数据表结构不正确");
				}
				else
				{
				$("#table_name").css("border","solid 1px #ccc");
				$('#post_form3').submit();
				}
	},
	error:function (XMLHttpRequest, textStatus, errorThrown)
	{
		$(".error_info").html(XMLHttpRequest.responseText);
	}
 });

}
}

</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main" class="table_main">
  <tr>
    <td colspan="9" id="navigate_head"><div class="top_nav"> <span class="t_button">
        <div class="t-img"><a href="<?php echo $admin_url;?>module_add"><img style="width: 24px; height: 24px; background-position: 0pt -316px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
        <div class="t-text"><a href="<?php echo $admin_url;?>module_add">添加模块</a></div>
        </span> <span class="t_button">
        <div class="t-img"><a href="javascript:delArc(0,'<?php echo $admin_url;?>module/delete')"><img style="width: 24px; height: 24px; background-position: 0pt -757px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
        <div class="t-text"><a href="javascript:delArc(0,'<?php echo $admin_url;?>module/delete')" style="margin:0 10px 0 0;">删除 &nbsp; </a></div>
        </span>
        <!--<span class="t_button" >
          <div class="t-img"><a href="<?php echo $admin_url;?>product/export_excel/<?php echo time();?>" target="_blank"><img style="width: 24px; height: 24px; background-position: 0pt -484px;" class="imgbundle icon" src="<?php echo $base_url;?>resource/images/admin_admin/transparent.gif"></a></div>
          <div class="t-text"><a href="<?php echo $admin_url;?>product/export_excel" target="_blank">导出excle</a></div>
        </span>-->
      </div></td>
  </tr>
</table>
<?php if($type==""){?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" id="main" class="table_main">
  <form class="cmxform" action="" method="post" id="post_form1" onsubmit="return add_submit()">
    <tr>
      <?php 
$i=1;
$get_module_type=get_module_type();
foreach ($get_module_type as $key=>$row)
{

   //var_dump($row);
?>
      <td style="height:30px;padding:4px 0 4px 20px;"><input type="radio" name="module" value="module_<?php echo $key;?>" id="cb0" <?php if($i==1){?>checked="checked"<?php }?>>
        <?php echo $row;?></td>
      <?php if($i % 2==0){?>
    </tr>
    <tr>
      <?php }?>
      <?php  $i++; }?>
    </tr>
    <tr>
      <td colspan="2" style="padding:0 0 0 20px"><input type="submit" class="btn btn-primary ui-wizard-content ui-formwizard-button ui-helper-reset ui-state-default ui-state-active" value="提 交">
</td>
    </tr>
  </form>
</table>
<?php }else{
if(preg_match("/^module_.*$/is",$type)){?>

<div id="post" style="margin:10px 0 0 15px;">
<?php if($type=="module_table_structure")
{?>
<form class="cmxform module_table" action="" method="post" id="post_form2">
 <div>中文名称:
      <input name="table_name_zh" id="table_name_zh" type="text" />
    </div>
 <table width="96%" border="0" cellpadding="1" cellspacing="1"  class="proa_table_class">
<tr>
<td><textarea name="table_structure"  id="table_structure" cols="" rows="" style="width:800px;height:300px;"></textarea></td>
</tr>
<tr>
        <td ><div style="float:left;">选择后台菜单位置:
		<select name="category" id="category">
        <option value="0">选择后台菜单位置</option>
        <?php echo $show_category;?>
      </select>
           
          </div>
          <div style="float:left;margin-left:40px;">选择类型:
          
           <?php $select_create1=(@$mod_row->create_view==0) ? 'selected="selected"' : "";?>
		   <?php $select_create2=(@$mod_row->create_view==1) ? 'selected="selected"' : "";?>
		<select name="create_type" id="create_type">
        <option value="" <?php echo $select_create1;?>>默认</option>
       <option value="create_view"  <?php echo $select_create2;?>>信息查看</option>
      </select>
      
           
          </div></td>
      </tr>
      <tr>
        <td><input type="button" value="提交" class="submitBtn" onclick="add_submit_struct();"/> </td>
      </tr>
	  </table>
	  </form>

<?php }else{?>
<form class="form-horizontal form-wizard ui-formwizard ui-helper-reset ui-widget ui-widget-content ui-corner-all wj_form" novalidate="novalidate" action="" method="post" id="post_form3">
 <div class="step ui-formwizard-content ui-helper-reset ui-corner-all rightsum" id="firstStep" style="display: block;">
  
    <div>数据表名:
	<?php $table_name=($table_url2) ? $table_url2 : str_replace("module_","",$type);?>
      <input name="table_name" id="table_name" type="text"  value="<?php echo $table_name;?>" class="wizard-ignore"/>
      &nbsp; &nbsp; 中文名称:
      <input name="table_name_zh" id="table_name_zh" type="text" value="<?php echo $table_name2;?>" data-rule-required="true"/>
	  <input name="first_time" id="first_time" type="hidden" value="1" class="wizard-ignore" />
     &nbsp; &nbsp; <a href="Javascript:window.history.go(-1)">返回</a></div>
    <table width="96%" border="0" cellpadding="1" cellspacing="1"  class="proa_table_class step1">
      <tr>
	  <th width="18"></th>
        <th width="80">字段</th>
        <th width="100">类型</th>
        <th width="50">长度/值</th>
        <th width="100">整理</th>
        <th width="100">属性</th>
        <th width="50">Null</th>
        <th width="50">默认</th>
        <th width="80">额外</th>
        <th width="100">注释</th>
		<th width="80">字段类型</th>
		<th width="60"></th>
      </tr>
<?php 
function get_pic_bg($type)
{	
	$class_name="";
	if($type=="pic")
	$class_name="icon_2";
	elseif($type=="editor")
	$class_name="icon_3";
	elseif($type=="cate")
	$class_name="icon_4";
	elseif($type=="cate_more")
	$class_name="icon_5";
	elseif($type=="postdate")
	$class_name="icon_1";
	elseif($type=="is_show")
	$class_name="icon_6";
	return $class_name;
}
?> 	  
      <?php 
	 
$i=1;
foreach($table as $t_row){

?>
      <tr>
        <?php 
$array2=array("VARCHAR","TINYINT","TEXT","DATE","SMALLINT","MEDIUMINT","INT","BIGINT","FLOAT","DOUBLE","DECIMAL","DATETIME","TIMESTAMP","TIME","YEAR","CHAR","TINYBLOB","TINYTEXT","BLOB","MEDIUMBLOB","MEDIUMTEXT","LONGBLOB","LONGTEXT","ENUM","SET","BIT","BOOL","BINARY","VARBINARY");
foreach($array2 as $key=>$v)
{
	$array2[$key]=strtolower($v);
}
$array4=array("","utf8_general_ci");
$array5=array("","unsigned");
$array6=array("NOT NULL","NULL");
$array8=array("","auto_increment");
$array_type=array("pic"=>"图片上传","editor"=>"文本编辑器","cate_simple"=>"分类下拉简化","cate_more_simple"=>"分类多选简化","cate"=>"分类下拉","cate_more"=>"分类多选","postdate"=>"时间(10位整数)","click"=>"点击次数","is_show"=>"审核显示","sort_order"=>"排序从大到小","pic_multiple"=>"图片批量上传","value_multiple"=>"多值输入","more_value"=>"enum值","input_text"=>"文本输入框","file_upload"=>"文件上传","ip_address"=>"IP地址");
$j=1;
foreach($t_row as $row_name){

if($j<=10){
$class_td_1=($j==1) ? ' class="icon_column_td" style="padding:0;"' : "";?>
        <td <?php echo $class_td_1;?>>
<?php if($j==2)
{?>

<select name="field_<?php echo $i;?>_<?php echo $j;?>" class="wizard-ignore">

<?php foreach($array2 as $array2_v){

$select=(strtolower($array2_v)==$row_name) ? 'selected="selected"' : '';?>
 <option value="<?php echo $array2_v;?>" <?php echo $select;?>><?php echo $array2_v;?></option>
<?php }?>
  </select>
<?php } elseif($j==4)
{?>
<select name="field_<?php echo $i;?>_<?php echo $j;?>" class="wizard-ignore">
<?php foreach($array4 as $array4_v){
$select=($array4_v==$row_name) ? 'selected="selected"' : '';?>
 <option value="<?php echo $array4_v;?>" <?php echo $select;?>><?php echo $array4_v;?></option>
<?php }?>
  </select>
<?php }elseif($j==5)
{?>
<select name="field_<?php echo $i;?>_<?php echo $j;?>" class="wizard-ignore">
<?php foreach($array5 as $array5_v){
$select=($array5_v==$row_name) ? 'selected="selected"' : '';?>
 <option value="<?php echo $array5_v;?>" <?php echo $select;?>><?php echo $array5_v;?></option>
<?php }?></select><?php }elseif($j==6)
{?>
<select name="field_<?php echo $i;?>_<?php echo $j;?>" class="wizard-ignore">
<?php foreach($array6 as $array6_v){
$select=($array6_v==$row_name) ? 'selected="selected"' : '';?>
 <option value="<?php echo $array6_v;?>" <?php echo $select;?>><?php echo $array6_v;?></option>
<?php }?></select><?php }elseif($j==8)
{?>
<select name="field_<?php echo $i;?>_<?php echo $j;?>" style="width:80px;" class="wizard-ignore">
<?php foreach($array8 as $array8_v){
$select=($array8_v==$row_name) ? 'selected="selected"' : '';?>
 <option value="<?php echo $array8_v;?>" <?php echo $select;?>><?php echo $array8_v;?></option>
<?php }?>
  </select>
<?php }
elseif($j==10)
{?>
<select name="field_<?php echo $i;?>_<?php echo $j;?>" style="width:80px;" class="wizard-ignore" onchange="javascript:set_column_icon(this)" attr_next="field_<?php echo $i;?>_<?php $jj=$j+1; echo $jj;?>">
<option value="">默认类型</option>
<?php foreach($array_type as $array_type_key=>$array_type_v){
$select=($array_type_key==$row_name) ? 'selected="selected"' : '';?>

 <option value="<?php echo $array_type_key;?>" <?php echo $select;?>><?php echo $array_type_v;?></option>
<?php }?>
  </select>
<?php $this_v=@$t_row[0]; $this_v2=@$data_enum[$this_v];?>
<input name="field_<?php echo $i;?>_<?php $jj=$j+1; echo $jj;?>" id="field_<?php echo $i;?>_<?php $jj=$j+1; echo $jj;?>" type="hidden" value='<?php echo $this_v2;?>'/><?php if(@$this_v2){?><img src="<?php echo $base_url;?>resource/images/admin_admin/preview.gif" border="0" class="more_value_pic" attr_value='<?php echo $this_v2;?>' attr_next="field_<?php echo $i;?>_<?php $jj=$j+1; echo $jj;?>" id="field_<?php echo $i;?>_<?php $jj=$j+1; echo $jj;?>_name"><?php }?>
<?php }else{?>		
<?php $style1=($j==3) ? 'style="width:50px;"' : '';?>
<?php $style1=($j==7) ? 'style="width:50px;"' : $style1;?>
<?php $js_fun=($j==1) ? ' onblur="set_type(this)"' : "";?>
<?php $js_fun2=($j==9) ? ' onblur="set_type2(this)"' : "";?>
 <?php $class_name=(get_pic_bg($t_row[9])) ? get_pic_bg($t_row[9]) : "";?>
<?php $pic_bg=($j==1) ? ' <span class="icon_column '.$class_name.'">&nbsp;</span></td><td>' : "";?>
		<?php echo $pic_bg;?><input name="field_<?php echo $i;?>_<?php echo $j;?>" type="text"  value="<?php if($j==7) $row_name=str_ireplace(array("default","'","\""," "),"",$row_name); echo $row_name;?>" <?php echo $style1;?> <?php echo $js_fun;?> <?php echo $js_fun2;?>/>
		<?php }?></td>
        <?php $j++;}}?>
		
		<td><span onclick="javascript:tr_insert(this)">插入</span> <span onclick="javascript:tr_delete(this)">删除</span></td>
        <?php $i++;}?>
      </tr>
      
      <tr>
        <td colspan="12"><div style="float:left">选择后台菜单位置:
		<select name="category" id="category" class="wizard-ignore">
        <option value="0">选择后台菜单位置</option>
        <?php echo $show_category;?>
      </select>
           
          </div>
           <div style="float:left;margin-left:40px;"><div class="control-group">
        <label for="is_show" class="control-label">选择类型：</label>
        <div class="controls" style="padding-top:0px;">
          <label class="radio inline">
          <?php $select_create1=(@$mod_row->create_view==0) ? 'checked="checked"' : "";?>
		   <?php $select_create2=(@$mod_row->create_view==1) ? 'checked="checked"' : "";?>
          <input type="radio" name="create_type" id="create_type" value="" <?php echo $select_create1;?>/>
          默认</label>
          <label class="radio inline">
          <input type="radio" name="create_type" id="create_type" value="create_view" <?php echo $select_create2;?>/>
          只编辑模式</label>
        </div>
      </div>
           
          </div>
          <div style="float:left;margin-left:40px;"><div class="control-group">
        <label for="is_show" class="control-label">laravel时间戳：</label>
        <div class="controls" style="padding-top:0px;">
          <label class="radio inline">
          <?php $select_create1=(@$mod_row->laravel_timestamp==1) ? 'checked="checked"' : "";?>
		   <?php $select_create2=(@$mod_row->laravel_timestamp==0) ? 'checked="checked"' : "";?>
          <input type="radio" name="laravel_timestamp" id="laravel_timestamp" value="1" <?php echo $select_create1;?>/>
          默认</label>
          <label class="radio inline">
          <input type="radio" name="laravel_timestamp" id="laravel_timestamp" value="0" <?php echo $select_create2;?>/>
          无</label>
        </div>
      </div>
           
          </div></td>
      </tr>
      <tr>
        <td colspan="12"><input name="url6" type="hidden" value="<?php echo $url6;?>" class="wizard-ignore"/> </td>
      </tr>
    </table>
	

  </div>
  <div class="step ui-formwizard-content ui-helper-reset ui-corner-all rightsum" id="secondStep" style="display: none;">
  
  </div>
  <div class="span12">
                                    <div class="form-actions">
                                      
										 <input type="reset" class="btn ui-wizard-content ui-formwizard-button ui-helper-reset ui-state-default ui-state-disabled" value="后退" id="back" disabled="disabled" onclick="step_cancle_click()">
                                        <input type="submit" class="btn btn-primary ui-wizard-content ui-formwizard-button ui-helper-reset ui-state-default ui-state-active" value="下一步" id="next" onclick="step_click()">
                                    </div>
									
                                </div>
								<div style="clear:both;"><span class="error_info" style="color:#f00;"></span></div>
								 </form>
								 
<div id="myModal_enum" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="form_more_value" action="<?php echo $admin_url;?>module/create_site" method="post" class="form-horizontal form-validate form-modal">
      <input type="hidden" id="more_value_col"/>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><i class="icon-check member_icon" style="background-position:-2px 2px;width:20px;height:20px;"></i>枚举类型</h4>
        </div>
        <div class="modal-body">
          
          <div class="control-group">
  <label for="sort_order" class="control-label" style="text-align:left;padding-left:5px;">排序：</label>
  <div class="controls" style="margin-left:5px;">
    <table id="listTable" class="table table-bordered table-hover dataTable">
      <thead>
        <tr>
          <th>字段类型</th>
          <th>字段名称</th>
          <th>初始内容</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody class="singlebody_more_value">
        <tr class="more_value" >
          <td>排序<span class="num_more_value">1</span>：</td>
          <td><input type="text" name="more_value_txt[]" id="txt1" value=""  class="input-small" ></td>
          <td><input name="more_value_value[]" id="value1" type="text" class="input-small"  ></td>
          <td><p><a class="btnGrayS vm" href="javascript:void(0);">添加</a>　<a class="more_value_del" href="javascript:void(0);">删除</a></p></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
          
          
          
        </div>
        <div class="modal-footer">
        <div style="float:left;width:160px;text-align:center;" class="create_site_left"> </div>
         <div style="float:left;"><button type="button" class="btn btn-primary" onclick="javascript:create_more_value();">提交</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">取消</button></div>
          
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>			
<script type="text/javascript">
function more_value_pic()
{
	$(".more_value_pic").each(function ()
	{
		$(this).on('click',function ()
		{
			more_value=$(this).attr('attr_value');
			this_next=$(this).attr('attr_next');
			url="<?php echo $admin_url;?>module/more_value_ajax_set_value";
			$.post(url,{"more_value":more_value},function (res)
			{
				$(".singlebody_more_value").html(res.info);
				$("#more_value_col").val(this_next);
				$("#myModal_enum").modal("show");
				$(".more_value").each(function(){
		
        $(this).find('.btnGrayS').on('click',function(){
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
        $(this).find('.more_value_del').on('click',function(){
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
			},"json")
			
		})
	})
}

    $(function () {
	more_value_pic();
    $(".more_value").each(function(){
		
        $(this).find('.btnGrayS').on('click',function(){
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
        $(this).find('.more_value_del').on('click',function(){
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
function create_more_value()
{
    more_value=$("#form_more_value").serialize();
    url="<?php echo $admin_url;?>module/more_value_ajax";
    more_value_col=$("#more_value_col").val();
    $.post(url,more_value,function (res)
    {
		$("#"+more_value_col+"_name").attr("attr_value",res.info);
    	$("#"+more_value_col).val(res.info);
    	$("#myModal_enum").modal("hide");
    },"json")
}   
</script>					 
  <script type="text/javascript">
function set_type2(this_v)
{
	this_v2=$(this_v).val().toLowerCase();
	input_val=$(this_v).parent().parent().children("td").eq(1).find("input").val();
	if(input_val.indexOf("..."))
	{
		url="<?php echo $admin_url;?>module/ajax_tranlate2";
		data={'keyword':this_v2}
		$.post(url,data,function (res)
		{
			if(res.info)
			$(this_v).parent().parent().children("td").eq(1).find("input").val(res.info);
		},"json")
	}
}
function set_type(this_v)
{
	option_v="";
	this_v2=$(this_v).val().toLowerCase();
	if(this_v2.indexOf("time")>=0 || this_v2.indexOf("postdate")>=0)
	{
		option_v="postdate";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("int");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("10");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("时间");
	}
	if(this_v2.indexOf("text")>=0 || this_v2.indexOf("info")>=0)
	option_v="editor";
	if(this_v2.indexOf("pic")>=0)
	{
		$(this_v).parent().parent().children("td").eq(2).find("select").val("varchar");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("图片");
		option_v="pic";
	}
	if(this_v2.indexOf("cate")>=0)
	option_v="cate";
	if(this_v2.indexOf("is_show")>=0)
	{
		option_v="is_show";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("tinyint");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("1");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("审核显示");
	}
	if(this_v2.indexOf("content")>=0)
	{
		option_v="editor";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("longtext");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("内容");
	}
	if(this_v2.indexOf("sort_order")>=0)
	{
		option_v="sort_order";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("int");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("10");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("排序");
	}
	if(this_v2.indexOf("click")>=0)
	{
		option_v="click";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("int");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("10");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("点击");
	}
	
	if(this_v2.indexOf("ip_address")>=0)
	{
		option_v="ip_address";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("varchar");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("20");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("IP");
	}
	if(this_v2.indexOf("file_upload")>=0)
	{
		option_v="file_upload";
		$(this_v).parent().parent().children("td").eq(2).find("select").val("varchar");
		$(this_v).parent().parent().children("td").eq(3).find("input").val("200");
		$(this_v).parent().parent().children("td").eq(9).find("input").val("文件上传");
	}
	
	$(this_v).parent().parent().children("td").eq(10).find("select").val(option_v);
	input_val=$(this_v).parent().parent().children("td").eq(9).find("input").val();
	if(input_val.indexOf("..."))
	{
		url="<?php echo $admin_url;?>module/ajax_tranlate";
		data={'keyword':this_v2}
		$.post(url,data,function (res)
		{
			if(res.info)
			$(this_v).parent().parent().children("td").eq(9).find("input").val(res.info);
		},"json")
	}
}
function input_backup(this_v)
{
	if($(this_v).val()==1)
	{
	$(this_v).parent().parent().find("input").attr("readonly","readonly");
	$(this_v).parent().parent().find("select").attr("readonly","readonly");
	$(this_v).parent().parent().find("a>span").css("background","#eee");
	$(this_v).removeAttr("readonly").removeAttr("disabled");
	}
	else if($(this_v).val()==2)
	{
	$(this_v).parent().parent().find("input").removeAttr("readonly");
	$(this_v).parent().parent().find("select").removeAttr("readonly");
	$(this_v).parent().parent().find("td").eq(0).find("input").attr("readonly","readonly");
	$(this_v).parent().parent().find("a>span").css("background","#fff");
	$(this_v).removeAttr("readonly");
	}
} 
function set_column_icon(this_v)
{
	input_v=$(this_v).val();
	if(input_v=="pic")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_2");
	}
	else if(input_v=="editor")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_3");
	}
	else if(input_v=="cate")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_4");
	}
	else if(input_v=="cate_more")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_5");
	}
	else if(input_v=="postdate")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_1");
	}
	else if(input_v=="is_show")
	{
		$(this_v).parent().parent().find("span").eq(0).addClass("icon_6");
	}
	else if(input_v=="more_value")
	{
		this_next=$(this_v).attr('attr_next');
		$("#more_value_col").val(this_next);
		$("#myModal_enum").modal("show");
	}
	else
	$(this_v).parent().parent().find("span").eq(0).removeClass("icon_1 icon_2 icon_3 icon_4 icon_5 icon_6");
	//$(".icon_column").attr("background-position","2px,-2px");
	
} 
function step_click()
{
	//$("input").removeAttr("disabled");
	
	if($(".step").eq(0).css("display")=="block")
	{
	$(".error_info").html('');
	$.ajax({
	type: "POST",
	url:"<?php echo $admin_url;?>module_add/ajax_valid/",
	data:$('#post_form3').serialize(),
	success: function(result) {
				if(result=="bad")
				{
					$(".step").eq(0).show();
					$(".step").eq(1).hide();
					$("#table_name").css("border","solid 1px #f00");
					$(".error_info").html("数据表名格式不对,字母或者下划线开头");
					$("#table_name").focus();	
				}			
				else if(result>0)
				{
										$(".step").eq(0).show();
					$(".step").eq(1).hide();
					$("#table_name").css("border","solid 1px #f00");
					$(".error_info").html("数据表名重复,请重新填写");
					$("#table_name").focus();	
				}
				else if(result=="db_error")
				{
										$(".step").eq(0).show();
					$(".step").eq(1).hide();
					$(".error_info").html("数据表结构不正确");
				}
				
	},
	error:function (XMLHttpRequest, textStatus, errorThrown)
	{
		$(".error_info").html(XMLHttpRequest.responseText);
	}
 	});
	$.ajax({
		type: "POST",
		url:"<?php echo $admin_url;?>ajax_module_step",
	data:$('#post_form3').serialize(),
	success: function(result) {
		$(".step").eq(1).html(result);
		$("#control_1").multiSelect();	
		}
		})
	
	}
	$('input').attr("disabled",false);

} 
function step_cancle_click()
{
	$("#first_time").val(0);
}
function tr_insert(this_p)
{
	tipAll=$(this_p).parent().parent().nextAll();
	for(i=0;i<tipAll.length-2;i++)
	{
		html1=$(tipAll[i]).html();
		var current_temp=html1.match(/field_\d+/);
		current_index=current_temp[0].replace("field_","");
		current_index=parseInt(current_index)+1;
		$(tipAll[i]).html($(tipAll[i]).html().replace(/field_\d+_/g,'field_'+current_index+'_'));
	}
	var current_temp=$(this_p).parent().parent().html().match(/field_\d+/);
	current_index=current_temp[0].replace("field_","");
	new_index=parseInt(current_index)+1;
	$(this_p).parent().parent().after('<tr><td  class="icon_column_td" style="padding:0;"><span class="icon_column ">&nbsp;</span></td><td><input name="field_'+new_index+'_1" type="text" value="name..." onblur="set_type(this)"></td><td><select name="field_'+new_index+'_2"><?php foreach($array2 as $array2_v){?><option value="<?php echo $array2_v;?>"><?php echo $array2_v;?></option><?php }?></select></td><td><input name="field_'+new_index+'_3" type="text" value="200" style="width:50px;"></td><td><select name="field_'+new_index+'_4"><?php foreach($array4 as $array4_v){?><option value="<?php echo $array4_v;?>"><?php echo $array4_v;?></option><?php }?></select></td><td><select name="field_'+new_index+'_5"><?php foreach($array5 as $array5_v){?><option value="<?php echo $array5_v;?>"><?php echo $array5_v;?></option><?php }?></select></td><td><select name="field_'+new_index+'_6"><?php foreach($array6 as $array6_v){?><option value="<?php echo $array6_v;?>"><?php echo $array6_v;?></option><?php }?></select></td><td><input name="field_'+new_index+'_7" type="text" value="" style="width:50px;"></td><td><select name="field_'+new_index+'_8" style="width:80px;"><?php $kk=0;foreach($array8 as $array8_v){$select_8=($kk==0) ? 'selected="selected"' : "";?><option value="<?php echo $array8_v;?>" <?php echo $select_8;?>><?php echo $array8_v;?></option><?php $kk++; }?></select></td><td><input name="field_'+new_index+'_9" type="text" value="name..." onblur="set_type2(this)"></td><td><select name="field_'+new_index+'_10" style="width:80px;" onchange="javascript:set_column_icon(this)" attr_next="field_'+new_index+'_11"><option>默认类型</option><?php foreach($array_type as $array_type_key=>$array_type_v){?><option value="<?php echo $array_type_key;?>"><?php echo $array_type_v;?></option><?php }?></select><input name="field_'+new_index+'_11" id="field_'+new_index+'_11" type="hidden" value="" attr_next="field_'+new_index+'_11" id="field_'+new_index+'_11_name"></td><td><span onclick="javascript:tr_insert(this)">插入</span> <span onclick="javascript:tr_delete(this)">删除</span></td></tr>');	
	more_value_pic();
	
}
function tr_delete(this_p)
{
	tipAll=$(this_p).parent().parent().nextAll();
	for(i=0;i<tipAll.length-2;i++)
	{
		html1=$(tipAll[i]).html();
		var current_temp=html1.match(/field_\d+/);
		current_index=current_temp[0].replace("field_","");
		current_index=parseInt(current_index)-1;
		$(tipAll[i]).html($(tipAll[i]).html().replace(/field_\d+_/g,'field_'+current_index+'_'));
	}
	$(this_p).parent().parent().remove();
	more_value_pic();
	
}
</script>
  <?php }?>
  
</div>
<?php }?>
<?php }?>
