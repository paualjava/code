<kindeditor>
<pic_multiple_include>
<div class="box">
  <div class="box-title">
    <div class="span10" style="float:left">
      <h3><i class="icon-table" style="background-position:-97px -71px"></i>编辑<table_name_zh>
      </h3>
    </div>
    <div class="span2" style="float:left"><a class="btn" href="Javascript:window.history.go(-1)">返回</a></div>
  </div>
  <div class="box-content">
  <form action="" method="post" class='form-horizontal form-validate wj_form' id="post_form">
  <input name="form_is_post" type="hidden" value="1" />
  <input name="<model_column_first>" type="hidden" value="<?php echo $this_data-><model_column_first>;?>" />
    <view_edit>
    <div class="form-actions">
      <?php if(role("friend_link_edit")){?><button id="bsubmit" type="submit" data-loading-text="提交中..." class="btn btn-primary">保存</button><?php }?>
      <button type="button" class="btn" onclick="Javascript:window.history.go(-1)">返回</button>
    </div>
    <kindeditor_file_upload_js>
    <kindeditor_js>
  </form>
   </div>
</div>
</div>
<str_last_edit>
<pic_multiple_js>
<value_multiple_js>
