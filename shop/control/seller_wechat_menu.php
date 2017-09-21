<?php
/**
 * 微信管理
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持
 */
defined('In33hao') or exit('Access Invalid!');
define('MAX_LAYER', 2);
class seller_wechat_menuControl extends BaseSellerControl {

     /**
     * 构造方法
     *
     */
    public function __construct() {
        parent::__construct();
    }
	
	 /**
     * 微信接口管理
     *
     */
    public function indexOp() {
	    $account_id = intval($_SESSION['member_id']);
		$model_wechat = Model('wechat');
		$find_data = $model_wechat->where(array('user_id'=>$account_id))->find();
		$model_wxconfig = Model('wxconfig');
		$data_wxconfig = $model_wxconfig->where(array('user_id'=>$account_id))->find();
    	if(empty($find_data))
    	{
		    @header('Location: index.php?act=seller_wechat&op=index');
    	}
    	if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
    	{
    	
    		if(empty($data_wxconfig))
    		{
    			$data=array('appid'=>$_POST['appid'],'appsecret'=>$_POST['appsecret'],'user_id'=>$account_id);
				$model_wxconfig->insert($data);
				$insert_id = $model_wxconfig->getLastID();
				if($insert_id > 0)
    			{
				    showMessage("添加成功！");
					return;
    			}else
    			{
				    showMessage('添加失败！');
    				return;
    			}
    		}
			else
    		{
    			$data=array('appid'=>$_POST['appid'],'appsecret'=>$_POST['appsecret']);
				$edit_id = $model_wxconfig->where(array('user_id'=>$account_id))->update($data);
				if($edit_id)
    			{
				    showMessage("修改成功！");
					return;
    			}else
    			{
				    showMessage('修改失败！');
    				return;
    			}
    		}
    	}
    	
        
		//取得商品分类 
		$model_menu = Model('wechat_menu');
		$gcategories_list = array();
		$gcategories_list = $model_menu->where(array('store_id'=>$account_id))->select();
		
        //$gcategories_list = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."wechat_menu WHERE store_id = ".$account_id." ORDER BY sort_order, cate_id");
		if($gcategories_list){
			foreach($gcategories_list as $gacte){
				$gcategories[$gacte['cate_id']] = $gacte;
			}
			$tree = & $this->_tree($gcategories);
			
			//先根排序
			$sorted_gcategories = array();
			$cate_ids = $tree->getChilds();
			foreach ($cate_ids as $id)
			{
				$sorted_gcategories[] = array_merge($gcategories[$id], array('layer' => $tree->getLayer($id)));
			}
		}
		
		Tpl::output('gcategories',$sorted_gcategories);
        //$GLOBALS['tmpl']->assign('gcategories', $sorted_gcategories);
		
		 //构造映射表（每个结点的父结点对应的行，从1开始）
        $row = array(0 => 0); // cate_id对应的row
        $map = array(); // parent_id对应的row
		if($sorted_gcategories)
		{
		    foreach ($sorted_gcategories as $key => $gcategory)
			{
				$row[$gcategory['cate_id']] = $key + 1;
				$map[] = $row[$gcategory['parent_id']];
			}
		}
		Tpl::output('map',json_encode($map));

        Tpl::output('info',$data_wxconfig);
        Tpl::output('account_id', $account_id);
		Tpl::output('page_title', $page_title);
		$this->profile_menu('wechat_menu');
        Tpl::showpage('wechat_menu');
	}
	
