<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=goods_class&op=goods_class&gc_id=<?php echo $output['class_array']['gc_parent_id']?>" title="返回商品分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?> - <?php echo $lang['nc_edit'];?>“<?php echo $output['class_array']['gc_name'];?>”</h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['goods_class_edit_prompts_one'];?></li>
      <li><?php echo $lang['goods_class_edit_prompts_two'];?></li>
    </ul>
  </div>
  <form id="goods_class_form" name="goodsClassForm" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gc_id" value="<?php echo $output['class_array']['gc_id'];?>" />
    <input type="hidden" name="gc_parent_id" id="gc_parent_id" value="<?php echo $output['class_array']['gc_parent_id'];?>" />
    <input type="hidden" name="old_type_id" value="<?php echo $output['class_array']['type_id'];?>">
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label class="gc_name" for="gc_name"><em>*</em><?php echo $lang['goods_class_index_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" maxlength="20" value="<?php echo $output['class_array']['gc_name'];?>" name="gc_name" id="gc_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>发布虚拟商品</label>
        </dt>
        <dd class="opt">
          <label>
            <input type="checkbox" class="checkbox" name="gc_virtual" id="gc_virtual" value="1" <?php if ($output['class_array']['gc_virtual'] == 1) {?>checked<?php }?>>
            允许</label>
          <p class="notic mb10">勾选允许发布虚拟商品后，在发布该分类的商品时可选择交易类型为“虚拟兑换码”形式。</p>
          <label for="t_gc_virtual">
            <input id="t_gc_virtual" type="checkbox" class="checkbox" value="1" name="t_gc_virtual">
            关联到子分类</label>
          <p class="notic">勾选关联到子分类后，该分类下的子分类交易类型也将被设定为“虚拟兑换码”形式。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>商品展示方式</label>
        </dt>
        <dd class="opt">
          <select name="show_type">
            <?php foreach ($output['show_type'] as $key => $val) {?>
            <option value="<?php echo $key;?>" <?php if ($key == $output['class_array']['show_type']){?>selected<?php }?>><?php echo $val?></option>
            <?php }?>
          </select>
          <span class="err"></span>
          <p class="notic mb10">在商品列表页的展示方式。<br>   “颜色”：每个SPU只展示不同颜色的SKU，同一颜色多个SKU只展示一个SKU。<br>    “SPU”：每个SPU只展示一个SKU。</p>
          <label for="t_show_type">
            <input id="t_show_type" type="checkbox" class="checkbox" value="1" name="t_show_type">
            关联到子分类</label>
          <p class="notic">勾选关联到子分类后，被绑定的商品展示方式也将继承到子分类中使用。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>分佣比例</label>
        </dt>
        <dd class="opt">
          <input id="commis_rate" class="w60" type="text" value="<?php echo $output['class_array']['commis_rate'];?>" name="commis_rate">
          <i>%</i> <span class="err"></span>
          <p class="notic mb10">分佣比例必须为0-100的整数。</p>
          <label for="t_commis_rate">
            <input id="t_commis_rate" class="checkbox" type="checkbox" value="1" name="t_commis_rate">
            关联到子分类</label>
          <p class="notic">勾选关联到子分类后，该分类下的子分类分佣比利也将按此继承设定。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['goods_class_add_type'];?></label>
        </dt>
        <dd class="opt">
          <div id="gcategory">快捷定位
            <select class="class-select">
              <option value="0"><?php echo $lang['nc_please_choose'];?></option>
              <?php if(!empty($output['gc_list'])){ ?>
              <?php foreach($output['gc_list'] as $k => $v){ ?>
              <?php if ($v['gc_parent_id'] == 0) {?>
              <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              <?php } ?>
              <?php } ?>
              <?php } ?>
            </select>
            分类下的商品类型</div>
          <input type="hidden" name="t_name" id="t_name" value="<?php echo $output['class_array']['type_name'];?>" />
          <div id="type_div" class="scrollbar-box">
            <div class="ncap-type-spec-list">
              <?php if(!empty($output['type_list'])){?>
              <dl>
                <dd>
                  <label>
                    <input type="radio" class="raido" name="t_id" value="0" <?php if($output['class_array']['type_id'] == 0){?>checked="checked"<?php }?> />
                    <?php echo $lang['goods_class_null_type'];?></label>
                </dd>
              </dl>
              <?php foreach($output['type_list'] as $k=>$val){?>
              <?php if(!empty($val['type'])){?>
              <dl>
                <dt id="type_dt_<?php echo $k;?>"><?php echo $val['name']?></dt>
                <?php foreach($val['type'] as $v){?>
                <dd>
                  <label>
                    <input type="radio" class="radio" name="t_id" value="<?php echo $v['type_id']?>" <?php if($output['class_array']['type_id'] == $v['type_id']){?>checked="checked"<?php }?> />
                    <span><?php echo $v['type_name'];?></span></label>
                </dd>
                <?php }?>
              </dl>
              <?php }?>
              <?php }?>
              <?php }?>
            </div>
          </div>
          <p class="notic mb10"><?php echo $lang['goods_class_add_type_desc_one'];?><a onclick="window.parent.openItem('shop|type')" href="JavaScript:void(0);" class="ncap-btn mr5 ml5"><?php echo $lang['nc_type_manage'];?></a><?php echo $lang['goods_class_add_type_desc_two'];?></p>
          <label for="t_associated">
            <input class="checkbox" type="checkbox" name="t_associated" value="1" id="t_associated" />
            <?php echo $lang['goods_class_edit_related_to_subclass'];?></label>
          <p class="notic">勾选关联到子分类后，被绑定的商品类型也将继承到子分类中使用。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="gc_sort"><?php echo $lang['nc_sort'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['class_array']['gc_sort'] == ''?0:$output['class_array']['gc_sort'];?>" name="gc_sort" id="gc_sort" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['goods_class_add_update_sort'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script>
$(function(){
//自动加载滚动条
    $('#type_div').perfectScrollbar();

// 点击查看图片
	$('.nyroModal').nyroModal();
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
	    if($("#goods_class_form").valid()){
	     $("#goods_class_form").submit();
		}
	});

	$('input[type="radio"][name="t_id"]').change(function(){
		// 标记类型时候修改 修改为ok
		var t_id = <?php echo $output['class_array']['type_id'];?>;
		if(t_id != $(this).val()){
			$('#t_sign').val('ok');
		}else{
			$('#t_sign').val('');
		}

		if($(this).val() == '0'){
			$('#t_name').val('');
		}else{
			$('#t_name').val($(this).next('span').html());
		}
	});

	$('#goods_class_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            gc_name : {
                required : true,
                remote   : {
                url :'index.php?act=goods_class&op=ajax&branch=check_class_name',
                type:'get',
                data:{
                    gc_name : function(){
                        return $('#gc_name').val();
                    },
                    gc_parent_id : function() {
                        return $('#gc_parent_id').val();
                    },
                    gc_id : '<?php echo $output['class_array']['gc_id'];?>'
                  }
                }
            },
            commis_rate : {
            	required :true,
                max :100,
                min :0,
                digits :true
            },
            gc_sort : {
                number   : true
            }
        },
        messages : {
             gc_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_name_null'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_name_exists'];?>'
            },
            commis_rate : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_commis_rate_error'];?>',
                maxlength :'<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_commis_rate_error'];?>',
                minlength :'<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_commis_rate_error'];?>',
                digits :'<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_commis_rate_error'];?>'
            },
            gc_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['goods_class_add_sort_int'];?>'
            }
        }
    });

    // 类型搜索
    $("#gcategory > select").live('change',function(){
    	type_scroll($(this));
    });
});
var typeScroll = 0;
function type_scroll(o){
	var id = o.val();
	if(!$('#type_dt_'+id).is('dt')){
		return false;
	}
	$('#type_div').scrollTop(-typeScroll);
	var sp_top = $('#type_dt_'+id).offset().top;
	var div_top = $('#type_div').offset().top;
	$('#type_div').scrollTop(sp_top-div_top);
	typeScroll = sp_top-div_top;
}
gcategoryInit('gcategory');
</script>
