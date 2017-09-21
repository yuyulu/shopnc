<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=ownshop&op=list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>自营店铺 - <?php echo $lang['nc_edit'];?>“<?php echo $output['store_array']['store_name'];?>”</h3>
        <h5>商城自营店铺相关设置与管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>可以修改自营店铺的店铺名称以及店铺状态是否为开启状态</li>
      <li>可以修改自营店铺的店主商家中心登录账号</li>
      <li>如需修改店主登录密码，请到会员管理中，搜索“店主账号”相应的会员并编辑</li>
      <li>已绑定所有类目的自营店，如果将“绑定所有类目”设置为“否”，则会下架其所有商品，请谨慎操作！</li>
    </ul>
  </div>
  <form id="store_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="store_id" value="<?php echo $output['store_array']['store_id']; ?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="store_name"><em>*</em>店铺名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['store_array']['store_name'];?>" id="store_name" name="store_name" class="input-txt" />
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="store_name">开店时间</label>
        </dt>
        <dd class="opt"><?php echo ($t = $output['store_array']['store_time'])?@date('Y-m-d',$t):'';?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>店主账号</label>
        </dt>
        <dd class="opt"><?php echo $output['store_array']['member_name'];?><span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="seller_name"><em>*</em>店主商家账号</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['store_array']['seller_name'];?>" id="seller_name" name="seller_name" class="input-txt" />
          <span class="err"></span>
          <p class="notic">用于登录商家中心，可与店主账号不同 </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="bind_all_gc">绑定所有类目</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="bind_all_gc1" class="cb-enable <?php if ($output['store_array']['bind_all_gc'] == '1'){ ?>selected<?php } ?>" ><span>是</span></label>
            <label for="bind_all_gc0" class="cb-disable <?php if($output['store_array']['bind_all_gc'] == '0'){ ?>selected<?php } ?>" ><span>否</span></label>
            <input id="bind_all_gc1" name="bind_all_gc" <?php if($output['store_array']['bind_all_gc'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="bind_all_gc0" name="bind_all_gc" <?php if($output['store_array']['bind_all_gc'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="state">状态</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="store_state1" class="cb-enable <?php if($output['store_array']['store_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['open'];?></label>
            <label for="store_state0" class="cb-disable <?php if($output['store_array']['store_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['close'];?></label>
            <input id="store_state1" name="store_state" <?php if($output['store_array']['store_state'] == '1'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').hide();" value="1" type="radio">
            <input id="store_state0" name="store_state" <?php if($output['store_array']['store_state'] == '0'){ ?>checked="checked"<?php } ?> onclick="$('#tr_store_close_info').show();" value="0" type="radio">
          </div>
          <p class="notic"> </p>
        </dd>
      </dl>
      <dl class="row" id="tr_store_close_info">
        <dt class="tit">
          <label for="store_close_info">关闭原因</label>
        </dt>
        <dd class="opt">
          <textarea name="store_close_info" rows="6" class="tarea" id="store_close_info"><?php echo $output['store_array']['store_close_info'];?></textarea>
          <span class="err"></span>
          <p class="notic"> </p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){

    $('input[name=store_state][value=<?php echo $output['store_array']['store_state'];?>]').trigger('click');

    //按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#store_form").valid()){
            $("#store_form").submit();
        }
    });

    $('#store_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            store_name: {
                required : true,
                remote   : '<?php echo urlAdminShop('ownshop', 'ckeck_store_name', array('store_id' => $output['store_array']['store_id']))?>'
            },
            seller_name: {
                required : true,
                remote   : {
                    url : 'index.php?act=ownshop&op=check_seller_name&id=<?php echo $output['store_array']['store_id']; ?>',
                    type: 'get',
                    data:{
                        seller_name : function(){
                            return $('#seller_name').val();
                        }
                    }
                }
            }
        },
        messages : {
            store_name: {
                required : '<i class="fa fa-exclamation-circle"></i>请输入店铺名称',
                remote   : '<i class="fa fa-exclamation-circle"></i>店铺名称已存在'
            },
            seller_name: {
                required : '<i class="fa fa-exclamation-circle"></i>请输入店主商家账号',
                remote   : '<i class="fa fa-exclamation-circle"></i>此名称已被其它店铺占用，请重新输入'
            }
        }
    });
});
</script> 
