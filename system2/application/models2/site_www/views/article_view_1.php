<script type="text/javascript">
$(document).ready(function(){
  //imgShowName("office_show",-200,300);
  searchTextClear("search_text","请输入要搜索的关键词");
  listBackCartoon("list_back");
     moreCartoon("content_more",-200,200);
  //hoverCartoon("content_hover",-200,200);
  cartoonNav("mainnav","navsel",90,1);
  navScroll("navbox","go_topnav");
});

</script>
<link href="<?php echo $base_url;?>resource/site_yn/css/cartoon.css" rel="stylesheet" type="text/css" />
	<!-- 主体 -->
<!--banner-->
<section class="content_banner" style="background:#409800;">
 <div class="main">
   <a href="/about/video/" target="_blank"><img src="<?php echo $base_url;?>resource/site_yn/images/content02.jpg" /></a>
 </div>
  <div class="clear"></div>
</section><!--banner END-->
<section class="contentbox02">
  <div class="content_main">
    <div class="search_box">
      <a class="list_back fl" href="/blog/zixun/" title="行业资讯">行业资讯<span><img src="<?php echo $base_url;?>resource/site_yn/images/arrow01-2.png" /></span></a>
     <script type="text/javascript">
function formCheck()
{
        var $q=$("#q").val();
		if ($.trim($q)=='' || $.trim($q)=='请输入要搜索的关键词'){
		showtip("亲~请输入关键词查询！");
		$("#q").focus();
		return false;
		}
}
</script>
      <form name="search" type="get" action="/blog" id="search" onsubmit="return formCheck();">
      <input type="submit" class="sub" value="新闻搜索" />
      <input type="text" class="text search_text" value="请输入要搜索的关键词"  name="q" id="q"/>
      </form>
      <div class="clear"></div>
    </div><!--search_box END-->
    <nav class="news_nav">
   
      <div class="clear"></div>
    </nav><!--news_nav END-->
    <menu class="essay_listbox">
     <?php 
	$i=1;
	foreach ($list["this_data"] as $row){?>
    <li>
        <a class="a1"  title="<?php echo $row-><field_title>;?>" href="<?php echo $site_url;?>article/show/<?php echo $row-><column_name_first>;?>"><img src="<?php echo (@$row-><field_pic>) ? ((substr(@$row-><field_pic>,0,4)=='http' || substr(@$row-><field_pic>,0,1)=='/') ? @$row-><field_pic> : $base_url.@$row-><field_pic>)  : "";?>" width="74" height="74" /></a>
        <a class="a2" href="<?php echo $site_url;?>article/show/<?php echo $row-><column_name_first>;?>"><?php echo $row-><field_title>;?></a>
        <p class="p1"><?php echo get_time($row-><field_postdate>);?> / 人气<?php echo $row-><column_name_first>;?></p>
        <p class="p2"><?php echo msubstr(strip_tags($row-><field_content>),0,100);?></p>
        <div class="clear"></div>
      </li>
      <?php }?>
    </menu>
    <div class="content_page ">
      <menu>
        <div> <?php echo $list['this_page'];?></div>
      <div class="clear"></div>
      </menu>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
    	    <table class="content_botbox">
      <tr>
        <td><a class="content_bot_a a1" href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODA1OTU5MF8yMjg2MzRfNDAwNzM5MTIwMF8yXw" target="_blank" rel="nofollow"></a></td>
        <td><a class="content_bot_a a2"  href="javascript:loginWinOpen('weixin_win','myselfbox',200);"></a></td>
        <td rowspan="2">
          <a class="content_more"  href="/about/video/">
            <span><img src="<?php echo $base_url;?>resource/site_yn/images/arrow02.png" /></span>
            <p>视频播放</p>
          </a>
        </td>
        <td rowspan="2" class="text">
          <p><img src="<?php echo $base_url;?>resource/site_yn/images/content_bot05.png" /></p>
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
</section>
	<!-- /主体 -->
