<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="ncsc-form-default">
  <form method="post" action="<?php echo empty($output['chain_info']) ? urlShop('store_chain', 'chain_add') : urlShop('store_chain', 'chain_edit', array('chain_id' => $output['chain_info']['chain_id']));?>" id="chain_form" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <h3>门店账户注册</h3>
    <dl>
      <dt><i class="required">*</i>登录名<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w200" name="chain_user" id="chain_user" value="<?php echo $output['chain_info']['chain_user'];?>" />
        <p class="hint">登录名请使用中文、字母、数字、下划线（最低三个字符），注册成功后不可以修改。</p>
      </dd>
    </dl>
    <dl>
      <dt>
        <?php if (empty($output['chain_info'])) {?>
        <i class="required">*</i>
        <?php }?>
        登录密码<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="password" class="text w200" name="chain_pwd" id="chain_pwd" autocomplete="off" value="" />
        <p class="hint">密码请使用6--20个字符（区分大小写），由字母(必填)、数字(必填)、下划线(可选)组成。</p>
      </dd>
    </dl>
    <dl>
      <dt>
        <?php if (empty($output['chain_info'])) {?>
        <i class="required">*</i>
        <?php }?>
        确认密码<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="password" class="text w200" name="confirm_pwd" id="confirm_pwd" value="" />
        <p class="hint">请再次输入登录密码，确保前后输入一致。</p>
      </dd>
    </dl>
    <h3>门店相关信息</h3>
    <dl>
      <dt><i class="required">*</i>门店名称<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w200" name="chain_name" id="chain_name" value="<?php echo $output['chain_info']['chain_name'];?>" />
        <p class="hint">请认真填写您的门店名称，以确保用户（购买者）线下到店自提时查找。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>所在地区<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input id="region" name="area_info" type="hidden" value="<?php echo $output['chain_info']['area_info'];?>" >
        <input id="_area_1" name="area_id_1" type="hidden" value="<?php echo $output['chain_info']['area_id_1'];?>" >
        <input id="_area_2" name="area_id_2" type="hidden" value="<?php echo $output['chain_info']['area_id_2'];?>" >
        <input id="_area_3" name="area_id_3" type="hidden" value="<?php echo $output['chain_info']['area_id_3'];?>" >
        <input id="_area_4" name="area_id_4" type="hidden" value="<?php echo $output['chain_info']['area_id_4'];?>" >
        <input id="_area" name="area_id" type="hidden" value="<?php echo $output['chain_info']['area_id'];?>" >
        <p class="hint">所在地区将直接影响购买者在选择线下自提时的地区筛选，因此请如实认真选择全部地区级。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>详细地址<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="text w400" name="chain_address" id="chain_address" value="<?php echo $output['chain_info']['chain_address'];?>" />
        <p class="hint">请认真填写详细地址，以确保用户（购物者）线下到店自提时能最准确的到达您的门店。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>联系电话<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <input type="text" class="textarea w200" name="chain_phone" id="chain_phone" value="<?php echo $output['chain_info']['chain_phone'];?>" />
        <p class="hint">请认真填写门店联系电话，方便用户（购物者）通过该电话与您直接取得联系。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>营业时间<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <textarea class="textarea w400" maxlength="50" rows="2" name="chain_opening_hours" id="chain_opening_hours"><?php echo $output['chain_info']['chain_opening_hours'];?></textarea>
        <p class="hint">如实填写您的线下门店营业时间，以免用户（购物者）在营业时间外到店产生误会。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required"></i>交通线路<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <textarea class="textarea w400" maxlength="50" rows="2" name="chain_traffic_line" id="chain_traffic_line"><?php echo $output['chain_info']['chain_traffic_line'];?></textarea>
        <p class="hint">如您的门店周围有公交、地铁线路到达，请填写该选项，多条线路请以“、”进行分隔。</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>实拍照片<?php echo $lang['nc_colon'];?></dt>
      <dd>
        <?php if ($output['chain_info']['chain_img'] != '') {?><p><img src="<?php echo getChainImage($output['chain_info']['chain_img'], $_SESSION['store_id']);?>" style="max-width: 240px; max-height: 240px;" /></p>
        <?php }?>
        <p>
          <input type="file" hidefocus="true" name="chain_img" id="chain_img" />
        </p>
        <p class="hint">将您的实体店面沿街图上传，方便用户（购物者）线下到店自提时能最准确直观的找到您的门店。</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="<?php echo $lang['nc_submit'];?>"/>
      </label>
    </div>
  </form>
</div>
<script>
$(function(){
    $("#region").nc_region();
    $('#chain_form').validate({
        submitHandler:function(form){
            ajaxpost('chain_form', '', '', 'onerror');
        },
        rules : {
            chain_user : {
                required : true,
                minlength: 3,
                remote   : 'index.php?act=store_chain&op=check_user<?php if (!empty($output['chain_info'])) {?>&no_id=<?php echo $output['chain_info']['chain_id'];}?>'
            },
            chain_pwd : {
                <?php if (empty($output['chain_info'])) {?>
                required : true,
                <?php }?>
                rangelength : [6,20]
            },
            confirm_pwd : {
                <?php if (empty($output['chain_info'])) {?>
                required : true,
                <?php }?>
                equalTo : '#chain_pwd'
            },
            chain_name : {
                required : true
            },
            area_info : {
            	checklast: true
            },
            chain_address : {
                required : true
            },
            chain_phone : {
                required : true
            },
            chain_opening_hours : {
                required : true
            },
            chain_img : {
                <?php if (empty($output['chain_info'])) {?>
                required : true
                <?php }?>
            }
        },
        messages : {
            chain_user : {
                required : '<i class="icon-exclamation-sign"></i>请填写门店登录名',
                minlength: '<i class="icon-exclamation-sign"></i>请填写正确的门店名称',
                remote   : '<i class="icon-exclamation-sign"></i>登录名已经存在'
            },
            area_info : {
                checklast : '<i class="icon-exclamation-sign"></i>请将地区选择完整'
            },
            chain_pwd : {
                required : '<i class="icon-exclamation-sign"></i>请填写门店登录密码',
                rangelength : '<i class="icon-exclamation-sign"></i>请填写正确密码'
            },
            confirm_pwd : {
                required : '<i class="icon-exclamation-sign"></i>请填写确认密码',
                equalTo : '<i class="icon-exclamation-sign"></i>与登录密码不同，请重新填写'
            },
            chain_name : {
                required : '<i class="icon-exclamation-sign"></i>请填写门店名称'
            },
            chain_address : {
                required : '<i class="icon-exclamation-sign"></i>请填写详细地址'
            },
            chain_phone : {
                required : '<i class="icon-exclamation-sign"></i>请填写联系方式'
            },
            chain_opening_hours : {
                required : '<i class="icon-exclamation-sign"></i>请填写营业时间'
            },
            chain_img : {
                required : '<i class="icon-exclamation-sign"></i>请上传实拍图片'
            }
        }
    });
});
</script> 
