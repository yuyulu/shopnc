<?php
/**
 * 虚拟抢购管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class vr_groupbuyControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();

        // 检查抢购功能是否开启
        if (C('groupbuy_allow') != 1) {
            showMessage('虚拟抢购功能尚未开启', 'index.php?act=setting', 'html', 'error');
        }
    }

    public function indexOp()
    {
        $this->class_listOp();
    }

    /*
     * 列表分类
     */
    public function class_listOp()
    {
        $model_vr_groupbuy_class = Model('vr_groupbuy_class');
        $list = $model_vr_groupbuy_class->getVrGroupbuyClassList();
        Tpl::output('list', $list);
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_groupbuy.class_list');
    }

    /*
     * 添加分类
     */
    public function class_addOp()
    {
        if (chksubmit()) { //添加虚拟抢购分类
            // 数据验证
            $obj_validate = new Validate();
            $validate_array = array(
                array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('groupbuy_class_name_is_not_null')),
                array('input'=>$_POST['class_name'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('groupbuy_class_sort_is_not_null')),
            );
            $obj_validate->validateparam = $validate_array;
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(Language::get('error').$error, '', '', 'error');
            }

            $params = array();
            $params['class_name'] = trim($_POST['class_name']);
            $params['class_sort'] = intval($_POST['class_sort']);
            if (isset($_POST['parent_class_id']) && intval($_POST['parent_class_id']) > 0) {
                $params['parent_class_id'] = $_POST['parent_class_id'];
            } else {
                $params['parent_class_id'] = 0;
            }

            $model_vr_groupbuy_class = Model('vr_groupbuy_class');
            $res = $model_vr_groupbuy_class->addVrGroupbuyClass($params); //添加分类
            if ($res) {
                // 删除虚拟抢购分类缓存
                Model('groupbuy')->dropCachedData('groupbuy_vr_classes');

                $this->log('添加虚拟抢购分类[ID:'.$res.']', 1);

                $url = array(
                    array(
                        'url'=>'index.php?act=vr_groupbuy&op=class_add&parent_class_id='.$params['parent_class_id'],
                        'msg'=>'继续添加',
                    ),
                    array(
                        'url'=>'index.php?act=vr_groupbuy&op=class_list',
                        'msg'=>'返回列表',
                    )
                );
                showMessage('添加成功', $url);
            } else {
                showMessage('添加失败', 'index.php?act=vr_groupbuy&op=class_list', '', 'error');
            }
        }

        $model_vr_groupbuy_class = Model('vr_groupbuy_class'); //一级分类
        $list = $model_vr_groupbuy_class->getVrGroupbuyClassList(array('parent_class_id'=>0));
        Tpl::output('list', $list);

        Tpl::output('parent_class_id', isset($_GET['parent_class_id']) ? intval($_GET['parent_class_id']) : 0);
		Tpl::setDirquna('shop');
        Tpl::showpage('vr_groupbuy.class_add');
    }

    /*
     * 编辑分类
     */
    public function class_editOp()
    {
        if (chksubmit()) {
            // 数据验证
            $obj_validate = new Validate();
            $validate_array = array(
                array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('groupbuy_class_name_is_not_null')),
                array('input'=>$_POST['class_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('groupbuy_class_sort_is_not_null')),
            );
            $obj_validate->validateparam = $validate_array;
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(Language::get('error').$error, '', '', 'error');
            }

            $params = array();
            $params['class_name'] = trim($_POST['class_name']);
            $params['class_sort'] = intval($_POST['class_sort']);
            if (isset($_POST['parent_class_id']) && intval($_POST['parent_class_id']) > 0) {
                $params['parent_class_id'] = $_POST['parent_class_id'];
            } else {
                $params['parent_class_id'] = 0;
            }

            $condition  = array(); //条件
            $condition['class_id'] = intval($_POST['class_id']);

            $model_vr_groupbuy_class = Model('vr_groupbuy_class');
            $res = $model_vr_groupbuy_class->editVrGroupbuyClass($condition,$params);

            if ($res) {
                // 删除虚拟抢购分类缓存
                Model('groupbuy')->dropCachedData('groupbuy_vr_classes');

                $this->log('编辑虚拟抢购分类[ID:'.intval($_POST['class_id']).']', 1);
                showMessage('编辑成功', 'index.php?act=vr_groupbuy&op=class_list', '', 'succ');
            } else {
                showMessage('编辑失败', 'index.php?act=vr_groupbuy&op=class_list', '', 'error');
            }
        }

        $model_vr_groupbuy_class = Model('vr_groupbuy_class'); //分类信息
        $class = $model_vr_groupbuy_class->getVrGroupbuyClassInfo(array('class_id'=>intval($_GET['class_id'])));
        if (empty($class)) {
            showMessage('该分类不存在', '', '', 'error');
        }
        Tpl::output('class', $class);


        $list = $model_vr_groupbuy_class->getVrGroupbuyClassList(array('parent_class_id'=>0));
        Tpl::output('list', $list);
		Tpl::setDirquna('shop');

        Tpl::showpage('vr_groupbuy.class_edit');
    }

    /*
     * 删除分类
     */
    public function class_delOp()
    {
        if (chksubmit()) {
            $classidArr = explode(",", $_POST['class_id']);
            if (!empty($classidArr)) {
                $model = Model();
                foreach ($classidArr as $val) {
                    $class = $model->table('vr_groupbuy_class')->where(array('class_id'=>$val))->find();
                    if ($class['parent_class_id'] == 0) {
                        $model->table('vr_groupbuy_class')->where(array('parent_class_id'=>$class['class_id']))->delete();
                    }
                    $model->table('vr_groupbuy_class')->where(array('class_id'=>$val))->delete();
                }
            }
        }

        // 删除虚拟抢购分类缓存
        Model('groupbuy')->dropCachedData('groupbuy_vr_classes');

        $this->log('删除虚拟抢购分类[ID:'.$_POST['class_id'].']', 1);
        showMessage('删除成功', 'index.php?act=vr_groupbuy&op=class_list', '', 'succ');
    }

    public function update_class_sortOp()
    {
        $err = null;

        do {
            $id = (int) $_GET['id'];
            if ($id < 0) {
                $err = '参数错误';
                break;
            }

            $value = (int) $_GET['value'];
            if ($value < 0 || $value > 255) {
                $err = '请输入0~255的整数';
                break;
            }

            $r = Model('vr_groupbuy_class')->editVrGroupbuyClass(array(
                'class_id' => $id,
            ), array(
                'class_sort' => $value,
            ));

            if (!$r) {
                $err = '操作失败';
                break;
            }

            // 删除虚拟抢购分类缓存
            Model('groupbuy')->dropCachedData('groupbuy_vr_classes');
            $this->log('编辑虚拟抢购分类[ID:'.$id.']', 1);

            echo json_encode(array(
                'result' => true,
            ));
            exit;

        } while (false);

        echo json_encode(array(
            'result' => false,
            'message' => $err,
        ));
        exit;
    }

    public function update_class_nameOp()
    {
        $err = null;

        do {
            $id = (int) $_GET['id'];
            if ($id < 0) {
                $err = '参数错误';
                break;
            }

            $value = trim($_GET['value']);
            if (!$value || mb_strlen((string) $value, 'utf-8') > 10) {
                $err = '请输入1~10个字符';
                break;
            }

            $r = Model('vr_groupbuy_class')->editVrGroupbuyClass(array(
                'class_id' => $id,
            ), array(
                'class_name' => $value,
            ));

            if (!$r) {
                $err = '操作失败';
                break;
            }

            // 删除虚拟抢购分类缓存
            Model('groupbuy')->dropCachedData('groupbuy_vr_classes');
            $this->log('编辑虚拟抢购分类[ID:'.$id.']', 1);

            echo json_encode(array(
                'result' => true,
            ));
            exit;

        } while (false);

        echo json_encode(array(
            'result' => false,
            'message' => $err,
        ));
        exit;
    }

    public function ajaxOp()
    {
        $field = $_GET['column'];
        $id = $_GET['id'];
        $value = $_GET['value'];

        switch ($_GET['column']) {
            case 'class_name':
                if (mb_strlen((string) $value, 'utf-8') > 10)
                    return;
                break;
            case 'class_sort':
                if ($value < 0 || $value > 255)
                    return;
                break;

            default:
                return;
        }

        switch ($_GET['branch']) {
            case 'class':
                $model_vr_groupbuy_class = Model('vr_groupbuy_class');
                $res = $model_vr_groupbuy_class->editVrGroupbuyClass(array('class_id'=>$id),array($field=>$value));
                if ($res) {
                    // 删除虚拟抢购分类缓存
                    Model('groupbuy')->dropCachedData('groupbuy_vr_classes');

                    $this->log('编辑虚拟抢购分类[ID:'.$id.']', 1);
                    echo 'true';
                } else {
                    echo 'false';
                }
                exit;

            default:
                return;
        }
    }

    /*
     * 区域列表
     */
    public function area_listOp()
    {
        // 城市首字母
        // Tpl::output('letter', $this->letterArr);
		Tpl::setDirquna('shop');
        Tpl::showpage("vr_groupbuy.area_list");
    }

    /*
     * 区域列表XML
     */
    public function area_list_xmlOp()
    {
        $condition = array();
        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'area_name':
                    $condition['area_name'] = array('like', '%' . $q . '%');
                    break;
                case 'first_letter':
                    $condition['first_letter'] = $q;
                    break;
            }
        }

        $condition['parent_area_id'] = 0;

        $model_vr_groupbuy_area = Model('vr_groupbuy_area');
        $list = (array) $model_vr_groupbuy_area->getVrGroupbuyAreaList($condition, '*', $_REQUEST['rp']);

        $data = array();
        $data['now_page'] = $model_vr_groupbuy_area->shownowpage();
        $data['total_num'] = $model_vr_groupbuy_area->gettotalnum();

        foreach ($list as $val) {
            $i = array();

            $i['operation'] = <<<EOB
<a href="javascript:;" onclick="submit_delete({$val['area_id']})" class="btn red"><i class="fa fa-trash-o"></i>删除</a>
<span class="btn">
  <em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em>
  <ul>
    <li><a href="index.php?act=vr_groupbuy&op=area_edit&area_id={$val['area_id']}">编辑区域</a></li>
    <li><a  href="index.php?act=vr_groupbuy&op=area_view&parent_area_id={$val['area_id']}">下级区域</a></li>
  </ul>
</span>
EOB;

            $i['area_name'] = $val['area_name'];
            $i['first_letter'] = $val['first_letter'];
            $i['area_number'] = $val['area_number'];
            $i['post'] = $val['post'];

            if ($val['hot_city'] == '1') {
                $i['hot_city'] = '<span class="yes"><i class="fa fa-check-circle"></i>是</span>';
            } else {
                $i['hot_city'] = '<span class="no"><i class="fa fa-ban"></i>否</span>';
            }

            $i['add_time'] = date("Y-m-d", $val['add_time']);

            $data['list'][$val['area_id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /*
     * 添加区域
     */
    public function area_addOp()
    {
        if (isset($_POST) && !empty($_POST)) {
            // 数据验证
            $obj_validate = new Validate();
            $validate_array = array(
                array('input'=>$_POST['area_name'],'require'=>'true','message'=>'区域名称不能为空'),
                array('input'=>$_POST['first_letter'],'require'=>'true','message'=>'首字母不能为空'),
            );
            $obj_validate->validateparam = $validate_array;
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(Language::get('error').$error, '', '', 'error');
            }

            $params = array(
                'area_name' => trim($_POST['area_name']),
                'parent_area_id'=> isset($_POST['parent_area_id']) && !empty($_POST['parent_area_id']) ? $_POST['parent_area_id'] : 0,
                'add_time' => time(),
                'first_letter' => $_POST['first_letter'],
                'area_number' => trim($_POST['area_number']),
                'post' => trim($_POST['post']),
                'hot_city' => intval($_POST['is_hot'])
            );

            $model_vr_groupbuy_area = Model('vr_groupbuy_area');
            $res = $model_vr_groupbuy_area->addVrGroupbuyArea($params);

            if ($res) {
                // 删除虚拟抢购区域缓存
                Model('groupbuy')->dropCachedData('groupbuy_vr_cities');

                $this->log('添加虚拟抢购区域[ID:'.$res.']',1);
                showMessage('添加成功','index.php?act=vr_groupbuy&op=area_list','','succ');
            } else {
                showMessage('添加失败','index.php?act=vr_groupbuy&op=area_list','','error');
            }
        }

        // 城市首字母
        Tpl::output('letter', $this->letterArr);

        if (isset($_GET['area_id'])) {
            $model_vr_groupbuy_area = Model('vr_groupbuy_area');
            $area = $model_vr_groupbuy_area->getVrGroupbuyAreaInfo(array('area_id'=>intval($_GET['area_id'])));

            Tpl::output('area_name', $area['area_name']);
            Tpl::output('area_id', $area['area_id']);
        } else {
            Tpl::output('area_name', Language::get('area_first_area'));
            Tpl::output('area_id', 0);
        }
		Tpl::setDirquna('shop');
        Tpl::showpage("vr_groupbuy.area_add");
    }

    /*
     * 编辑区域
     */
    public function area_editOp()
    {
        if (isset($_POST) && !empty($_POST)) {
            //数据验证
            $obj_validate = new Validate();
            $validate_array = array(
                array('input'=>$_POST['area_name'],'require'=>'true','message'=>'区域名称不能为空'),
                array('input'=>$_POST['first_letter'],'require'=>'true','message'=>'首字母不能为空'),
            );
            $obj_validate->validateparam = $validate_array;
            $error = $obj_validate->validate();
            if ($error != '') {
                showMessage(Language::get('error').$error,'','','error');
            }

            $params = array(
                'area_name' => trim($_POST['area_name']),
                'add_time' => time(),
                'first_letter' => $_POST['first_letter'],
                'area_number' => trim($_POST['area_number']),
                'post' => trim($_POST['post']),
                'hot_city' => intval($_POST['is_hot'])
            );

            $condition = array();
            $condition['area_id'] = intval($_POST['area_id']);

            $model_vr_groupbuy_area = Model('vr_groupbuy_area');
            $res = $model_vr_groupbuy_area->editVrGroupbuyArea($condition,$params);
            if ($res) {
                // 删除虚拟抢购区域缓存
                Model('groupbuy')->dropCachedData('groupbuy_vr_cities');

                $this->log('编辑虚拟抢购区域[ID:'.intval($_POST['area_id']).']', 1);
                showMessage('编辑成功', 'index.php?act=vr_groupbuy&op=area_list', '', 'succ');
            } else {
                showMessage('编辑失败', 'index.php?act=vr_groupbuy&op=area_list', '', 'error');
            }
        }

        //城市首字母
        Tpl::output('letter', $this->letterArr);

        $model_vr_groupbuy_area = Model('vr_groupbuy_area');

        $model = Model();
        $area = $model->table('vr_groupbuy_area')->where(array('area_id'=>intval($_GET['area_id'])))->find();
        Tpl::output('area',$area);

        $parent_area = $model->table('vr_groupbuy_area')->where(array('area_id'=>$area['parent_area_id']))->find();
        if(!empty($parent_area)){
            Tpl::output('parent_area_name',$parent_area['area_name']);
        }else{
            Tpl::output('parent_area_name',Language::get('area_first_area'));
        }
		Tpl::setDirquna('shop');

        Tpl::showpage("vr_groupbuy.area_edit");
    }

    /*
     * 查看区域
     */
    public function area_viewOp()
    {
        //获取区域信息
        $model = Model();
        $area_list = $model->table('vr_groupbuy_area')
            ->where(array('parent_area_id'=>intval($_GET['parent_area_id'])))
            ->limit(false)
            ->select();

        Tpl::output('list', $area_list);

        $area = $model->table('vr_groupbuy_area')->where(array('area_id'=>intval($_GET['parent_area_id'])))->find();
        Tpl::output('parent_area', $area);
		Tpl::setDirquna('shop');
        Tpl::showpage("vr_groupbuy.area_view");
    }

    /*
     * 查看商区
     */
    public function area_streetOp()
    {
        //获取区域信息
        $model = Model();
        $mall_list = $model->table('vr_groupbuy_area')
            ->where(array('parent_area_id'=>intval($_GET['parent_area_id'])))
            ->limit(false)
            ->select();

        Tpl::output('list', $mall_list);

        $mall = $model->table('vr_groupbuy_area')->where(array('area_id'=>intval($_GET['parent_area_id'])))->find();
        Tpl::output('parent_area', $mall);
		Tpl::setDirquna('shop');

        Tpl::showpage("vr_groupbuy.area_street");
    }

    /*
     * 删除区域
     */
    public function area_dropOp()
    {
        $model = Model();
        $res = $model->table('vr_groupbuy_area')->where(array('area_id'=>array('in', trim($_POST['area_id']))))->delete();

        if ($res) {
            // 删除虚拟抢购区域缓存
            Model('groupbuy')->dropCachedData('groupbuy_vr_cities');

            $this->log('删除虚拟抢购区域[ID:'.intval($_POST['area_id']).']',1);
            showMessage('删除成功','index.php?act=vr_groupbuy&op=area_list','','succ');
        } else {
            showMessage('删除失败','index.php?act=vr_groupbuy&op=area_list','','error');
        }
    }

    protected $letterArr = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    );
}