	function addOp()
    {
        if(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')
        { 
		    Tpl::setLayout('null_layout');
            $pid = empty($_GET['pid']) ? 0 : intval($_GET['pid']);
            $gcategory = array('parent_id' => $pid, 'if_show' => 1);//'sort_order' => 255,
            header('Content-Type:text/html;charset=utf-8');
			
			Tpl::output('op','add');
			Tpl::output('gcategory',$gcategory);
			Tpl::output('parents', $this->_get_options());
		    Tpl::showpage('wechat_menu_form');
        }
        else
        {
			$account_id = intval($_SESSION['member_id']);
			$model_wechat = Model('wechat');
		    $find_data = $model_wechat->where(array('user_id'=>$account_id))->find();
    	
            $data = array(
                'cate_name'  => $_POST['cate_name'],
                'parent_id'  => $_POST['parent_id'],
                'sort_order' => $_POST['sort_order'],
                'if_show'    => $_POST['if_show'],
                'type'    => $_POST['type'],
                'keyvalue'    => $_POST['keyvalue'],
                'keyword'    => $_POST['keyword'],
                'token'=>$find_data['token'],
				'store_id' => $account_id,
            );

            /* 保存 */
			$model_menu = Model('wechat_menu');
			$model_menu->insert($data);
			$cate_id = $model_menu->getLastID();

            if (!$cate_id)
            {
			    showMessage('新增失败！');
    			return;
            }

            $this->pop_warning('ok', 'my_category_add');
        }
    }
	
	function editOp()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if(strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')
        {
		    Tpl::setLayout('null_layout');
			
            /* 是否存在 */
			$model_menu = Model('wechat_menu');
		    $gcategory = $model_menu->where(array('cate_id'=>$id))->find();
            if (!$gcategory)
            {
                echo "分类不存在！";
                return;
            }
			
			Tpl::output('op','edit');
			Tpl::output('id',$id);
			Tpl::output('gcategory',$gcategory);
			Tpl::output('parents',$this->_get_options($id));
			
            //$GLOBALS['tmpl']->assign('gcategory', $gcategory);
            //$GLOBALS['tmpl']->assign('parents', $this->_get_options($id)); 
            header("Content-Type:text/html;charset=utf-8");
			Tpl::showpage('wechat_menu_form');
            //$GLOBALS['tmpl']->display('biz/wechat_menu.form.html');
        }
        else
        {
            $data = array(
                'cate_name'  => $_POST['cate_name'],
                'parent_id'  => $_POST['parent_id'],
                'sort_order' => $_POST['sort_order'],
                'if_show' => $_POST['if_show'],
                'type' => $_POST['type'],
                'keyvalue' => $_POST['keyvalue'],
               'keyword'=> $_POST['keyword'],
            );

            /* 保存 */
            //$rows = $this->_gcategory_mod->edit($id, $data);
			//if ($this->_gcategory_mod->has_error())
			$model_menu = Model('wechat_menu');
			$edit_id = $model_menu->where(array('cate_id'=>$id))->update($data);
			//if(!$GLOBALS['db']->autoExecute(DB_PREFIX."wechat_menu",$data,"UPDATE","cate_id=".$id))
            if(!$edit_id)
			{
                $this->pop_warning("修改失败！");
                return;
            }
          
            $this->pop_warning('ok','my_category_edit');
        }
    }
	
	function dropOp()
    {
        $id = isset($_GET['id']) ? trim($_GET['id']) : '';
        if (!$id)
        {
		    showMessage('分类不存在！');
    		return;
        }

        $ids = explode(',', $id);
		$model_menu = Model('wechat_menu');
		$model_menu->where(array('cate_id' => array('in', $ids)))->delete();
		//$GLOBALS['db']->getRow("DELETE FROM ".DB_PREFIX."wechat_menu WHERE cate_id IN (".implode(',',$ids).")");
		//if (!$this->_gcategory_mod->drop($ids))
        /*if(!$res_id)
        {
		    showErr('删除失败！');
            return;
        }*/

		showMessage("删除成功！");
    }
	
	function creat_menuOp()
    {
		$account_id = intval($_SESSION['member_id']);
		$model_wxconfig = Model('wxconfig');
		$wechat = $model_wxconfig->where(array('user_id'=>$account_id))->find();
		//$wechat = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."wxconfig WHERE user_id = ".$account_id);
		
   	    //$userid=$this->visitor->get('user_id');
    	//$model_wxconfig=& m('wxconfig');
    	//$wechat = $model_wxconfig->get("user_id =".$userid);

		if(!is_array($wechat))
		{
		    showMessage('请先添加微信APPID和微信AppSecret!');
    		return;
		}
		else 
		{
			if($wechat['appid']==''||$wechat['appsecret']=='')
			{
			    showMessage('微信APPID或微信AppSecret不能为空!');
				return;
			}
		}
		
		$ACCESS_LIST=$this->curl($wechat['appid'],$wechat['appsecret']);
		if($ACCESS_LIST['access_token']!='')
		{
			$access_token=$ACCESS_LIST['access_token'];//获取到ACCESS_TOKEN
			$data=$this->getmenu();
			//var_dump(preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $data));exit;
			$msg=$this->curl_menu($access_token,preg_replace("#\\\u([0-9a-f]{4}+)#ie", "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))", $data));
			if($msg['errmsg']=='ok')
			{
				showMessage('创建自定义菜单成功');return ;
			}
			else 
			{
				$wechat_error= $this->wechat_error($msg['errcode']);
				showMessage('创建自定义菜单失败!'.$wechat_error);
			}
		}
		else 
		{
		    showMessage('创建失败,微信AppId或微信AppSecret填写错误!');
		}
    }
	
	public function delete_menuOp()
    {
	    $account_id = intval($_SESSION['member_id']);
		$model_wxconfig = Model('wxconfig');
		$wechat = $model_wxconfig->where(array('user_id'=>$account_id))->find();
		//$wechat = $GLOBALS['db']->getRow("SELECT * FROM ".DB_PREFIX."wxconfig WHERE user_id = ".$account_id);
   	    //$userid=$this->visitor->get('user_id');
    	//$model_wxconfig=& m('wxconfig');
    	//$wechat = $model_wxconfig->get("user_id =".$userid);
		if(!is_array($wechat))
		{
		    showMessage('请先添加微信APPID和微信AppSecret!');
    		return;
		}
		else 
		{
			if($wechat['appid']==''||$wechat['appsecret']=='')
			{
			    showMessage('微信APPID或微信AppSecret不能为空!');
				return;
			}
		}
		
		$ACCESS_LIST=$this->curl($wechat['appid'],$wechat['appsecret']);
		if($ACCESS_LIST['access_token']!='')
		{
			$access_token=$ACCESS_LIST['access_token'];//获取到ACCESS_TOKEN
			$msg=$this->curl_delete($access_token);
			if($msg['errmsg']=='ok')
			{
				showMessage('删除自定义菜单成功!');return;
			}
			else
			{ 
				showMessage('删除自定义菜单失败!');return;
			}
		}
		else 
		{
		    showMessage('微信AppId或微信AppSecret填写错误!');return;
		}
    }
	
	public function curl_delete($ACCESS_TOKEN)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACCESS_TOKEN);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    	//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$tmpInfo = curl_exec($ch);
    	if (curl_errno($ch)) {
    		echo 'Errno'.curl_error($ch);
    	}
    	curl_close($ch);
    	$arr = json_decode($tmpInfo,true);
    	return $arr;
    }
	
	public function curl($appid,$secret)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$tmpInfo = curl_exec($ch);
    	if (curl_errno($ch)) {
    		echo 'Errno'.curl_error($ch);
    	}
    	curl_close($ch);
    	$arr = json_decode($tmpInfo,true);
    	return $arr;
    }
	
	public function getmenu()
   {
        $account_id = intval($_SESSION['member_id']);
       	$keyword = array();
		$model_menu = Model('wechat_menu');
		$topmemu = $model_menu->where(array('store_id'=>$account_id,'if_show'=>1,'parent_id'=>0))->select();
		//$topmemu = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."wechat_menu WHERE store_id =".$account_id." AND if_show=1 AND parent_id=0 ORDER BY sort_order asc,cate_id asc");
		//$model_custom=& m('wechat_menu');
       	//$userid=$this->visitor->get('user_id');
       	/*$topmemu = $model_custom->find(array(
                'conditions' => "store_id =".$userid." AND if_show=1 AND parent_id=0",
                'order'=>'sort_order asc,cate_id asc',
                ));
        */        
       /*	echo "<pre>";
       print_r($topmemu);
       	echo "</pre>";
       	exit;*/
    	foreach ($topmemu as $key )
    	{
		    $nextmenu = $model_menu->where(array('store_id'=>$account_id,'if_show'=>1,'parent_id'=>$key['cate_id']))->select(); 
		    //$nextmenu = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."wechat_menu WHERE store_id =".$account_id." AND if_show=1 AND parent_id=".$key['cate_id']." ORDER BY sort_order asc,cate_id asc");
    		/*$nextmenu = $model_custom->find(array(
                'conditions' => "store_id =".$userid." AND if_show=1 AND parent_id=".$key['cate_id'],
                'order'=>'sort_order asc,cate_id asc',
            ));*/
    		//$nextmenu=M('wechat_menu')->where(array('token'=>session('token'),'pid'=>$key['id']))->order('sort asc')->select();
    		if(count($nextmenu)!=0)//没有下级栏目
    		{
                 foreach ($nextmenu as $key2)
                 {
                 	if($key2['type']==1)
                 	{
                 	    $kk[]=array('type'=>'view','name'=>$key2['cate_name'],'url'=>$key2['keyvalue']);
                 	}
					else 
                 	{
                 		$kk[]=array('type'=>'click','name'=>$key2['cate_name'],'key'=>$key2['keyvalue']);
                 	}
                 }
                 $keyword['button'][]=array('name'=>$key['cate_name'],'sub_button'=>$kk);
                 $kk='';
    		}
			else
    		{
    			if($key['type']==1)
    			{
    			    $keyword['button'][]=array('type'=>'view','name'=>$key['cate_name'],'url'=>$key['keyvalue']);
    			}
				else 
    			{
    				$keyword['button'][]=array('type'=>'click','name'=>$key['cate_name'],'key'=>$key['keyvalue']);
    			}
    		}
    	}
        return	json_encode($keyword);
    } 
	
	public function curl_menu($ACCESS_TOKEN,$data)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACCESS_TOKEN);
    	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$tmpInfo = curl_exec($ch);
    	if (curl_errno($ch)) {
    		echo 'Errno'.curl_error($ch);
    	}
    	curl_close($ch);
    	$arr = json_decode($tmpInfo,true);
    	return $arr;
    }
	
	public function wechat_error($error)
	{
	  	$wechat_error= array('-1'=>'系统繁忙',
	  	  '0'=>'请求成功',
	  	  '40001'=>'验证失败',
	  	  '40002'=>'不合法的凭证类型',
	  	  '40003'=>'不合法的OpenID',
	  	  '40013'=>'不合法的APPID',
	  	  '40014'=>'不合法的access_token',
	  	  '40015'=>'不合法的菜单类型',
	  	  '40016'=>'不合法的按钮个数',
	  	  '40017'=>'不合法的按钮个数',
	  	  '40018'=>'不合法的按钮名字长度',
	  	  '40019'=>'不合法的按钮KEY长度',
	  	  '40020'=>'不合法的按钮URL长度',
	  	  '40021'=>'不合法的菜单版本号',
	  	  '40022'=>'不合法的子菜单级数',
	  	  '40023'=>'不合法的子菜单按钮个数',
	  	  '40024'=>'不合法的子菜单按钮类型',
	  	  '40025'=>'不合法的子菜单按钮名字长度',
	  	  '40026'=>'不合法的子菜单按钮KEY长度',
	  	  '40027'=>'不合法的子菜单按钮URL长度',
	  	  '40028'=>'不合法的自定义菜单使用用户',
	  	  '41001'=>'缺少access_token参数',
	  	  '41002'=>'缺少appid参数',
	  	  '41003'=>'缺少refresh_token参数',
	  	  '41004'=>'缺少secret参数',
	  	  '41005'=>'缺少多媒体文件数据',
	  	  '41006'=>'缺少media_id参数',
	  	  '41007'=>'缺少子菜单数据',
	  	  '42001'=>'access_token超时',
	  	  '43001'=>'需要GET请求',
	  	  '43002'=>'需要POST请求',
	  	  '43003'=>'需要HTTPS请求',
	  	  '45010'=>'创建菜单个数超过限制',
	  	  '46002'=>'不存在的菜单版本',
	  	  '46003'=>'不存在的菜单数据',
	  	  '47001'=>'解析JSON/XML内容错误',

	  	);
	  	return $wechat_error[$error];
	}
	
	function pop_warning ($msg, $dialog_id = '',$url = '')
    {
        if($msg == 'ok')
        {
            /*if(empty($dialog_id))
            {
                $dialog_id = APP . '_' . ACT;
            }*/
            if (!empty($url))
            {
                echo "<script type='text/javascript'>window.parent.location.href='".$url."';</script>";
            }
            echo "<script type='text/javascript'>window.parent.js_success('" . $dialog_id ."');</script>";
        }
        else
        {
            header("Content-Type:text/html;charset=utf-8");
            $msg = is_array($msg) ? $msg : array(array('msg' => $msg));
            $errors = '';
            foreach ($msg as $k => $v)
            {
                //$error = $v[obj] ? Lang::get($v[msg]) . " [" . Lang::get($v[obj]) . "]" : Lang::get($v[msg]);
			    //$error = $v[obj] ? $GLOBALS['lang'][$v[msg]] .  " [" . $GLOBALS['lang'][$v[obj]] . "]" : $GLOBALS['lang'][$v[msg]];
                //$errors .= $errors ? "<br />" . $error : $error;
            }
            echo "<script type='text/javascript'>window.parent.js_fail('" . $errors . "');</script>";
        }
    }
	
	
	/* 取得可以作为上级的商品分类数据 */
    function _get_options($except = NULL)
    {
	    //$s_account_info = es_session::get("account_info");
		//$account_id = intval($s_account_info['id']);
		$account_id = intval($_SESSION['member_id']);
		$model_menu = Model('wechat_menu');
		$gcategories = $model_menu->where(array('store_id'=>$account_id))->select();
        //$gcategories = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."wechat_menu WHERE store_id = ".$account_id." ORDER BY sort_order, cate_id");
        $tree = & $this->_tree($gcategories);
		//print_r($tree->getOptions(MAX_LAYER - 1, 0, $except));exit;
        return $tree->getOptions(MAX_LAYER - 1, 0, $except);
    }
	
	/* 构造并返回树 */
    function &_tree($gcategories)
    {
	    include(BASE_CORE_PATH.'/tree.lib.php');
        $trees = new Tree();
        $trees->setTree($gcategories, 'cate_id', 'parent_id', 'cate_name');
        return $trees;
    }
	/**
     * 用户中心右边，小导航
     *
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key = '') {
        $menu_array = array();
        $menu_array[] = array(
            'menu_key' => 'wechat_menu',
            'menu_name' => '自定义菜单',
            'menu_url' => urlShop('seller_wechat_menu', 'index')
        );
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }
}