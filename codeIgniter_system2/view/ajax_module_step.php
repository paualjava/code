
<table width="96%" border="0" cellpadding="1" cellspacing="1" >
  <tr>
    <th width="80">字段名</th>
    <th width="80">列名</th>
    <th width="210">字段验证</th>
    <th width="200">验证提示文字</th>
    <th width="100">字段宽度</th>
    <th width="100">备用字段</th>
    <th width="100">列显示</th>
	<th width="100">添加编辑显示</th>
  </tr>
  <?php 
$i=1;
$k=0;
foreach($array_url as $key=>$url)
{

$value=($array_url_v[$key]) ? $array_url_v[$key] : $url;
?>
  <tr>
    <td><input name="column_name_<?php echo $url;?>_1" type="text"  value="<?php echo $url;?>"  style="width:80px" class="wizard-ignore" readonly=""/>
    </td>
	<?php 
	$column2=($info['first_time']==1 && !$info['url6']) ? @$info['field_'.$i.'_9'] : '';
	$column2=($info['first_time']!=1) ? @$info['field_'.$i.'_9'] : $column2;
	$column2=($info['first_time']==1 && $info['url6']) ? @$info['field_'.$i.'_9'] : $column2;
	$column2=($column2=="") ? @$info['field_'.$i.'_9'] : $column2;
	$readonly=($info['first_time']!=1 && @$info['column_name_'.$url.'_6']==1) ? 'readonly=""' : "";
	$readonly=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][14]==1) ? 'readonly=""' : $readonly;
	$style_border=($readonly=='readonly=""') ? 'border:solid 1px #f00;' : "";

	?>
    <td><input name="column_name_<?php echo $url;?>_2" type="text"  value="<?php echo $column2;?>" style="width:80px;<?php echo $style_border;?>" class="wizard-ignore " <?php echo $readonly;?>  />
    </td>
	
	
    <td><select id="column_name_<?php echo $url;?>_3" name="column_name_<?php echo $url;?>_3[]" multiple="multiple" size="5" style="width:190px;<?php echo $style_border;?>" class="wizard-ignore" <?php echo $readonly;?>>
        <optgroup label="验证规则">
        <?php 
		
					$array_temp=array("require"=>"必填项","email"=>"邮箱","phone"=>"手机","number"=>"数字","digits"=>"整数","url"=>"url","minlength"=>"最小字符长度(默认4)","maxlength"=>"最大字符长度(默认500)","range"=>"输入范围(默认1和100之间)","min"=>"输入最小值(默认1)","max"=>"输入最大值(默认500)","money"=>"货币");
					foreach($array_temp as $key2=>$value)
					{
					
					 $checked=(@in_array($key2,@$info['column_name_'.$url.'_3'])) ? 'selected="selected"' : '';
					 $checked=(strpos(@$info['field_'.$i.'_2'],"int")!==false && $key2=="digits") ? 'selected="selected"' : $checked;
					 $checked=(strpos(@$info['field_'.$i.'_1'],"url")!==false && $key2=="url") ? 'selected="selected"' : $checked;
					 $checked=(strpos(@$info['field_'.$i.'_1'],"email")!==false && $key2=="email") ? 'selected="selected"' : $checked;
					 $checked=(strpos(@$info['field_'.$i.'_1'],"money")!==false && $key2=="money") ? 'selected="selected"' : $checked;
					 $checked=(strpos(@$info['field_'.$i.'_2'],"char")!==false && $key2=="maxlength") ? 'selected="selected"' : $checked;
					 $checked=($i==2 && $info['first_time']==1 && !$info['url6'] && $key2=="require") ? 'selected="selected"' : $checked;
					 //@$info['column_name_'.$url.'_2']
					 //var_dump(@$modele_data[$url]);die();
					 $checked=($info['first_time']==1 && @$url=="sort_order" && $key2=="number") ? 'selected="selected"' : $checked;
					 $checked=($info['first_time']==1 && @$url=="sort_order" && $key2=="number") ? 'selected="selected"' : $checked;
					 $checked=($info['first_time']==1 && $info['url6'] && @in_array($key2,@$modele_data[$url][11])) ? 'selected="selected"' : $checked;
					 ?>

        <option value="<?php echo $key2;?>" <?php echo $checked;?> <?php echo $readonly;?>><?php echo $value;?></option>
        <?php }?>
        </optgroup>
        <optgroup label="其他">
        <?php
					$check1=(@in_array("readonly",@$info['column_name_'.$url.'_3'])) ? 'selected="selected"' : '';
					$check2=(@in_array("disabled",@$info['column_name_'.$url.'_3'])) ? 'selected="selected"' : '';?>
        <option value="readonly" <?php echo $check1;?>>只读</option>
        <option value="disabled" <?php echo $check2;?>>禁用</option>
        </optgroup>
      </select>
    </td>
	<?php $value=($info['first_time']==1 && !$info['url6']) ? "请输入".@$info['field_'.$i.'_9'] : @$info['column_name_'.$url.'_4'];?>
	<?php $value=($info['first_time']==1 && $info['url6']) ? @$modele_data[$url][12] : $value;?>
	<?php $value=($i==1 || stripos(@$info['field_'.$i.'_10'],"postdate")!==false) ? "" : $value;?>
	
	<?php $value=($value=='' && stripos(@$info['field_'.$i.'_10'],"postdate")===false) ? "请输入".@$info['field_'.$i.'_9'] : $value;?>
	<?php $value=(stripos(@$info['field_'.$i.'_10'],"cate")!==false) ? "请选择".@$info['field_'.$i.'_9'] : $value;?>
	<?php $value=(stripos(@$info['field_'.$i.'_10'],"pic")!==false) ? "请上传".@$info['field_'.$i.'_9'] : $value;?>
    <?php $value=(stripos(@$info['field_'.$i.'_10'],"file_upload")!==false) ? @$info['field_'.$i.'_9'] : $value;?>
	<?php $value=(stripos(@$info['field_'.$i.'_10'],"is_show")!==false) ? @$info['field_'.$i.'_9'] : $value;?>
    <td><input name="column_name_<?php echo $url;?>_4" type="text"  value="<?php echo $value;?>" class="wizard-ignore" style="<?php echo $style_border;?>" <?php echo $readonly;?> />
    </td>
    <td><select name="column_name_<?php echo $url;?>_5" style="width:120px;<?php echo $style_border;?>" class="wizard-ignore" <?php echo $readonly;?> >
	<?php $array_temp=array("input-small"=>"small--90px","input-medium"=>"medium--150px","input-large"=>"large--210px","input-xlarge"=>"xlarge--270px","input-xxlarge"=>"xxlarge--530px");
	foreach($array_temp as $key3=>$row_size){
	
	$checked=($info['first_time']==1 && !$info['url6'] && $key3=="input-xxlarge") ? 'selected="selected"' : '';
	$checked=($info['first_time']==1 && $info['url6'] && $key3==@$modele_data[$url][13]) ? 'selected="selected"' : $checked;
	$checked=($info['first_time']!=1 && @$info['column_name_'.$url.'_5']==$key3) ? 'selected="selected"' : $checked;
	$checked=($checked==='' && $key3=="input-xxlarge") ? 'selected="selected"' : $checked;
	/*
	$column2=($info['first_time']!=1) ? @$info['column_name_'.$url.'_2'] : $column2;
	$column2=($info['first_time']==1 && $info['url6']) ? @$modele_data[$url][10] : $column2;
	
	
	 $checked=(@$info['column_name_'.$url.'_5']==$key3) ? 'selected="selected"' : '';
	 $checked=($info['first_time']==1 && !$info['url6'] && $key3=="input-xlarge") ? 'selected="selected"' : $checked;
	 $checked=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][13]==$key3) ? 'selected="selected"' : $checked;*/?>
<option value="<?php echo $key3;?>" <?php echo $checked;?>><?php echo $row_size;?></option>
        <?php }?>

      </select>
    </td>
    <td width="100">
	  <?php 
	  $checked1=(@$info['column_name_'.$url.'_6']==1 && $info['first_time']==1) ? 'selected="selected"' : '';
		$checked1=($info['first_time']!=1 && @$info['column_name_'.$url.'_6']==1) ? 'selected="selected"' : $checked1;
	  $checked1=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][14]==1) ? 'selected="selected"' : $checked1;
	$checked2=($info['first_time']==1 && !$info['url6']) ? 'selected="selected"' : "";
	$checked2=(@$info['column_name_'.$url.'_6']==2) ? 'selected="selected"' : $checked2;
	$checked1=($info['first_time']!=1 && @$info['column_name_'.$url.'_6']==2) ? 'selected="selected"' : $checked1;
	$checked2=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][14]==2) ? 'selected="selected"' : $checked2;
	$checked2=($checked1=="") ? 'selected="selected"' : $checked2;
	?>
	<select name="column_name_<?php echo $url;?>_6" class="wizard-ignore" style="width:60px;<?php echo $style_border;?>" onchange="javascript:input_backup(this)">
	  <option class="wizard-ignore" value="1" <?php echo $checked1;?>>是</option>
	  <option class="wizard-ignore" value="2" <?php echo $checked2;?>>否</option>
	</select>
      </td>
  
    <td width="100">
	<?php 
	
	$checked1=(@$info['field_'.$i.'_10']=="editor" || @$info['field_'.$i.'_10']=="input_text") ? '' : 'selected="selected"';
	$checked1=($info['first_time']!=1 && @$info['column_name_'.$url.'_7']==1) ? 'selected="selected"' : $checked1;
	$checked1=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][15]==1) ? 'selected="selected"' : $checked1;

	
	$checked2=($info['first_time']==1 && !$info['url6'] && (@$info['field_'.$i.'_10']=="editor" || @$info['field_'.$i.'_10']=="input_text")) ? 'selected="selected"' : "";
	$checked2=($info['first_time']!=1 && @$info['column_name_'.$url.'_7']==2) ? 'selected="selected"' : $checked2;
	$checked2=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][15]==2) ? 'selected="selected"' : $checked2;
	
	?>
	<select name="column_name_<?php echo $url;?>_7" class="wizard-ignore" style="width:70px;<?php echo $style_border;?>" <?php echo $readonly;?>>
	  <option class="wizard-ignore" value="1" <?php echo $checked1;?>>显示</option>
	  <option class="wizard-ignore" value="2" <?php echo $checked2;?>>不显示</option>
	</select>
     </td>
	 <td width="100">
	<?php 

	if($i==1 || stripos(@$info['field_'.$i.'_10'],"postdate")!==false || stripos(@$info['field_'.$i.'_10'],"ip_address")!==false || stripos(@$info['field_'.$i.'_10'],"click")!==false)
	{
		$checked1='';
		$checked2='selected="selected"';
	}
	else
	{
		$checked1=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][16]==1) ? 'selected="selected"' : '';
		$checked1=($info['first_time']!=1 && @$info['column_name_'.$url.'_8']==1) ? 'selected="selected"' : $checked1;
		$checked2=($info['url6'] && @$modele_data[$url][16]==2) ? 'selected="selected"' : '';
		$checked2=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][16]==2) ? 'selected="selected"' : $checked2;
		$checked2=($info['first_time']!=1 && @$info['column_name_'.$url.'_8']==2) ? 'selected="selected"' : $checked2;
		
		
	}
	
	
	
	
	
