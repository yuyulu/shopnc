<?php
/**
 * 邀请返利页面 
 * by 好商城V5 ww w.33 ha o.c om 
 */
defined('In33hao') or exit('Access Invalid!');
class inviteControl extends BaseHomeControl{
	public function indexOp(){
		$myurl=$this->maker_qrcodeOp($_SESSION['member_id']);
		Tpl::output('myurl', $myurl);
		
		$mydownurl=BASE_SITE_URL."/index.php?act=invite&op=downqrfile&id=".$_SESSION['member_id'];
		Tpl::output('mydownurl', $mydownurl);
		Tpl::showpage('invite');
	}
	
	public function maker_qrcodeOp($id)
	{
		$id=intval($id);
		if($id<=0)
		{
			$id = intval($_GET['id']);
			
		}
		if($id<=0)
		{
		   return UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.'default_qrcode.png';
		}
		
		$str_member="memberqr_".$id;
		$imgfile=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member . '.png';
		if(!file_exists($imgfile)){	
			$member_id = base64_encode(intval($id)*1);
			$myurl=BASE_SITE_URL."/#V5".$member_id;
			require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
			$PhpQRCode = new PhpQRCode();
			
			$PhpQRCode->set('pngTempDir',BASE_UPLOAD_PATH.DS."shop".DS."member".DS);
			$PhpQRCode->set('date',$myurl);
			$PhpQRCode->set('pngTempName', $str_member . '.png');
			$PhpQRCode->init();
		}
		return UPLOAD_SITE_URL.DS."shop".DS."member".DS.$str_member.'.png';
		
	}
	
	public function downqrfileOp()
   {
	   
	 $id=$_GET['id'];
	 if($id<=0)die('请先登录会员后，再来这里操作哦。');
	 $str_member="memberqr_".$id;
	 $fileurl=BASE_UPLOAD_PATH.DS."shop".DS."member".DS.$str_member.".png";
	 
	 
	 
	 ob_start(); 
	 $filename=$fileurl;
	 $date=date("Ymd-H:i:m");
	 header( "Content-type:  application/octet-stream "); 
	 header( "Accept-Ranges:  bytes "); 
	 header( "Content-Disposition:  attachment;  filename= {$str_member}.png"); 
	 $size=readfile($filename); 
	 header( "Accept-Length: " .$size);
   }

	
}
