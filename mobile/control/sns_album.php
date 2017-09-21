<?php
/**
 * 相册
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class sns_albumControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 上传图片
     *
     * @param
     * @return
     */
    public function file_uploadOp() {
        /**
         * 读取语言包
         */
        Language::read('sns_home');
        $lang   = Language::getLangContent();
        $member_id  = $this->member_info['member_id'];
        
        $model = Model();
        // 验证图片数量
        $count = $model->table('sns_albumpic')->where(array('member_id'=>$member_id))->count();
        if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
            output_error('已经超出允许上传图片数量，不能在上传图片！');
        }
    
        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload_dir = ATTACH_MALBUM.DS.$member_id.DS;
    
        $upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
        $thumb_width    = '240,1024';
        $thumb_height   = '2048,1024';
    
        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('thumb_width', $thumb_width);
        $upload->set('thumb_height',$thumb_height);
        $upload->set('fprefix',$member_id);
        $upload->set('thumb_ext', '_240,_1024');
        $result = $upload->upfile('file');
        if (!$result){
            output_error($upload->error);
        }
    
        $img_path = $upload->getSysSetPath().$upload->file_name;
        list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.$member_id.DS.$img_path);
    
        $image = explode('.', $_FILES["file"]["name"]);

        $model_sns_alumb = Model('sns_album');
        $ac_id = $model_sns_alumb->getSnsAlbumClassDefault($member_id);
        $insert = array();
        $insert['ap_name']      = $image['0'];
        $insert['ac_id']        = $ac_id;
        $insert['ap_cover']     = $img_path;
        $insert['ap_size']      = intval($_FILES['file']['size']);
        $insert['ap_spec']      = $width.'x'.$height;
        $insert['upload_time']  = time();
        $insert['member_id']    = $member_id;
        $result = $model->table('sns_albumpic')->insert($insert);
    
        $data = array();
        $data['file_id'] = $result;
        $data['file_name'] = $img_path;
        $data['origin_file_name'] = $_FILES["file"]["name"];
        $data['file_url'] = snsThumb($img_path, 240);
        output_data($data);
    }

}
