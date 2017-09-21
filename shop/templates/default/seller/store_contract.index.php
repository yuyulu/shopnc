<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
    <div style="width:100%;">
        <?php if ($output['item_list']) {?>
            <?php foreach($output['item_list'] as $k=>$v){?>
            <div style="float: left; width: 50%;">
                <div style="border: 1px solid #f5f5f5;background: none repeat scroll 0 0 #f5f5f5; margin: 20px 20px 0px 0px; height: 60px; padding: 30px;">
                    <div style="float: left; width: 60px; height: 60px;">
                    <img style="width: 60px;" src="<?php echo $v['cti_icon_url_60']; ?>" />
                    </div>
                    <div style="float: left; margin-left: 15px; width: 320px;">
                        <div style="height: 35px;">
                            <em style="float:left; margin-bottom: 12px;font-size: 16px;font-weight: 700;"><?php echo $v['cti_name']; ?></em>
                                <?php if($v['ct_auditstate_key'] == 'notaudit' && $v['ct_joinstate_key'] == 'applying' && $v['ct_closestate_key'] == 'open'){//申请未审核?>
                                    <span style="float:right;"><?php echo $v['ct_auditstate_text']; ?></span>
                                <?php } ?>
                                <?php if($v['ct_auditstate_key'] == 'auditpass' && $v['ct_closestate_key'] == 'open' && $v['ct_joinstate_key'] == 'applying'){ //申请通过，待付款?>
                                    <span style="float:right;">审核通过，<a href="index.php?act=store_contract&op=applypay&itemid=<?php echo $v['cti_id']; ?>">点击支付保证金</a></span>
                                <?php } ?>
                                <?php if($v['ct_auditstate_key'] == 'auditfailure' && $v['ct_closestate_key'] == 'open'){//申请未通过?>
                                    <span style="float:right;"><?php echo $v['ct_auditstate_text']; ?>，<a href="index.php?act=store_contract&op=contractlog&itemid=<?php echo $v['cti_id']; ?>">点击查看详情</a></span>
                                <?php } ?>
                                <?php if($v['ct_auditstate_key'] == 'costpay' && $v['ct_closestate_key'] == 'open'){//保证金待审核?>
                                    <span style="float:right;"><?php echo $v['ct_auditstate_text']; ?></span>
                                <?php } ?>
                                <?php if($v['ct_auditstate_key'] == 'costfailure' && $v['ct_closestate_key'] == 'open'){ //保证金审核失败?>
                                    <span style="float:right;"><?php echo $v['ct_auditstate_text']; ?>，<a href="index.php?act=store_contract&op=applypay&itemid=<?php echo $v['cti_id']; ?>">重新提交</a></span>
                                <?php } ?>
                                <?php if($v['ct_state_sign'] == 'added' && $v['ct_quitstate_key'] == 'applying' && $v['ct_closestate_key'] == 'open'){ //显示退出申请审核中 ?>
                                    <span style="float:right;"><?php echo $v['ct_quitstate_text']; ?></span>
                                <?php } ?>
                                <?php if($v['ct_state_sign'] == 'added' && $v['ct_quitstate_key'] == 'applyfailure' && $v['ct_closestate_key'] == 'open'){ //显示退出申请审核失败 ?>
                                    <span style="float:right;"><?php echo $v['ct_quitstate_text']; ?>，<a href="index.php?act=store_contract&op=contractlog&itemid=<?php echo $v['cti_id']; ?>">点击查看详情</a></span>
                                <?php } ?>
                        </div>
                        <div style="text-align: left;">
                            <?php if($v['ct_state_sign'] == 'notapply' || !$v['ct_state_sign']){//未申请服务 ?>
                            <a title="加入" class="ncbtn ncbtn-mint" nc_type="applybtn" data-param='{"itemid":"<?php echo $v['cti_id']; ?>"}'>加入</a>
                            <?php } ?>
                            <?php if($v['ct_state_sign'] == 'applying'){ //服务申请审核中?>
                            <span style="color:#5bb75b;" ><?php echo $v['ct_state_text']; ?></span>
                            <?php } ?>
                            <?php if($v['ct_state_sign'] == 'closed'){ //永久关闭服务?>
                            <span style="color:red;" ><?php echo $v['ct_state_text']; ?></span>
                            <?php } ?>
                            <?php if($v['ct_state_sign'] == 'added'){ //已加入服务?>
                            <span><?php echo $v['ct_state_text']; ?></span>
                            <?php } ?>
                            <?php if($v['ct_state_sign']){//已申请过服务，显示查看详情链接 ?>
                            <a href="index.php?act=store_contract&op=contractlog&itemid=<?php echo $v['cti_id']; ?>" style="margin-left: 30px;">查看服务详情</a>
                            <?php } ?>
                            <?php if($v['ct_state_sign'] == 'added' && in_array($v['ct_quitstate_key'],array('notapply','applyfailure'))){ //已加入服务，并且未申请退出 ?>
                            |&nbsp;<a nc_type="quitbtn" href="javascript:void(0);" data-param='{"itemid":"<?php echo $v['cti_id']; ?>"}'>退出</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div style="width: 100%;">
                    <div style="min-height: 60px; margin-right: 20px; padding: 10px; color: #9c9c9c; border: 1px solid #f5f5f5;">
                        <?php echo $v['cti_describe']; ?>
                        <?php if($v['cti_descurl']){ ?>
                            <a href="<?php echo $v['cti_descurl']; ?>">详细说明 ></a>
                        <?php } ?>
                    </div>

                </div>
            </div>
            <?php } ?>
        <?php }?>
    </div>
</div>
<script>
$(document).ready(function(){
    $("[nc_type='applybtn']").click(function(){
        var data_str = $(this).attr('data-param');
        if(data_str){
            eval( "data_str = "+data_str);
            var itemid = parseInt(data_str.itemid);
            ajaxget('index.php?act=store_contract&op=ctiapply&itemid='+itemid);
        }
    });
    $("[nc_type='quitbtn']").click(function(){
        var data_str = $(this).attr('data-param');
        if(data_str){
            eval( "data_str = "+data_str);
            var itemid = parseInt(data_str.itemid);
            ajaxget('index.php?act=store_contract&op=ctiquit&itemid='+itemid);
        }
    });
});
</script> 
