<kindeditor>
<pic_multiple_include>
<div class="box">
  <div class="box-title">
    <div class="span10" style="float:left">
      <h3><i class="icon-pencil"></i>添加<table_name_zh>
      </h3>
    </div>
    <div class="span2" style="float:left"><a class="btn" href="Javascript:window.history.go(-1)">返回</a></div>
  </div>
  <div class="box-content">
    <form action="" method="post" class="form-horizontal form-validate" id="post_form">
	<input name="form_is_post" type="hidden" value="1" />
      <view_add>
      <div class="form-actions">
        <?php if(role("friend_link_add")){?><button id="bsubmit" type="submit" data-loading-text="提交中..." class="btn btn-primary">保存</button><?php }?>
        <button class="btn" onclick="Javascript:window.history.go(-1)">返回</button>
      </div>
    </form>
  </div>
</div>
<kindeditor_file_upload_js>
<kindeditor_js> 
<str_last>
<pic_multiple_js>
<value_multiple_js>