<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<script type="text/javascript" src="<?php echo $base_url;?>resource/js/jquery.js"></script>

<title>信息添加</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<meta content="application/xhtml+xml;charset=UTF-8" http-equiv="Content-Type">
<meta content="no-cache,must-revalidate" http-equiv="Cache-Control">
<meta content="no-cache" http-equiv="pragma">
<meta content="0" http-equiv="expires">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
</head>
<style type="text/css">
* {margin: 0;outline: 0;padding: 0;font-size: 100%;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);}
a {color:#000;text-decoration: none;-webkit-tap-highlight-color: rgba(0, 0, 0, 0.3);}
html {height: 100%;-webkit-text-size-adjust:none;}
body {margin: 0;padding: 0;width: 100%;height: 100%;min-height: 100%;/*font-family: Helvetica, Arial, sans-serif;*/font-family: Helvetica, Arial, sans-serif;font-size: 12px;line-height: 1.231;-webkit-touch-callout: none;display: -webkit-box;-webkit-box-orient: vertical;-webkit-box-align: stretch;position: relative;}
img {-ms-interpolation-mode: bicubic;vertical-align: middle;}
img:not([src*="/"]) {display:none;}
table {border-collapse: collapse;border-spacing: 0;width: 100%;}
th, td, caption {vertical-align: middle;}
textarea {resize: none;border: 0;padding: 8px 0;border-radius: 0;}
input, button, select, textarea {outline: none;-webkit-appearance: none;border-radius: 0;}
li {list-style: none;}
/**********************************common**********************************/
.fl {float: left;}
.fr {float: right;}
.ofh {overflow: hidden;}
.align_left {text-align: left;}
.relative {position: relative;}
.absolute {position: absolute;}
.hidden {display: none;}
.hidden.on {display: block;}
.vhidden {visibility: hidden;}
.vhidden.on {visibility: visible;}
.empty:empty {display: none;}
.block {display: block;}
.pt_10 {padding-top: 10px;}
.box {width: 100%;display: -webkit-box;display: -moz-box;display: box;-webkit-box-orient: horizontal;-moz-box-orient: horizontal;box-orient: horizontal;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.box > * {-webkit-box-flex: 1;-moz-box-flex: 1;box-flex: 1;}
.tbox {width: 100%;height: 100%;}
.tbox > * {height: 100%;display: table-cell;vertical-align: top;}
.tbox > *:last-child {width: 100%;}
.pre {white-space: nowrap;}
.center {text-align:center;}
html, body, .container {min-height:100%;}
.container {font-size:12px;max-width:640px;min-width:320px;margin:auto;position:relative;background: url(../../img/member/imgs/1.jpg) no-repeat center 0;background-size:100% 100%;}
.container.success .body .group_btn2 {border:0;padding:0 20px;}
.container.success .body .group_btn2 h6 {color: #333333;padding: 15px 1px;font-size:17px;font-weight: 100;}
.container.success .body .group_btn2 .btn {border-radius: 1px;display: block;line-height: 45px;text-align: center;color: #ffffff;font-size: 20px;background:#5ac5d4;}
.container.success header .desc {padding: 20px;background: -webkit-gradient(linear, 0 0, 100% 0, from(#c7e1e5), to(#dceaec));-webkit-box-sizing: border-box;}
.container.success header .desc>div {vertical-align: middle;}
.container.success header .desc label {display:block;line-height:23px;font-size:20px;color:#333333;overflow:hidden;word-break:break-all;word-wrap:nowrap;}
.container.success header .desc span.img {display:inline-block;width:40px;height:40px;background: url(../../images/icons2.png) no-repeat -8px -7px;-webkit-background-size: 50px auto;}
.container.success header .desc label r {color:#d90101;}
*[data-card] {font-size: 14px;}
*[data-card] header {padding:10px 30px;-webkit-box-sizing:border-box;background:-webkit-gradient(linear, 0 0, 100% 0, from(#ebebeb), to(#f3f9fa), color-stop(30%, #f5f9f9));}
*[data-card] .input {display: block;padding:11px 5px;border:1px solid #dddddd;border-radius:3px;width:100%;background:#f3f3f3;-webkit-box-sizing:border-box;color:#606366;}
*[data-card] input::-webkit-input-placeholder {color:#bfbfbf;}
*[data-card] .button {display:block;height: 38px;line-height: 38px;padding:0 5px;background:#4d7dd0;color:#ffffff;text-align: center;border-radius: 1px;font-size: 16px;}
*[data-card] .select {color:#606366;padding:11px 25px 11px 5px;-webkit-appearance:none;border:1px solid #dddddd;border-radius:3px;background:url(../../images/icons2.png) no-repeat right -121px;-webkit-background-size:40px auto;background-color:#f3f3f3;-webkit-box-sizing:border-box;}
*[data-card] .input.wrapInput {padding:3px;font-size:16px;}
*[data-card] .input.wrapInput .input {padding: 11px 3px;}
*[data-card] .input.wrapInput select {width:100%;background-color:#ffffff;border:0;color:#333333;}
.info_tx {background:#d2e6e9;}
.list_ul_card {margin:10px;margin-top: 1px;}
.list_ul_card *[data-card] header label {display: block;line-height:40px;color:#3e79d4;font-size: 18px;}
.list_ul_card *[data-card] header label span {display:inline-block;width:40px;height:40px;background:url(../../images/icons2.png) no-repeat center -106px;-webkit-background-size:50px auto;}
.list_ul_card .forms {background:#ffffff;padding: 1px 0;}
.list_ul_card .forms dl {padding:0 10px;margin: 10px 0px;-webkit-box-sizing:border-box;}
.list_ul_card .forms hr {margin:10px 0;border:0;border-top:1px solid #dddddd;}
.list_ul_card .forms dt {font-size: 16px;color: #606366;padding:5px 10px;}
.list_ul_card .forms .select_box>div {width:33.3%;}
.list_ul_card .forms .select_box>div select {width:100%;margin:auto;}
.list_ul_card .forms .select_box>div:not(:last-of-type) { padding-right:10px; -webkit-box-sizing:border-box;}
.add_op a {display: block;margin: 10px 0;border-radius: 1px;height: 40px;line-height: 40px;text-align: center;color: #ffffff;font-size: 18px;background: #4d7dd0;}
/***对话***/
.dialogWindow{display:none;position: absolute;top:70px;left:0;right:0;/*bottom:0;*/margin:auto;z-index:9000;width:300px;/*height:200px;*/min-height:100px;background:#fff;border-radius:5px;-webkit-box-sizing:border-box;}
.dialogWindow header, .dialogWindow footer{height:35px;line-height: 35px;padding: 0 10px;}
.dialogWindow header:empty, .dialogWindow footer:empty{display:none;}
.dialogWindow.on{display:block;}
.dialogCover{display:none;}
.dialogWindow.on+.dialogCover{display:block;position:fixed;left:0;top:0;width:100%;height:100%;z-index:1000;background:rgba(0,0,0,0.6);}
.dialogWindow header>dl{display:table;}
.dialogWindow header>dl>dd{display:table-cell;width:100%;}
.dialogWindow .dialogContent{/*height:120px;*/padding:0;-webkit-box-sizing:border-box;}/****************************************************/
.dialogWindow.alert{background:rgba(0,0,0,0.5);padding-bottom:10px;width:200px;}
.dialogWindow.alert header{height:10px;}
.dialogWindow.alert .icon.success{height:50px;width:50px;margin:auto;background:url(<?php echo $base_url;?>resource/images/form_icons2.png) no-repeat center -50px;-webkit-background-size:50px auto;}
</style>
<script type="application/javascript">
function alert(text, time){
	$("#dialoger").show();
	$(".dialoger_wenzi").html(text);
	setTimeout(function (){$("#dialoger").hide();},time);
}
function submit1(){
		var form = document.getElementById("form1");
		var obj = {
<js_add_value>
					}
		<js_add>
		//redirect(res.url,1500);
		//loading(true);
		$.ajax({
			url: "<?php echo $site_url;?>friend_link/ajax_friend_link_add",
			type:"POST",
			data:$("#form1").serialize(),
			dataType:"json",
			success: function(res){
				//loading(false);
				if(res.errno == 0){
					
					alert(res.error, 1500);
					//redirect(res.url,1500);
				}else{
					alert(res.error, 1500);
					//loading(false);
				}
			}
		});

	}

</script>
<body onselectstart="return true;" ondragstart="return false;">
<section id="dialoger" style="display:none;">
  <div data-type="" id="dialogWindow_1100" class="dialogWindow on alert" style="z-index:1100">
    <header>
      <dl>
        <dd>
          <label></label>
        </dd>
        <dd><span onClick="this.parentNode.parentNode.parentNode.parentNode.classList.remove('on');"></span></dd>
      </dl>
    </header>
    <article class="dialogContent">
      <div class="icon success"></div>
      <div style="padding:10px 30px;line-height:23px;text-align:center;font-size:22px;color:#ffffff;" class="dialoger_wenzi"></div>
    </article>
    <footer></footer>
  </div>
  <div class="dialogCover"></div>
</section>

<div class="container info_tx">
  <div class="body pt_10">
    <ul class="list_ul_card">
      <form id="form1" action="javascript:;" method="post">
        <li data-card="">
          <header class="center">
            <label style="display:inline-block;"><span>&nbsp;</span>用户登录</label>
          </header>
          <div class="forms">
          <view_add>
          </div>
        </li>
        <ul class="add_op">
          <li style="padding:10px 0 0;"> <a href="javascript:submit1();" style="width:100%;">提&nbsp;&nbsp;&nbsp;交</a> </li>
        
        </ul>
      </form>
    </ul>
  </div>
</div>
</body>
</html>
