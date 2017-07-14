<?php

/**
 * ECSHOP 商品管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods.php 17217 2011-01-19 06:29:08Z liubo $
*/
define('IN_ECS', true);

require(dirname(dirname(__FILE__)) . '/wm18admin/includes/init.php');
require_once(ROOT_PATH . 'includes_new/lib_goods_admin.php');

include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
$exc = new exchange($ecs->table('pocket_goods'), $db, 'goods_id', 'goods_name');
require(ROOT_PATH . 'includes_new/function.php');
$GLOBALS['smarty']->assign('base_url', get_base_url());
$GLOBALS['smarty']->assign('nav', "goods");


/*------------------------------------------------------ */
//-- 商品列表，商品回收站
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list' || $_REQUEST['act'] == 'trash')
{
	require_once(ROOT_PATH . 'includes_new/pagination.class.php');
	require_once(ROOT_PATH . 'includes_new/lib_base.php');
	get_pinyin(3000);//获取商品的拼音
	$page_size=12;
	$sql = "UPDATE " .$ecs->table('pocket_goods'). " SET time = '".gmtime()."' where (time<1 or time is null)";
	$GLOBALS['db']->query($sql);
	/* 显示商品列表页面 */
	$curpage = (empty($_GET['page']) && !preg_match("/^\d+$/is",$_GET['page'])) ? 1 : $_GET['page'];
	$keyword=$_REQUEST['keyword'];
	$url = "?act=list&cat_id=".$_GET['cat_id']."&tag_id=".$_GET['tag_id']."&on_sale=".$_GET['on_sale']."&on_show=".$_GET['on_show']."&keyword=".$keyword."&type=".$_GET['type']."&sort=".$_GET['sort']."&sale_number=".$_GET['sale_number']."&goods_name_sort=".$_GET['goods_name_sort']."&goods_time=".$_GET['goods_time']."&page={page}";
	$where=get_search_count_where($_GET['type']);
	$sql = "select count(*) from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where;
	$total = $GLOBALS['db']->getOne($sql);
	$GLOBALS['smarty']->assign('cat_id', $_GET['cat_id']);
	$GLOBALS['smarty']->assign('type',$_GET['type']);
	$GLOBALS['smarty']->assign('tag_id', $_GET['tag_id']);
	$GLOBALS['smarty']->assign('on_sale', $_GET['on_sale']);
	$GLOBALS['smarty']->assign('on_show', $_GET['on_show']);
	$GLOBALS['smarty']->assign('keyword_type', $_GET['keyword_type']);
	if($_GET['cat_id'])
	{
		$sql = "select cat_name from ". $GLOBALS['ecs']->table("pocket_goods_cat")." where cat_id='".$_GET['cat_id']."'";
		$cat_name2 = $GLOBALS['db']->getOne($sql);
		$GLOBALS['smarty']->assign('cat_name2',$cat_name2);
	}

	if (!empty($_GET['page']) && $total != 0 && $curpage > ceil($total / $page_size))
	$curpage = ceil($total_rows / $page_size); //当前页数大于最后页数，取最后一页
	$curpage=($curpage) ? $curpage : 1;
		$page = new pagination($total, $page_size, $curpage, $url, 2);
		$GLOBALS['smarty']->assign('page_bar',$page->myde_write());
	$where=get_search_where($_GET['type']);
	//var_dump($where);
	$ss = $_GET['sort'];
	$sale_number = $_GET['sale_number'];
	$goods_name_sort = $_GET['goods_name_sort'];
	$goods_time = $_GET['goods_time'];
	if ($ss==1) {
		$GLOBALS['smarty']->assign('sort', 2); 
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by goods_number asc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
}elseif($ss==2){
	$GLOBALS['smarty']->assign('sort', 1);
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by goods_number desc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";	
}

	elseif($sale_number==1){
		$GLOBALS['smarty']->assign('sale_number', 2);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by salesnum_pay asc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
	}
	elseif($sale_number==2){
		$GLOBALS['smarty']->assign('sale_number', 1);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by salesnum_pay desc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
	}
	elseif($goods_name_sort==1){
		$GLOBALS['smarty']->assign('sale_number', 2);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by pinyin asc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
		$GLOBALS['smarty']->assign('goods_name_sort',$goods_name_sort);
	}
	elseif($goods_name_sort==2){
		$GLOBALS['smarty']->assign('sale_number', 1);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by pinyin desc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
		$GLOBALS['smarty']->assign('goods_name_sort',$goods_name_sort);
	}
	elseif($goods_time==1){
		$GLOBALS['smarty']->assign('goods_time', 2);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by `time` asc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
	}
	elseif($goods_time==2){
		$GLOBALS['smarty']->assign('goods_time', 1);
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by `time` desc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
	}
	else{
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." ".$where." order by sort_order desc, goods_id desc LIMIT " . ($curpage - 1) * $page_size . ",$page_size";
	$GLOBALS['smarty']->assign('sort', 2);
		$GLOBALS['smarty']->assign('sale_number', 2);
		$GLOBALS['smarty']->assign('goods_name_sort', 2);
	$GLOBALS['smarty']->assign('goods_time', 2);
	}
	//echo "<BR>";
	//var_dump($sql);
	//分页
	$info = $GLOBALS['db']->getAll($sql);
	foreach($info as $key=>$row)
	{
		$goods = get_goods_info($row['goods_id']);
		$sql = "select tag_name from ". $GLOBALS['ecs']->table("pocket_goods_tag")." where tag_id='".$row['tag_id']."'";
		$tag_name = $GLOBALS['db']->getOne($sql);

		$cat_name=array();
		$cat_id=explode(",",$row['cat_id']);
		foreach ($cat_id AS $cat_id2)
		{
			$sql = "select cat_name from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where cat_id='".$cat_id2."' limit 1";
			$cat_name2 = $GLOBALS['db']->getOne($sql);
			array_push($cat_name,"<a href='".get_site_url()."goods_list.php?c_id=".$cat_id2."' target='_blank'>".$cat_name2."</a>");
		}
		$cat_name=($cat_name) ? implode($cat_name,"<br>") : "";
		//商品评论
		$sql_comment = "select count(*) as total from ".$GLOBALS['ecs']->table('comment')." where goods_id = ".$row['goods_id'];
		$comment_count = $GLOBALS['db']->getOne($sql_comment);
		$info[$key]['comment_count']=($comment_count) ? $comment_count : 0;
		$info[$key]['goods_info']=$goods;
		$info[$key]['tag_name']=$tag_name;
		$info[$key]['cat_name']=$cat_name;
		$info[$key]['time']=local_date('Y/m/d H:i:s',$row['time']);
		$info[$key]['buy_link']=get_buy_link($row['goods_id']);
       //商品付款购买量
	 $sql_salesnum = "select sum(og.goods_number) from ".$GLOBALS['ecs']->table('order_goods')." og,".$GLOBALS['ecs']->table('order_info')." o where og.order_id=o.order_id and o.pay_status=2 and goods_id = ".$row['goods_id'];
       $salesnum = $GLOBALS['db']->getOne($sql_salesnum);

	   $info[$key]['paysalesnum']=($salesnum)>0?$salesnum:0;
		$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET salesnum_pay='".$info[$key]['paysalesnum']."' where goods_id=".$row['goods_id']." limit 1";
		//echo $sql;
		$GLOBALS['db']->query($sql);
	}
	$cat_sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_cat')."  order by sort_order desc, cat_id asc";
	$cat_info = $GLOBALS['db']->getAll($cat_sql);
	$tag_sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_tag')."  order by sort_order desc, tag_id asc";
	$tag_info = $GLOBALS['db']->getAll($tag_sql);
	$GLOBALS['smarty']->assign('tag_info', $tag_info);
	$GLOBALS['smarty']->assign('cat_info', $cat_info);
	$GLOBALS['smarty']->assign('info', $info);
	$GLOBALS['smarty']->assign('nav_left', $_GET['type']);
	$GLOBALS['smarty']->assign('keyword', $_GET['keyword']);
	$GLOBALS['smarty']->assign('site_url', get_site_url());
	//var_dump($info);die();
	//分类列表
	$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where parent_id=0 and is_show=1 order by sort_order desc, cat_id asc";
	$info = $GLOBALS['db']->getAll($sql);
	$array=array();
	foreach($info as $key=>$v)
	{
		$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where parent_id='".$v['cat_id']."'  and is_show=1 order by sort_order desc, cat_id asc";
		$info2 = $GLOBALS['db']->getAll($sql);
		$array2=array();
		foreach($info2 as $key2=>$v2)
		{
			$sql = "select cat_name from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where cat_id='".$v2['parent_id']."' limit 1";
			$cat_name = $GLOBALS['db']->getOne($sql);
			$v2['cat_name_p']=$cat_name;
			$array2[$key2]=$v2;
		}
		$v['cat_name_p']='';
		$v['list']=$array2;
		$array[$key]=$v;
	}
	$GLOBALS['smarty']->assign('cate_list', $array);
	$GLOBALS['smarty']->display('goods_list.htm');
}

