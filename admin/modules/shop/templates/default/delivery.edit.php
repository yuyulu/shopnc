<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="<?php echo urlAdminShop('delivery', 'index');?>" title="返回<?php echo $lang['nc_manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>物流自提服务站 - 编辑服务站“<?php echo $output['dlyp_info']['dlyp_address_name'];?>”</h3>
        <h5>商城对线下物流自提点的设定集管理</h5>
      </div>
      
    </div>
  </div>
  <form id="delivery_form" method="post" action="<?php echo urlAdminShop('delivery', 'save_edit');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="did" value="<?php echo $output['dlyp_info']['dlyp_id'];?>">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="">物流自提服务站用户名</label>
        </dt>
        <dd class="opt"><?php echo $output['dlyp_info']['dlyp_name'];?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">真实姓名</label>
        </dt>
        <dd class="opt"><?php echo $output['dlyp_info']['dlyp_truename'];?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">手机号</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="dmobile" value="<?php echo $output['dlyp_info']['dlyp_mobile'];?>">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">座机号</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="dtelephony" value="<?php echo $output['dlyp_info']['dlyp_telephony'];?>">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">自提服务站名称</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="daddressname" value="<?php echo $output['dlyp_info']['dlyp_address_name'];?>">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">所在地区</label>
        </dt>
        <dd class="opt">
            <input type="hidden" value="<?php echo $output['dlyp_info']['dlyp_area_info']?>" name="region" id="region">
            <input type="hidden" name="area_id_1" id="_area_1" value="<?php echo $output['dlyp_info']['dlyp_area_1']?>">
            <input type="hidden" name="area_id_2" id="_area_2" value="<?php echo $output['dlyp_info']['dlyp_area_2']?>">
            <input type="hidden" name="area_id_3" id="_area_3" value="<?php echo $output['dlyp_info']['dlyp_area_3']?>">
            <input type="hidden" name="area_id_4" id="_area_4" value="<?php echo $output['dlyp_info']['dlyp_area_4']?>">
            <input type="hidden" name="area_id" id="_area" value="<?php echo $output['dlyp_info']['dlyp_area']?>" />
            <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">详细地址</label>
        </dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="daddress" value="<?php echo $output['dlyp_info']['dlyp_address'];?>">
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">身份证号码</label>
        </dt>
        <dd class="opt"><?php echo $output['dlyp_info']['dlyp_idcard'];?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">身份证图片</label>
        </dt>
        <dd class="opt"><a href="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DELIVERY.DS.$output['dlyp_info']['dlyp_idcard_image'];?>" target="_blank"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_DELIVERY.DS.$output['dlyp_info']['dlyp_idcard_image'];?>"></a><span class="err"></span>
          <p class="notic">点击查看大图 </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">申请时间</label>
        </dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s', $output['dlyp_info']['dlyp_addtime']);?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">登录密码</label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" name="dpasswd">
          <span class="err"></span>
          <p class="notic">不填为不修改密码 </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="">状态</label>
        </dt>
        <?php if (in_array($output['dlyp_info']['dlyp_state'], array(0,1))) {?>
        <dd class="opt">
          <div class="onoff">
            <label for="site_status1" class="cb-enable <?php if($output['dlyp_info']['dlyp_state'] == '1'){ ?>selected<?php } ?>" ><span>开启</span></label>
            <label for="site_status0" class="cb-disable <?php if($output['dlyp_info']['dlyp_state'] == '0'){ ?>selected<?php } ?>" ><span>关闭</span></label>
            <input id="site_status1" name="dstate" <?php if($output['dlyp_info']['dlyp_state'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="site_status0" name="dstate" <?php if($output['dlyp_info']['dlyp_state'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } else {?>
      <dd class="opt">
        <div class="onoff">
          <label for="site_status1" class="cb-enable selected" ><span>通过</span></label>
          <label for="site_status20" class="cb-disable" ><span>失败</span></label>
          <input id="site_status1" name="dstate" checked="checked" value="1" type="radio">
          <input id="site_status20" name="dstate" value="20" type="radio">
        </div>
        <p class="notic"></p>
      </dd>
      </dl>
      <dl class="row" style="display: none;" nctype="fail_reason">
        <dt class="tit">
          <label for="">审核失败原因</label>
        </dt>
        <dd class="opt">
          <textarea id="fail_reason" class="tarea" rows="6" name="fail_reason"></textarea>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php }?>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
    $("#region").nc_region();
    $("#submitBtn").click(function(){
        $("#delivery_form").submit();
    });
    $('input[name="dstate"]').change(function(){
        _val = $('input[name="dstate"]:checked').val();
        if (_val == 20) {
            $('[nctype="fail_reason"]').show();
        } else {
            $('[nctype="fail_reason"]').hide();
        }
    });
});
</script> 
