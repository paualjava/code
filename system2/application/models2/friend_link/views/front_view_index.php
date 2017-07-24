<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>resource/css/test/test.css"/>
<div class="container">
  <div class="row">
    <div class="col-md-8">
      <div id="breadcrumbs">
        <ul class="breadcrumbs breadcrumb">
          <li><a href="<?php echo $site_url;?>">主页</a></li>
          <li class="active">知识库</li>
        </ul>
      </div>
      <div class="block clearfix">
        <?php foreach ($list['this_data']  as $info){

 ?>
        <div class="posts">
          <article>
            <h1><a href="<?php echo $site_url;?>content/show/<?php echo $info->id;?>"><?php echo $info->title;?></a></h1>
            <div class="entry-info clearfix">
              <div class="fs-12"> <i class="fa fa-calendar mr5"></i><span class="mr20">2014-05-22</span> <i class="fa fa-tags mr5"></i> <a href="/zh/content/index?tag=%E7%AE%80%E5%8E%86">简历</a> </div>
            </div>
            <p><?php echo (strlen(strip_tags($info->brief))>50) ? strip_tags($info->brief) : msubstr(strip_tags($info->content),0,200);?></p>
            <div class="entry-action clearfix"> <a href="<?php echo $site_url;?>content/show/<?php echo $info->id;?>" class="btn btn-info">阅读全文</a>
              <div class="entry-sns pull-right"></div>
            </div>
          </article>
        </div>
        <?php }?>
        <div class="pagination pull-right">
          <ul id="yw0" class="pagination">
            <?php echo $list['this_page'];?>
          </ul>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="block search-blog">
        <form id="formSearch" action="<?php echo $site_url;?>content/index" method="get">
          <input id="keyword" name="keyword" type="text" class="form-control input-lg noshadow" placeholder="搜索...">
          <span class="glyphicon glyphicon-search"></span> <a class="btnClear fade" href="/zh/content">&times;</a>
        </form>
      </div>
      <cate_list>
      <div class="block side-bar">
        <h4>热门文章</h4>
        <ul class="unstyled blog-side">
          <?php foreach ($rank_list  as $rand_list_row){?>
          <li><a href="<?php echo $site_url;?>content/show/<?php echo $rand_list_row->id;?>"><?php echo $rand_list_row->title;?></a></li>
          <?php }?>
        </ul>
      </div>
      
      
      <br/>
      <br/>
    </div>
  </div>
</div>