/*------------------------------------------------------ */
//-- 添加新商品 编辑商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit' || $_REQUEST['act'] == 'copy')
{
	include_once(ROOT_PATH . 'includes/fckeditor/fckeditor.php'); // 包含 html editor 类文件
	$is_add = $_REQUEST['act'] == 'add'; // 添加还是编辑的标识
	$is_copy = $_REQUEST['act'] == 'copy'; //是否复制
	$code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
	$code=$code=='virual_card' ? 'virual_card': '';
	if ($code == 'virual_card')
	{
		//admin_priv('virualcard'); // 检查权限
	}
	else
	{
		//admin_priv('goods_manage'); // 检查权限
	}
	/* 供货商名 */
	$suppliers_list_name = suppliers_list_name();
	$suppliers_exists = 1;
	if (empty($suppliers_list_name))
	{
		$suppliers_exists = 0;
	}

	$smarty->assign('suppliers_exists', $suppliers_exists);
	$smarty->assign('suppliers_list_name', $suppliers_list_name);
	unset($suppliers_list_name, $suppliers_exists);
	//图片
	$sql = "select *  FROM " . $GLOBALS['ecs']->table('goods_gallery') ." WHERE goods_id = '".$_REQUEST[goods_id]."' order by img_id asc";
	$pic_list = $GLOBALS['db']->getAll($sql);
	$smarty->assign('pic_list', $pic_list);



	/* 如果是安全模式，检查目录是否存在 */
	if (ini_get('safe_mode') == 1 && (!file_exists('../' . IMAGE_DIR . '/'.date('Ym')) || !is_dir('../' . IMAGE_DIR . '/'.date('Ym'))))
	{
		if (@!mkdir('../' . IMAGE_DIR . '/'.date('Ym'), 0777))
		{
			$warning = sprintf($_LANG['safe_mode_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
			$smarty->assign('warning', $warning);
		}
	}

	/* 如果目录存在但不可写，提示用户 */
	elseif (file_exists('../' . IMAGE_DIR . '/'.date('Ym')) && file_mode_info('../' . IMAGE_DIR . '/'.date('Ym')) < 2)
	{
		$warning = sprintf($_LANG['not_writable_warning'], '../' . IMAGE_DIR . '/'.date('Ym'));
		$smarty->assign('warning', $warning);
	}
	/* 取得商品信息 */
	if ($is_add)
	{
		/* 默认值 */
		$last_choose = array(0, 0);
		if (!empty($_COOKIE['ECSCP']['last_choose']))
		{
			$last_choose = explode('|', $_COOKIE['ECSCP']['last_choose']);
		}
		$goods = array(
		'goods_id'      => 0,
		'goods_desc'    => '',
		'cat_id'        => $last_choose[0],
		'brand_id'      => $last_choose[1],
		'is_on_sale'    => '1',
		'is_alone_sale' => '1',
		'is_only_show' => '0',
		'is_shipping' => '0',
		'other_cat'     => array(), // 扩展分类
		'goods_type'    => 0,       // 商品类型
		'shop_price'    => 0,
		'promote_price' => 0,
		'market_price'  => 0,
		'integral'      => 0,
		'goods_number'  => $_CFG['default_storage'],
		'warn_number'   => 1,
		'promote_start_date' => local_date('Y-m-d'),
		'promote_end_date'   => local_date('Y-m-d', local_strtotime('+1 month')),
		'goods_weight'  => 0,
		'give_integral' => -1,
		'rank_integral' => -1,
		'imgcanfg'		=> 0
		);

		if ($code != '')
		{
			$goods['goods_number'] = 0;
		}
		/* 关联商品 */
		$link_goods_list = array();
		$sql = "DELETE FROM " . $ecs->table('link_goods') .
		" WHERE (goods_id = 0 OR link_goods_id = 0)" .
		" AND admin_id = '$_SESSION[admin_id]'";
		$db->query($sql);
		/* 组合商品 */
		$group_goods_list = array();
		$sql = "DELETE FROM " . $ecs->table('group_goods') .
		" WHERE parent_id = 0 AND admin_id = '$_SESSION[admin_id]'";
		$db->query($sql);

		/* 关联文章 */
		$goods_article_list = array();
		$sql = "DELETE FROM " . $ecs->table('goods_article') .
		" WHERE goods_id = 0 AND admin_id = '$_SESSION[admin_id]'";
		$db->query($sql);

		/* 属性 */
		$sql = "DELETE FROM " . $ecs->table('goods_attr') . " WHERE goods_id = 0";
		$db->query($sql);

		/* 图片列表 */
		$img_list = array();
	}
	else
	{
		/* 商品信息 */
		$sql = "SELECT * FROM " . $ecs->table('pocket_goods') . " WHERE goods_id = '$_REQUEST[goods_id]'";
		$goods = $db->getRow($sql);
		$sql = "select tag_name from ". $GLOBALS['ecs']->table("pocket_goods_tag")." where tag_id='".$goods['tag_id']."'";
		$this_tag_name = $GLOBALS['db']->getOne($sql);
		$smarty->assign('this_tag_name', $this_tag_name);
		//品牌
		$sql = "select brand_name from ". $GLOBALS['ecs']->table("brand")." where brand_id='".$goods['brand_id']."'";
		$this_brand_name = $GLOBALS['db']->getOne($sql);
		$smarty->assign('this_brand_name', $this_brand_name);
		//分类
		$sql = "select cat_name from ". $GLOBALS['ecs']->table("pocket_goods_cat")." where cat_id='".$goods['cat_id']."'";
		$this_cat_name = $GLOBALS['db']->getOne($sql);
		$smarty->assign('this_cat_name', $this_cat_name);
		/* 虚拟卡商品复制时, 将其库存置为0*/
		if ($is_copy && $code != '')
		{
			$goods['goods_number'] = 0;
		}

		if (empty($goods) === true)
		{
			/* 默认值 */
			$goods = array(
			'goods_id'      => 0,
			'goods_desc'    => '',
			'cat_id'        => 0,
			'is_on_sale'    => '1',
			'is_alone_sale' => '1',
			'is_only_show' => '0',
			'is_shipping' => '0',
			'other_cat'     => array(), // 扩展分类
			'goods_type'    => 0,       // 商品类型
			'shop_price'    => 0,
			'promote_price' => 0,
			'market_price'  => 0,
			'integral'      => 0,
			'goods_number'  => 1,
			'warn_number'   => 1,
			'promote_start_date' => local_date('Y-m-d'),
			'promote_end_date'   => local_date('Y-m-d', time()+30*86400),
			'goods_weight'  => 0,
			'give_integral' => -1,
			'rank_integral' => -1,
			'imgcanfg'		=> 0
			);
		}

		/* 获取商品类型存在规格的类型 */
		$specifications = get_goods_type_specifications();
		$goods['specifications_id'] = $specifications[$goods['goods_type']];
		$_attribute = get_goods_specifications_list($goods['goods_id']);
		$goods['_attribute'] = empty($_attribute) ? '' : 1;


		/* 根据商品重量的单位重新计算 */
		if ($goods['goods_weight'] > 0)
		{
			$goods['goods_weight_by_unit'] = ($goods['goods_weight'] >= 1) ? $goods['goods_weight'] : ($goods['goods_weight'] / 0.001);
			$goods['goods_weight_by_unit']=str_replace(".000","",$goods['goods_weight_by_unit']);
		}

		if (!empty($goods['goods_brief']))
		{
			//$goods['goods_brief'] = trim_right($goods['goods_brief']);
			$goods['goods_brief'] = $goods['goods_brief'];
		}
		if (!empty($goods['keywords']))
		{
			//$goods['keywords']    = trim_right($goods['keywords']);
			$goods['keywords']    = $goods['keywords'];
		}


		/* 如果不是促销，处理促销日期 */
		if (isset($goods['is_promote']) && $goods['is_promote'] == '0')
		{
			unset($goods['promote_start_date']);
			unset($goods['promote_end_date']);
		}
		else
		{
			$goods['promote_start_date'] = local_date('Y-m-d', $goods['promote_start_date']);
			$goods['promote_end_date'] = local_date('Y-m-d', $goods['promote_end_date']);
		}

		/* 如果是复制商品，处理 */
		if ($_REQUEST['act'] == 'copy')
		{
			// 商品信息
			$goods['goods_id'] = 0;
			$goods['goods_sn'] = '';
			$goods['style_sn'] = '';
			$goods['product_sn'] = '';
			$goods['goods_name'] = '';
			$goods['goods_img'] = '';
			$goods['goods_thumb'] = '';
			$goods['original_img'] = '';

			$goods['goods_zizhi'] = '';

			// 扩展分类不变

			// 关联商品
			$sql = "DELETE FROM " . $ecs->table('link_goods') .
			" WHERE (goods_id = 0 OR link_goods_id = 0)" .
			" AND admin_id = '$_SESSION[admin_id]'";
			$db->query($sql);

			$sql = "SELECT '0' AS goods_id, link_goods_id, is_double, '$_SESSION[admin_id]' AS admin_id" .
			" FROM " . $ecs->table('link_goods') .
			" WHERE goods_id = '$_REQUEST[goods_id]' ";
			$res = $db->query($sql);
			while ($row = $db->fetchRow($res))
			{
				$db->autoExecute($ecs->table('link_goods'), $row, 'INSERT');
			}

			$sql = "SELECT goods_id, '0' AS link_goods_id, is_double, '$_SESSION[admin_id]' AS admin_id" .
			" FROM " . $ecs->table('link_goods') .
			" WHERE link_goods_id = '$_REQUEST[goods_id]' ";
			$res = $db->query($sql);
			while ($row = $db->fetchRow($res))
			{
				$db->autoExecute($ecs->table('link_goods'), $row, 'INSERT');
			}

			// 配件
			$sql = "DELETE FROM " . $ecs->table('group_goods') .
			" WHERE parent_id = 0 AND admin_id = '$_SESSION[admin_id]'";
			$db->query($sql);

			$sql = "SELECT 0 AS parent_id, goods_id, goods_price, '$_SESSION[admin_id]' AS admin_id " .
			"FROM " . $ecs->table('group_goods') .
			" WHERE parent_id = '$_REQUEST[goods_id]' ";
			$res = $db->query($sql);
			while ($row = $db->fetchRow($res))
			{
				$db->autoExecute($ecs->table('group_goods'), $row, 'INSERT');
			}

			// 关联文章
			$sql = "DELETE FROM " . $ecs->table('goods_article') .
			" WHERE goods_id = 0 AND admin_id = '$_SESSION[admin_id]'";
			$db->query($sql);

			$sql = "SELECT 0 AS goods_id, article_id, '$_SESSION[admin_id]' AS admin_id " .
			"FROM " . $ecs->table('goods_article') .
			" WHERE goods_id = '$_REQUEST[goods_id]' ";
			$res = $db->query($sql);
			while ($row = $db->fetchRow($res))
			{
				$db->autoExecute($ecs->table('goods_article'), $row, 'INSERT');
			}

			// 图片不变

			// 商品属性
			$sql = "DELETE FROM " . $ecs->table('goods_attr') . " WHERE goods_id = 0";
			$db->query($sql);

			$sql = "SELECT 0 AS goods_id, attr_id, attr_value, attr_price " .
			"FROM " . $ecs->table('goods_attr') .
			" WHERE goods_id = '$_REQUEST[goods_id]' ";
			$res = $db->query($sql);
			while ($row = $db->fetchRow($res))
			{
				$db->autoExecute($ecs->table('goods_attr'), addslashes_deep($row), 'INSERT');
			}
		}
		// 扩展分类
		$other_cat_list = array();
		$sql = "SELECT cat_id FROM " . $ecs->table('goods_cat') . " WHERE goods_id = '$_REQUEST[goods_id]'";
		$goods['other_cat'] = $db->getCol($sql);
		foreach ($goods['other_cat'] AS $cat_id)
		{
			$other_cat_list[$cat_id] = cat_list(0, $cat_id);
		}
		$smarty->assign('other_cat_list', $other_cat_list);
		// 多分类
		$cat_id_array = array();
		$cat_id=$goods['cat_id'];
		$cat_id=explode(",",$cat_id);
		foreach ($cat_id AS $key=>$cat_id2)
		{
			if(!empty($cat_id2))
			{
				$sql = "select cat_name from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where cat_id='".$cat_id2."' limit 1";
				$cat_name = $GLOBALS['db']->getOne($sql);
				$cat_id_array[$key]['cat_name']=$cat_name;
				$cat_id_array[$key]['id']=$cat_id2;
			}
		}
		$smarty->assign('cat_id_array', $cat_id_array);

		$link_goods_list    = get_linked_goods($goods['goods_id']); // 关联商品
		$group_goods_list   = get_this_group_goods($goods['goods_id']); // 配件
		$goods_article_list = get_goods_articles($goods['goods_id']);   // 关联文章

		/* 商品图片路径 */
		if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 10) && !empty($goods['original_img']))
		{
			$goods['goods_img'] = get_image_path($_REQUEST['goods_id'], $goods['goods_img']);
			$goods['goods_thumb'] = get_image_path($_REQUEST['goods_id'], $goods['goods_thumb'], true);

			$goods['goods_zizhi'] = get_image_path($_REQUEST['goods_id'], $goods['goods_zizhi'], true);
		}

		/* 图片列表 */
		$sql = "SELECT * FROM " . $ecs->table('goods_gallery') . " WHERE goods_id = '$goods[goods_id]'  order by img_asc asc, img_id desc";
		$img_list = $db->getAll($sql);

		/* 格式化相册图片路径 */
		if (isset($GLOBALS['shop_id']) && ($GLOBALS['shop_id'] > 0))
		{
			foreach ($img_list as $key => $gallery_img)
			{
				$gallery_img[$key]['img_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], false, 'gallery');
				$gallery_img[$key]['thumb_url'] = get_image_path($gallery_img['goods_id'], $gallery_img['img_original'], true, 'gallery');
			}
		}
		else
		{
			foreach ($img_list as $key => $gallery_img)
			{
				$gallery_img[$key]['thumb_url'] = '../' . (empty($gallery_img['thumb_url']) ? $gallery_img['img_url'] : $gallery_img['thumb_url']);
			}
		}



	}
	// 标签
	$tag_list = array();
	$sql = "SELECT * FROM " . $ecs->table('pocket_goods_tag') . " order by sort_order desc, tag_id asc";
	$tag_list = $db->getAll($sql);
	$smarty->assign('tag_list', $tag_list);
	/* 拆分商品名称样式 */
	$goods_name_style = explode('+', empty($goods['goods_name_style']) ? '+' : $goods['goods_name_style']);
	/* 创建 html editor */
	create_html_editor('goods_desc', $goods['goods_desc']);

	/* 模板赋值 */
	$smarty->assign('code',    $code);
	$smarty->assign('ur_here', $is_add ? (empty($code) ? $_LANG['02_goods_add'] : $_LANG['51_virtual_card_add']) : ($_REQUEST['act'] == 'edit' ? $_LANG['edit_goods'] : $_LANG['copy_goods']));
	$smarty->assign('action_link', list_link($is_add, $code));
	$smarty->assign('goods', $goods);
	$smarty->assign('goods_name_color', $goods_name_style[0]);
	$smarty->assign('goods_name_style', $goods_name_style[1]);
	$this_cat_list=cat_list(0, $goods['cat_id']);
	//$this_cat_list=preg_replace("/<option value=\"([^\"]*)\" >([^<]*)<\/option>/is",'<li><a alt="$1">$2</a></li>',$this_cat_list);
	
	$smarty->assign('cat_list',$this_cat_list);
	$brand_list=get_brand_list();
	$brand_list_array=array();
	foreach($brand_list as $key=>$val)
	{
		$brand_list_array[$key]['brand_id']=$key;
		$brand_list_array[$key]['brand_name']=$val;
	} 
	$smarty->assign('brand_list',$brand_list_array);
	$smarty->assign('brandkeywords1_list',   get_brandkeywords_list(1));
	$smarty->assign('brandkeywords2_list',   get_brandkeywords_list(2));
	$smarty->assign('unit_list', get_unit_list());
	$smarty->assign('user_rank_list', get_user_rank_list());
	$smarty->assign('weight_unit', $is_add ? '1' : ($goods['goods_weight'] >= 1 ? '1' : '0.001'));
	$smarty->assign('cfg', $_CFG);
	$smarty->assign('form_act', $is_add ? 'insert' : ($_REQUEST['act'] == 'edit' ? 'update' : 'insert'));
	if ($_REQUEST['act'] == 'add')
	{
		$smarty->assign('start_time', date('Y-m-d', time() + 86400)." 00:00");
		$smarty->assign('end_time', date('Y-m-d', time() + 4 * 86400)." 00:00");
	}
	if ($_REQUEST['act'] == 'edit')
	{
		$smarty->assign('start_time', local_date("Y-m-d H:i", $goods['start_time']));
		$smarty->assign('end_time', local_date("Y-m-d H:i", $goods['end_time']));
	}
	if ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
	{
		$smarty->assign('is_add', true);
	}
	if(!$is_add)
	{
		$smarty->assign('member_price_list', get_member_price_list($_REQUEST['goods_id']));
	}
	$smarty->assign('link_goods_list', $link_goods_list);
	$smarty->assign('group_goods_list', $group_goods_list);
	$smarty->assign('goods_article_list', $goods_article_list);
	$smarty->assign('img_list', $img_list);
	$smarty->assign('goods_type_list', goods_type_list($goods['goods_type']));
	$smarty->assign('gd', gd_version());
	$smarty->assign('thumb_width', $_CFG['thumb_width']);
	$smarty->assign('thumb_height', $_CFG['thumb_height']);
	$smarty->assign('goods_attr_html', build_attr_html($goods['goods_type'], $goods['goods_id']));
	$volume_price_list = '';
	if(isset($_REQUEST['goods_id']))
	{
		$volume_price_list = get_volume_price_list($_REQUEST['goods_id']);
	}
	if (empty($volume_price_list))
	{
		$volume_price_list = array('0'=>array('number'=>'','price'=>''));
	}
	$smarty->assign('volume_price_list', $volume_price_list);
	/* 显示商品信息页面 */
	assign_query_info();

	//分类列表
	$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where parent_id=0 and is_show=1 order by sort_order desc, cat_id asc";
	$info = $GLOBALS['db']->getAll($sql);
	$array=array();
	foreach($info as $key=>$v)
	{
		$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where parent_id='".$v['cat_id']."'  and is_show=1 order by sort_order desc, cat_id asc";
		$info2 = $GLOBALS['db']->getAll($sql);
		$array2=array();
		foreach($info2 as $key2=>$v2)
		{
			$sql = "select cat_name from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where cat_id='".$v2['parent_id']."' limit 1";
			$cat_name = $GLOBALS['db']->getOne($sql);
			$v2['cat_name_p']=$cat_name;
			$array2[$key2]=$v2;
		}
		$v['cat_name_p']='';
		$v['list']=$array2;
		$array[$key]=$v;
	}
	//适用范围
	$sql = "select * from ".$GLOBALS['ecs']->table('users2_tag')."";
	$can_list = $GLOBALS['db']->getAll($sql);
	$GLOBALS['smarty']->assign('can_list', $can_list);
	$can_id_array = array();
		$can_usertag=$goods['can_usertag'];
		$can_usertag=explode(",",$can_usertag); 
		foreach ($can_usertag AS $key=>$can_id2)
		{
			if(!empty($can_id2))
			{
				$sql = "select name from ". $GLOBALS['ecs']->table('users2_tag')." where id='".$can_id2."' limit 1";
				$can_name = $GLOBALS['db']->getOne($sql);
				$can_id_array[$key]['name']=$can_name;
				$can_id_array[$key]['id']=$can_id2;
			}
		} 
	$smarty->assign('can_id_array', $can_id_array);
	$GLOBALS['smarty']->assign('cate_list', $array);
	$smarty->display('goods_add.htm');
}
elseif ($_REQUEST['act'] == 'import_goods_cvs')
{
		$where=get_search_where($_GET['type']);
		if($_GET['keyword'])
		$sql = "select * from " . $GLOBALS['ecs']->table("pocket_goods") . " " . $where . " order by sort_order desc, goods_id desc LIMIT 10000";
		else
		$sql = "select * from " . $GLOBALS['ecs']->table("pocket_goods") . " order by sort_order desc, goods_id desc LIMIT 10000";
		$user = $GLOBALS['db']->getAll($sql);
		$data=array();
		foreach ($user as $key => $row) {
			$data[$key]['goods_id'] = $row['goods_id'];
			$data[$key]['goods_name'] = $row['goods_name'];
			$data[$key]['goods_name2'] = $row['goods_name2'];
			$data[$key]['goods_sn'] = "'".$row['goods_sn'];
			//分类
			$cat_name=array();
			$cat_id=explode(",",$row['cat_id']);
			foreach ($cat_id AS $cat_id2)
			{
				if($cat_id2)
				{
					$sql = "select cat_name from ". $GLOBALS['ecs']->table('pocket_goods_cat')." where cat_id='".$cat_id2."' limit 1";
					$cat_name2 = $GLOBALS['db']->getOne($sql);
					array_push($cat_name,$cat_name2);
				}
			}
			$data[$key]['cat_name']=($cat_name) ? implode($cat_name,",") : "";
			$data[$key]['pv'] = $row['pv'];
			$data[$key]['shop_price'] = $row['shop_price'];
			$data[$key]['market_price'] = $row['market_price'];
			$data[$key]['goods_number'] = $row['goods_number'];
			$data[$key]['salesnum'] = $row['salesnum_pay'];
			$data[$key]['time'] = local_date('Y-m-d H:i:s',$row['time']);
			$data[$key]['is_on_sale'] = ($row['is_on_sale']==1) ? "已上架" : "已下架";
			$data[$key]['is_show'] = ($row['is_show']==1) ? "显示" : "隐藏";
			$data[$key]['sort_order'] = $row['sort_order'];
		}
		$title_arr[] = array('商品编号','商品名称','缩写名称','商品货号','商品分类','PV值','价格','市场售价','库存','总销量','时间','上架','显示','排序');
		$datelist = array_merge ($title_arr,$data);
		outputCsvHeaderNew($datelist,"goods-".local_date('Y-m-d', gmtime()));
}
/*------------------------------------------------------ */
//-- 插入商品 更新商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'delete_this')
{
	$goods_id=$_POST['goods_id'];
	$goods = get_goods_info($goods_id);
	if(!empty($goods))
	{
		$sql = "DELETE FROM " .$GLOBALS['ecs']->table("pocket_goods"). " where goods_id=".$goods_id." limit 1";
		$GLOBALS['db']->query($sql);
		//$this->goods_unlink($goods_id);
	}
}
elseif ($_REQUEST['act'] == 'ajax_save_goods_gallery_sort_order')
{
	$img_id=$_POST['this_id'];
	$sort_order=$_POST['sort_order'];
	$sort_order = ($sort_order) ? $sort_order : 0;
	$data=array("img_asc"=>$sort_order);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table("goods_gallery"),$data,'',"img_id=".$img_id." limit 1");
}
elseif ($_REQUEST['act'] == 'ajax_save_is_show')
{
	$goods_id=$_POST['goods_id'];
	$goods = get_goods_info($goods_id);
	$is_show = ($goods['is_show'] == 1) ? 2 : 1;
	$data=array("is_show"=>$is_show);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table("pocket_goods"),$data,'',"goods_id=".$goods_id." limit 1");
}
elseif ($_REQUEST['act'] == 'ajax_save_sort_order')
{
	$goods_id=$_POST['goods_id'];
	$orderby=$_POST['orderby'];
	$data=array("sort_order"=>$orderby);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$goods_id." limit 1");
}
elseif ($_REQUEST['act'] == 'ajax_goods_delete')
{
	ajax_goods_delete();
}
elseif ($_REQUEST['act'] == 'ajax_is_on_sale')
{
	$goods_id=$_POST['goods_id'];
	$sql = "select is_on_sale from ". $GLOBALS['ecs']->table('pocket_goods')." where goods_id='".$goods_id."' limit 1";
	$is_on_sale = $GLOBALS['db']->getOne($sql);
	$is_on_sale=($is_on_sale) ? 0 : 1;
	$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET is_on_sale='".$is_on_sale."' where goods_id=".$goods_id." limit 1";
	$GLOBALS['db']->query($sql);
	$array = array("error" => 0,"info" => $is_on_sale,"goods_id" => $goods_id);
	echo json_encode($array);die();
}
elseif ($_REQUEST['act'] == 'ajax_is_show')
{
	$goods_id=$_POST['goods_id'];
	$sql = "select is_show from ". $GLOBALS['ecs']->table('pocket_goods')." where goods_id='".$goods_id."' limit 1";
	$is_show = $GLOBALS['db']->getOne($sql);
	$is_show=($is_show==1) ? 2 : 1;
	$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET is_show='".$is_show."' where goods_id=".$goods_id." limit 1";
	$GLOBALS['db']->query($sql);
	$array = array("error" => 0,"info" => $is_show,"goods_id" => $goods_id);
	echo json_encode($array);die();
}
elseif ($_REQUEST['act'] == 'ajax_get_goods_info')
{
	$goods_id=$_POST['goods_id'];
	$goods = get_goods_info($goods_id);
	$data=array("info"=>$goods);
	//分类
	$cat_id=$goods['cat_id'];
	if($cat_id)
	{
		$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods_cat")." where cat_id='".$cat_id."' limit 1";
		$cat = $GLOBALS['db']->getRow($sql);
		$data['cat_info']=$cat;
	}
	echo json_encode($data);die();
}
elseif ($_REQUEST['act'] == 'create_qRcode')
{
	$goods_id=$_POST['goods_id'];
	$QR='QRcode/goodsQRcode/QRcode_'.$goods_id.'.png';
	$filename = ROOT_PATH.$QR;
	//	if(!@file_exists($filename))
	//	{
	$data = get_buy_link($goods_id);
	include(ROOT_PATH.'plugins/phpqrcode/phpqrcode.php');
	// 二维码数据
	// 生成的文件名
	// 纠错级别：L、M、Q、H
	$errorCorrectionLevel = 'Q';
	// 点的大小：1到10
	$matrixPointSize = 4;
	QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	$pic_url=get_admin_url().$QR;
	$array=array("101","102","125","126");
	foreach($array as $val)
	{
		$file="http://192.168.1.".$val."/weixin_shop/".$QR;
		$file_arr=@getimagesize($file);
		//var_dump($file_arr);
		if($file_arr[0]>0)
		{
			$pic_url=$file;
			break;
		}
	}
	$QR2=copy_this_pic_to_server($pic_url,"pocket_QRgoods/goods_".$goods_id.'.png');
	$buy_link=get_buy_link($goods_id);
	$array=array("pic"=>$QR2,"error"=>0,"buy_link"=>$buy_link);
	echo json_encode($array);die();
	/*}
	else
	{
	$array=array("pic"=>get_site_url().$QR,"error"=>0);
	echo json_encode($array);die();
	}*/
}
elseif ($_REQUEST['act'] == 'create_qRcode_link')
{
	$goods_id=$_GET['goods_id'];
	$QR='QRcode/goodsQRcode/QRcode_'.$goods_id.'_link.png';
	$filename2 = ROOT_PATH.'phone/pocket/'.$QR;
	$data = get_buy_link($goods_id);
	include(ROOT_PATH.'plugins/phpqrcode/phpqrcode.php');
	// 二维码数据
	// 生成的文件名
	// 纠错级别：L、M、Q、H
	$errorCorrectionLevel = 'H';
	// 点的大小：1到10
	$matrixPointSize = 12;
	QRcode::png($data, $filename2, $errorCorrectionLevel, $matrixPointSize,2);
	$pic_url=get_site_url().$QR;
	$array=array("101","102","125","126");
	foreach($array as $val)
	{
		$file="http://192.168.1.".$val."/phone/pocket/".$QR;
		$file_arr=@getimagesize($file);
		if($file_arr[0]>0)
		{
			$pic_url=$file;
			break;
		}
	}
	$filename=copy_this_pic_to_server($pic_url,"pocket_QRgoods/goods_".$goods_id.'_link.png');
	down_img($filename2);
}
elseif ($_REQUEST['act'] == 'ajax_layer_save')
{
	ajax_layer_save();
}
elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
	$code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
	/* 是否处理缩略图 */
	$proc_thumb = (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0)? false : true;
	if ($code == 'virtual_card')
	{
		//admin_priv('virualcard'); // 检查权限
	}
	else
	{
		//admin_priv('goods_manage'); // 检查权限
	}

	/* 检查货号是否重复 */
	if ($_POST['goods_sn'])
	{
		$sql = "SELECT COUNT(*) FROM " . $ecs->table('pocket_goods') .
		" WHERE goods_sn = '$_POST[goods_sn]' AND is_delete = 0 AND goods_id <> '$_POST[goods_id]'";
		if ($db->getOne($sql) > 0)
		{
			sys_msg($_LANG['goods_sn_exists'], 1, array(), false);
		}
	}

	/* 检查图片：如果有错误，检查尺寸是否超过最大值；否则，检查文件类型 */
	if (isset($_FILES['goods_img']['error'])) // php 4.2 版本才支持 error
	{
		// 最大上传文件大小
		$php_maxsize = ini_get('upload_max_filesize');
		$htm_maxsize = '2M';

		// 商品图片
		/*if ($_FILES['goods_img']['error'] == 0)
		{
			if (!$image->check_img_type($_FILES['goods_img']['type']))
			{
				sys_msg($_LANG['invalid_goods_img'], 1, array(), false);
			}
		}
		elseif ($_FILES['goods_img']['error'] == 1)
		{
			sys_msg(sprintf($_LANG['goods_img_too_big'], $php_maxsize), 1, array(), false);
		}
		elseif ($_FILES['goods_img']['error'] == 2)
		{
			sys_msg(sprintf($_LANG['goods_img_too_big'], $htm_maxsize), 1, array(), false);
		}*/

		// 商品缩略图
		/*if (isset($_FILES['goods_thumb']))
		{
			if ($_FILES['goods_thumb']['error'] == 0)
			{
				if (!$image->check_img_type($_FILES['goods_thumb']['type']))
				{
					sys_msg($_LANG['invalid_goods_thumb'], 1, array(), false);
				}
			}
			elseif ($_FILES['goods_thumb']['error'] == 1)
			{
				sys_msg(sprintf($_LANG['goods_thumb_too_big'], $php_maxsize), 1, array(), false);
			}
			elseif ($_FILES['goods_thumb']['error'] == 2)
			{
				sys_msg(sprintf($_LANG['goods_thumb_too_big'], $htm_maxsize), 1, array(), false);
			}
		}*/

		// 相册图片
		foreach ($_FILES['img_url']['error'] AS $key => $value)
		{
			if ($value == 0)
			{
				if (!$image->check_img_type($_FILES['img_url']['type'][$key]))
				{
					sys_msg(sprintf($_LANG['invalid_img_url'], $key + 1), 1, array(), false);
				}
			}
			elseif ($value == 1)
			{
				sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $php_maxsize), 1, array(), false);
			}
			elseif ($_FILES['img_url']['error'] == 2)
			{
				sys_msg(sprintf($_LANG['img_url_too_big'], $key + 1, $htm_maxsize), 1, array(), false);
			}
		}
	}
	/* 4.1版本 */
	else
	{
		// 商品图片
		/*if ($_FILES['goods_img']['tmp_name'] != 'none')
		{
			if (!$image->check_img_type($_FILES['goods_img']['type']))
			{

				sys_msg($_LANG['invalid_goods_img'], 1, array(), false);
			}
		}

		// 商品缩略图
		if (isset($_FILES['goods_thumb']))
		{
			if ($_FILES['goods_thumb']['tmp_name'] != 'none')
			{
				if (!$image->check_img_type($_FILES['goods_thumb']['type']))
				{
					sys_msg($_LANG['invalid_goods_thumb'], 1, array(), false);
				}
			}
		}*/

		// 相册图片
		foreach ($_FILES['img_url']['tmp_name'] AS $key => $value)
		{
			if ($value != 'none')
			{
				if (!$image->check_img_type($_FILES['img_url']['type'][$key]))
				{
					sys_msg(sprintf($_LANG['invalid_img_url'], $key + 1), 1, array(), false);
				}
			}
		}
	}
	/* 插入还是更新的标识 */
	$is_insert = $_REQUEST['act'] == 'insert';

	/* 处理商品图片 */
	$goods_img        = '';  // 初始化商品图片
	$goods_thumb      = '';  // 初始化商品缩略图
	$original_img     = '';  // 初始化原始图片
	$old_original_img = '';  // 初始化原始图片旧图
	// 如果上传了商品图片，相应处理

	//if (($_FILES['goods_img']['tmp_name'] != '' && $_FILES['goods_img']['tmp_name'] != 'none') or (($_POST['goods_img_url'] != $_LANG['lab_picture_url'] && $_POST['goods_img_url'] != 'http://') && $is_url_goods_img = 1))
	if (($_FILES['goods_img']['tmp_name'] != '' && $_FILES['goods_img']['tmp_name'] != 'none'))
	{
		if ($_REQUEST['goods_id'] > 0)
		{
			/* 删除原来的图片文件 */
			$sql = "SELECT goods_thumb, goods_img, original_img " .
			" FROM " . $ecs->table('pocket_goods') .
			" WHERE goods_id = '$_REQUEST[goods_id]'";
			$row = $db->getRow($sql);
			if ($row['goods_thumb'] != '' && is_file('../' . $row['goods_thumb']))
			{
				@unlink('../' . $row['goods_thumb']);
			}
			if ($row['goods_img'] != '' && is_file('../' . $row['goods_img']))
			{
				@unlink('../' . $row['goods_img']);
			}
			if ($row['original_img'] != '' && is_file('../' . $row['original_img']))
			{
				/* 先不处理，以防止程序中途出错停止 */
				//$old_original_img = $row['original_img']; //记录旧图路径
			}
			/* 清除原来商品图片 */
			if ($proc_thumb === false)
			{
				get_image_path($_REQUEST[goods_id], $row['goods_img'], false, 'goods', true);
				get_image_path($_REQUEST[goods_id], $row['goods_thumb'], true, 'goods', true);
			}
		}
		if (empty($is_url_goods_img))
		{
			$original_img   = $image->upload_image($_FILES['goods_img']); // 原始图片

		}
		elseif (copy(trim($_POST['goods_img_url']), ROOT_PATH . 'temp/' . basename($_POST['goods_img_url'])))
		{
			$original_img = 'temp/' . basename($_POST['goods_img_url']);
		}

		if ($original_img === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
		$goods_img      = $original_img;   // 商品图片

		/* 复制一份相册图片 */
		/* 添加判断是否自动生成相册图片 */
		if ($_CFG['auto_generate_gallery'])
		{
			$img        = $original_img;   // 相册图片
			$pos        = strpos(basename($img), '.');
			$newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
			if (!copy('../../../' . $img, '../../../' . $newname))
			{
				sys_msg('fail to copy file: ' . realpath('../../../' . $img), 1, array(), false);
			}
			$img        = $newname;

			$gallery_img    = $img;
			$gallery_thumb  = $img;
		}
		// 如果系统支持GD，缩放商品图片，且给商品图片和相册图片加水印
		if ($proc_thumb && $image->gd_version() > 0 && $image->check_img_function($_FILES['goods_img']['type']) || $is_url_goods_img)
		{
			if (empty($is_url_goods_img))
			{
				// 如果设置大小不为0，缩放图片
				if ($_CFG['image_width'] != 0 || $_CFG['image_height'] != 0)
				{
					$goods_img = $image->make_thumb('../../../'. $goods_img , $GLOBALS['_CFG']['image_width'],  $GLOBALS['_CFG']['image_height']);
					if ($goods_img === false)
					{
						sys_msg($image->error_msg(), 1, array(), false);
					}
				}

				/* 添加判断是否自动生成相册图片 */
				if ($_CFG['auto_generate_gallery'])
				{
					$newname    = dirname($img) . '/' . $image->random_filename() . substr(basename($img), $pos);
					if (!copy('../../../' . $img, '../../../' . $newname))
					{
						sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
					}
					$gallery_img        = $newname;
				}
				//var_dump($goods_img);die();

				// 加水印
				if (intval($_CFG['watermark_place']) > 0 && !empty($GLOBALS['_CFG']['watermark']))
				{
					if ($image->add_watermark('../../../'.$goods_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
					{
						sys_msg($image->error_msg(), 1, array(), false);
					}
					/* 添加判断是否自动生成相册图片 */
					if ($_CFG['auto_generate_gallery'])
					{
						if ($image->add_watermark('../../../'. $gallery_img,'',$GLOBALS['_CFG']['watermark'], $GLOBALS['_CFG']['watermark_place'], $GLOBALS['_CFG']['watermark_alpha']) === false)
						{
							sys_msg($image->error_msg(), 1, array(), false);
						}
					}
				}
			}

			// 相册缩略图
			/* 添加判断是否自动生成相册图片 */
			if ($_CFG['auto_generate_gallery'])
			{
				if ($_CFG['thumb_width'] != 0 || $_CFG['thumb_height'] != 0)
				{
					$gallery_thumb = $image->make_thumb('../../../' . $img, $GLOBALS['_CFG']['thumb_width'],  $GLOBALS['_CFG']['thumb_height']);
					if ($gallery_thumb === false)
					{
						sys_msg($image->error_msg(), 1, array(), false);
					}
				}
			}

		}
		/* 取消该原图复制流程 */
		// else
		// {
		//     /* 复制一份原图 */
		//     $pos        = strpos(basename($img), '.');
		//     $gallery_img = dirname($img) . '/' . $image->random_filename() . // substr(basename($img), $pos);
		//     if (!copy('../' . $img, '../' . $gallery_img))
		//     {
		//         sys_msg('fail to copy file: ' . realpath('../' . $img), 1, array(), false);
		//     }
		//     $gallery_thumb = '';
		// }
	}
	// 是否上传商品缩略图
	if (isset($_FILES['goods_thumb']) && $_FILES['goods_thumb']['tmp_name'] != '' &&
	isset($_FILES['goods_thumb']['tmp_name']) &&$_FILES['goods_thumb']['tmp_name'] != 'none')
	{
		// 上传了，直接使用，原始大小
		$goods_thumb = $image->upload_image($_FILES['goods_thumb']);
		if ($goods_thumb === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
	}
	else
	{
		// 未上传，如果自动选择生成，且上传了商品图片，生成所略图
		if ($proc_thumb && isset($_POST['auto_thumb']) && !empty($original_img))
		{
			// 如果设置缩略图大小不为0，生成缩略图
			if ($_CFG['thumb_width'] != 0 || $_CFG['thumb_height'] != 0)
			{
				$goods_thumb = $image->make_thumb('../../../' . $original_img, $GLOBALS['_CFG']['thumb_width'],  $GLOBALS['_CFG']['thumb_height']);
				if ($goods_thumb === false)
				{
					sys_msg($image->error_msg(), 1, array(), false);
				}
			}
			else
			{
				$goods_thumb = $original_img;
			}
		}
	}
	if (isset($_FILES['goods_zizhi']) && $_FILES['goods_zizhi']['tmp_name'] != '' &&
	isset($_FILES['goods_zizhi']['tmp_name']) &&$_FILES['goods_zizhi']['tmp_name'] != 'none')
	{
		// 上传了，直接使用，原始大小
		$goods_zizhi = $image->upload_image($_FILES['goods_zizhi']);
		if ($goods_zizhi === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
	}

	//商品背景图
	if (isset($_FILES['goods_background']) && $_FILES['goods_background']['tmp_name'] != '' &&
	isset($_FILES['goods_background']['tmp_name']) &&$_FILES['goods_background']['tmp_name'] != 'none')
	{
		// 上传了，直接使用，原始大小
		$goods_background = $image->upload_image($_FILES['goods_background']);
		if ($goods_background === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
	}

	//积分产品图
	if (isset($_FILES['goods_points_img']) && $_FILES['goods_points_img']['tmp_name'] != '' &&
	isset($_FILES['goods_points_img']['tmp_name']) &&$_FILES['goods_points_img']['tmp_name'] != 'none')
	{
		// 上传了，直接使用，原始大小
		$goods_points_img = $image->upload_image($_FILES['goods_points_img']);
		if ($goods_points_img === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
	}

	/* 删除下载的外链原图 */
	if (!empty($is_url_goods_img))
	{
		unlink(ROOT_PATH . $original_img);
		empty($newname) || unlink(ROOT_PATH . $newname);
		$url_goods_img = $goods_img = $original_img = htmlspecialchars(trim($_POST['goods_img_url']));
	}
	/* 如果没有输入商品货号则自动生成一个商品货号 */
	if (empty($_POST['goods_sn']))
	{
		$max_id     = $is_insert ? $db->getOne("SELECT MAX(goods_id) + 1 FROM ".$ecs->table('pocket_goods')) : $_REQUEST['goods_id'];
		$goods_sn   = generate_goods_sn($max_id);
	}
	else
	{
		$goods_sn   = $_POST['goods_sn'];
	}
	/* 处理商品数据 */
	$cat_arr=$_POST['cat_arr'];
	$cat_arr=array_filter($cat_arr);
	$catgory_id = implode($cat_arr,",");
	$catgory_id=(!empty($catgory_id)) ? ",".$catgory_id."," : "";
	//适用范围
	$can_arr=$_POST['can_arr'];
	$can_arr=array_filter($can_arr);
	$can_usertag = implode($can_arr,",");
	$can_usertag=(!empty($can_usertag)) ? $can_usertag : "";
	$shop_price = !empty($_POST['shop_price']) ? $_POST['shop_price'] : 0;
	$shop_pricecode = !empty($_POST['shop_pricecode']) ? $_POST['shop_pricecode'] : '';
	$integral_pricecode = !empty($_POST['integral_pricecode']) ? $_POST['integral_pricecode'] : '';
	$integral_price = !empty($_POST['integral_price']) ? $_POST['integral_price'] : 0;
	$integral_money_price = !empty($_POST['integral_money_price']) ? $_POST['integral_money_price'] : 0;
	$market_price = !empty($_POST['market_price']) ? $_POST['market_price'] : 0;
	$promote_price = !empty($_POST['promote_price']) ? floatval($_POST['promote_price'] ) : 0;
	$is_promote = empty($promote_price) ? 0 : 1;
	$promote_start_date = ($is_promote && !empty($_POST['promote_start_date'])) ? local_strtotime($_POST['promote_start_date']) : 0;
	$promote_end_date = ($is_promote && !empty($_POST['promote_end_date'])) ? local_strtotime($_POST['promote_end_date']) : 0;
	$goods_weight = !empty($_POST['goods_weight']) ? $_POST['goods_weight'] * $_POST['weight_unit'] : 0;
	$goods_weight_unit = $_POST['weight_unit']==1 ? 1 : 2;
	$pv = $_POST['pv'];
	$is_best = isset($_POST['is_best']) ? 1 : 0;
	$is_new = isset($_POST['is_new']) ? 1 : 0;
	$is_hot = isset($_POST['is_hot']) ? 1 : 0;
	$is_index = isset($_POST['is_index']) ? 1 : 0;
	$is_hd = isset($_POST['is_hd']) ? 1 : 0;
	$is_hdbk = isset($_POST['is_hdbk']) ? 1 : 0;
	$is_share = isset($_POST['is_share']) ? 1: 0;
	$is_fenxiao = isset($_POST['is_fenxiao']) ? 1: 0;
	$is_pocket = isset($_POST['is_pocket']) ? 1: 0;
	$price_stepone = !empty($_POST['price_stepone']) ? $_POST['price_stepone'] : 0;
	$price_steptwo= !empty($_POST['price_steptwo']) ? $_POST['price_steptwo'] : 0;
	$limitations = !empty($_POST['limitations'])?$_POST['limitations']:0;
	$is_on_sale = isset($_POST['is_on_sale']) ? 1 : 0;
	$is_alone_sale = isset($_POST['is_alone_sale']) ? 1 : 0;
	$is_only_show = isset($_POST['is_only_show']) ? 1 : 0;
	$is_shipping = isset($_POST['is_shipping']) ? 1 : 0;
	$goods_number = isset($_POST['goods_number']) ? $_POST['goods_number'] : 0;
	$warn_number = isset($_POST['warn_number']) ? $_POST['warn_number'] : 0;
	$goods_type = isset($_POST['goods_type']) ? $_POST['goods_type'] : 0;
	$give_integral = isset($_POST['give_integral']) ? intval($_POST['give_integral']) : '-1';
	$rank_integral = isset($_POST['rank_integral']) ? intval($_POST['rank_integral']) : '-1';
	$suppliers_id = isset($_POST['suppliers_id']) ? intval($_POST['suppliers_id']) : '0';
	$imgcanfg = isset($_POST['imgcanfg']) ? 1 : 0;
	$goods_name_style = $_POST['goods_name_color'] . '+' . $_POST['goods_name_style'];

	
	$tag_id = empty($_POST['tag_id']) ? '' : intval($_POST['tag_id']);
	$brand_id = empty($_POST['brand_id']) ? '' : intval($_POST['brand_id']);

	$goods_thumb = (empty($goods_thumb) && !empty($_POST['goods_thumb_url']) && goods_parse_url($_POST['goods_thumb_url'])) ? htmlspecialchars(trim($_POST['goods_thumb_url'])) : $goods_thumb;
	$goods_thumb = (empty($goods_thumb) && isset($_POST['auto_thumb']))? $goods_img : $goods_thumb;

	$keywords01 = !empty($_POST['keywords01']) ? $_POST['keywords01'] : 0;
	$keywords02 = !empty($_POST['keywords02']) ? $_POST['keywords02'] : 0;

	$goods_leixing = !empty($_POST['goods_leixing']) ? $_POST['goods_leixing'] : 0;
	$optional_num = !empty($_POST['optional_num']) ? $_POST['optional_num'] : 0;
	$buy_quick = !empty($_POST['buy_quick']) ? $_POST['buy_quick'] : 0;
	$NewCustomer = !empty($_POST['NewCustomer']) ? $_POST['NewCustomer'] : 0;
	$is_update = !empty($_POST['is_update']) ? $_POST['is_update'] : 0;
	$start_time = !empty($_POST['start_time']) ? local_strtotime($_POST['start_time']) : 0;
	$end_time = !empty($_POST['end_time']) ? local_strtotime($_POST['end_time']) : 0;
	$person_canbuynum = !empty($_POST['person_canbuynum']) ? $_POST['person_canbuynum'] : 0;
	$buy_skip_goods_id = !empty($_POST['buy_skip_goods_id']) ? $_POST['buy_skip_goods_id'] : 0;
	$is_show = !empty($_POST['is_show']) ? $_POST['is_show'] : 1;
    $sxname = !empty($_POST['sxname']) ? $_POST['sxname'] : '';
	//若果有同款商品，并且同款商品的库存不为0，则得到同款商品的库存
	$mysql2="SELECT product_sn FROM ". $ecs->table('pocket_goods')."WHERE goods_id='$goods_id' LIMIT 1";
	$myproduct_sn2 = $db->getOne($mysql2);
	if($myproduct_sn2){
		update_stock($goods_number,$myproduct_sn2,0);
	}
	/* 入库 */
	if ($is_insert)
	{
		if ($code == '')
		{
			if($goods_background){
				$sql = "INSERT INTO " . $ecs->table('pocket_goods') . " (goods_name, goods_name2, goods_name_style, goods_sn, style_sn, product_sn, " .
				"cat_id,tag_id, brand_id,shop_price,shop_pricecode, integral_price,integral_pricecode,integral_money_price, market_price, is_promote, promote_price, " .
				"promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, " .
				"seller_note, goods_weight,goods_weight_unit,pv, goods_number, warn_number, integral, give_integral, is_hd,is_hdbk,is_share,is_fenxiao,price_stepone,price_steptwo,limitations,is_best, is_new, is_hot, is_index, " .
				"is_on_sale, is_alone_sale, is_only_show, is_shipping, goods_desc, add_time, last_update, goods_type, rank_integral, suppliers_id, keywords01, keywords02, goods_leixing, optional_num, goods_zizhi,imgcanfg,goods_background,buy_quick,start_time,end_time,person_canbuynum,buy_skip_goods_id,is_show,NewCustomer,is_update,sxname,can_usertag)" .
				"VALUES ('$_POST[goods_name]','$_POST[goods_name2]', '$goods_name_style', '$goods_sn', '$_POST[style_sn]', '$_POST[product_sn]', '$catgory_id','$tag_id', " .
				"'$brand_id','$shop_price','$shop_pricecode', '$integral_price','$integral_pricecode','$integral_money_price', '$market_price', '$is_promote','$promote_price', ".
				"'$promote_start_date', '$promote_end_date', '$goods_img', '$goods_thumb', '$original_img', ".
				"'$_POST[keywords]', '$_POST[goods_brief]', '$_POST[seller_note]', '$goods_weight', '$goods_weight_unit','$pv', '$goods_number',".
				" '$warn_number', '$_POST[integral]', '$give_integral', '$is_hd','$is_hdbk','$is_share','$is_fenxiao','$is_pocket','$price_stepone','$price_steptwo','$limitations','$is_best', '$is_new', '$is_hot', '$is_index', '$is_on_sale', '$is_alone_sale', 'is_only_show', $is_shipping, ".
				" '$_POST[goods_desc]', '" . gmtime() . "', '". gmtime() ."', '$goods_type', '$rank_integral', '$suppliers_id', '$keywords01', '$keywords02', '$goods_leixing', '$optional_num', '$goods_zizhi','$imgcanfg',$goods_background,'$buy_quick','$start_time','$end_time','$person_canbuynum','$buy_skip_goods_id','$is_show','$NewCustomer'.'$is_update','$sxname','$can_usertag')";
			}else{
				$sql = "INSERT INTO " . $ecs->table('pocket_goods') . " (goods_name,goods_name2, goods_name_style, goods_sn, style_sn, product_sn, " .
				"cat_id,tag_id, brand_id, shop_price,shop_pricecode, integral_price, integral_pricecode,integral_money_price, market_price, is_promote, promote_price, " .
				"promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, " .
				"seller_note, goods_weight,goods_weight_unit,pv, goods_number, warn_number, integral, give_integral, is_hd,is_hdbk,is_share,is_fenxiao,price_stepone,price_steptwo,limitations,is_best, is_new, is_hot, is_index, " .
				"is_on_sale, is_alone_sale, is_only_show, is_shipping, goods_desc, add_time, last_update, goods_type, rank_integral, suppliers_id, keywords01, keywords02, goods_leixing, optional_num, goods_zizhi,imgcanfg,buy_quick,start_time,end_time,person_canbuynum,buy_skip_goods_id,is_show,NewCustomer,is_update,sxname,can_usertag)" .
				"VALUES ('$_POST[goods_name]','$_POST[goods_name2]', '$goods_name_style', '$goods_sn', '$_POST[style_sn]', '$_POST[product_sn]', '$catgory_id', '$tag_id', " .
				"'$brand_id', '$shop_price', '$shop_pricecode', '$integral_price', '$integral_pricecode','$integral_money_price', '$market_price', '$is_promote','$promote_price', ".
				"'$promote_start_date', '$promote_end_date', '$goods_img', '$goods_thumb', '$original_img', ".
				"'$_POST[keywords]', '$_POST[goods_brief]', '$_POST[seller_note]', '$goods_weight', '$goods_weight_unit','$pv', '$goods_number',".
				" '$warn_number', '$_POST[integral]', '$give_integral', '$is_hd','$is_hdbk','$is_share','$is_fenxiao','$price_stepone','$price_steptwo','$limitations','$is_best', '$is_new', '$is_hot', '$is_index', '$is_on_sale', '$is_alone_sale', 'is_only_show', $is_shipping, ".
				" '$_POST[goods_desc]', '" . gmtime() . "', '". gmtime() ."', '$goods_type', '$rank_integral', '$suppliers_id', '$keywords01', '$keywords02', '$goods_leixing', '$optional_num', '$goods_zizhi','$imgcanfg','$buy_quick','$start_time','$end_time','$person_canbuynum','$buy_skip_goods_id','$is_show','$NewCustomer','$is_update','$sxname','$can_usertag')";
			}

		}
		else
		{
			if($goods_background){
				$sql = "INSERT INTO " . $ecs->table('pocket_goods') . " (goods_name,goods_name2, goods_name_style, goods_sn, style_sn, product_sn, " .
				"cat_id,tag_id, brand_id,shop_price,shop_pricecode, integral_price,integral_pricecode,integral_money_price, market_price, is_promote, promote_price, " .
				"promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, " .
				"seller_note, goods_weight,goods_weight_unit,pv, goods_number, warn_number, integral, give_integral, is_hd,is_hdbk,is_share,is_fenxiao,price_stepone,price_steptwo,limitations,is_best, is_new, is_hot, is_index, is_real, " .
				"is_on_sale, is_alone_sale, is_only_show, is_shipping, goods_desc, add_time, last_update, goods_type, extension_code, rank_integral, keywords01, keywords02, goods_leixing, optional_num, goods_zizhi,imgcanfg,goods_background,buy_quick,start_time,end_time,person_canbuynum,buy_skip_goods_id,is_show,NewCustomer,is_update,sxname,can_usertag)" .
				"VALUES ('$_POST[goods_name]','$_POST[goods_name2]', '$goods_name_style', '$goods_sn', '$_POST[style_sn]', '$_POST[product_sn]', '$catgory_id','$tag_id', " .
				"'$brand_id',  '$shop_price', '$shop_pricecode', '$integral_price', '$integral_pricecode','$integral_money_price', '$market_price', '$is_promote','$promote_price', ".
				"'$promote_start_date', '$promote_end_date', '$goods_img', '$goods_thumb', '$original_img', ".
				"'$_POST[keywords]', '$_POST[goods_brief]', '$_POST[seller_note]', '$goods_weight','$goods_weight_unit','$pv', '$goods_number',".
				" '$warn_number', '$_POST[integral]', '$give_integral','$is_hd','$is_hdbk','$is_share','$is_fenxiao','$is_pocket','$price_stepone','$price_steptwo','$limitations','$is_best', '$is_new', '$is_hot', '$is_index', 0, '$is_on_sale', '$is_alone_sale', '$is_only_show', $is_shipping, ".
				" '$_POST[goods_desc]', '" . gmtime() . "', '". gmtime() ."', '$goods_type', '$code', '$rank_integral', '$keywords01', '$keywords02', '$goods_leixing', '$optional_num', '$goods_zizhi','imgcanfg',$goods_background,'$buy_quick','$start_time','$end_time','$person_canbuynum','$buy_skip_goods_id','$is_show','$NewCustomer','$is_update','$sxname','$can_usertag')";
			}else{
				$sql = "INSERT INTO " . $ecs->table('pocket_goods') . " (goods_name, goods_name2, goods_name_style, goods_sn, style_sn, product_sn, " .
				"cat_id,tag_id, brand_id, shop_price,shop_pricecode, integral_price,integral_pricecode,integral_money_price, market_price, is_promote, promote_price, " .
				"promote_start_date, promote_end_date, goods_img, goods_thumb, original_img, keywords, goods_brief, " .
				"seller_note, goods_weight,goods_weight_unit,pv, goods_number, warn_number, integral, give_integral, is_hd,is_hdbk,is_share,is_fenxiao,price_stepone,price_steptwo,limitations,is_best, is_new, is_hot, is_index, is_real, " .
				"is_on_sale, is_alone_sale, is_only_show, is_shipping, goods_desc, add_time, last_update, goods_type, extension_code, rank_integral, keywords01, keywords02, goods_leixing, optional_num, goods_zizhi,imgcanfg,buy_quick,start_time,end_time,person_canbuynum,buy_skip_goods_id,is_show,NewCustomer,is_update,sxname,can_usertag)" .
				"VALUES ('$_POST[goods_name]','$_POST[goods_name2]', '$goods_name_style', '$goods_sn', '$_POST[style_sn]', '$_POST[product_sn]', '$catgory_id', '$tag_id', " .
				"'$brand_id', '$shop_price', '$shop_pricecode', '$integral_price', '$integral_pricecode','$integral_money_price', '$market_price', '$is_promote','$promote_price', ".
				"'$promote_start_date', '$promote_end_date', '$goods_img', '$goods_thumb', '$original_img', ".
				"'$_POST[keywords]', '$_POST[goods_brief]', '$_POST[seller_note]', '$goods_weight','$goods_weight_unit','$pv', '$goods_number',".
				" '$warn_number', '$_POST[integral]', '$give_integral','$is_hd','$is_hdbk','$is_share','$is_fenxiao','$is_pocket','$price_stepone','$price_steptwo','$limitations','$is_best', '$is_new', '$is_hot', '$is_index', 0, '$is_on_sale', '$is_alone_sale', '$is_only_show', $is_shipping, ".
				" '$_POST[goods_desc]', '" . gmtime() . "', '". gmtime() ."', '$goods_type', '$code', '$rank_integral', '$keywords01', '$keywords02', '$goods_leixing', '$optional_num', '$goods_zizhi','imgcanfg','$buy_quick','$start_time','$end_time','$person_canbuynum','$buy_skip_goods_id','$is_show','$NewCustomer','$is_update','$sxname','$can_usertag')";
			}

		}
	}
	else
	{
		/* 如果有上传图片，删除原来的商品图 */
		$sql = "SELECT goods_thumb, goods_img, original_img " .
		" FROM " . $ecs->table('pocket_goods') .
		" WHERE goods_id = '$_REQUEST[goods_id]'";
		$row = $db->getRow($sql);
		if ($proc_thumb && $goods_img && $row['goods_img'] && !goods_parse_url($row['goods_img']))
		{
			@unlink(ROOT_PATH . $row['goods_img']);
			@unlink(ROOT_PATH . $row['original_img']);
		}

		if ($proc_thumb && $goods_thumb && $row['goods_thumb'] && !goods_parse_url($row['goods_thumb']))
		{
			@unlink(ROOT_PATH . $row['goods_thumb']);
		}
		$sql = "UPDATE " . $ecs->table('pocket_goods') . " SET " .
		"goods_name = '$_POST[goods_name]', " .
		"goods_name2 = '$_POST[goods_name2]', " .
		"goods_name_style = '$goods_name_style', " .
		"goods_sn = '$goods_sn', " .
		"style_sn = '$_POST[style_sn]', " .
		"product_sn = '$_POST[product_sn]', " .
		"cat_id = '$catgory_id', " .
		"pv = '$pv', " .
		"tag_id = '$tag_id', " .
		"brand_id = '$brand_id', " .
		"shop_price = '$shop_price', " .
		"shop_pricecode = '$shop_pricecode', " .
		"integral_price = '$integral_price', " .
		"integral_pricecode = '$integral_pricecode', " .
		"integral_money_price = '$integral_money_price', " .
		"market_price = '$market_price', " .
		"is_promote = '$is_promote', " .
		"promote_price = '$promote_price', " .
		"promote_start_date = '$promote_start_date', " .
		"suppliers_id = '$suppliers_id', " .
		"promote_end_date = '$promote_end_date', " .
		"sxname = '$sxname', " .
		"time = '".gmtime()."', ".
		"can_usertag = '$can_usertag', " ;

		/* 如果有上传图片，需要更新数据库 */
		if ($goods_img)
		{
			$sql .= "goods_img = '$goods_img', original_img = '$original_img', ";
		}
		if ($goods_thumb)
		{
			$sql .= "goods_thumb = '$goods_thumb', ";
		}
		if($goods_zizhi)
		{
			$sql .= "goods_zizhi = '$goods_zizhi', ";
		}
		if ($code != '')
		{
			$sql .= "is_real=0, extension_code='$code', ";
		}
		if($goods_background)
		{
			$sql .= "goods_background = '$goods_background', ";
		}
		if($goods_points_img)
		{
			$sql .= "goods_points_img = '$goods_points_img', ";
		}
		$sql .= "keywords = '$_POST[keywords]', " .
		"goods_brief = '$_POST[goods_brief]', " .
		"seller_note = '$_POST[seller_note]', " .
		"goods_weight = '$goods_weight'," .
		"goods_weight_unit = '$goods_weight_unit'," .
		"pv = '$pv'," .
		"goods_number = '$goods_number', " .
		"warn_number = '$warn_number', " .
		"integral = '$_POST[integral]', " .
		"give_integral = '$give_integral', " .
		"rank_integral = '$rank_integral', " .
		"is_hd = '$is_hd', " .
		"is_hdbk = '$is_hdbk', " .
		"is_best = '$is_best', " .
		"is_new = '$is_new', " .
		"is_hot = '$is_hot', " .
		"is_share = '$is_share', " .
		"is_fenxiao = '$is_fenxiao', " .
		"is_pocket = '$is_pocket', " .
		"is_index = '$is_index', " .
		"is_on_sale = '$is_on_sale', " .
		"is_alone_sale = '$is_alone_sale', " .

		"price_stepone = '$price_stepone', " .
		"price_steptwo = '$price_steptwo', " .
		"limitations = '$limitations', " .
		"is_only_show = '$is_only_show', " .
		"is_shipping = '$is_shipping', " .
		"goods_desc = '$_POST[goods_desc]', " .
		"last_update = '". gmtime() ."', ".
		"goods_type = '$goods_type', " .
		"keywords01 = '$keywords01', " .
		"keywords02 = '$keywords02', " .
		"goods_leixing  = '$goods_leixing', " .
		"optional_num = '$optional_num', " .
		"buy_quick = '$buy_quick', " .
		"start_time = '$start_time', " .
		"end_time = '$end_time', " .
		"person_canbuynum = '$person_canbuynum', " .
		"buy_skip_goods_id = '$buy_skip_goods_id', " .
		"is_show = '$is_show', " .
		"imgcanfg = '$imgcanfg', " .
		"NewCustomer = '$NewCustomer', " .
		"is_update = '$is_update' " .
		"WHERE goods_id = '$_REQUEST[goods_id]' LIMIT 1";
	}
	$db->query($sql); 
	if($_POST['product_sn']){
		update_stock($goods_number,$_POST['product_sn'],0);
	}
	/* 商品编号 */
	$goods_id = $is_insert ? $db->insert_id() : $_REQUEST['goods_id'];

//套组单品价格平摊
     $sql = "SELECT goods_leixing,shop_price " .
			" FROM " . $ecs->table('pocket_goods') .
			" WHERE goods_id = '$goods_id'";
	$tzgoods_info = $db->getRow($sql);
	$goods_leixing=$tzgoods_info['goods_leixing'];
     if($goods_leixing==2){
		  $sql = "SELECT sum(a.MaxQuantity*b.market_price) " .
				" FROM " . $ecs->table('group_goods') ." a, ". $ecs->table('pocket_goods') . " b ".
				" WHERE a.goods_id = b.goods_id and a.parent_id = '$goods_id'";  
		  $total = $db->getOne($sql);

		  $sql = "SELECT * " .
				" FROM " . $ecs->table('group_goods') .
				" WHERE parent_id = '$goods_id'";  
		   $tzgoods = $GLOBALS['db']->getAll($sql);
			foreach($tzgoods as $key2=>$row_goods)
			{
                 $tdgid=$row_goods['goods_id'];
				 $tdgnum=$row_goods['MaxQuantity'];
				 $sql = "SELECT market_price " .
			" FROM " . $ecs->table('pocket_goods') .
			" WHERE goods_id = '$tdgid'";
				$tdgoods_price = $db->getOne($sql);
				$tzgoods_price=$tzgoods_info['shop_price'];
				//根据公式算出平摊价格 （单价*数量/总价）  * 套组价 / 数量 
				/*echo '单价:'.$tdgoods_price.' 数量'.$tdgnum.' 单品总价'.$tdgoods_price*$tdgnum.' 套组原总价'.$total.'单品总价格/套组原总价'.($tdgoods_price*$tdgnum)/$total.'套组价格'.$tzgoods_price.'比率*套组价格'.(($tdgoods_price*$tdgnum)/$total)*$tzgoods_price.'<br/>';*/
                $newtdprice=((($tdgoods_price*$tdgnum)/$total)*$tzgoods_price)/$tdgnum;
				$sql = "UPDATE " . $ecs->table('group_goods') . " SET " .
		"goods_price = '$newtdprice' " ."WHERE goods_id = '$tdgid' and parent_id='$goods_id' LIMIT 1";
					$db->query($sql);
                
			}


	 }









	/**图片列表新***/
	$pic_list=$_POST['pic_list'];
	if($pic_list)
	{
		$sql = "DELETE FROM " . $GLOBALS['ecs']->table('goods_gallery') ." WHERE goods_id = '".$goods_id."'";
		$GLOBALS['db']->query($sql);
		$pic_list=explode(";",$pic_list);
		foreach($pic_list as $val)
		{
			if($val)
			{
				//var_dump($val);
				//$val=substr($val,strrpos($val,"upload"));
				//var_dump($val);
				//$val=substr($val,strrpos($val,"images"));

				$thumb_url = $image->make_thumb($val, 220,  220,"thumb_img");
				$img_url = $image->make_thumb($val, 640,  640,"goods_img");
				$data = array(
						'goods_id'         =>$goods_id,
						'img_url'          =>$img_url,
						'thumb_url'        =>$thumb_url,
						'img_original'     =>$val,
				);
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods_gallery'),$data);
			}
		}
	}





	/* 记录日志 */
	if ($is_insert)
	{
		admin_log($_POST['goods_name'], 'add', 'goods');
	}
	else
	{
		admin_log($_POST['goods_name'], 'edit', 'goods');
	}

	/* 处理属性 */
	if ((isset($_POST['attr_id_list']) && isset($_POST['attr_value_list'])) || (empty($_POST['attr_id_list']) && empty($_POST['attr_value_list'])))
	{
		// 取得原有的属性值
		$goods_attr_list = array();

		$keywords_arr = explode(" ", $_POST['keywords']);

		$keywords_arr = array_flip($keywords_arr);
		if (isset($keywords_arr['']))
		{
			unset($keywords_arr['']);
		}

		$sql = "SELECT attr_id, attr_index FROM " . $ecs->table('attribute') . " WHERE cat_id = '$goods_type'";

		$attr_res = $db->query($sql);

		$attr_list = array();

		while ($row = $db->fetchRow($attr_res))
		{
			$attr_list[$row['attr_id']] = $row['attr_index'];
		}

		$sql = "SELECT g.*, a.attr_type
                FROM " . $ecs->table('goods_attr') . " AS g
                    LEFT JOIN " . $ecs->table('attribute') . " AS a
                        ON a.attr_id = g.attr_id
                WHERE g.goods_id = '$goods_id'";

		$res = $db->query($sql);

		while ($row = $db->fetchRow($res))
		{
			$goods_attr_list[$row['attr_id']][$row['attr_value']] = array('sign' => 'delete', 'goods_attr_id' => $row['goods_attr_id']);
		}
		// 循环现有的，根据原有的做相应处理
		if(isset($_POST['attr_id_list']))
		{
			foreach ($_POST['attr_id_list'] AS $key => $attr_id)
			{
				$attr_value = $_POST['attr_value_list'][$key];
				$attr_price = $_POST['attr_price_list'][$key];
				if (!empty($attr_value))
				{
					if (isset($goods_attr_list[$attr_id][$attr_value]))
					{
						// 如果原来有，标记为更新
						$goods_attr_list[$attr_id][$attr_value]['sign'] = 'update';
						$goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
					}
					else
					{
						// 如果原来没有，标记为新增
						$goods_attr_list[$attr_id][$attr_value]['sign'] = 'insert';
						$goods_attr_list[$attr_id][$attr_value]['attr_price'] = $attr_price;
					}
					$val_arr = explode(' ', $attr_value);
					foreach ($val_arr AS $k => $v)
					{
						if (!isset($keywords_arr[$v]) && $attr_list[$attr_id] == "1")
						{
							$keywords_arr[$v] = $v;
						}
					}
				}
			}
		}
		$keywords = join(' ', array_flip($keywords_arr));

		$sql = "UPDATE " .$ecs->table('pocket_goods'). " SET keywords = '$keywords' WHERE goods_id = '$goods_id' LIMIT 1";

		$db->query($sql);

		/* 插入、更新、删除数据 */
		foreach ($goods_attr_list as $attr_id => $attr_value_list)
		{
			foreach ($attr_value_list as $attr_value => $info)
			{
				if ($info['sign'] == 'insert')
				{
					$sql = "INSERT INTO " .$ecs->table('goods_attr'). " (attr_id, goods_id, attr_value, attr_price)".
					"VALUES ('$attr_id', '$goods_id', '$attr_value', '$info[attr_price]')";
				}
				elseif ($info['sign'] == 'update')
				{
					$sql = "UPDATE " .$ecs->table('goods_attr'). " SET attr_price = '$info[attr_price]' WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
				}
				else
				{
					$sql = "DELETE FROM " .$ecs->table('goods_attr'). " WHERE goods_attr_id = '$info[goods_attr_id]' LIMIT 1";
				}
				$db->query($sql);
			}
		}
	}
	/* 处理会员价格 */
	if (isset($_POST['user_rank']) && isset($_POST['user_price']))
	{
		handle_member_price($goods_id, $_POST['user_rank'], $_POST['user_price']);
	}

	/* 处理优惠价格 */
	if (isset($_POST['volume_number']) && isset($_POST['volume_price']))
	{
		$temp_num = array_count_values($_POST['volume_number']);
		foreach($temp_num as $v)
		{
			if ($v > 1)
			{
				sys_msg($_LANG['volume_number_continuous'], 1, array(), false);
				break;
			}
		}
		handle_volume_price($goods_id, $_POST['volume_number'], $_POST['volume_price']);
	}
	/* 处理扩展分类 */
	if (isset($_POST['other_cat']))
	{
		handle_other_cat($goods_id, array_unique($_POST['other_cat']));
	}

	if ($is_insert)
	{
		/* 处理关联商品 */
		handle_link_goods($goods_id);

		/* 处理组合商品 */
		handle_group_goods($goods_id);

		/* 处理关联文章 */
		handle_goods_article($goods_id);
	}
	/* 重新格式化图片名称 */
	$original_img = reformat_image_name('goods', $goods_id, $original_img, 'source');
	$goods_img = reformat_image_name('goods', $goods_id, $goods_img, 'goods');
	$goods_thumb = reformat_image_name('goods_thumb', $goods_id, $goods_thumb, 'thumb');
	if ($goods_img !== false)
	{
		$db->query("UPDATE " . $ecs->table('pocket_goods') . " SET goods_img = '$goods_img' WHERE goods_id='$goods_id'");
	}
	if ($original_img !== false)
	{
		$db->query("UPDATE " . $ecs->table('pocket_goods') . " SET original_img = '$original_img' WHERE goods_id='$goods_id'");
	}

	if ($goods_thumb !== false)
	{
		$db->query("UPDATE " . $ecs->table('pocket_goods') . " SET goods_thumb = '$goods_thumb' WHERE goods_id='$goods_id'");
	}


	//abiao 130802
	if (($_CFG['image_width'] != 0 || $_CFG['image_height'] != 0) && (isset($gallery_img) && $gallery_img != ''))
	{
		$gallery_img = $image->make_thumb('../../../'. $gallery_img , $GLOBALS['_CFG']['image_width'],  $GLOBALS['_CFG']['image_height']);
		if ($gallery_img === false)
		{
			sys_msg($image->error_msg(), 1, array(), false);
		}
	}

	/* 如果有图片，把商品图片加入图片相册 */
	if (isset($img))
	{
		/* 重新格式化图片名称 */
		if (empty($is_url_goods_img))
		{
			$img = reformat_image_name('gallery', $goods_id, $img, 'source');
			if($gallery_img){
				$gallery_img = reformat_image_name('gallery', $goods_id, $gallery_img, 'goods');
			}
		}
		else
		{
			$img = $url_goods_img;
			$gallery_img = $url_goods_img;
		}

		if($gallery_img){
			$gallery_thumb = reformat_image_name('gallery_thumb', $goods_id, $gallery_thumb, 'thumb');
			$sql = "INSERT INTO " . $ecs->table('goods_gallery') . " (goods_id, img_url, img_desc, thumb_url, img_original,imgecanfg) " .
			"VALUES ('$goods_id', '$gallery_img', '', '$gallery_thumb', '$img','$imgcanfg')";
			$db->query($sql);
		}
	}
	/* 处理相册图片 */
	handle_gallery_image($goods_id, $_FILES['img_url'], $_POST['img_desc'], $_POST['img_file']);
	/* 编辑时处理相册图片描述 */
	if (!$is_insert && isset($_POST['old_img_desc']))
	{
		foreach ($_POST['old_img_desc'] AS $img_id => $img_desc)
		{
			$sql = "UPDATE " . $ecs->table('goods_gallery') . " SET img_desc = '$img_desc' WHERE img_id = '$img_id' LIMIT 1";
			$db->query($sql);
		}
	}

	/* 不保留商品原图的时候删除原图 */
	if ($proc_thumb && !$_CFG['retain_original_img'] && !empty($original_img))
	{
		$db->query("UPDATE " . $ecs->table('pocket_goods') . " SET original_img='' WHERE `goods_id`='{$goods_id}'");
		$db->query("UPDATE " . $ecs->table('goods_gallery') . " SET img_original='' WHERE `goods_id`='{$goods_id}'");
		@unlink('../' . $original_img);
		@unlink('../' . $img);
	}

	/* 记录上一次选择的分类和品牌 */
	setcookie('ECSCP[last_choose]', $catgory_id . '|' . $brand_id, gmtime() + 86400);
	/* 清空缓存 */
	clear_cache_files();

	/* 提示页面 */
	$link = array();
	if (check_goods_specifications_exist($goods_id))
	{
		$link[0] = array('href' => 'goods.php?act=product_list&goods_id=' . $goods_id, 'text' => $_LANG['product']);
	}
	if ($code == 'virtual_card')
	{
		$link[1] = array('href' => 'virtual_card.php?act=replenish&goods_id=' . $goods_id, 'text' => $_LANG['add_replenish']);
	}
	if ($is_insert)
	{
		$link[2] = add_link($code);
	}
	$link[3] = list_link($is_insert, $code);


	//$key_array = array_keys($link);
	for($i=0;$i<count($link);$i++)
	{
		$key_array[]=$i;
	}
	krsort($link);
	$link = array_combine($key_array, $link);
	//sys_msg($is_insert ? $_LANG['add_goods_ok'] : $_LANG['edit_goods_ok'], 0, $link);


	//套组单品价格平摊
     $sql = "SELECT goods_leixing,shop_price " .
			" FROM " . $ecs->table('pocket_goods') .
			" WHERE goods_id = '$goods_id'";
	$tzgoods_info = $db->getRow($sql);
	$goods_leixing=$tzgoods_info['goods_leixing'];
     if($goods_leixing==2){
		  $sql = "SELECT sum(a.MaxQuantity*b.market_price) " .
				" FROM " . $ecs->table('group_goods') ." a, ". $ecs->table('pocket_goods') . " b ".
				" WHERE a.goods_id = b.goods_id and a.parent_id = '$goods_id'";  
		  $total = $db->getOne($sql);

		  $sql = "SELECT * " .
				" FROM " . $ecs->table('group_goods') .
				" WHERE parent_id = '$goods_id'";  
		   $tzgoods = $GLOBALS['db']->getAll($sql);
			foreach($tzgoods as $key2=>$row_goods)
			{
                 $tdgid=$row_goods['goods_id'];
				 $tdgnum=$row_goods['MaxQuantity'];
				 $sql = "SELECT market_price " .
			" FROM " . $ecs->table('pocket_goods') .
			" WHERE goods_id = '$tdgid'";
				$tdgoods_price = $db->getOne($sql);
				$tzgoods_price=$tzgoods_info['shop_price'];
				//根据公式算出平摊价格 （单价*数量/总价）  * 套组价 / 数量 
				/*echo '单价:'.$tdgoods_price.' 数量'.$tdgnum.' 单品总价'.$tdgoods_price*$tdgnum.' 套组原总价'.$total.'单品总价格/套组原总价'.($tdgoods_price*$tdgnum)/$total.'套组价格'.$tzgoods_price.'比率*套组价格'.(($tdgoods_price*$tdgnum)/$total)*$tzgoods_price.'<br/>';*/
                $newtdprice=((($tdgoods_price*$tdgnum)/$total)*$tzgoods_price)/$tdgnum;
				$sql = "UPDATE " . $ecs->table('group_goods') . " SET " .
		"goods_price = '$newtdprice' " ."WHERE goods_id = '$tdgid' and parent_id='$goods_id' LIMIT 1";
					$db->query($sql);
                
			}
	 }



	//更新首页缓存
	define('WEB_URL','http://www.wm18.com/');
	require(dirname(dirname(__FILE__)) . '/includes_new/lib_base.php');
	require(dirname(dirname(__FILE__)) . '/cache_class.php');
	$cache_class->clear_cache('index_cache.php');
	ecs_header("Location: goods.php?act=list\n");
	exit;
}

/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'batch')
{
	$code = empty($_REQUEST['extension_code'])? '' : trim($_REQUEST['extension_code']);

	/* 取得要操作的商品编号 */
	$goods_id = !empty($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0;

	if (isset($_POST['type']))
	{
		/* 放入回收站 */
		if ($_POST['type'] == 'trash')
		{
			/* 检查权限 */
			admin_priv('remove_back');

			update_goods($goods_id, 'is_delete', '1');

			/* 记录日志 */
			admin_log('', 'batch_trash', 'goods');
		}
		/* 上架 */
		elseif ($_POST['type'] == 'on_sale')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_on_sale', '1');
		}

		/* 下架 */
		elseif ($_POST['type'] == 'not_on_sale')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_on_sale', '0');
		}

		/* 设为精品 */
		elseif ($_POST['type'] == 'best')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_best', '1');
		}

		/* 取消精品 */
		elseif ($_POST['type'] == 'not_best')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_best', '0');
		}

		/* 设为新品 */
		elseif ($_POST['type'] == 'new')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_new', '1');
		}

		/* 取消新品 */
		elseif ($_POST['type'] == 'not_new')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_new', '0');
		}

		/* 设为热销 */
		elseif ($_POST['type'] == 'hot')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_hot', '1');
		}

		/* 取消热销 */
		elseif ($_POST['type'] == 'not_hot')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'is_hot', '0');
		}

		/* 转移到分类 */
		elseif ($_POST['type'] == 'move_to')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'cat_id', $_POST['target_cat']);
		}

		/* 转移到供货商 */
		elseif ($_POST['type'] == 'suppliers_move_to')
		{
			/* 检查权限 */
			admin_priv('goods_manage');
			update_goods($goods_id, 'suppliers_id', $_POST['suppliers_id']);
		}

		/* 还原 */
		elseif ($_POST['type'] == 'restore')
		{
			/* 检查权限 */
			admin_priv('remove_back');

			update_goods($goods_id, 'is_delete', '0');

			/* 记录日志 */
			admin_log('', 'batch_restore', 'goods');
		}
		/* 删除 */
		elseif ($_POST['type'] == 'drop')
		{
			/* 检查权限 */
			admin_priv('remove_back');

			delete_goods($goods_id);

			/* 记录日志 */
			admin_log('', 'batch_remove', 'goods');
		}
	}

	/* 清除缓存 */
	clear_cache_files();

	if ($_POST['type'] == 'drop' || $_POST['type'] == 'restore')
	{
		$link[] = array('href' => 'goods.php?act=trash', 'text' => $_LANG['11_goods_trash']);
	}
	else
	{
		$link[] = list_link(true, $code);
	}
	sys_msg($_LANG['batch_handle_ok'], 0, $link);
}