/*	$checked1=($info['first_time']==1 && !$info['url6'] && @$info['field_'.$i.'_10']=="editor") ? '' : 'selected="selected"';
	$checked1=($info['first_time']!=1 && @$info['column_name_'.$url.'_7']==1) ? 'selected="selected"' : $checked1;
	

	
	$checked2=($info['first_time']==1 && !$info['url6'] && @$info['field_'.$i.'_10']=="editor") ? 'selected="selected"' : "";
	$checked2=($info['first_time']!=1 && @$info['column_name_'.$url.'_7']==2) ? 'selected="selected"' : $checked2;
	$checked2=($info['first_time']==1 && $info['url6'] && @$modele_data[$url][15]==2) ? 'selected="selected"' : $checked2;*/?>
	<select name="column_name_<?php echo $url;?>_8" class="wizard-ignore" style="width:70px;" <?php echo $readonly;?>>
	  <option class="wizard-ignore" value="1" <?php echo $checked1;?>>显示</option>
	  <option class="wizard-ignore" value="2" <?php echo $checked2;?>>不显示</option>
	</select>
     </td>
  </tr>
  <script type="text/javascript">
  $("#column_name_<?php echo $url;?>_3").multiSelect();	
  </script>
  <?php $i++; $k++;}?>
</table>
<style type="text/css">
.multiSelectOptions{width:190px;}
input[type="radio"], input[type="checkbox"] { margin: 0 3px 0 4px;}
</style>


