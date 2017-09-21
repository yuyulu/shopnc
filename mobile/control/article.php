<?php
/**
 * 文章 
 * @好商城b1 (c) 2005-2016 33hao Inc.
 * @license    http://www.33hao.com
 * @link       交流群号：216611541
 * @since      好商城提供技术支持 授权请购买shopnc授权
 * 
 **/

defined('In33hao') or exit('Access Invalid!');
class articleControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 文章列表
     */
    public function article_listOp() {
        if(!empty($_GET['ac_id']) && intval($_GET['ac_id']) > 0) {
			$article_class_model	= Model('article_class');
			$article_model	= Model('article');
			$condition	= array();
			
			$child_class_list = $article_class_model->getChildClass(intval($_GET['ac_id']));
			$ac_ids	= array();
			if(!empty($child_class_list) && is_array($child_class_list)){
				foreach ($child_class_list as $v){
					$ac_ids[]	= $v['ac_id'];
				}
			}
			$ac_ids	= implode(',',$ac_ids);
			$condition['ac_ids']	= $ac_ids;
			$condition['article_show']	= '1';
			$article_list = $article_model->getArticleList($condition);			
			$article_type_name = $this->article_type_name($ac_ids);
			output_data(array('article_list' => $article_list, 'article_type_name'=> $article_type_name));		
		}
		else {
			output_error('缺少参数:文章类别编号');
		}    	
    }

    /**
     * 根据类别编号获取文章类别信息
     */
    private function article_type_name() {
    	if(!empty($_GET['ac_id']) && intval($_GET['ac_id']) > 0) {
			$article_class_model = Model('article_class');
			$article_class = $article_class_model->getOneClass(intval($_GET['ac_id']));
			return ($article_class['ac_name']);
		}
		else {
			return ('缺少参数:文章类别编号');			
		}    	
    }
    
    /**
     * 单篇文章显示
     */
    public function article_showOp() {
		$article_model	= Model('article');

        if(!empty($_GET['article_id']) && intval($_GET['article_id']) > 0) {
			$article	= $article_model->getOneArticle(intval($_GET['article_id']));

			if (empty($article)) {
				output_error('文章不存在');
			}
			else {
				output_data($article);
			}
        } 
        else {
			output_error('缺少参数:文章编号');
        }
    }
}