/*------------------------------------------------------ */
//-- 显示图片
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'show_image')
{

	if (isset($GLOBALS['shop_id']) && $GLOBALS['shop_id'] > 0)
	{
		$img_url = $_GET['img_url'];
	}
	else
	{
		if (strpos($_GET['img_url'], 'http://') === 0)
		{
			$img_url = $_GET['img_url'];
		}
		else
		{
			$img_url = '../' . $_GET['img_url'];
		}
	}
	$smarty->assign('img_url', $img_url);
	$smarty->display('goods_show_image.htm');
}

/*------------------------------------------------------ */
//-- 修改商品名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_goods_name')
{
	//check_authz_json('goods_manage');

	$goods_id   = intval($_POST['id']);
	$goods_name = json_str_iconv(trim($_POST['val']));

	if ($exc->edit("goods_name = '$goods_name', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result(stripslashes($goods_name));
	}
}

/*------------------------------------------------------ */
//-- 修改商品货号
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_goods_sn')
{
	//check_authz_json('goods_manage');

	$goods_id = intval($_POST['id']);
	$goods_sn = json_str_iconv(trim($_POST['val']));

	/* 检查是否重复 */
	if (!$exc->is_only('goods_sn', $goods_sn, $goods_id))
	{
		make_json_error($_LANG['goods_sn_exists']);
	}
	$sql="SELECT goods_id FROM ". $ecs->table('products')."WHERE product_sn='$goods_sn'";
	if($db->getOne($sql))
	{
		make_json_error($_LANG['goods_sn_exists']);
	}
	if ($exc->edit("goods_sn = '$goods_sn', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result(stripslashes($goods_sn));
	}
}

elseif ($_REQUEST['act'] == 'check_goods_sn')
{
	//check_authz_json('goods_manage');

	$goods_id = intval($_REQUEST['goods_id']);
	$goods_sn = htmlspecialchars(json_str_iconv(trim($_REQUEST['goods_sn'])));
	/* 检查是否重复 */
	if (!$exc->is_only('goods_sn', $goods_sn, $goods_id))
	{
		$array=array("error"=>1,"info"=>"商品货号已存在");
		echo json_encode($array);die();
	}
	if(!empty($goods_sn))
	{
		$sql="SELECT goods_id FROM ". $ecs->table('products')."WHERE product_sn='$goods_sn'";
		if($db->getOne($sql))
		{
			$array=array("error"=>1,"info"=>"商品货号已存在");
			echo json_encode($array);die();
		}
	}
	$array=array("error"=>0,"info"=>"");
	echo json_encode($array);die();
}
elseif ($_REQUEST['act'] == 'check_products_goods_sn')
{
	//check_authz_json('goods_manage');

	$goods_id = intval($_REQUEST['goods_id']);
	$goods_sn = json_str_iconv(trim($_REQUEST['goods_sn']));
	$products_sn=explode('||',$goods_sn);
	if(!is_array($products_sn))
	{
		make_json_result('');
	}
	else
	{
		foreach ($products_sn as $val)
		{
			if(empty($val))
			{
				continue;
			}
			if(is_array($int_arry))
			{
				if(in_array($val,$int_arry))
				{
					make_json_error($val.$_LANG['goods_sn_exists']);
				}
			}
			$int_arry[]=$val;
			if (!$exc->is_only('goods_sn', $val, '0'))
			{
				make_json_error($val.$_LANG['goods_sn_exists']);
			}
			$sql="SELECT goods_id FROM ". $ecs->table('products')."WHERE product_sn='$val'";
			if($db->getOne($sql))
			{
				make_json_error($val.$_LANG['goods_sn_exists']);
			}
		}
	}
	/* 检查是否重复 */
	make_json_result('');
}

/*------------------------------------------------------ */
//-- 修改商品价格
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_goods_price')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$goods_price    = floatval($_POST['val']);
	$price_rate     = floatval($_CFG['market_price_rate'] * $goods_price);

	if ($goods_price < 0 || $goods_price == 0 && $_POST['val'] != "$goods_price")
	{
		make_json_error($_LANG['shop_price_invalid']);
	}
	else
	{
		if ($exc->edit("shop_price = '$goods_price', market_price = '$price_rate', last_update=" .gmtime(), $goods_id))
		{
			clear_cache_files();
			make_json_result(number_format($goods_price, 2, '.', ''));
		}
	}
}

/*------------------------------------------------------ */
//-- 修改商品库存数量
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_goods_number')
{
	//check_authz_json('goods_manage');

	$goods_id   = intval($_POST['id']);
	$goods_num  = intval($_POST['val']);

	if($goods_num < 0 || $goods_num == 0 && $_POST['val'] != "$goods_num")
	{
		make_json_error($_LANG['goods_number_error']);
	}

	if(check_goods_product_exist($goods_id) == 1)
	{
		make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_goods_number']);
	}

	if ($exc->edit("goods_number = '$goods_num', last_update=" .gmtime(), $goods_id))
	{
		//修改款号相同的商品的库存
		$sql="SELECT product_sn FROM ". $ecs->table('pocket_goods')."WHERE goods_id='$goods_id'";
		$product_sn = $db->getOne($sql);
		if($product_sn){
			$sql2 = "UPDATE " .$ecs->table('pocket_goods') . " SET goods_number = '$goods_num' ".
			" WHERE product_sn = '$product_sn'";
			$db->query($sql2);
		}

		clear_cache_files();
		make_json_result($goods_num);
	}
}

/*------------------------------------------------------ */
//-- 修改上架状态
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_on_sale')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$on_sale        = intval($_POST['val']);

	if ($exc->edit("is_on_sale = '$on_sale', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result($on_sale);
	}
}

/*------------------------------------------------------ */
//-- 修改精品推荐状态
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_best')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$is_best        = intval($_POST['val']);

	if ($exc->edit("is_best = '$is_best', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result($is_best);
	}
}

/*------------------------------------------------------ */
//-- 修改新品推荐状态
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_new')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$is_new         = intval($_POST['val']);

	if ($exc->edit("is_new = '$is_new', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result($is_new);
	}
}

/*------------------------------------------------------ */
//-- 修改热销推荐状态
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'toggle_hot')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$is_hot         = intval($_POST['val']);

	if ($exc->edit("is_hot = '$is_hot', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result($is_hot);
	}
}

