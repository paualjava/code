<link rel="stylesheet" href="<?php echo $base_url;?>resource/kindeditor-4.1.9/themes/default/default.css" />
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/kindeditor-min.js"></script>
<script charset="utf-8" src="<?php echo $base_url;?>resource/kindeditor-4.1.9/lang/zh_CN.js"></script>
<div class="box">
  <div class="box-title">
    <div class="span10" style="float:left">
      <h3><i class="icon-pencil"></i>添加<column_name_zh> </h3>
    </div>
    <div class="span2" style="float:left"><a class="btn" href="Javascript:window.history.go(-1)">返回</a></div>
  </div>
  <div class="box-content">
    <form action="" method="post" class="form-horizontal form-validate" id="post_form">
      <input name="form_is_post" type="hidden" value="1" />
      <div class="control-group">
        <label for="name" class="control-label">名称：</label>
        <div class="controls">
          <input type="text" name="name" id="name" value="" class="input-xlarge ui-wizard-content ui-helper-reset ui-state-default valid" data-rule-required="true" >
          <span class="maroon">*</span><span class="help-inline">请输入名称</span> <span for="name" class="help-block error valid"></span></div>
      </div>
      <div class="control-group">
        <label for="parent_id" class="control-label">父类：</label>
        <div class="controls">
         <select id="parent_id" name="parent_id" class="input-xlarge ui-wizard-content ui-helper-reset ui-state-default valid" style="width:280px;"><option value="0">根分类</option>
										<?php show_class_select($table_name,0,0,$parent_id);?></select>

          <span class="help-inline">请输入父类</span> <span for="parent_id" class="help-block error valid"></span></div>
      </div>
      <div class="control-group">
        <label for="url" class="control-label">seo_url：</label>
        <div class="controls">
          <input type="text" name="url" id="url" value="" class="input-xlarge ui-wizard-content ui-helper-reset ui-state-default valid" >
          <span class="help-inline">请输入seo_url</span> <span for="url" class="help-block error valid"></span></div>
      </div>
      <div class="control-group">
        <label for="txt" class="control-label">图片：</label>
        <div class="controls">
          <input class="input-xlarge ui-wizard-content ui-helper-reset ui-state-default valid" name="pic" type="text" value="" >
          <span class="help-inline"><a class="btn insertimage">上传图片</a></span>
          <div><img src="" style="max-width:360px;margin:10px 0 2px 0;"></div>
        </div>
      </div>
      <div class="control-group">
      <label for="is_show" class="control-label">审核：</label>
      <div class="controls">
        <label class="radio inline">
          <input type="radio" name="is_show" id="is_show" value="1" checked="checked"/>
          是</label>
        <label class="radio inline">
          <input type="radio" name="is_show" id="is_show" value="0" />
          否</label>
      </div></div>
      <div class="control-group">
        <label for="sort_order" class="control-label">排序：</label>
        <div class="controls">
          <input type="text" name="sort_order" id="sort_order" value="0" class="input-small ui-wizard-content ui-helper-reset ui-state-default valid" data-rule-digits="true" >
          <span class="help-inline">请输入排序</span> <span for="sort_order" class="help-block error valid"></span></div>
      </div>
      <div class="control-group">
        <label for="info" class="control-label">备注：</label>
        <div class="controls">
          <textarea class="input-xlarge" name="info" id="info" ></textarea>
          <span class="help-inline">请输入备注</span> </div>
      </div>
      <div class="form-actions">
        <button id="bsubmit" type="submit" data-loading-text="提交中..." class="btn btn-primary">保存</button>
        <button class="btn" onclick="Javascript:window.history.go(-1)">返回</button>
      </div>
    </form>
  </div>
</div>
<script>

                        var seting = {
                                themeType: "simple",
                                resizeType: 1,
                                syncType:"",
                                allowPreviewEmoticons: false,
                                items: [
        'source', 'undo', 'redo', 'plainpaste', 'plainpaste', 'wordpaste', 'clearhtml', 'quickformat', 'selectall', 'fullscreen', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline', 'hr',
        'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
        'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'unlink', 'baidumap'],
                                allowFileManager: true,
                                minWidth: 600,
                                width: 800,
								height: 200,
                                afterCreate: function () {
                                    this.sync();
                                },
                                afterBlur: function () {
                                    this.sync();
                                }
                            }
                            KindEditor.ready(function (K) {
                               var editor1 = K.create('#info', seting)

                                K('a.insertimage').click(function (e) {
                                    editor1.loadPlugin('smimage', function () {
                                        editor1.plugin.imageDialog({
                                            imageUrl: $(e.target).parent().prev().val(),
                                            clickFn: function (url, title, width, height, border, align) {
                                            	$(e.target).parent().prev().val(url);
                                                $(e.target).parent().next().find("img").attr("src",url);
                                                editor1.hideDialog();
                                            }
                                        });
                                    });
                                });
                            });
                        </script> 
