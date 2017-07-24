<div id="content_main">
  <div id=content>
   <ul>
<?php 
$st=array("about_us"=>"关于我们","contact_us"=>"联系我们","use_guide"=>"使用指南","ad_together"=>"广告合作","friend-link"=>"友情链接"); 
foreach($page_left as $row){
$this_on=($row->id==$current) ? "on" : "";
?>
	<li class="<?php echo $this_on;?>"><span><a href="<?php echo $site_url;?>page/<?php echo $key;?>"><?php echo $row->name;?></a></span></li>
<?php }?>	
</ul> 
    <div id=eric-ad> </div>
    <div id="zhenzi_img">
      <div id="centernav">
        <h2>您所在的位置：<a href="<?php echo $site_url;?>">网站首页</a> > <?php echo $page_info->name;?> </h2>
      </div>
      <div id="box"><?php echo $page_info->content;?> </div>
    </div>
  </div>
  <div></div>
</div>
