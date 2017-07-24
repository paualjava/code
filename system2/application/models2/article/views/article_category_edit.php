

<form name="this_form" id="this_form" action="" method="post">
<table width="500" border="0" cellpadding="1" cellspacing="1"  class="table_class">
<tr><td width="150">名称</td><td><input name="name" id="name" type="text" class="input1" style="width:400px;height:20px;" value="<?php echo $this_data->name; ?>"/></td></tr>
<tr><td width="150">别名</td><td><input name="url" id="url" type="text" class="input1" style="width:400px;height:20px;" value="<?php echo $this_data->url; ?>"/></td></tr>
<tr><td>排序</td><td><input name="sort_order" id="sort_order" type="text" class="input1" style="width:400px;height:20px;" value="<?php echo $this_data->sort_order;?>"/><input name="current" type="hidden" id="current"  value="<?php echo $current;?>"/><input name="id" type="hidden" id="id"  value="<?php echo $id;?>"/></td></tr>
<tr><td><input type="submit" value="提交" class="submitBtn"/></td><td><a href="<?php echo $admin_url;?>article_category/<?php echo $current;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/return.gif" border="0" align="absmiddle"/> 返回</a></td></tr>
</table>
</form>