/*------------------------------------------------------ */
//-- 修改商品排序
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_sort_order')
{
	//check_authz_json('goods_manage');

	$goods_id       = intval($_POST['id']);
	$sort_order     = intval($_POST['val']);

	if ($exc->edit("sort_order = '$sort_order', last_update=" .gmtime(), $goods_id))
	{
		clear_cache_files();
		make_json_result($sort_order);
	}
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
	$is_delete = empty($_REQUEST['is_delete']) ? 0 : intval($_REQUEST['is_delete']);
	$code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
	$goods_list = goods_list($is_delete, ($code=='') ? 1 : 0);

	$handler_list = array();
	$handler_list['virtual_card'][] = array('url'=>'virtual_card.php?act=card', 'title'=>$_LANG['card'], 'img'=>'icon_send_bonus.gif');
	$handler_list['virtual_card'][] = array('url'=>'virtual_card.php?act=replenish', 'title'=>$_LANG['replenish'], 'img'=>'icon_add.gif');
	$handler_list['virtual_card'][] = array('url'=>'virtual_card.php?act=batch_card_add', 'title'=>$_LANG['batch_card_add'], 'img'=>'icon_output.gif');

	if (isset($handler_list[$code]))
	{
		$smarty->assign('add_handler',      $handler_list[$code]);
	}
	$smarty->assign('code',         $code);
	$smarty->assign('goods_list',   $goods_list['goods']);
	$smarty->assign('filter',       $goods_list['filter']);
	$smarty->assign('record_count', $goods_list['record_count']);
	$smarty->assign('page_count',   $goods_list['page_count']);
	$smarty->assign('list_type',    $is_delete ? 'trash' : 'goods');
	$smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);

	/* 排序标记 */
	$sort_flag  = sort_flag($goods_list['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	/* 获取商品类型存在规格的类型 */
	//$specifications = get_goods_type_specifications();
	//$smarty->assign('specifications', $specifications);

	$tpl = $is_delete ? 'goods_trash.htm' : 'goods_list.htm';

	make_json_result($smarty->fetch($tpl), '',
	array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
}

/*------------------------------------------------------ */
//-- 放入回收站
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
	$goods_id = intval($_REQUEST['id']);

	/* 检查权限 */
	//check_authz_json('remove_back');

	if ($exc->edit("is_delete = 1", $goods_id))
	{
		clear_cache_files();
		$goods_name = $exc->get_name($goods_id);

		admin_log(addslashes($goods_name), 'trash', 'goods'); // 记录日志

		$url = 'goods.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

		ecs_header("Location: $url\n");
		exit;
	}
}

/*------------------------------------------------------ */
//-- 还原回收站中的商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'restore_goods')
{
	$goods_id = intval($_REQUEST['id']);

	//check_authz_json('remove_back'); // 检查权限

	$exc->edit("is_delete = 0, add_time = '" . gmtime() . "'", $goods_id);
	clear_cache_files();

	$goods_name = $exc->get_name($goods_id);

	admin_log(addslashes($goods_name), 'restore', 'goods'); // 记录日志

	$url = 'goods.php?act=query&' . str_replace('act=restore_goods', '', $_SERVER['QUERY_STRING']);

	ecs_header("Location: $url\n");
	exit;
}

/*------------------------------------------------------ */
//-- 彻底删除商品
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_goods')
{
	// 检查权限
	//check_authz_json('remove_back');

	// 取得参数
	$goods_id = intval($_REQUEST['id']);
	if ($goods_id <= 0)
	{
		make_json_error('invalid params');
	}

	/* 取得商品信息 */
	$sql = "SELECT goods_id, goods_name, is_delete, is_real, goods_thumb, " .
	"goods_img, original_img " .
	"FROM " . $ecs->table('pocket_goods') .
	" WHERE goods_id = '$goods_id'";
	$goods = $db->getRow($sql);
	if (empty($goods))
	{
		make_json_error($_LANG['goods_not_exist']);
	}

	if ($goods['is_delete'] != 1)
	{
		make_json_error($_LANG['goods_not_in_recycle_bin']);
	}

	/* 删除商品图片和轮播图片 */
	if (!empty($goods['goods_thumb']))
	{
		@unlink('../' . $goods['goods_thumb']);
	}
	if (!empty($goods['goods_img']))
	{
		@unlink('../' . $goods['goods_img']);
	}
	if (!empty($goods['original_img']))
	{
		@unlink('../' . $goods['original_img']);
	}
	/* 删除商品 */
	$exc->drop($goods_id);

	/* 删除商品的货品记录 */
	$sql = "DELETE FROM " . $ecs->table('products') .
	" WHERE goods_id = '$goods_id'";
	$db->query($sql);

	/* 记录日志 */
	admin_log(addslashes($goods['goods_name']), 'remove', 'goods');

	/* 删除商品相册 */
	$sql = "SELECT img_url, thumb_url, img_original " .
	"FROM " . $ecs->table('goods_gallery') .
	" WHERE goods_id = '$goods_id'";
	$res = $db->query($sql);
	while ($row = $db->fetchRow($res))
	{
		if (!empty($row['img_url']))
		{
			@unlink('../' . $row['img_url']);
		}
		if (!empty($row['thumb_url']))
		{
			@unlink('../' . $row['thumb_url']);
		}
		if (!empty($row['img_original']))
		{
			@unlink('../' . $row['img_original']);
		}
	}

	$sql = "DELETE FROM " . $ecs->table('goods_gallery') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);

	/* 删除相关表记录 */
	$sql = "DELETE FROM " . $ecs->table('collect_goods') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('goods_article') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('goods_attr') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('goods_cat') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('member_price') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('group_goods') . " WHERE parent_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('group_goods') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('link_goods') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('link_goods') . " WHERE link_goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('tag') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('comment') . " WHERE comment_type = 0 AND id_value = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('collect_goods') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('booking_goods') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);
	$sql = "DELETE FROM " . $ecs->table('goods_activity') . " WHERE goods_id = '$goods_id'";
	$db->query($sql);

	/* 如果不是实体商品，删除相应虚拟商品记录 */
	if ($goods['is_real'] != 1)
	{
		$sql = "DELETE FROM " . $ecs->table('virtual_card') . " WHERE goods_id = '$goods_id'";
		if (!$db->query($sql, 'SILENT') && $db->errno() != 1146)
		{
			die($db->error());
		}
	}

	clear_cache_files();
	$url = 'goods.php?act=query&' . str_replace('act=drop_goods', '', $_SERVER['QUERY_STRING']);

	ecs_header("Location: $url\n");

	exit;
}

