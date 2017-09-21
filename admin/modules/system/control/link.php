<?php
/**
 * 合作伙伴管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class linkControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('link');
	}
	    public function indexOp() {
        $this->linkOp();
    }
	/**
	 * 合作伙伴
	 */
	public function linkOp(){
		$lang	= Language::getLangContent();
		$model_link = Model('link');
		/**
		 * 删除
		 */
		if ($_POST['form_submit'] == 'ok'){
			if (is_array($_POST['del_id']) && !empty($_POST['del_id'])){
				foreach ($_POST['del_id'] as $k => $v){
					/**
					 * 删除图片
					 */
					$v = intval($v);
					$tmp = $model_link->getOneLink($v);
					if (!empty($tmp['link_pic'])){
						@unlink(BasePath.DS.ATTACH_LINK.DS.$tmp['link_pic']);
					}
					unset($tmp);
					$model_link->del($v);
				}
				dkcache('link',null);
				showMessage($lang['link_index_del_succ']);
			}else {
				showMessage($lang['link_index_choose_del']);
			}
		}
		
		/**
		 * 检索条件
		 */
		$condition['like_link_title'] = $_GET['search_link_title'];
		$condition['order'] = 'link_sort asc';
		Tpl::output('search_link_title',$_GET['search_link_title']);
		/**
		 * 分页
		 */
		$page	= new Page();
		$page->setEachNum(10);
		$page->setStyle('admin');
		if ($_GET['type'] == '0'){
			$condition['link_pic'] = 'yes';
		}
		if ($_GET['type'] == '1'){
			$condition['link_pic'] = 'no';
		}
		$link_list = $model_link->getLinkList($condition,$page);
		/**
		 * 整理图片链接
		 */
		if (is_array($link_list)){
			foreach ($link_list as $k => $v){
				if (!empty($v['link_pic'])){
					$link_list[$k]['link_pic'] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common/'.DS.$v['link_pic'];
				}
			}
		}
		
		Tpl::output('link_list',$link_list);
		Tpl::output('page',$page->show());
		Tpl::setDirquna('system');
		Tpl::showpage('link.index');
	}
	
	/**
	 * 合作伙伴删除
	 */
	public function link_delOp(){
		$lang	= Language::getLangContent();
		if (intval($_GET['link_id']) > 0){
			$model_link = Model('link');
			/**
			 * 删除图片
			 */
			$tmp = $model_link->getOneLink(intval($_GET['link_id']));
			if (!empty($tmp['link_pic'])){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$tmp['link_pic']);
			}
			$model_link->del($tmp['link_id']);
			dkcache('link',null);
			showMessage(删除成功,'index.php?act=link&op=link');
		}else {
			showMessage(删除失败,'index.php?act=link&op=link');
		}
	}
	
	/**
	 * 合作伙伴 添加
	 */
	public function link_addOp(){
		$lang	= Language::getLangContent();
		$model_link = Model('link');
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>$lang['link_add_title_null']),
				//array("input"=>$_POST["link_url"], "require"=>"true", 'validator'=>'Url', "message"=>$lang['link_add_url_wrong']),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['link_add_sort_int']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				/**
				 * 上传图片
				 */
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_COMMON);
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showMessage($upload->error);
					}
				}
				
				$insert_array = array();
				$insert_array['link_title'] = trim($_POST['link_title']);
				$insert_array['link_url'] = trim($_POST['link_url']);
				$insert_array['link_pic'] = trim($_POST['link_pic']);
				$insert_array['link_sort'] = trim($_POST['link_sort']);
				
				$result = $model_link->add($insert_array);
				if ($result){
					dkcache('link',null);
					$url = array(
						array(
							'url'=>'index.php?act=link&op=link_add',
							'msg'=>再次发布,
						),
						array(
							'url'=>'index.php?act=link&op=link',
							'msg'=>返回列表,
						)
					);
					showMessage(添加成功,$url);
				}else {
					showMessage(添加失败);
				}
			}
		}
		Tpl::setDirquna('system');
		Tpl::showpage('link.add');
	}
	
	/**
	 * 合作伙伴 编辑
	 */
	public function link_editOp(){
		$lang	= Language::getLangContent();
		$model_link = Model('link');
		
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>$lang['link_add_title_null']),
				//array("input"=>$_POST["link_url"], "require"=>"true", 'validator'=>'Url', "message"=>$lang['link_add_url_wrong']),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['link_add_sort_int']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				/**
				 * 上传图片
				 */
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_PATH.'/common');
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showMessage($upload->error);
					}
				}
				
				$update_array = array();
				$update_array['link_id'] = intval($_POST['link_id']);
				$update_array['link_title'] = trim($_POST['link_title']);
				$update_array['link_url'] = trim($_POST['link_url']);
				if ($_POST['link_pic']){
					$update_array['link_pic'] = $_POST['link_pic'];
				}
				$update_array['link_sort'] = trim($_POST['link_sort']);
				
				$result = $model_link->update($update_array);
				if ($result){
					dkcache('link',null);
					/**
					 * 删除图片
					 */
					if (!empty($_POST['link_pic']) && !empty($_POST['old_link_pic'])){
						@unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$_POST['old_link_pic']);
					}
					$url = array(
						array(
							'url'=>'index.php?act=link&op=link_edit&link_id='.intval($_POST['link_id']),
							'msg'=>再次编辑
						),
						array(
							'url'=>'index.php?act=link&op=link',
							'msg'=>返回列表,
						)
					);
					showMessage(编辑完成,$url);
				}else {
					showMessage(编辑失败);
				}
			}
		}
		
		$link_array = $model_link->getOneLink(intval($_GET['link_id']));
		if (empty($link_array)){
			showMessage($lang['wrong_argument']);
		}
		
		Tpl::output('link_array',$link_array);
		Tpl::setDirquna('system');
		Tpl::showpage('link.edit');
	}
	

	
	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 合作伙伴 排序
			 */
			case 'link_sort':
				$model_link = Model('link');
				$update_array = array();
				$update_array['link_id'] = intval($_GET['id']);
				$update_array['link_sort'] = trim($_GET['value']);
				$result = $model_link->update($update_array);
				dkcache('link',null);
				echo 'true';exit;
				break;
		}
	}
}