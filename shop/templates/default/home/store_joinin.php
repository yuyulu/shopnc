<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="banner">
  <div class="user-box">
    <?php if($_SESSION['is_login'] == '1'){?>
    <div class="user-joinin">
      <h3>亲爱的：<?php echo $_SESSION['member_name'];?></h3>
      <dl>
        <dt><?php echo $lang['welcome_to_site'].$output['setting_config']['site_name']; ?></dt>
        <dd> 若您还没有填写入驻申请资料<br/>
          请点击“<a href="<?php echo urlShop('store_joinin', 'step0');?>" target="_blank">我要入驻</a>”进行入驻资料填写</dd>
        <dd>若您的店铺还未开通<br/>
          请通过“<a href="<?php echo urlShop('store_joinin', 'index');?>" target="_blank">查看入驻进度</a>”了解店铺开通的最新状况 </dd>
      </dl>
      <div class="bottom"><a href="<?php echo urlShop('store_joinin', 'step0');?>" target="_blank">我要入驻</a><a href="<?php echo urlShop('store_joinin', 'index');?>" target="_blank">查看入驻进度</a></div>
    </div>
     <?php }else { ?>
    <div class="user-login">
      <h3>商务登录<em>（使用已注册的会员账号）</em></h3>
      <form id="login_form" action="<?php echo urlLogin('login')?>" method="post">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
        <dl>
          <dt>用户名：</dt>
          <dd>
            <input type="text" class="text" autocomplete="off"  name="user_name" id="user_name">
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>密&nbsp;&nbsp;&nbsp;码：</dt>
          <dd>
            <input type="password" class="text" name="password" autocomplete="off"  id="password">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt>验证码：</dt>
          <dd>
            <input type="text" name="captcha" class="text w50 fl" id="captcha" maxlength="4" size="10" />
            <a href="JavaScript:void(0);" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();" class="change" title="<?php echo $lang['login_index_change_checkcode'];?>">
            <img src="index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>" class="fl ml5" name="codeimage" id="codeimage" border="0"/></a>
            <label></label>
          </dd>
        </dl>
        <?php } ?>
        <dl>
          <dt></dt>
          <dd>
            <input type="hidden" value="<?php echo SHOP_SITE_URL?>/index.php?act=show_joinin" name="ref_url">
            <input name="提交" type="submit" class="button" value="登&nbsp;&nbsp;录">
            <a href="<?php echo urlLogin('login', 'forget_password');?>" target="_blank"><?php echo $lang['login_index_forget_password'];?></a></dd>
        </dl>
      </form>
      <div class="register">还没有成为我们的合作伙伴？ <a href="<?php echo urlLogin('login', 'register');?>" target="_blank">快速注册</a></div>
    </div>
    <?php } ?>
  </div>
  <ul id="fullScreenSlides" class="full-screen-slides">
    <?php $pic_n = 0;?>
    <?php if(!empty($output['pic_list']) && is_array($output['pic_list'])){ ?>
    <?php foreach($output['pic_list'] as $key => $val){ ?>
    <?php if(!empty($val)){ $pic_n++; ?>
    <li style="background-color: #F1A595; background-image: url(<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/'.$val;?>)" ></li>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </ul>
</div>

<div class="indextip">
  <div class="container"> <span class="title"><i></i>
    <h3>贴心提示</h3>
    </span> <span class="content"><?php echo $output['show_txt'];?></span></div>
</div>
<div class="mains">
<div class="joinin-index-step">
    <span class="step">
      <a href="javascript:;"><i class="a"></i>
        入驻流程</a>
    </span>
    <span class="step">
      <a href="javascript:;"><i class="b"></i>
      入驻标准</a>
    </span>
    <span class="step">
      <a href="javascript:;"><i class="c"></i>
      公司资质要求</a>
    </span>
    <span class="step">
      <a href="javascript:;"><i class="d"></i>
      品牌资质要求</a>
    </span>
    <span class="step">
      <a href="javascript:;"><i class="e"></i>
      合作细则</a>
    </span>
    <span class="step">
      <a href="javascript:;"><i class="f"></i>
      已合作品牌</a>
    </span>
  </div></div>
  <div class="flow_line"><span></span></div>
  <div class="platform_advantage">
  <div class="platform_logo">
    <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/platform_logo.png" alt="">
  </div>
  <ul class="advantage-list">
    <li class="list-item item-leader shake shake-slow">
      <p class="item-number"><span>01</span></p>
      <p class="item-logo"><i class="logo_1"></i></p>
      <div class="item-text">
          <p class="title">行业领先</p>
          <p class="t-eng">INDUSTRY LEADING</p>
          <p class="line"></p>
          <p class="text-1">国内首创，行业领先</p>
          <p class="text-2">在国内首批创立综合销售这一独特商业模式</p>
          <p class="text-3">创始初期即与拥有千万级活跃用户</p>
          <p class="text-4">垂直综合平台-成为合作伙伴</p>
      </div>
    </li>
    <li class="list-item item-user shake shake-slow">
      <p class="item-number"><span>02</span></p>
      <p class="item-logo"><i class="logo_2"></i></p>
      <div class="item-text">
          <p class="title">庞大的用户群</p>
          <p class="t-eng">LARGE USER BASE</p>
          <p class="line"></p>
          <p class="text-1">庞大的用户群</p>
          <p class="text-2">线上线下媒体深度合作万千网友的信赖</p>
          <p class="text-3">携手<?php echo $output['setting_config']['site_name']; ?></p>
          <p class="text-4">深耕以销售用户为基础的综合市场</p>
      </div>
    </li>
    <li class="list-item item-team shake shake-slow">
      <p class="item-number"><span>03</span></p>
      <p class="item-logo"><i class="logo_3"></i></p>
      <div class="item-text">
          <p class="title">专业团队</p>
          <p class="t-eng">PROFESSIONAL TEAM</p>
          <p class="line"></p>
          <p class="text-1">专业店铺管家</p>
          <p class="text-2">专业招商团队，一对一服务</p>
          <p class="text-4">清晰申请流程，高效的审核制度</p>
      </div>
    </li>
    <li class="list-item item-cost shake shake-slow">
      <p class="item-number"><span>04</span></p>
      <p class="item-logo"><i class="logo_4"></i></p>
      <div class="item-text">
          <p class="title">零风险低成本</p>
          <p class="t-eng">ZERO RISK AND LOW COST</p>
          <p class="line"></p>
          <p class="text-1">0佣金，助你实现0库存</p>
          <p class="text-2">暂不收取商品销售佣金</p>
          <p class="text-3">独特销售模式为你实现0库存</p>
      </div>
    </li>
  </ul></div>

<!--整体流程-->
<div class="flow-box">
      <?php if(!empty($output['help_list']) && is_array($output['help_list'])){ $i = 0;?>
      <?php foreach($output['help_list'] as $key => $val){ $i++;?>
<?php if($i==1){?>
  <div class="settled-flow sb_floor"><div class="settled"> <div class="left"><i class="i_1"></i><p><?php echo $val['help_title'];?></p> <p class="eng">PROCESS</p><span class="span_1"></span></div>
       <?php }else if($i==2){?>
<div class="settled-stander sb_floor"><div class="stand"><div class="left"><i class="i_2"></i> <p><?php echo $val['help_title'];?></p><p class="eng">STANDARD</p><span class="span_2"></span> </div>
       <?php }else if($i==3){?> 
<div class="settled-rules sb_floor"><div class="rule"><div class="left"> <i class="i_3"></i> <p><?php echo $val['help_title'];?></p><p class="eng">PROCESS</p> <span class="span_3"></span></div>
       <?php }else if($i==4){?>
<div class="settled-brands sb_floor"><div class="brand"><div class="left"><i class="i_4"></i><p>合作品牌</p><p class="eng">COOPERATIVE RULES</p><span class="span_4"></span> </div>
       <?php } ?>
        <div class="right"><?php echo $val['help_info'];?></div></div></div>
       <?php } ?>
      <?php } ?></div>  
</div>
<a class="back-top">
</a>
<div class="suspension-box">
  <div class="joinin-index-step suspension-step">
    <span class="step">
      <a href="javascript:;">
        入驻流程</a>
    </span>
    <span class="step">
      <a href="javascript:;">
      入驻标准</a>
    </span>
    <span class="step">
      <a href="javascript:;">
      公司资质要求</a>
    </span>
    <span class="step">
      <a href="javascript:;">
      品牌资质要求</a>
    </span>
    <span class="step">
      <a href="javascript:;">
      合作细则</a>
    </span>
    <span class="step">
      <a href="javascript:;">
      已合作品牌</a>
    </span>
    <div class="item-l2">
      <a href="<?php echo urlShop('store_joinin', 'step0');?>" class="login">
      立即入驻<i></i></a>
    </div>
  </div>
</div>  

<script>
$(document).ready(function(){
	$("#login_form ").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
		rules: {
			user_name: "required",
			password: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    }
                }
            }
			<?php } ?>
		},
		messages: {
			user_name: "用户名不能为空",
			password: "密码不能为空"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : '验证码不能为空',
                minlength: '验证码不能为空',
				remote	 : '验证码错误'
            }
			<?php } ?>
		}
	});
});
</script>
<?php if( $pic_n > 1) { ?>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<?php }else { ?>
<script>
$(document).ready(function(){
    $(".tabs-nav > li > h3").bind('mouseover', (function(e) {
    	if (e.target == this) {
    		var tabs = $(this).parent().parent().children("li");
    		var panels = $(this).parent().parent().parent().children(".tabs-panel");
    		var index = $.inArray(this, $(this).parent().parent().find("h3"));
    		if (panels.eq(index)[0]) {
    			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
    			panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
    		}
    	}
    }));
});
</script>
<?php } ?>
<script type="text/javascript" src="<?php echo SHOP_SITE_URL;?>/resource/js/index_introduce.js" charset="utf-8"></script>