<!--模板选择-->
<?php 
$template_tep=explode(":",$template_wap);?>
  <div class="box-content">
          <ul class="nav nav-tabs">
            <li class="<?php echo ($template_tep[0]=="help" || $template_tep[0]=="") ? "active" : "";?>"><a href="#help" data-toggle="tab">帮助中心</a></li>
            <li class="<?php echo ($template_tep[0]=="channel") ? "active" : "";?>"><a href="#channel" data-toggle="tab">频道模板风格</a></li>
            <li class="<?php echo ($template_tep[0]=="article") ? "active" : "";?>"><a href="#article" data-toggle="tab">文章列表</a></li>
            <li class="<?php echo ($template_tep[0]=="photo") ? "active" : "";?>"><a href="#photo" data-toggle="tab">图文列表</a></li>
            <li class="<?php echo ($template_tep[0]=="index") ? "active" : "";?>"><a href="#index" data-toggle="tab">首页模板</a></li>
            <li class="<?php echo ($template_tep[0]=="product") ? "active" : "";?>"><a href="#product" data-toggle="tab">产品模板</a></li>
			<li class="<?php echo ($template_tep[0]=="header") ? "active" : "";?>"><a href="#header" data-toggle="tab">头部模板</a></li>
			<li class="<?php echo ($template_tep[0]=="footer") ? "active" : "";?>"><a href="#footer" data-toggle="tab">尾部模板</a></li>
            <li class="<?php echo ($template_tep[0]=="page") ? "active" : "";?>"><a href="#page" data-toggle="tab">页面模板</a></li>
			<li class="<?php echo ($template_tep[0]=="member") ? "active" : "";?>"><a href="#member" data-toggle="tab">会员模板</a></li>
            <li class="<?php echo ($template_tep[0]=="admin") ? "active" : "";?>"><a href="#admin" data-toggle="tab">后台管理</a></li>
            <li><a href="#color" data-toggle="tab" style="display:none;">颜色选择</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane <?php echo ($template_tep[0]=="help" || $template_tep[0]=="") ? "active" : "fade";?>" id="help">
             
             <ul class="cateradio unstyled">
    <?php 
			  $type="help";
			  for($i=1;$i<=2;$i++)
			  {
			  ?>
                <li class="<?php echo ($template_wap==$type.":".$i) ? "active" : "";?>">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/article_01.jpg" alt="模板<?php echo $i;?>" title="模板<?php echo $i;?>">
                  <input type="radio" name="template_wap" value="<?php echo $type;?>:<?php echo $i;?>" <?php echo ($template_wap==$type.":".$i) ? 'checked="checked"' : "";?> >
                  列表<?php echo $i;?> </label>
                </li>
			<?php  }?>
              