/*------------------------------------------------------ */
//-- 切换商品类型
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'get_attr')
{
	//check_authz_json('goods_manage');

	$goods_id   = empty($_GET['goods_id']) ? 0 : intval($_GET['goods_id']);
	$goods_type = empty($_GET['goods_type']) ? 0 : intval($_GET['goods_type']);

	$content    = build_attr_html($goods_type, $goods_id);

	make_json_result($content);
}

/*------------------------------------------------------ */
//-- 删除图片
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_image')
{
	//check_authz_json('goods_manage');

	$img_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);

	/* 删除图片文件 */
	$sql = "SELECT img_url, thumb_url, img_original " .
	" FROM " . $GLOBALS['ecs']->table('goods_gallery') .
	" WHERE img_id = '$img_id'";
	$row = $GLOBALS['db']->getRow($sql);

	if ($row['img_url'] != '' && is_file('../' . $row['img_url']))
	{
		@unlink('../' . $row['img_url']);
	}
	if ($row['thumb_url'] != '' && is_file('../' . $row['thumb_url']))
	{
		@unlink('../' . $row['thumb_url']);
	}
	if ($row['img_original'] != '' && is_file('../' . $row['img_original']))
	{
		@unlink('../' . $row['img_original']);
	}

	/* 删除数据 */
	$sql = "DELETE FROM " . $GLOBALS['ecs']->table('goods_gallery') . " WHERE img_id = '$img_id' LIMIT 1";
	$GLOBALS['db']->query($sql);

	clear_cache_files();
	make_json_result($img_id);
}
/*------------------------------------------------------ */
//-- 删除积分图片
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_points_image')
{
	//check_authz_json('goods_manage');

	$goods_id = empty($_REQUEST['img_id']) ? 0 : intval($_REQUEST['img_id']);

	/* 删除图片文件 */
	$sql = "SELECT goods_points_img " .
	" FROM " . $GLOBALS['ecs']->table('pocket_goods') .
	" WHERE goods_id =".$goods_id;
	$row = $GLOBALS['db']->getRow($sql);

	if ($row['goods_points_img'] != '' && is_file('../' . $row['goods_points_img']))
	{
		@unlink('../' . $row['goods_points_img']);
	}
	/* 删除数据 */
	$sql = "UPDATE " . $GLOBALS['ecs']->table('pocket_goods') . " SET goods_points_img = '' WHERE goods_id = '$goods_id' limit 1";
	$GLOBALS['db']->query($sql);

	clear_cache_files();
	make_json_result($goods_id);
}
elseif ($_REQUEST['act'] == 'get_group_goods_info')
{
	$parent_id=$_POST['parent_id'];
	$goods_id=$_POST['goods_id'];
	$sql = "SELECT * FROM " . $ecs->table('group_goods') . " WHERE goods_id = '$goods_id' and parent_id='$parent_id' limit 0,1";
	$goods = $db->getRow($sql);
	make_json_result($goods);
}
elseif ($_REQUEST['act'] == 'save_group_goods_info')
{
	$goods_price=$_POST['goods_price'];
	$MinQuantity=$_POST['MinQuantity'];
	$MaxQuantity=$_POST['MaxQuantity'];
	$goods_id=$_POST['goods_id'];
	$parent_id=$_POST['parent_id'];
	//查询商品存在不存在
	$sql = "select * from " . $GLOBALS['ecs']->table('group_goods') . " WHERE goods_id = '$goods_id' and parent_id='$parent_id' limit 1";
	$info=$db->getRow($sql);
	if(empty($info))
	{
		echo json_encode(array("affected_rows"=>0,"error_info"=>"商品不存在"));die();
	}
	$sql = "UPDATE " . $GLOBALS['ecs']->table('group_goods') . " SET goods_price='$goods_price',MinQuantity='$MinQuantity',MaxQuantity='$MaxQuantity' WHERE goods_id = '$goods_id' and parent_id='$parent_id' limit 1";
	$GLOBALS['db']->query($sql);
	$affected_rows=$GLOBALS['db']->affected_rows();
	$sql = "SELECT  CONCAT('(', gg.MinQuantity,',', gg.MaxQuantity, ') ',g.goods_name, ' -- [', g.goods_sn, '],-- [', gg.goods_price, ']') AS goods_name " .
	"FROM " . $GLOBALS['ecs']->table('group_goods') . " AS gg, " .$GLOBALS['ecs']->table('pocket_goods') . " AS g " .
	"WHERE gg.parent_id = '$parent_id' AND gg.goods_id = g.goods_id and g.goods_id='$goods_id'";
	$goods_info = $GLOBALS['db']->getOne($sql);
	echo json_encode(array("affected_rows"=>$affected_rows,"goods_id"=>$goods_id,"goods_info"=>$goods_info));die();
}
elseif ($_REQUEST['act'] == 'ajax_get_group_goods_info')
{
	$goods_id=$_POST['goods_id'];
	$parent_id=$_POST['parent_id'];
	$price=$_POST['price'];
	//查询商品存在不存在
	$sql = "select * from " . $GLOBALS['ecs']->table('group_goods') . " WHERE goods_id = '$goods_id' and parent_id='$parent_id' limit 1";
	$info=$db->getRow($sql);
	if(!empty($info))
	{
		echo json_encode(array("affected_rows"=>0,"error_info"=>"商品已存在"));die();
	}
	$sql = "INSERT INTO " . $ecs->table('group_goods') . " (parent_id, goods_id, goods_price, admin_id) " .
	"VALUES ('$parent_id', '$goods_id', '$price', '$_SESSION[admin_id]')";
	$db->query($sql, 'SILENT');
	$affected_rows=$GLOBALS['db']->affected_rows();
	$sql = "SELECT  CONCAT('(', gg.MinQuantity,',', gg.MaxQuantity, ') ',g.goods_name, ' -- [', g.goods_sn, '],-- [', gg.goods_price, ']') AS goods_name " .
	"FROM " . $GLOBALS['ecs']->table('group_goods') . " AS gg, " .$GLOBALS['ecs']->table('pocket_goods') . " AS g " .
	"WHERE gg.parent_id = '$parent_id' AND gg.goods_id = g.goods_id and g.goods_id='$goods_id'";
	$goods_info = $GLOBALS['db']->getOne($sql);
	echo json_encode(array("affected_rows"=>$affected_rows,"goods_id"=>$goods_id,"goods_info"=>$goods_info));die();
}
elseif ($_REQUEST['act'] == 'delete_group_goods_info')
{
	$goods_id=$_POST['goods_id'];
	$parent_id=$_POST['parent_id'];
	$sql = "DELETE FROM " .$ecs->table('group_goods') ." WHERE parent_id='$parent_id' AND goods_id='$goods_id'";
	$GLOBALS['db']->query($sql);
	$affected_rows=$GLOBALS['db']->affected_rows();
	echo json_encode(array("affected_rows"=>$affected_rows));die();
}

