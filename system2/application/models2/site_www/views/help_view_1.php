<!-- 主体 -->
<!--banner-->
<section class="content_banner">
 <div class="main">
   <a href="/about/"><img src="<?php echo $base_url;?>resource/site_yn/images/content01.jpg" /></a>
 </div>
  <div class="clear"></div>
</section><!--banner END-->
<section class="contentbox01" style="background:#a2be56;">
  <div class="content_main">
    <menu class="custom_menu">
      <a class="a0" href="/about/" title="返回">返回</a>
      <div class="box">
      <?php 
	
   foreach($page_left as $info){
	   $current_class=($current==$info->id) ? "sel" : "";?>
	 <a class="a1 <?php echo $current_class;?>"  href="<?php echo $base_url;?>help/index/<?php echo $info->id;?>" title="<?php echo $info-><field_title>;?>"><?php echo msubstr($info-><field_title>,0,4);?></a><!--sel-->
        <?php }?>

      
      </div>
      <div class="clear"></div>
    </menu>
    <article class="custom_main">
      <h2 class="fs30 fw400 mb15"><?php echo $page_info-><field_title>;?></h2>
      <?php echo $page_info-><field_content>;?>
      <div class="clear"></div>
    </article>
    <div class="clear"></div>
    	    <table class="content_botbox">
      <tr>
        <td><a class="content_bot_a a1" href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODA1OTU5MF8yMjg2MzRfNDAwNzM5MTIwMF8yXw" target="_blank" rel="nofollow"></a></td>
        <td><a class="content_bot_a a2"  href="javascript:loginWinOpen('weixin_win','myselfbox',200);"></a></td>
        <td rowspan="2">
          <a class="content_more"  href="/about/video/">
            <span><img src="images/arrow02.png" /></span>
            <p>视频播放</p>
          </a>
        </td>
        <td rowspan="2" class="text">
          <p><img src="images/content_bot05.png" /></p>
          <p>地址：云南省.昆明市环城南路668号（云纺商业区）云纺国际商厦A座18层-19层</p>
          <p>电话：0871-63167196、63157171、63157172、63157517、63168206、63156299</p>
          <p>咨询：0871-63395062、售后：0871-63365932、投诉：0871-63386022</p>
          <p>传真：0871-63365715、邮箱：info@ynyes.com</p>
        </td>
      </tr>
      <tr>
        <td><a class="content_bot_a a3" href="http://weibo.com/ynsite" target="_blank" rel="nofollow"></a></td>
        <td><a class="content_bot_a a4" href="/about/service/"></a></td>
      </tr>
    </table><!--content_botbox END-->
<!--content_botbox END-->
  </div><!--main END-->
  <div class="clear"></div>
  <div class="content_bot"></div><!--content_bot END-->
</section><!-- index_part END-->
	<!-- /主体 -->