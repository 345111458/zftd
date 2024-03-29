<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Template\TagLib;
use Think\Template\TagLib;

/**
 * 自定义标签
 */

class Zfuwl extends TagLib {
	protected $tags = array(
		'adv' => array('attr'=>'limit,order,where,item','close'=>1),
//		'article' => array('attr'=>'limit,order,where','close'=>1),
                'zfuwl' => array('attr'=>'sql,key,item,result_name','close'=>1,'level'=>3), // tpshop sql 万能标签
                'goods_img' =>  array('attr'=>'id,width,height','close'=>0), // 商品缩列图标签
	);


	/**
	 * 广告标签
	 * @access public
	 * @param array $tag 标签属性
	 * @param string $content  标签内容
	 * @return string
	 */
	public function _adv($tag,$content){
     	$order = $tag['order']; //排序
        $limit = !empty($tag['limit']) ? $tag['limit'] : '1';
        $where = $tag['where']; //查询条件
        $item  = !empty($tag['item']) ? $tag['item'] : 'item';// 返回的变量item
        $key  =  !empty($tag['key']) ? $tag['key'] : 'key';// 返回的变量key
        $pid  =  !empty($tag['pid']) ? $tag['pid'] : '0';// 返回的变量key

        $str = '<?php ';
        $str .= '$pid ='.$pid.';';
        $str .= '$ad_position = M("ad_position")->cache(true,ZFUWL_CACHE_TIME)->getField("position_id,position_name,ad_width,ad_height");';
        $str .= '$result = D("ad")->where("pid=$pid  and enabled = 1 and start_time < '.strtotime(date('Y-m-d H:00:00')).' and end_time > '.strtotime(date('Y-m-d H:00:00')).' ")->order("orderby desc")->cache(true,ZFUWL_CACHE_TIME)->limit("'.$limit.'")->select();';
        $str .= '

if(!in_array($pid,array_keys($ad_position)) && $pid)
{
  M("ad_position")->add(array(
         "position_id"=>$pid,
         "position_name"=>CONTROLLER_NAME."页面自动增加广告位 $pid ",
         "is_open"=>1,
         "position_desc"=>CONTROLLER_NAME."页面",
  ));
  delFile(RUNTIME_PATH); // 删除缓存
}


$c = '.$limit.'- count($result); //  如果要求数量 和实际数量不一样 并且编辑模式
if($c > 0 && $_GET[edit_ad])
{
    for($i = 0; $i < $c; $i++) // 还没有添加广告的时候
    {
      $result[] = array(
          "ad_code" => "/Public/images/not_adv.jpg",
          "ad_link" => "/index.php?m=Zbsht&c=Ad&a=ad&pid=$pid",
          "title"   =>"暂无广告图片",
          "not_adv" => 1,
          "target" => 0,
      );
    }
}
foreach($result as $'.$key.'=>$'.$item.'):

    $'.$item.'[position] = $ad_position[$'.$item.'[pid]];
    if($_GET[edit_ad] && $'.$item.'[not_adv] == 0 )
    {
        $'.$item.'[style] = "filter:alpha(opacity=50); -moz-opacity:0.5; -khtml-opacity: 0.5; opacity: 0.5"; // 广告半透明的样式
        $'.$item.'[ad_link] = "/index.php?m=Zbsht&c=Ad&a=ad&act=edit&ad_id=$'.$item.'[ad_id]";
        $'.$item.'[title] = $ad_position[$'.$item.'[pid]][position_name]."===".$'.$item.'[ad_name];
        $'.$item.'[target] = 0;
    }
    ?>';
        $str .=  $this->tpl->parse($content);
        $str .= '<?php endforeach; ?>';
        return $str;
	}

	public function _article($tag,$content){

	}


	/**
	 * sql 语句万能标签
	 * @access public
	 * @param array $tag 标签属性
	 * @param string $content  标签内容
	 * @return string
	 */
	public function _zfuwl($tag,$content){
            $sql = $tag['sql']; // sql 语句
            //  file_put_contents('a.html', $sql.PHP_EOL, FILE_APPEND);
            $sql = str_replace(' eq ', ' = ', $sql); // 等于
            $sql = str_replace(' neq  ', ' != ', $sql); // 不等于
            $sql = str_replace(' gt ', ' > ', $sql);// 大于
            $sql = str_replace(' egt ', ' >= ', $sql);// 大于等于
            $sql = str_replace(' lt ', ' < ', $sql);// 小于
            $sql = str_replace(' elt ', ' <= ', $sql);// 小于等于
            //$sql = str_replace(' heq ', ' == ', $sql);// 恒等于
            //$sql = str_replace(' nheq ', ' !== ', $sql);// 不恒等于

           // $sql = str_replace(')', '."', $sql);

            $key  =  !empty($tag['key']) ? $tag['key'] : 'key';// 返回的变量key
            $item  =  !empty($tag['item']) ? $tag['item'] : 'item';// 返回的变量item
            $result_name  =  !empty($tag['result_name']) ? $tag['result_name'] : 'result_name';// 返回的变量key

            //$Model = new \Think\Model();
            //$name = 'sql_result_'.$item.rand(10000000,99999999); // 数据库结果集返回命名
            $name = 'sql_result_'.$item;//.rand(10000000,99999999); // 数据库结果集返回命名
            //$this->tpl->tVar[$name] = $Model->query($sql); // 变量存储到模板里面去
            $parseStr   =   '<?php

                                $md5_key = md5("'.$sql.'");
                                $'.$name.' = S("sql_".$md5_key);
                                if(empty($'.$name.'))
                                {
                                    $Model = new \Think\Model();
                                    $'.$result_name.' = $'.$name.' = $Model->query("'.$sql.'");
                                    S("sql_".$md5_key,$'.$name.',ZFUWL_CACHE_TIME);
                                }
                             ';
            $parseStr  .=   ' foreach($'.$name.' as $'.$key.'=>$'.$item.'): ?>';
            $parseStr  .=   $this->tpl->parse($content).$tag['level'];
            $parseStr  .=   '<?php endforeach; ?>';

            if(!empty($parseStr)) {
                return $parseStr;
            }
            return ;
    }
}