/*------------------------------------------------------ */
//-- 搜索商品，仅返回名称及ID
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'get_goods_list')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	$filters = $json->decode($_GET['JSON']);

	$arr = get_goods_list($filters);
	$opt = array();

	foreach ($arr AS $key => $val)
	{
		$opt[] = array('value' => $val['goods_id'],
		'text' => $val['goods_name'],
		'data' => $val['shop_price']);
	}
	make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 把商品加入关联
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add_link_goods')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$linked_array   = $json->decode($_GET['add_ids']);
	$linked_goods   = $json->decode($_GET['JSON']);
	$goods_id       = $linked_goods[0];
	$is_double      = $linked_goods[1] == true ? 0 : 1;

	foreach ($linked_array AS $val)
	{
		if ($is_double)
		{
			/* 双向关联 */
			$sql = "INSERT INTO " . $ecs->table('link_goods') . " (goods_id, link_goods_id, is_double, admin_id) " .
			"VALUES ('$val', '$goods_id', '$is_double', '$_SESSION[admin_id]')";
			$db->query($sql, 'SILENT');
		}

		$sql = "INSERT INTO " . $ecs->table('link_goods') . " (goods_id, link_goods_id, is_double, admin_id) " .
		"VALUES ('$goods_id', '$val', '$is_double', '$_SESSION[admin_id]')";
		$db->query($sql, 'SILENT');
	}

	$linked_goods   = get_linked_goods($goods_id);
	$options        = array();

	foreach ($linked_goods AS $val)
	{
		$options[] = array('value'  => $val['goods_id'],
		'text'      => $val['goods_name'],
		'data'      => '');
	}

	clear_cache_files();
	make_json_result($options);
}
elseif ($_REQUEST['act'] == 'ajax_goods_is_on_sale')
{
	ajax_goods_is_on_sale();
}

/*------------------------------------------------------ */
//-- 删除关联商品
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_link_goods')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$drop_goods     = $json->decode($_GET['drop_ids']);
	$drop_goods_ids = db_create_in($drop_goods);
	$linked_goods   = $json->decode($_GET['JSON']);
	$goods_id       = $linked_goods[0];
	$is_signle      = $linked_goods[1];

	if (!$is_signle)
	{
		$sql = "DELETE FROM " .$ecs->table('link_goods') .
		" WHERE link_goods_id = '$goods_id' AND goods_id " . $drop_goods_ids;
	}
	else
	{
		$sql = "UPDATE " .$ecs->table('link_goods') . " SET is_double = 0 ".
		" WHERE link_goods_id = '$goods_id' AND goods_id " . $drop_goods_ids;
	}
	if ($goods_id == 0)
	{
		$sql .= " AND admin_id = '$_SESSION[admin_id]'";
	}
	$db->query($sql);

	$sql = "DELETE FROM " .$ecs->table('link_goods') .
	" WHERE goods_id = '$goods_id' AND link_goods_id " . $drop_goods_ids;
	if ($goods_id == 0)
	{
		$sql .= " AND admin_id = '$_SESSION[admin_id]'";
	}
	$db->query($sql);

	$linked_goods = get_linked_goods($goods_id);
	$options      = array();

	foreach ($linked_goods AS $val)
	{
		$options[] = array(
		'value' => $val['goods_id'],
		'text'  => $val['goods_name'],
		'data'  => '');
	}

	clear_cache_files();
	make_json_result($options);
}

/*------------------------------------------------------ */
//-- 增加一个配件
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add_group_goods')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$fittings   = $json->decode($_GET['add_ids']);
	$arguments  = $json->decode($_GET['JSON']);
	$goods_id   = $arguments[0];
	$price      = $arguments[1];

	foreach ($fittings AS $val)
	{
		$sql = "INSERT INTO " . $ecs->table('group_goods') . " (parent_id, goods_id, goods_price, admin_id) " .
		"VALUES ('$goods_id', '$val', '$price', '$_SESSION[admin_id]')";
		$db->query($sql, 'SILENT');
	}

	$arr = get_group_goods($goods_id);
	$opt = array();

	foreach ($arr AS $val)
	{
		$opt[] = array('value'      => $val['goods_id'],
		'text'      => $val['goods_name'],
		'data'      => '');
	}

	clear_cache_files();
	make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 删除一个配件
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'drop_group_goods')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$fittings   = $json->decode($_GET['drop_ids']);
	$arguments  = $json->decode($_GET['JSON']);
	$goods_id   = $arguments[0];
	$price      = $arguments[1];

	$sql = "DELETE FROM " .$ecs->table('group_goods') .
	" WHERE parent_id='$goods_id' AND " .db_create_in($fittings, 'goods_id');
	if ($goods_id == 0)
	{
		$sql .= " AND admin_id = '$_SESSION[admin_id]'";
	}
	$db->query($sql);

	$arr = get_group_goods($goods_id);
	$opt = array();

	foreach ($arr AS $val)
	{
		$opt[] = array('value'      => $val['goods_id'],
		'text'      => $val['goods_name'],
		'data'      => '');
	}

	clear_cache_files();
	make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 搜索文章
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'get_article_list')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	$filters =(array) $json->decode(json_str_iconv($_GET['JSON']));

	$where = " WHERE cat_id > 0 ";
	if (!empty($filters['title']))
	{
		$keyword  = trim($filters['title']);
		$where   .=  " AND title LIKE '%" . mysql_like_quote($keyword) . "%' ";
	}

	$sql        = 'SELECT article_id, title FROM ' .$ecs->table('article'). $where.
	'ORDER BY article_id DESC LIMIT 50';
	$res        = $db->query($sql);
	$arr        = array();

	while ($row = $db->fetchRow($res))
	{
		$arr[]  = array('value' => $row['article_id'], 'text' => $row['title'], 'data'=>'');
	}

	make_json_result($arr);
}

/*------------------------------------------------------ */
//-- 添加关联文章
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add_goods_article')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$articles   = $json->decode($_GET['add_ids']);
	$arguments  = $json->decode($_GET['JSON']);
	$goods_id   = $arguments[0];

	foreach ($articles AS $val)
	{
		$sql = "INSERT INTO " . $ecs->table('goods_article') . " (goods_id, article_id, admin_id) " .
		"VALUES ('$goods_id', '$val', '$_SESSION[admin_id]')";
		$db->query($sql);
	}

	$arr = get_goods_articles($goods_id);
	$opt = array();

	foreach ($arr AS $val)
	{
		$opt[] = array('value'      => $val['article_id'],
		'text'      => $val['title'],
		'data'      => '');
	}

	clear_cache_files();
	make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 删除关联文章
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'drop_goods_article')
{
	include_once(ROOT_PATH . 'includes/cls_json.php');
	$json = new JSON;

	//check_authz_json('goods_manage');

	$articles   = $json->decode($_GET['drop_ids']);
	$arguments  = $json->decode($_GET['JSON']);
	$goods_id   = $arguments[0];

	$sql = "DELETE FROM " .$ecs->table('goods_article') . " WHERE " . db_create_in($articles, "article_id") . " AND goods_id = '$goods_id'";
	$db->query($sql);

	$arr = get_goods_articles($goods_id);
	$opt = array();

	foreach ($arr AS $val)
	{
		$opt[] = array('value'      => $val['article_id'],
		'text'      => $val['title'],
		'data'      => '');
	}

	clear_cache_files();
	make_json_result($opt);
}

/*------------------------------------------------------ */
//-- 货品列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'product_list')
{
	admin_priv('goods_manage');

	/* 是否存在商品id */
	if (empty($_GET['goods_id']))
	{
		$link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['cannot_found_goods']);
		sys_msg($_LANG['cannot_found_goods'], 1, $link);
	}
	else
	{
		$goods_id = intval($_GET['goods_id']);
	}

	/* 取出商品信息 */
	$sql = "SELECT goods_sn, goods_name, goods_type, shop_price FROM " . $ecs->table('pocket_goods') . " WHERE goods_id = '$goods_id'";
	$goods = $db->getRow($sql);
	if (empty($goods))
	{
		$link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']);
		sys_msg($_LANG['cannot_found_goods'], 1, $link);
	}
	$smarty->assign('sn', sprintf($_LANG['good_goods_sn'], $goods['goods_sn']));
	$smarty->assign('price', sprintf($_LANG['good_shop_price'], $goods['shop_price']));
	$smarty->assign('goods_name', sprintf($_LANG['products_title'], $goods['goods_name']));
	$smarty->assign('goods_sn', sprintf($_LANG['products_title_2'], $goods['goods_sn']));


	/* 获取商品规格列表 */
	$attribute = get_goods_specifications_list($goods_id);
	if (empty($attribute))
	{
		$link[] = array('href' => 'goods.php?act=edit&goods_id=' . $goods_id, 'text' => $_LANG['edit_goods']);
		sys_msg($_LANG['not_exist_goods_attr'], 1, $link);
	}
	foreach ($attribute as $attribute_value)
	{
		//转换成数组
		$_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
		$_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
		$_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
	}
	$attribute_count = count($_attribute);

	$smarty->assign('attribute_count',          $attribute_count);
	$smarty->assign('attribute_count_3',        ($attribute_count + 3));
	$smarty->assign('attribute',                $_attribute);
	$smarty->assign('product_sn',               $goods['goods_sn'] . '_');
	$smarty->assign('product_number',           $_CFG['default_storage']);

	/* 取商品的货品 */
	$product = product_list($goods_id, '');

	$smarty->assign('ur_here',      $_LANG['18_product_list']);
	$smarty->assign('action_link',  array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']));
	$smarty->assign('product_list', $product['product']);
	$smarty->assign('product_null', empty($product['product']) ? 0 : 1);
	$smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);
	$smarty->assign('goods_id',     $goods_id);
	$smarty->assign('filter',       $product['filter']);
	$smarty->assign('full_page',    1);

	/* 显示商品列表页面 */
	assign_query_info();

	$smarty->display('product_info.htm');
}

/*------------------------------------------------------ */
//-- 货品排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'product_query')
{
	/* 是否存在商品id */
	if (empty($_REQUEST['goods_id']))
	{
		make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
	}
	else
	{
		$goods_id = intval($_REQUEST['goods_id']);
	}

	/* 取出商品信息 */
	$sql = "SELECT goods_sn, goods_name, goods_type, shop_price FROM " . $ecs->table('pocket_goods') . " WHERE goods_id = '$goods_id'";
	$goods = $db->getRow($sql);
	if (empty($goods))
	{
		make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
	}
	$smarty->assign('sn', sprintf($_LANG['good_goods_sn'], $goods['goods_sn']));
	$smarty->assign('price', sprintf($_LANG['good_shop_price'], $goods['shop_price']));
	$smarty->assign('goods_name', sprintf($_LANG['products_title'], $goods['goods_name']));
	$smarty->assign('goods_sn', sprintf($_LANG['products_title_2'], $goods['goods_sn']));


	/* 获取商品规格列表 */
	$attribute = get_goods_specifications_list($goods_id);
	if (empty($attribute))
	{
		make_json_error($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods']);
	}
	foreach ($attribute as $attribute_value)
	{
		//转换成数组
		$_attribute[$attribute_value['attr_id']]['attr_values'][] = $attribute_value['attr_value'];
		$_attribute[$attribute_value['attr_id']]['attr_id'] = $attribute_value['attr_id'];
		$_attribute[$attribute_value['attr_id']]['attr_name'] = $attribute_value['attr_name'];
	}
	$attribute_count = count($_attribute);

	$smarty->assign('attribute_count',          $attribute_count);
	$smarty->assign('attribute',                $_attribute);
	$smarty->assign('attribute_count_3',        ($attribute_count + 3));
	$smarty->assign('product_sn',               $goods['goods_sn'] . '_');
	$smarty->assign('product_number',           $_CFG['default_storage']);

	/* 取商品的货品 */
	$product = product_list($goods_id, '');

	$smarty->assign('ur_here', $_LANG['18_product_list']);
	$smarty->assign('action_link', array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']));
	$smarty->assign('product_list',  $product['product']);
	$smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);
	$smarty->assign('goods_id',    $goods_id);
	$smarty->assign('filter',       $product['filter']);

	/* 排序标记 */
	$sort_flag  = sort_flag($product['filter']);
	$smarty->assign($sort_flag['tag'], $sort_flag['img']);

	make_json_result($smarty->fetch('product_info.htm'), '',
	array('filter' => $product['filter'], 'page_count' => $product['page_count']));
}

/*------------------------------------------------------ */
//-- 货品删除
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'product_remove')
{
	/* 检查权限 */
	//check_authz_json('remove_back');

	/* 是否存在商品id */
	if (empty($_REQUEST['id']))
	{
		make_json_error($_LANG['product_id_null']);
	}
	else
	{
		$product_id = intval($_REQUEST['id']);
	}

	/* 货品库存 */
	$product = get_product_info($product_id, 'product_number, goods_id');

	/* 删除货品 */
	$sql = "DELETE FROM " . $ecs->table('products') . " WHERE product_id = '$product_id'";
	$result = $db->query($sql);
	if ($result)
	{
		/* 修改商品库存 */
		if (update_goods_stock($product['goods_id'], $product_number - $product['product_number']))
		{
			//记录日志
			admin_log('', 'update', 'goods');
		}

		//记录日志
		admin_log('', 'trash', 'products');

		$url = 'goods.php?act=product_query&' . str_replace('act=product_remove', '', $_SERVER['QUERY_STRING']);

		ecs_header("Location: $url\n");
		exit;
	}
}

/*------------------------------------------------------ */
//-- 修改货品价格
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_product_sn')
{
	//check_authz_json('goods_manage');

	$product_id       = intval($_POST['id']);
	$product_sn       = json_str_iconv(trim($_POST['val']));
	$product_sn       = ($_LANG['n_a'] == $product_sn) ? '' : $product_sn;

	if (check_product_sn_exist($product_sn, $product_id))
	{
		make_json_error($_LANG['sys']['wrong'] . $_LANG['exist_same_product_sn']);
	}

	/* 修改 */
	$sql = "UPDATE " . $ecs->table('products') . " SET product_sn = '$product_sn' WHERE product_id = '$product_id'";
	$result = $db->query($sql);
	if ($result)
	{
		clear_cache_files();
		make_json_result($product_sn);
	}
}

