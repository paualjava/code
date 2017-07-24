
<script type="text/javascript" src="<?php echo $base_url;?>resource/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo $base_url;?>resource/ckeditor/ckfinder/ckfinder.js"></script>
<style type="text/css">
.proa_table_class{background:#ddd}
.proa_table_class td{background:#fff;padding:4px;}
.require{color:#f00;}
</style>
<script type="text/javascript">
function add_submit()
{
    	var title= $("input[@name='title']").val();
		var cat_id= $("#category").val();
		if(title=="")
		{
		  alert("请输入标题");
		  $("input[@name='title']").focus();	 
		  return false;
		}
		else if(cat_id=="")
		{
		  alert("请选择分类");
		  $("#category").focus();	 
		  return false;	
		}
		else
		return true;
}
</script>
<div id="post" style="margin:10px 0 0 15px;">

  <form action="" method="post" enctype="multipart/form-data" class="cmxform" id="post_form" onsubmit="return add_submit()">
    <table width="95%" border="0" cellpadding="1" cellspacing="1"  class="proa_table_class">
      <tr>
        <td width="100">文章标题<span class="require">*</span></td>
        <td> <input name="title" id="title" type="text" class="input1" style="width:400px;height:20px;"/> &nbsp; <a href="<?php echo $admin_url;?>article/<?php echo $current;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/return.gif" border="0" align="absmiddle"/> 返回</a></td>
      </tr>
      <tr>
        <td>文章分类<span class="require">*</span></td>
        <td>       <select name="category" id="category">
        <option value="">请选择分类</option>
        <?php echo $category;?>
      </select></td>
      </tr>
      <tr>
        <td>文章内容</td>
        <td> <textarea name="page_text"></textarea>
    <script type="text/javascript">
  CKEDITOR.replace( 'page_text',
{
filebrowserBrowseUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/ckfinder.html',
filebrowserImageBrowseUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/ckfinder.html?Type=Images',
filebrowserFlashBrowseUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/ckfinder.html?Type=Flash',
filebrowserUploadUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
filebrowserImageUploadUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
filebrowserFlashUploadUrl : '<?php echo $base_url;?>resource/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
</script></td>
      </tr>
      <tr>
        <td></td>
        <td> <input type="submit" value="提交" class="submitBtn"/></td>
      </tr>
        
    </table>

  </form>

</div>