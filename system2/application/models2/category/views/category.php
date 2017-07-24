<div class="box">
  <div class="box-title">
    <div class="span8" style="float:left">
      <h3><i class="icon-table" style="background-position:-265px 1px"></i><column_name_zh>管理</h3>
    </div>
    <div class="pull-right"><a class="btn" href="Javascript:window.history.go(-1)">返回</a></div>
  </div>
  <div class="box-content">
    <div class="row-fluid">
      <div class="span12 control-group">
        <div class="span7"> <a class="btn" href="<?php echo $admin_url;?>category_add"><i class="icon-plus"></i>添加<column_name_zh></a> &nbsp;<a class="btn" href="javascript:location.reload()"><i class="icon-refresh"></i>刷新</a></div>
      </div>
    </div>
    <div class="row-fluid dataTables_wrapper">
      <form name="form2" method="post">
        <table id="listTable" class="table table-bordered table-hover dataTable">
          <tr class="main2">
            <th>ID</th>
            <th>分类名称</th>
            
            <th>图片</th>
            <th>审核</th>
            <th>seo_url</th>
            <th>排序</th>
            <th style="width:100px">操作</th>
          </tr>
          <?php echo show_class_list($table_name,'');?>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript">
$(".is_show").each(function ()
{
	$(this).live("click",function ()
	{
		sort_id=$(this).parent().parent().find("td").eq(0).html();
		this_url="<?php echo $admin_url?>category/ajax_save_is_show/"+sort_id+"/+?rand=<?php echo mt_rand(1,999);?>";
		var a=$(this);
		$.post(this_url,{},function (result)
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

</script>
<script type="text/javascript">
$(".sort_order").focusin(function() {
		$(this).attr("v", $(this).val());
	}).focusout(function() {
		var orderby = $(this).val();
		var old_orderby = $(this).attr("v");
		if(orderby == old_orderby) {return;}
		sort_id=$(this).parent().parent().find("td").eq(0).html();
		this_url="<?php echo $admin_url?>category/ajax_save_sort_order";
		$.post(this_url,{id:sort_id, orderby:orderby}, function(data){
			//if(data.err==0) //get_data();
		});
	});
</script> 