/*------------------------------------------------------ */
//-- 修改货品库存
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_product_number')
{
	//check_authz_json('goods_manage');

	$product_id       = intval($_POST['id']);
	$product_number       = intval($_POST['val']);

	/* 货品库存 */
	$product = get_product_info($product_id, 'product_number, goods_id');

	/* 修改货品库存 */
	$sql = "UPDATE " . $ecs->table('products') . " SET product_number = '$product_number' WHERE product_id = '$product_id'";
	$result = $db->query($sql);
	if ($result)
	{
		/* 修改商品库存 */
		if (update_goods_stock($product['goods_id'], $product_number - $product['product_number']))
		{
			clear_cache_files();
			make_json_result($product_number);
		}
	}
}

/*------------------------------------------------------ */
//-- 货品添加 执行
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'product_add_execute')
{
	admin_priv('goods_manage');

	$product['goods_id']        = intval($_POST['goods_id']);
	$product['attr']            = $_POST['attr'];
	$product['product_sn']      = $_POST['product_sn'];
	$product['product_number']  = $_POST['product_number'];

	/* 是否存在商品id */
	if (empty($product['goods_id']))
	{
		sys_msg($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods'], 1, array(), false);
	}

	/* 判断是否为初次添加 */
	$insert = true;
	if (product_number_count($product['goods_id']) > 0)
	{
		$insert = false;
	}

	/* 取出商品信息 */
	$sql = "SELECT goods_sn, goods_name, goods_type, shop_price FROM " . $ecs->table('pocket_goods') . " WHERE goods_id = '" . $product['goods_id'] . "'";
	$goods = $db->getRow($sql);
	if (empty($goods))
	{
		sys_msg($_LANG['sys']['wrong'] . $_LANG['cannot_found_goods'], 1, array(), false);
	}

	/*  */
	foreach($product['product_sn'] as $key => $value)
	{
		//过滤
		$product['product_number'][$key] = empty($product['product_number'][$key]) ? (empty($_CFG['use_storage']) ? 0 : $_CFG['default_storage']) : trim($product['product_number'][$key]); //库存

		//获取规格在商品属性表中的id
		foreach($product['attr'] as $attr_key => $attr_value)
		{
			/* 检测：如果当前所添加的货品规格存在空值或0 */
			if (empty($attr_value[$key]))
			{
				continue 2;
			}

			$is_spec_list[$attr_key] = 'true';

			$value_price_list[$attr_key] = $attr_value[$key] . chr(9) . ''; //$key，当前

			$id_list[$attr_key] = $attr_key;
		}
		$goods_attr_id = handle_goods_attr($product['goods_id'], $id_list, $is_spec_list, $value_price_list);

		/* 是否为重复规格的货品 */
		$goods_attr = sort_goods_attr_id_array($goods_attr_id);
		$goods_attr = implode('|', $goods_attr['sort']);
		if (check_goods_attr_exist($goods_attr, $product['goods_id']))
		{
			continue;
			//sys_msg($_LANG['sys']['wrong'] . $_LANG['exist_same_goods_attr'], 1, array(), false);
		}
		//货品号不为空
		if (!empty($value))
		{
			/* 检测：货品货号是否在商品表和货品表中重复 */
			if (check_goods_sn_exist($value))
			{
				continue;
				//sys_msg($_LANG['sys']['wrong'] . $_LANG['exist_same_goods_sn'], 1, array(), false);
			}
			if (check_product_sn_exist($value))
			{
				continue;
				//sys_msg($_LANG['sys']['wrong'] . $_LANG['exist_same_product_sn'], 1, array(), false);
			}
		}

		/* 插入货品表 */
		$sql = "INSERT INTO " . $GLOBALS['ecs']->table('products') . " (goods_id, goods_attr, product_sn, product_number)  VALUES ('" . $product['goods_id'] . "', '$goods_attr', '$value', '" . $product['product_number'][$key] . "')";
		if (!$GLOBALS['db']->query($sql))
		{
			continue;
			//sys_msg($_LANG['sys']['wrong'] . $_LANG['cannot_add_products'], 1, array(), false);
		}

		//货品号为空 自动补货品号
		if (empty($value))
		{
			$sql = "UPDATE " . $GLOBALS['ecs']->table('products') . "
                    SET product_sn = '" . $goods['goods_sn'] . "g_p" . $GLOBALS['db']->insert_id() . "'
                    WHERE product_id = '" . $GLOBALS['db']->insert_id() . "'";
			$GLOBALS['db']->query($sql);
		}

		/* 修改商品表库存 */
		$product_count = product_number_count($product['goods_id']);
		if (update_goods($product['goods_id'], 'goods_number', $product_count))
		{
			//记录日志
			admin_log($product['goods_id'], 'update', 'goods');
		}
	}

	clear_cache_files();

	/* 返回 */
	if ($insert)
	{
		$link[] = array('href' => 'goods.php?act=add', 'text' => $_LANG['02_goods_add']);
		$link[] = array('href' => 'goods.php?act=list', 'text' => $_LANG['01_goods_list']);
		$link[] = array('href' => 'goods.php?act=product_list&goods_id=' . $product['goods_id'], 'text' => $_LANG['18_product_list']);
	}
	else
	{
		$link[] = array('href' => 'goods.php?act=list&uselastfilter=1', 'text' => $_LANG['01_goods_list']);
		$link[] = array('href' => 'goods.php?act=edit&goods_id=' . $product['goods_id'], 'text' => $_LANG['edit_goods']);
		$link[] = array('href' => 'goods.php?act=product_list&goods_id=' . $product['goods_id'], 'text' => $_LANG['18_product_list']);
	}
	sys_msg($_LANG['save_products'], 0, $link);
}

/*------------------------------------------------------ */
//-- 货品批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_product')
{
	/* 定义返回 */
	$link[] = array('href' => 'goods.php?act=product_list&goods_id=' . $_POST['goods_id'], 'text' => $_LANG['item_list']);

	/* 批量操作 - 批量删除 */
	if ($_POST['type'] == 'drop')
	{
		//检查权限
		admin_priv('remove_back');

		//取得要操作的商品编号
		$product_id = !empty($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0;
		$product_bound = db_create_in($product_id);

		//取出货品库存总数
		$sum = 0;
		$goods_id = 0;
		$sql = "SELECT product_id, goods_id, product_number FROM  " . $GLOBALS['ecs']->table('products') . " WHERE product_id $product_bound";
		$product_array = $GLOBALS['db']->getAll($sql);
		if (!empty($product_array))
		{
			foreach ($product_array as $value)
			{
				$sum += $value['product_number'];
			}
			$goods_id = $product_array[0]['goods_id'];

			/* 删除货品 */
			$sql = "DELETE FROM " . $ecs->table('products') . " WHERE product_id $product_bound";
			if ($db->query($sql))
			{
				//记录日志
				admin_log('', 'delete', 'products');
			}

			/* 修改商品库存 */
			if (update_goods_stock($goods_id, -$sum))
			{
				//记录日志
				admin_log('', 'update', 'goods');
			}

			/* 返回 */
			sys_msg($_LANG['product_batch_del_success'], 0, $link);
		}
		else
		{
			/* 错误 */
			sys_msg($_LANG['cannot_found_products'], 1, $link);
		}
	}

	/* 返回 */
	sys_msg($_LANG['no_operation'], 1, $link);
}

/**
 * 列表链接
 * @param   bool    $is_add         是否添加（插入）
 * @param   string  $extension_code 虚拟商品扩展代码，实体商品为空
 * @return  array('href' => $href, 'text' => $text)
 */
function list_link($is_add = true, $extension_code = '')
{
	$href = 'goods.php?act=list';
	if (!empty($extension_code))
	{
		$href .= '&extension_code=' . $extension_code;
	}
	if (!$is_add)
	{
		$href .= '&' . list_link_postfix();
	}

	if ($extension_code == 'virtual_card')
	{
		$text = $GLOBALS['_LANG']['50_virtual_card_list'];
	}
	else
	{
		$text = $GLOBALS['_LANG']['01_goods_list'];
	}

	return array('href' => $href, 'text' => $text);
}
//二维数组排序
 function my_sort($arrays,$sort_key,$sort_order,$sort_type=SORT_NUMERIC ){   
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    }  
/**
 * 添加链接
 * @param   string  $extension_code 虚拟商品扩展代码，实体商品为空
 * @return  array('href' => $href, 'text' => $text)
 */
function add_link($extension_code = '')
{
	$href = 'goods.php?act=add';
	if (!empty($extension_code))
	{
		$href .= '&extension_code=' . $extension_code;
	}

	if ($extension_code == 'virtual_card')
	{
		$text = $GLOBALS['_LANG']['51_virtual_card_add'];
	}
	else
	{
		$text = $GLOBALS['_LANG']['02_goods_add'];
	}

	return array('href' => $href, 'text' => $text);
}

/**
 * 检查图片网址是否合法
 *
 * @param string $url 网址
 *
 * @return boolean
 */
function goods_parse_url($url)
{
	$parse_url = @parse_url($url);
	return (!empty($parse_url['scheme']) && !empty($parse_url['host']));
}

/**
 * 保存某商品的优惠价格
 * @param   int     $goods_id    商品编号
 * @param   array   $number_list 优惠数量列表
 * @param   array   $price_list  价格列表
 * @return  void
 */
function handle_volume_price($goods_id, $number_list, $price_list)
{
	$sql = "DELETE FROM " . $GLOBALS['ecs']->table('volume_price') .
	" WHERE price_type = '1' AND goods_id = '$goods_id'";
	$GLOBALS['db']->query($sql);


	/* 循环处理每个优惠价格 */
	foreach ($price_list AS $key => $price)
	{
		/* 价格对应的数量上下限 */
		$volume_number = $number_list[$key];

		if (!empty($price))
		{
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('volume_price') .
			" (price_type, goods_id, volume_number, volume_price) " .
			"VALUES ('1', '$goods_id', '$volume_number', '$price')";
			$GLOBALS['db']->query($sql);
		}
	}
}

/**
 * 修改商品库存
 * @param   string  $goods_id   商品编号，可以为多个，用 ',' 隔开
 * @param   string  $value      字段值
 * @return  bool
 */
function update_goods_stock($goods_id, $value)
{
	if ($goods_id)
	{
		/* $res = $goods_number - $old_product_number + $product_number; */
		$sql = "UPDATE " . $GLOBALS['ecs']->table('pocket_goods') . "
                SET goods_number = goods_number + $value,
                    last_update = '". gmtime() ."'
                WHERE goods_id = '$goods_id'";
		$result = $GLOBALS['db']->query($sql);

		/* 清除缓存 */
		clear_cache_files();

		return $result;
	}
	else
	{
		return false;
	}
}
/**

 * 获得指定商品的配件

 *

 * @access  public

 * @param   integer $goods_id

 * @return  array

 */
function get_this_group_goods($goods_id)
{
	$sql = "SELECT gg.goods_id, CONCAT('(', gg.MinQuantity,',', gg.MaxQuantity, ') ',g.goods_name, ' -- [', g.goods_sn, '],-- [', gg.goods_price, ']') AS goods_name " .
	"FROM " . $GLOBALS['ecs']->table('group_goods') . " AS gg, " .
	$GLOBALS['ecs']->table('pocket_goods') . " AS g " .
	"WHERE gg.parent_id = '$goods_id' " .
	"AND gg.goods_id = g.goods_id ";
	if ($goods_id == 0)
	{
		$sql .= " AND gg.admin_id = '$_SESSION[admin_id]'";
	}
	$row = $GLOBALS['db']->getAll($sql);
	return $row;

}
/***add***/
function get_search_count_where($type)
{

	$where = ' ';
	if($type=="is_show"){
		$keyword=$_REQUEST['keyword'];
		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where (goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."')";
				if(!empty($_GET['cat_id']))
				{
				$where .=get_cate_id_where($_GET['cat_id']);
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}

			}else{
				$where .= " where (goods_sn like   '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."')";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
			}

			if(!empty($_GET['on_show']))
			{

				$where .= " and is_show=1";

			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
		$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."' ";
		 }
				$where .= " and is_show=1 and is_on_sale=1";

		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' and is_on_sale=1";
		if(!empty($_GET['cat_id']))
		{
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		$where .= " and is_show=1 and is_on_sale=1";

	return $where;
}

	return 'where is_show=1 and is_on_sale=1';
}
	elseif($type=="is_show_no_sell_on"){
		$keyword=$_REQUEST['keyword'];
		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where (goods_id='$keyword' or goods_name='$keyword'  or goods_id='".$keyword."')";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
					//$where .= " and cat_id='".$_GET['cat_id']."' ";
				}
				if(!empty($_GET['tag_id']))
				{
					$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}

			}else{
				$where .= " where (goods_sn like   '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."') and goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
				if(!empty($_GET['tag_id']))
				{
					$where .= " and tag_id='".$_GET['tag_id']."'";
				}
			}

			if(!empty($_GET['on_show']))
			{

				$where .= " and is_show=1 and is_on_sale=0";

			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
			//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
			if(!empty($_GET['tag_id']))
			{
				$where .= " and tag_id='".$_GET['tag_id']."' and is_on_sale=0 and is_show=1";
			}
			$where .= " and is_show=1 and is_on_sale=0";

			return $where;
		}
		else if(!empty($_GET['tag_id']))
		{
			$where .= " where tag_id='".$_GET['tag_id']."' and is_on_sale=0";
			if(!empty($_GET['cat_id']))
			{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
				$where .=get_cate_id_where($_GET['cat_id']);
			}
			$where .= " and is_show=1 and is_on_sale=0";

			return $where;
		}

		return 'where is_show=1 and is_on_sale=0';
	}else if($type=="is_show_no"){
	$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{

				$where .= " and is_show=2";

			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}

				$where .= " and is_show=2";

		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}

				$where .= " and is_show=2";

	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}

				$where .= " and is_show=2";

		return $where;
	}
	return 'where is_show=2';
}else if($type=="sell_out"){



		$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
		if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
			$where .= " and goods_number=0";
		return $where;
	}

	return 'where goods_number=0';
}else if($type=="search")
	{

		$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
		if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
		return $where;
	}

	}
	else if(!empty($_GET['cat_id']))
	{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
		$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
	{
		$where .= " and tag_id='".$_GET['tag_id']."'  ";
	}
	if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
	}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."'";
		if(!empty($_GET['cat_id']))
	{
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
		$where .=get_cate_id_where($_GET['cat_id']);
	}
	if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
	}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
	}
	else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
		return $where;
	}
	else
	{
	return 'where is_show=1';
}
}
function get_cate_id_where($cat_id,$type=0)
{
	$type=($type==0) ? "and" : "where";
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods_cat")." where parent_id='".$cat_id."'";
	$goods_cat = $GLOBALS['db']->getAll($sql);
	//$goods_cat=array(array("cat_id"=>4),array("cat_id"=>6));

	if($goods_cat)
	{
		$str="";
		foreach($goods_cat as $val)
		{
			$where .= " ".$type." cat_id like '%,".$cat_id.",%' ";
			$str.=$val['cat_id'].",";
		}
		$str.=$cat_id.",";
		$str=substr($str,0,strlen($str)-1);
		$where= " ".$type." cat_id in (".$str.")";
	}
	else
	$where .= " ".$type." cat_id like '%,".$cat_id.",%' ";
	return 	$where;
}
function get_search_where($type)
{

	$where = ' ';
	if($type=="is_show"){


		$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			$where .= " where (goods_sn like  '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."')";
			if(!empty($_GET['cat_id']))
			{
				$where .=get_cate_id_where($_GET['cat_id']);
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
			}
			if(!empty($_GET['tag_id']))
			{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
			}
			return $where;
		}else if(!empty($_GET['cat_id']))
		{
		$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."' ";
		 }
				$where .= " and is_show=1 and is_on_sale=1";

		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' and is_on_sale=1 ";
		if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id']);
		//$where .= " and cat_id='".$_GET['cat_id']."' ";
		}
		$where .= " and is_show=1 and is_on_sale=1";

	return $where;
}
	return 'where is_show=1 and is_on_sale=1';
}elseif($type=="is_show_no_sell_on"){
		$keyword=$_REQUEST['keyword'];
		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where (goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."') ";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
					//$where .= " and cat_id='".$_GET['cat_id']."' ";
				}
				if(!empty($_GET['tag_id']))
				{
					$where .= " and tag_id='".$_GET['tag_id']."' and is_on_sale=0 and is_show=1  ";
				}

			}else{
				$where .= " where (goods_sn like   '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."')";
				if(!empty($_GET['cat_id']))
				{
					//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
				if(!empty($_GET['tag_id']))
				{
					$where .= " and tag_id='".$_GET['tag_id']."'";
				}
			}

			if(!empty($_GET['on_show']))
			{

				$where .= " and is_show=1 and is_on_sale=0";

			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
			//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
			if(!empty($_GET['tag_id']))
			{
				$where .= " and tag_id='".$_GET['tag_id']."' and is_on_sale=0 ";
			}
			$where .= " and is_show=1 and is_on_sale=0";

			return $where;
		}
		else if(!empty($_GET['tag_id']))
		{
			$where .= " where tag_id='".$_GET['tag_id']."' and is_on_sale0";
			if(!empty($_GET['cat_id']))
			{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
				$where .=get_cate_id_where($_GET['cat_id']);
			}
			$where .= " and is_show=1 and is_on_sale=0";

			return $where;
		}

		return 'where is_show=1 and is_on_sale=0';
	}else if($type=="is_show_no"){
	$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
				//$where .= " and cat_id='".$_GET['cat_id']."' ";
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{

				$where .= " and is_show=2";

			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}

				$where .= " and is_show=2";

		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}

				$where .= " and is_show=2";

	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}

				$where .= " and is_show=2";

		return $where;
	}
	return 'where is_show=2';
}else if($type=="sell_out"){


		$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
		if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			$where .= " and goods_number=0";
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
			$where .= " and goods_number=0";
		return $where;
	}

	return 'where goods_number=0';
}else if($type=="search")
	{

		$keyword=$_REQUEST['keyword'];

		if(!empty($keyword))
		{
			if(preg_match("/^\d+$/is",$keyword)){
				$where .= " where goods_id='$keyword' or goods_name='$keyword' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."'  ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0'  ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."' ";
					}
				}
			}else{
				$where .= " where goods_sn like '%$keyword%' or goods_name like '%$keyword%' or goods_id='".$keyword."'";
				if(!empty($_GET['cat_id']))
				{
					$where .=get_cate_id_where($_GET['cat_id']);
				}
	 			if(!empty($_GET['tag_id']))
				{
				$where .= " and tag_id='".$_GET['tag_id']."' ";
				}
				if(!empty($_GET['on_sale']))
				{
					if($_GET['on_sale'] == 2){
					$where .= " and is_on_sale='0' ";
					}else{
					$where .= " and is_on_sale='".$_GET['on_sale']."'  ";
					}
				}
			}

			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
			return $where;

		}else if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id'],1);
		 if(!empty($_GET['tag_id']))
		 {
			$where .= " and tag_id='".$_GET['tag_id']."'  ";
		 }
		 if(!empty($_GET['on_sale']))
	 	 {
			if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
			}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
		if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."' ";
		if(!empty($_GET['cat_id']))
		{
			$where .=get_cate_id_where($_GET['cat_id']);
		}
		if(!empty($_GET['on_sale']))
		{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
		}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
}else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
		return $where;
	}

	}
	else if(!empty($_GET['cat_id']))
	{
		$where .=get_cate_id_where($_GET['cat_id']);
		//$where .= " where cat_id='".$_GET['cat_id']."' ";
		 if(!empty($_GET['tag_id']))
	{
		$where .= " and tag_id='".$_GET['tag_id']."'  ";
	}
	if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
	}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
	}
	else if(!empty($_GET['tag_id']))
	{
		$where .= " where tag_id='".$_GET['tag_id']."'";
		if(!empty($_GET['cat_id']))
	{
		$where .= " and cat_id='".$_GET['cat_id']."' ";
	}
	if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " and is_on_sale='0' ";
		}else{
			$where .= " and is_on_sale='".$_GET['on_sale']."' ";
			}
	}
	if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
	return $where;
	}
	else if(!empty($_GET['on_sale']))
	{
		if($_GET['on_sale'] == 2){
			$where .= " where is_on_sale='0' ";
		}else{
			$where .= " where is_on_sale='".$_GET['on_sale']."' ";
			}
			if(!empty($_GET['on_show']))
			{
				if($_GET['on_show'] == 2){
				$where .= " and is_show=2";
				}else{
				$where .= " and is_show=1";
				}
			}
		return $where;
	}else if(!empty($_GET['on_show']))
	{
		if($_GET['on_show'] == 2){
			$where .= " where is_show=2";
		}else{
			$where .= " where is_show=1";
			}
		return $where;
	}
	else
	{
	return 'where is_show=1';
}
}
/**
 * 删除商品
 *
 */
