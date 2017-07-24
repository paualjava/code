<div class="box">
  <div class="box-title">
    <div class="span8" style="float:left">
      <h3><i class="icon-table" style="background-position:-265px 1px"></i><table_name_zh></h3>
    </div>
    <div class="pull-right"><a class="btn" href="Javascript:window.history.go(-1)">返回</a></div>
  </div>
  <div class="box-content">
    <div class="row-fluid">
      <div class="span12 control-group">
       <div class="span5 pull-left"><a class="btn" href="javascript:location.reload()"><i class="icon-refresh"></i>刷新</a> <?php if(role("friend_link_del")){?><a class="btn" href="javascript:delete_all('<?php echo $admin_url;?>friend_link/delete_all/')"><i class="icon-trash"></i>批量删除</a><?php }?> </div>
       <div class="span7 pull-right">
          <form name="filter" method="get" action="<?php echo $admin_url;?>friend_link" style="text-align:right;">
            <input type="text" name="keyword" id="keyword" value="<?php echo @$keyword;?>" class="input-large ui-wizard-content ui-helper-reset ui-state-default valid" data-rule-required="true">
      
           <button id="bsubmit" type="submit" data-loading-text="提交中..." class="btn btn-primary">搜索</button> <?php if(@$keyword){?><a href="<?php echo $admin_url;?>friend_link" class="btn btn-small btn-link"><i class="icon-list"></i> 全部</a><?php }?>
          </form>
        </div>
        
      </div>
    </div>
    <div class="row-fluid dataTables_wrapper">
      <form name="form2" method="post">
        <table id="listTable" class="table table-bordered table-hover dataTable">
          <tr class="main2">
            <th class="with-checkbox"><input type="checkbox" class="check_all" style="width:13px;"/></th>
<table_col>
             <th style="width:100px">查看</th>
            <th style="width:100px">删除</th>
          </tr>
<?php 
$i=1;
if(@$list['this_data']){
foreach ($list['this_data'] as $row)
{
?>
          <tr>
            <td class="with-checkbox"><input name="check" type="checkbox" value="<?php echo $row->id;?>" style="width:13px;"/></td>
<str_view_list>
<td>&nbsp;<?php if(role("friend_link_list")){?><a href="<?php echo $admin_url;?>friend_link_edit/<?php echo $row->id;?>/<?php echo $current;?>/<?php echo $query_string;?>">查看</a><?php }?>  </td>
            <td>&nbsp;<?php if(role("friend_link_del")){?><a href="<?php echo $admin_url;?>friend_link/delete/<?php echo $row->id;?>/<?php echo $current;?>/<?php echo $query_string;?>"><img src="<?php echo $base_url;?>resource/images/admin_admin/admin_del.gif" border="0" onclick='{if(confirm("该操作不可恢复")){return true;}return false;}'/></a><?php }?> &nbsp;</td>
          </tr>
          <?php  $i++; }}?>
        </table>
      </form>
    <?php if($list['total_record']>$per_page){?>
      <div class="pagenation2">每页 <?php echo $per_page;?> 条记录 总计 <?php echo $list['total_record'];?> 条记录<?php echo $list['this_page'];?></div>
      <?php }?>
      
    </div>
  </div>
</div>
<is_show>
<sort_order>