</ul>

            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="channel" || $template_tep[0]=="") ? "active" : "fade";?>" id="channel">
              <ul class="cateradio">
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-71.png?v=7" alt="模板0" title="模板0">
                  <input type="radio" name="channel" value="home-71">
                  频道0 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-70.png?v=7" alt="模板1" title="模板1">
                  <input type="radio" name="channel" value="home-70">
                  频道1 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-69.png?v=7" alt="模板2" title="模板2">
                  <input type="radio" name="channel" value="home-69">
                  频道2 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-68.png?v=7" alt="模板3" title="模板3">
                  <input type="radio" name="channel" value="home-68">
                  频道3 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-67.png?v=7" alt="模板4" title="模板4">
                  <input type="radio" name="channel" value="home-67">
                  频道4 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-66.png?v=7" alt="模板5" title="模板5">
                  <input type="radio" name="channel" value="home-66">
                  频道5 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-65.png?v=7" alt="模板6" title="模板6">
                  <input type="radio" name="channel" value="home-65">
                  频道6 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-64.png?v=7" alt="模板7" title="模板7">
                  <input type="radio" name="channel" value="home-64">
                  频道7 </label>
                </li>
				          <li class="">
                  <div class="mbtip">图标式模版，顶部幻灯片建议使用尺寸为640*320或近似等比例图片；分类图片请使用正方形尺寸的图片。</div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/home-28.png?v=7" alt="模板8" title="模板8">
                  <input type="radio" name="channel" value="home-28">
                  频道1 </label>
                </li>
                <li style="display:none">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/home-13.png?v=7" alt="模板9" title="模板9">
                  <input type="radio" name="channel" value="home-13">
                  频道9 </label>
                </li>
                <li style="display:none">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/home-14.png?v=7" alt="模板10" title="模板10">
                  <input type="radio" name="channel" value="home-14">
                  频道10 </label>
                </li>
					     <li class="active">
                  <div class="mbtip">左右双栏模版，顶部幻灯片尺寸为640*320或近似等比例图片，如使用正方形图片会使得页面不美观；分类图片建议使用300*200或近似等比例图片，使用宽度小于高度的(如200*300)尺寸图片将使页面惨不忍睹。</div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/home-0.png?v=3" alt="模板11" title="模板11">
                  <input type="radio" name="channel" value="home-0">
                  频道2 </label>
                </li>
                <li style="display:none">
                  <div class="mbtip">左右双栏模版，顶部幻灯片建议使用尺寸为640*320或近似等比例图片；分类图片建议使用300*300或近似等比例图片。</div>
                  <label> <img src="http://stc.weimob.com/img/template/home-2.png?v=7" alt="模板12" title="模板12">
                  <input type="radio" name="channel" value="home-2">
                  频道12 </label>
                </li>
                <li style="display:none">
                  <div class="mbtip">图标式模版，顶部幻灯片建议使用尺寸为640*320或近似等比例图片；分类图片请使用系统提供的图标。</div>
                  <label> <img src="http://stc.weimob.com/img/template/home-4.png?v=7" alt="模板13" title="模板13">
                  <input type="radio" name="channel" value="home-4">
                  频道13 </label>
                </li>
                <li style="display:none">
                  <div class="mbtip">图标式模版，顶部幻灯片建议使用尺寸为640*320或近似等比例图片；分类图片请使用系统提供的图标。</div>
                  <label> <img src="http://stc.weimob.com/img/template/home-23.png?v=7" alt="模板14" title="模板14">
                  <input type="radio" name="channel" value="home-23">
                  频道14 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-29.png?v=7" alt="模板15" title="模板15">
                  <input type="radio" name="channel" value="home-29">
                  频道15 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-15.png?v=7" alt="模板16" title="模板16">
                  <input type="radio" name="channel" value="home-15">
                  频道16 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/home-16.png?v=7" alt="模板17" title="模板17">
                  <input type="radio" name="channel" value="home-16">
                  频道17 </label>
                </li>
                <li style="display:none">
                  <label> <img src="http://stc.weimob.com/img/template/home-1.png?v=7" alt="模板18" title="模板18">
                  <input type="radio" name="channel" value="home-1">
                  频道18 </label>
                </li>
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="article" || $template_tep[0]=="") ? "active" : "fade";?>" id="article">
              <ul class="cateradio">
              <?php 
			  $type="article";
			  for($i=1;$i<=2;$i++)
			  {
			  ?>
                <li class="<?php echo ($template_wap==$type.":".$i) ? "active" : "";?>">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/article_01.jpg" alt="模板<?php echo $i;?>" title="模板<?php echo $i;?>">
                  <input type="radio" name="template_wap" value="<?php echo $type;?>:<?php echo $i;?>" <?php echo ($template_wap==$type.":".$i) ? 'checked="checked"' : "";?> >
                  列表<?php echo $i;?> </label>
                </li>
			<?php  }?>
              
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="photo" || $template_tep[0]=="") ? "active" : "fade";?>" id="photo">
              <ul class="cateradio">
              
               <?php 
			  $type="photo";
			  for($i=1;$i<=2;$i++)
			  {
			  ?>
                <li class="<?php echo ($template_wap==$type.":".$i) ? "active" : "";?>">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/article_01.jpg" alt="模板<?php echo $i;?>" title="模板<?php echo $i;?>">
                  <input type="radio" name="template_wap" value="<?php echo $type;?>:<?php echo $i;?>" <?php echo ($template_wap==$type.":".$i) ? 'checked="checked"' : "";?> >
                  列表<?php echo $i;?> </label>
                </li>
			<?php  }?>
              
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="index" || $template_tep[0]=="") ? "active" : "fade";?>" id="index">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/index_01.jpg" alt="首页1" title="首页1">
                  <input type="radio" name="index" value="1">
                  首页1 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/index_01.jpg" alt="首页2" title="首页2">
                  <input type="radio" name="index" value="2">
                  首页2</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-10.png?v=7" alt="模板10" title="模板10">
                  <input type="radio" name="menu" value="10">
                  详情2 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-11.png?v=7" alt="模板11" title="模板11">
                  <input type="radio" name="menu" value="11">
                  详情3 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-7.png?v=7" alt="模板7" title="模板7">
                  <input type="radio" name="menu" value="7">
                  详情4 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-4.png?v=7" alt="模板4" title="模板4">
                  <input type="radio" name="menu" value="4">
                  详情5 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-5.png?v=7" alt="模板5" title="模板5">
                  <input type="radio" name="menu" value="5">
                  详情6 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-6.png?v=7" alt="模板6" title="模板6">
                  <input type="radio" name="menu" value="6">
                  详情7 </label>
                </li>
				          <li class="active">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-0.png?v=7" alt="模板0" title="模板0">
                  <input type="radio" name="menu" value="0" checked="checked">
                  详情1</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-1.png?v=7" alt="模板1" title="模板1">
                  <input type="radio" name="menu" value="1">
                  详情9 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-2.png?v=7" alt="模板2" title="模板2">
                  <input type="radio" name="menu" value="2">
                  详情10 </label>
                </li>
				      <li class="">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-3.png?v=7" alt="模板3" title="模板3">
                  <input type="radio" name="menu" value="3">
                  详情2 </label>
                </li>
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="product" || $template_tep[0]=="") ? "active" : "fade";?>" id="product">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/product_01.jpg" alt="产品1" title="产品1">
                  <input type="radio" name="product" value="1">
                  产品1 </label>
                </li>
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/product_02.jpg" alt="产品2" title="产品2">
                  <input type="radio" name="product" value="2">
                  产品2</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-10.png?v=7" alt="模板10" title="模板10">
                  <input type="radio" name="menu" value="10">
                  详情2 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-11.png?v=7" alt="模板11" title="模板11">
                  <input type="radio" name="menu" value="11">
                  详情3 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-7.png?v=7" alt="模板7" title="模板7">
                  <input type="radio" name="menu" value="7">
                  详情4 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-4.png?v=7" alt="模板4" title="模板4">
                  <input type="radio" name="menu" value="4">
                  详情5 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-5.png?v=7" alt="模板5" title="模板5">
                  <input type="radio" name="menu" value="5">
                  详情6 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-6.png?v=7" alt="模板6" title="模板6">
                  <input type="radio" name="menu" value="6">
                  详情7 </label>
                </li>
				    <li class="active">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-0.png?v=7" alt="模板0" title="模板0">
                  <input type="radio" name="menu" value="0" checked="checked">
                  详情1</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-1.png?v=7" alt="模板1" title="模板1">
                  <input type="radio" name="menu" value="1">
                  详情9 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-2.png?v=7" alt="模板2" title="模板2">
                  <input type="radio" name="menu" value="2">
                  详情10 </label>
                </li>
				   <li class="">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-3.png?v=7" alt="模板3" title="模板3">
                  <input type="radio" name="menu" value="3">
                  详情2 </label>
                </li>
              </ul>
            </div>
			
			<div class="tab-pane <?php echo ($template_tep[0]=="header" || $template_tep[0]=="") ? "active" : "fade";?>" id="header">
              <ul class="cateradio">
             
			                  <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_01.jpg" alt="模板1" title="模板1">
                  <input type="radio" name="header" value="1">
                  模板1 </label>
                </li>
                                <li style="margin:5px 0 8px 0;border:solid 1px #f00;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_02.jpg" alt="模板2" title="模板2">
                  <input type="radio" name="header" value="2" checked="checked">
                  模板2 </label>
                </li>
                                <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_03.jpg" alt="模板3" title="模板3">
                  <input type="radio" name="header" value="3">
                  模板3 </label>
                </li>

                                <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_04.jpg" alt="模板4" title="模板4">
                  <input type="radio" name="header" value="4">
                  模板4 </label>
                </li>
                                <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_05.jpg" alt="模板5" title="模板5">
                  <input type="radio" name="header" value="5">
                  模板5 </label>
                </li>
                                <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_06.jpg" alt="模板6" title="模板6">
                  <input type="radio" name="header" value="6">
                  模板6 </label>
                </li>
                                <li style="margin:5px 0 8px 0;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_07.jpg" alt="模板7" title="模板7">
                  <input type="radio" name="header" value="7">
                  模板7 </label>
                </li>
                              <!--  <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_02.jpg" alt="模板2" title="模板2"/>
                  <input type="radio" name="header" value="2" >
                  模板2 </label>
                </li>
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_03.jpg" alt="模板3" title="模板3"/>
                  <input type="radio" name="header" value="3" >
                  模板3 </label>
                </li>
                
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_04.jpg" alt="模板4" title="模板4"/>
                  <input type="radio" name="header" value="4" >
                  模板4 </label>
                </li>
				  <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_05.jpg" alt="模板5" title="模板5"/>
                  <input type="radio" name="header" value="5" >
                  模板5 </label>
                </li>
				 <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_06.jpg" alt="模板6" title="模板6"/>
                  <input type="radio" name="header" value="6" >
                  模板6 </label>
                </li>
				<li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/header_07.jpg" alt="模板7" title="模板7"/>
                  <input type="radio" name="header" value="7" >
                  模板7 </label>
                </li>-->
                
                
               
              </ul>
            </div>
			
			<div class="tab-pane <?php echo ($template_tep[0]=="footer" || $template_tep[0]=="") ? "active" : "fade";?>" id="footer">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/footer_01.jpg" alt="底部1" title="底部1">
                  <input type="radio" name="footer" value="1">
                  底部1 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/footer_02.jpg" alt="底部2" title="底部2">
                  <input type="radio" name="footer" value="2">
                  底部2 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/footer_03.jpg" alt="底部3" title="底部3">
                  <input type="radio" name="footer" value="3">
                  底部3</label>
                </li>
                 <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/footer_04.jpg" alt="底部4" title="底部4">
                  <input type="radio" name="footer" value="4">
                  底部4</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-11.png?v=7" alt="模板11" title="模板11">
                  <input type="radio" name="menu" value="11">
                  详情3 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-7.png?v=7" alt="模板7" title="模板7">
                  <input type="radio" name="menu" value="7">
                  详情4 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-4.png?v=7" alt="模板4" title="模板4">
                  <input type="radio" name="menu" value="4">
                  详情5 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-5.png?v=7" alt="模板5" title="模板5">
                  <input type="radio" name="menu" value="5">
                  详情6 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-6.png?v=7" alt="模板6" title="模板6">
                  <input type="radio" name="menu" value="6">
                  详情7 </label>
                </li>
				                <li class="active">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-0.png?v=7" alt="模板0" title="模板0">
                  <input type="radio" name="menu" value="0" checked="checked">
                  详情1</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-1.png?v=7" alt="模板1" title="模板1">
                  <input type="radio" name="menu" value="1">
                  详情9 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-2.png?v=7" alt="模板2" title="模板2">
                  <input type="radio" name="menu" value="2">
                  详情10 </label>
                </li>
				                <li class="">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-3.png?v=7" alt="模板3" title="模板3">
                  <input type="radio" name="menu" value="3">
                  详情2 </label>
                </li>
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="page" || $template_tep[0]=="") ? "active" : "fade";?>" id="page">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/page_01.jpg" alt="页面1" title="页面1">
                  <input type="radio" name="page" value="1">
                  页面1 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/page_02.jpg" alt="页面2" title="页面2">
                  <input type="radio" name="page" value="2">
                  页面2 </label>
                </li>
                 <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/page_03.jpg" alt="页面3" title="页面3">
                  <input type="radio" name="page" value="3">
                  页面3 </label>
                </li>
                  <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/page_03.jpg" alt="页面4" title="页面4">
                  <input type="radio" name="page" value="4">
                  页面4</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-10.png?v=7" alt="模板10" title="模板10">
                  <input type="radio" name="menu" value="10">
                  详情2 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-11.png?v=7" alt="模板11" title="模板11">
                  <input type="radio" name="menu" value="11">
                  详情3 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-7.png?v=7" alt="模板7" title="模板7">
                  <input type="radio" name="menu" value="7">
                  详情4 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-4.png?v=7" alt="模板4" title="模板4">
                  <input type="radio" name="menu" value="4">
                  详情5 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-5.png?v=7" alt="模板5" title="模板5">
                  <input type="radio" name="menu" value="5">
                  详情6 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-6.png?v=7" alt="模板6" title="模板6">
                  <input type="radio" name="menu" value="6">
                  详情7 </label>
                </li>
				                <li class="active">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-0.png?v=7" alt="模板0" title="模板0">
                  <input type="radio" name="menu" value="0" checked="checked">
                  详情1</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-1.png?v=7" alt="模板1" title="模板1">
                  <input type="radio" name="menu" value="1">
                  详情9 </label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-2.png?v=7" alt="模板2" title="模板2">
                  <input type="radio" name="menu" value="2">
                  详情10 </label>
                </li>
				 <li class="">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/member_center/menu-3.png?v=7" alt="模板3" title="模板3">
                  <input type="radio" name="menu" value="3">
                  详情2 </label>
                </li>
              </ul>
            </div>
			<div class="tab-pane <?php echo ($template_tep[0]=="member" || $template_tep[0]=="") ? "active" : "fade";?>" id="member">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/member_01.jpg" alt="会员1" title="会员1">
                  <input type="radio" name="member" value="1">
                  会员页面1 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/member_02.jpg" alt="会员2" title="会员2">
                  <input type="radio" name="page" value="2">
                  会员页面2 </label>
                </li>
                 <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/member_03.jpg" alt="会员3" title="会员3">
                  <input type="radio" name="page" value="3">
                  会员页面3 </label>
                </li>
               
            
              </ul>
            </div>
            <div class="tab-pane <?php echo ($template_tep[0]=="admin" || $template_tep[0]=="") ? "active" : "fade";?>" id="admin">
              <ul class="cateradio">
                <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/admin_01.jpg" alt="后台模板1" title="后台模板1">
                  <input type="radio" name="admin" value="1">
                  后台模板1 </label>
                </li>
               <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/admin_02.jpg" alt="后台模板2" title="后台模板2">
                  <input type="radio" name="admin" value="2">
                  后台模板2 </label>
                </li>
                 <li>
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://localhost/codeIgniter_system2/resource/images/admin_admin/admin_03.jpg" alt="后台模板3" title="后台模板3">
                  <input type="radio" name="admin" value="3">
                  后台模板3</label>
                </li>
                <li style="display:none;">
                  <div class="mbtip" style="display:none;"></div>
                  <label> <img src="http://stc.weimob.com/img/template/menu-10.png?v=7" alt="模板10" title="模板10">
                  <input type="radio" name="admin" value="10">
                  详情2 </label>
                </li>
               
              </ul>
            </div>
          </div>
        </div>
        <!--模板选择-->