function ajax_goods_delete()
{
	$goods_id=$_POST['goods_id'];
	if(strpos($goods_id,";")!==false)
	{
		$goods_str=explode(";",$goods_id);
		foreach($goods_str as $v)
		{
			if($v)
			{
				$goods_id=$v;
				$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods')." where goods_id='".$goods_id."'";
				$goods= $GLOBALS['db']->getRow($sql);
				if(!empty($goods))
				{
					$sql = "DELETE FROM " .$GLOBALS['ecs']->table("pocket_goods"). " where goods_id=".$goods_id." limit 1";
					$GLOBALS['db']->query($sql);
					$sql = "DELETE FROM " .$GLOBALS['ecs']->table("goods_gallery"). " where goods_id='".$goods_id."'";
					$GLOBALS['db']->query($sql);
				}
			}
		}
		$array=array("error"=>0,"info"=>"删除成功");
		echo json_encode($array);die();
	}
	else
	{
		$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods')." where goods_id='".$goods_id."'";
		$cate= $GLOBALS['db']->getRow($sql);
		if(!empty($cate))
		{
			$sql = "DELETE FROM " .$GLOBALS['ecs']->table("pocket_goods"). " where goods_id=".$goods_id." limit 1";
			$GLOBALS['db']->query($sql);
			$sql = "DELETE FROM " .$GLOBALS['ecs']->table("goods_gallery"). " where goods_id='".$goods_id."'";
			$GLOBALS['db']->query($sql);
			$array=array("error"=>0,"info"=>"删除成功");
		}
		else
			$array=array("error"=>0,"info"=>"删除失败");
		echo json_encode($array);die();
	}
}
/**
 * 批量上下架
 *
 */
function ajax_goods_is_on_sale()
{
	$goods_id=$_POST['goods_id'];
	$is_on_sale=$_POST['is_on_sale'];
	$is_on_sale=($is_on_sale) ? $is_on_sale : 0;
	if(strpos($goods_id,";")!==false) {
		$goods_str = explode(";", $goods_id);
		foreach ($goods_str as $v) {
			if ($v) {
				$goods_id = $v;
				$sql = "select * from " . $GLOBALS['ecs']->table('pocket_goods') . " where goods_id='" . $goods_id . "'";
				$goods = $GLOBALS['db']->getRow($sql);
				if (!empty($goods)) {
					if($is_on_sale==1)
					$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET is_on_sale='".$is_on_sale."' ,is_show=1 where goods_id=".$goods_id." limit 1";
					else
						$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET is_on_sale='".$is_on_sale."' where goods_id=".$goods_id." limit 1";
					$GLOBALS['db']->query($sql);
				}
			}
		}
		$array = array("error" => 0, "info" => "更改成功");
		echo json_encode($array);
		die();
	}
}
function get_title()
{
	if($type=="is_show")
	return '出售中的商品';
	else if($type=="is_show_no")
	return '未审核的商品';
	else if($type=="sell_out")
	return '已售罄的商品';
	else
	return '仓库中的商品';
}
function edit()
{
	$goods_id=$_GET['goods_id'];
	$goods = get_goods_info($goods_id);
	if(empty($goods))
	$goods = get_goods_info($goods_id);
	$goods_info=$goods;
	/**编辑器**/
	require(get_base_dir() . 'includes/fckeditor/fckeditor.php');
	$editor             = new FCKeditor('goods_info');
	$editor->BasePath   = '../includes/fckeditor/';
	$editor->ToolbarSet = 'Normal';
	$editor->Width      = '965px';
	$editor->Height     = '750px';
	$editor->Value = $goods['goods_desc'];
	$FCKeditor     = $editor->CreateHtml();
	$GLOBALS['smarty']->assign('FCKeditor', $FCKeditor);
	//
	$sql = "select * from ". $GLOBALS['ecs']->table('pocket_goods')." where goods_id='".$goods_id."'";
	$goods_info2= $GLOBALS['db']->getRow($sql);
	$goods_cat=$goods_info2['goods_cat'];
	$goods_cat_array=explode(",",$goods_cat);
	//调用分类
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods_cat")." order by sort_order desc, cat_id desc";
	$goods_cat = $GLOBALS['db']->getAll($sql);
	foreach($goods_cat as $key=>$val)
	{
		$goods_cat[$key]['is_have']=(in_array($val['cat_id'],$goods_cat_array)) ? 1 : 0;
	}
	$GLOBALS['smarty']->assign('goods_cat', $goods_cat);
	$GLOBALS['smarty']->assign('goods_info', $goods_info);
	$GLOBALS['smarty']->assign('goods_info2', $goods_info2);
	$GLOBALS['smarty']->display('goods_edit.htm');
}
/***层编辑***/
function ajax_layer_save()
{
	$data = array(
		'goods_name'         =>$_POST['goods_name'],
		'shop_price'         =>$_POST['shop_price'],
		'goods_number'       =>$_POST['goods_number'],
		'pv'      			 =>$_POST['pv'],
		'sort_order'         =>$_POST['sort_order'],
		'time'               =>gmtime(),
		);
	if($_POST['cat_id'])
	$data['cat_id']	=$_POST['cat_id'];
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$_POST['goods_id']." limit 1");
	$array=array("error"=>0,"info"=>"修改成功");
	echo json_encode($array);die();
}
/**
	 * 删除操作
	 *
	 */
function delete()
{
	$goods_id=$_POST['goods_id'];
	$goods = parent::table_get_row('pocket_goods',$goods_id,$id="goods_id");
	if(!empty($goods))
	{
		$data=array("is_pocket"=>0);
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$goods_id." limit 1");
		$sql = "DELETE FROM " .$GLOBALS['ecs']->table('pocket_goods'). " where goods_id=".$goods_id." limit 1";
		$GLOBALS['db']->query($sql);
		$this->goods_unlink($goods_id);
	}
}
/**
	 * 商品显示
	 *
	 */
if (@$_POST['act'] == 'ajax_sale_on_show' )
{
	$goods_str=$_POST['id'];
	$goods_str=explode(";",$goods_str);

	foreach($goods_str as $v)
	{
		if($v)
		{
			$data=array("is_show"=>1);
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$v." limit 1");
		}
	}
	$array=json_encode(array("error"=>0,"info"=>"操作成功"));
	echo $array;die();
}
/**
	 * 商品隐藏
	 *
	 */
if (@$_POST['act'] == 'ajax_sale_on' )
{
	$goods_str=$_POST['id'];
	$goods_str=explode(";",$goods_str);

	foreach($goods_str as $v)
	{
		if($v)
		{
			$data=array("is_show"=>2);
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$v." limit 1");
		}
	}
	$array=json_encode(array("error"=>0,"info"=>"操作成功"));
	echo $array;die();
}
/**
	 * 批量删除操作
	 *
	 */
	  if (@$_POST['act'] == 'ajax_delete_all' )
{
	$goods_str=$_POST['id'];
	$goods_str=explode(";",$goods_str);
	foreach($goods_str as $v)
	{
		if($v)
		{
			$goods_id=$v;
			$data=array("is_show"=>2);
			$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET is_on_sale=0,is_show=2 where goods_id=".$v." limit 1";
			$GLOBALS['db']->query($sql);
			/*$goods = parent::table_get_row('pocket_goods',$goods_id,$id="goods_id");
			if(!empty($goods))
			{
				$data=array("is_pocket"=>0);
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$goods_id." limit 1");
				$sql = "DELETE FROM " .$GLOBALS['ecs']->table('pocket_goods'). " where goods_id=".$goods_id." limit 1";
				$GLOBALS['db']->query($sql);
				$this->goods_unlink($goods_id);
			}*/
		}
	}
	$array=json_encode(array("error"=>0,"info"=>"操作成功"));
	echo $array;die();


}
/**
	 * 批量分类操作
	 *
	 */
	  if (@$_POST['act'] == 'ajax_batch_category' )
{
	$goods_str=$_POST['id'];
	$batch_category=$_POST['batch_category'];
	$goods_str=explode(";",$goods_str);
	foreach($goods_str as $v)
	{
		if($v)
		{
			$sql = " UPDATE ".$GLOBALS['ecs']->table('pocket_goods')." SET cat_id='".$batch_category."' where goods_id=".$v." limit 1";
			$GLOBALS['db']->query($sql);
		}
	}
	$array=json_encode(array("error"=>0,"info"=>"操作成功"));
	echo $array;die();


}
/**
	 * 控制商品显示
	 *
	 */
function ajax_save_is_show()
{
	$goods_id=$_POST['goods_id'];
	$is_show = parent::table_get_one('pocket_goods',"is_show",$goods_id,"goods_id");
	$is_show = ($is_show == 1) ? 2 : 1;
	$data=array("is_show"=>$is_show);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$goods_id." limit 1");
}

/**
	 * 商品保存
	 *
	 */
function ajax_edit_save()
{
	$goods_id=trim($_POST['goods_id']);
	$data = array(
	'goods_name'        =>trim($_POST['goods_name']),//产品名称
	'goods_desc'        =>trim($_POST['goods_info']),
	'goods_cat'         =>trim($_POST['goods_cat']),
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods'),$data,'',"goods_id=".$goods_id." limit 1");
	$array=array("error"=>0,"info"=>"操作失败");
	echo json_encode($array);die();
}
/**
	 * 商品预览保存
	 *
	 */
function ajax_save_preview()
{
	$goods_id=trim($_POST['goods_id']);
	$data = array(
	'goods_id'      	=>$goods_id,//产品编号
	'goods_name'        =>trim($_POST['goods_name']),//产品名称
	'goods_desc'        =>trim($_POST['goods_info']),
	'goods_cat'         =>trim($_POST['goods_cat']),
	'time'              =>gmtime()//时间
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('pocket_goods_preview'),$data);
	$insert_id=$GLOBALS['db']->insert_id();
	if($insert_id)
	{
		$array=array("error"=>0,"pid"=>$insert_id);
		$this->createQRcode($goods_id);
	}
	else
	$array=array("error"=>1,"info"=>"操作失败");
	echo json_encode($array);die();
}
/**
	 * 获取购买链接
	 *
	 * @param unknown_type $goods_id
	 */
function get_buy_link($goods_id)
{
	return get_site_url()."goods.php?act=info&id=".$goods_id;;
}
/**
	 * 删除产品的时候,删除生成的二维码
	 *
	 * @param unknown_type $goods_id
	 */
function goods_unlink($goods_id)
{
	@unlink(ROOT_PATH.'pocket/QRcode/QRcode_'.$goods_id.'.png');
}
/**
	 * 获得商品的详细信息
	 *
	 * @access  public
	 * @param   integer     $goods_id
	 * @return  void
 	*/
function get_goods_info($goods_id)
{
	$time = gmtime();
	$sql = 'SELECT g.*, c.measure_unit, b.brand_id, b.brand_name AS goods_brand, m.type_money AS bonus_money, ' .
	'IFNULL(AVG(r.comment_rank), 0) AS comment_rank, ' .
	"IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS rank_price " .
	'FROM ' . $GLOBALS['ecs']->table('pocket_goods') . ' AS g ' .
	'LEFT JOIN ' . $GLOBALS['ecs']->table('category') . ' AS c ON g.cat_id = c.cat_id ' .
	'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON g.brand_id = b.brand_id ' .
	'LEFT JOIN ' . $GLOBALS['ecs']->table('comment') . ' AS r '.
	'ON r.id_value = g.goods_id AND comment_type = 0 AND r.parent_id = 0 AND r.status = 1 ' .
	'LEFT JOIN ' . $GLOBALS['ecs']->table('bonus_type') . ' AS m ' .
	"ON g.bonus_type_id = m.type_id AND m.send_start_date <= '$time' AND m.send_end_date >= '$time'" .
	" LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
	"ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
	"WHERE g.goods_id = '$goods_id'  AND g.is_delete = 0 " .
	"GROUP BY g.goods_id";
	$row = $GLOBALS['db']->getRow($sql);
	if ($row !== false)
	{
		/* 用户评论级别取整 */
		$row['comment_rank']  = ceil($row['comment_rank']) == 0 ? 5 : ceil($row['comment_rank']);
		/* 获得商品的销售价格 */
		$row['market_price0']        = $row['market_price'];
		$row['market_price']        = price_format($row['market_price']);
		$row['shop_price_formated'] = price_format($row['shop_price']);
		$row['market_price1']        = $row['market_price'];
		$row['shop_price1'] = $row['shop_price'];
		$row['sheng_price'] = $row['market_price']-$row['shop_price'];
		$row['cuxiao_sheng_price'] = $row['market_price']-$row['promote_price'];
		/* 修正促销价格 */
		if ($row['promote_price'] > 0)
		{
			$promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
		}
		else
		{
			$promote_price = 0;
		}
		/* 处理商品水印图片 */
		$watermark_img = '';
		if ($promote_price != 0)
		{
			$watermark_img = "watermark_promote";
		}
		elseif ($row['is_new'] != 0)
		{
			$watermark_img = "watermark_new";
		}
		elseif ($row['is_best'] != 0)
		{
			$watermark_img = "watermark_best";
		}
		elseif ($row['is_hot'] != 0)
		{
			$watermark_img = 'watermark_hot';
		}
		if ($watermark_img != '')
		{
			$row['watermark_img'] =  $watermark_img;
		}
		$sql = "select * from ". $GLOBALS['ecs']->table("goods_gallery")." where goods_id='".$row['goods_id']."' order by img_id asc LIMIT 1";
		$img_list = $GLOBALS['db']->getRow($sql);
		$row['goods_thumb']=$img_list['thumb_url'];
		$row['promote_price_org'] =  $promote_price;
		$row['promote_price'] =  price_format($promote_price);
		/* 修正重量显示 */
		$row['goods_weight']  = (intval($row['goods_weight']) > 0) ?
		$row['goods_weight'] . $GLOBALS['_LANG']['kilogram'] :
		($row['goods_weight'] * 1000) . $GLOBALS['_LANG']['gram'];
		/* 修正上架时间显示 */
		$row['add_time']      = local_date("Y-m-d", $row['add_time']);
		$row['add_time2']      = local_date("H:i:s", $row['add_time']);
		/* 促销时间倒计时 */
		$time = gmtime();
		if ($time >= $row['promote_start_date'] && $time <= $row['promote_end_date'])
		{
			$row['gmt_end_time']  = $row['promote_end_date'];
		}
		else
		{
			$row['gmt_end_time'] = 0;
		}
		/* 是否显示商品库存数量 */
		$row['goods_number']  = ($GLOBALS['_CFG']['use_storage'] == 1) ? $row['goods_number'] : '';
		/* 修正积分：转换为可使用多少积分（原来是可以使用多少钱的积分） */
		$row['integral']      = $GLOBALS['_CFG']['integral_scale'] ? round($row['integral'] * 100 / $GLOBALS['_CFG']['integral_scale']) : 0;
		/* 修正优惠券 */
		$row['bonus_money']   = ($row['bonus_money'] == 0) ? 0 : price_format($row['bonus_money'], false);
		/* 修正商品图片 */
		$row['goods_img']   = get_image_path($goods_id, $row['goods_img']);
		$row['goods_thumb'] = get_image_path($goods_id, $row['goods_thumb'], true);
		if($row['is_share'] !=1){
			$row['rank_price'] = $row['shop_price'];
		}
		return $row;
	}
	else
	{
		return false;
	}
}
function outputCsvHeaderNew($data,$file_name = 'todayOrder')
	{
		$str = $file_name;
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment;filename="' .$str . '.csv"');
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		$csv_data = '';
		foreach ($data as $line)
		{
			foreach ($line as $key => &$item)
			{
				$item = str_replace (',','，',str_replace(PHP_EOL,'',$item));   //过滤生成csv文件中的(,)逗号和换行
				$item = mb_convert_encoding($item, 'gbk', 'utf-8');
			}
			$csv_data .= implode(',', $line) . PHP_EOL;
		}
		echo $csv_data;
	}
function get_pinyin($limit=50)
{
	$sql = "select * from ". $GLOBALS['ecs']->table("pocket_goods")." where pinyin='' and goods_name!='' limit ".$limit;
	$res = $GLOBALS['db']->getAll($sql);
	foreach($res as $item)
	{
		$goods_id=$item['goods_id'];
		$pinyin=utf8_to($item['goods_name']);
		$sql = "UPDATE " . $GLOBALS['ecs']->table('pocket_goods') . "
                SET pinyin = '". $pinyin ."'
                WHERE goods_id = '$goods_id'";
		$result = $GLOBALS['db']->query($sql);
	}
}
function utf8_to($s, $isfirst = false) {

	return to(utf8_to_gb2312($s), $isfirst);
}

function utf8_to_gb2312($s) {
	return iconv('UTF-8', 'GB2312//IGNORE', $s);
}

// 字符串必须为GB2312编码
function to($s, $isfirst = false) {
	$res = '';
	$len = strlen($s);
	$pinyin_arr = get_pinyin_array();
	for($i=0; $i<$len; $i++) {
		$ascii = ord($s{$i});
		if($ascii > 0x80) {
			$ascii2 = ord($s{++$i});
			$ascii = $ascii * 256 + $ascii2 - 65536;
		}

		if($ascii < 255 && $ascii > 0) {
			if(($ascii >= 48 && $ascii <= 57) || ($ascii >= 97 && $ascii <= 122)) {
				$res .= $s{$i}; // 0-9 a-z
			}elseif($ascii >= 65 && $ascii <= 90) {
				$res .= strtolower($s{$i}); // A-Z
			}else{
				$res .= '_';
			}
		}elseif($ascii < -20319 || $ascii > -10247) {
			$res .= '_';
		}else{
			foreach($pinyin_arr as $py=>$asc) {
				if($asc <= $ascii) {
					$res .= $isfirst ? $py{0} : $py;
					break;
				}
			}
		}
	}
	return $res;
}

function to_first($s) {
	$ascii = ord($s{0});
	if($ascii > 0xE0) {
		$s = utf8_to_gb2312($s{0}.$s{1}.$s{2});
	}elseif($ascii < 0x80) {
		if($ascii >= 65 && $ascii <= 90) {
			return strtolower($s{0});
		}elseif($ascii >= 97 && $ascii <= 122) {
			return $s{0};
		}else{
			return false;
		}
	}

	if(strlen($s) < 2) {
		return false;
	}

	$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;

	if($asc>=-20319 && $asc<=-20284) return 'a';
	if($asc>=-20283 && $asc<=-19776) return 'b';
	if($asc>=-19775 && $asc<=-19219) return 'c';
	if($asc>=-19218 && $asc<=-18711) return 'd';
	if($asc>=-18710 && $asc<=-18527) return 'e';
	if($asc>=-18526 && $asc<=-18240) return 'f';
	if($asc>=-18239 && $asc<=-17923) return 'g';
	if($asc>=-17922 && $asc<=-17418) return 'h';
	if($asc>=-17417 && $asc<=-16475) return 'j';
	if($asc>=-16474 && $asc<=-16213) return 'k';
	if($asc>=-16212 && $asc<=-15641) return 'l';
	if($asc>=-15640 && $asc<=-15166) return 'm';
	if($asc>=-15165 && $asc<=-14923) return 'n';
	if($asc>=-14922 && $asc<=-14915) return 'o';
	if($asc>=-14914 && $asc<=-14631) return 'p';
	if($asc>=-14630 && $asc<=-14150) return 'q';
	if($asc>=-14149 && $asc<=-14091) return 'r';
	if($asc>=-14090 && $asc<=-13319) return 's';
	if($asc>=-13318 && $asc<=-12839) return 't';
	if($asc>=-12838 && $asc<=-12557) return 'w';
	if($asc>=-12556 && $asc<=-11848) return 'x';
	if($asc>=-11847 && $asc<=-11056) return 'y';
	if($asc>=-11055 && $asc<=-10247) return 'z';
	return false;
}

function get_pinyin_array() {
	static $py_arr;
	if(isset($py_arr)) return $py_arr;

	$k = 'a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo';
	$v = '-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274|-10270|-10262|-10260|-10256|-10254';
	$key = explode('|', $k);
	$val = explode('|', $v);
	$py_arr = array_combine($key, $val);
	arsort($py_arr);


	return $py_arr;
}
?>
