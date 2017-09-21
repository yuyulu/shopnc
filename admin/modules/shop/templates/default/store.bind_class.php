<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
  <div class="item-title"><a class="back" href="index.php?act=store&op=store" title="返回<?php echo $lang['manage'];?>列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
      <h3>修改“<?php echo $output['store_info']['store_name'];?>”的经验类目</h3>
      <h5><?php echo $lang['nc_store_manage_subhead'];?></h5>
    </div>
  </div>
</div>
<!-- 操作说明 -->
<div class="explanation" id="explanation">
  <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
    <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
    <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
  <ul>
    <li>删除店铺的经营类目会造成相应商品下架，请谨慎操作</li>
    <li>所有修改即时生效</li>
  </ul>
</div>
<table class="flex-table">
  <thead>
    <tr>
      <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
      <th width="60" class="handle-s" align="center"><?php echo $lang['nc_handle'];?></th>
      <th width="150" align="center">分佣比例</th>
      <th width="150" align="center">分类1</th>
      <th width="150" align="center">分类2</th>
      <th width="150" align="center">分类3</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['store_bind_class_list']) && is_array($output['store_bind_class_list'])){ ?>
    <?php foreach($output['store_bind_class_list'] as $key => $value){ ?>
    <tr class="hover edit">
      <td class="sign"><i class="ico-check"></i></td>
      <td class="handle-s"><a class='btn red' nctype="btn_del_store_bind_class" href="javascript:;" data-bid="<?php echo $value['bid'];?>"><i class="fa fa-trash"></i>删除</a></td>
      <td class="sort"><span nc_type="commis_rate" column_id="<?php echo $value['bid'];?>" title="<?php echo $lang['nc_editable'];?>" class="editable" style="vertical-align: middle; margin-right: 4px; width:40px;"><?php echo $value['commis_rate'];?></span>% </td>
      <td><?php echo $value['class_1_name'];?></td>
      <td><?php echo $value['class_2_name'];?></td>
      <td><?php echo $value['class_3_name'];?></td>
      <td></td>
    </tr>
    <?php } ?>
    <?php }else { ?>
    <tr class="no-data">
      <td colspan="100" class="no-data"><i class="fa fa-lightbulb-o"></i><?php echo $lang['nc_no_record'];?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="ncap-form-default" >
  <div class="title">
    <h3>添加经营类目</h3>
  </div>
  <dl class="row">
    <dt class="tit"> 选择分类</dt>
    <dd class="opt" id="gcategory">
      <select id="gcategory_class1" style="width: auto;">
        <option value="0">-请选择-</option>
        <?php if(!empty($output['gc_list']) && is_array($output['gc_list']) ) {?>
        <?php foreach ($output['gc_list'] as $gc) {?>
        <option value="<?php echo $gc['gc_id'];?>" data-explain="<?php echo $gc['commis_rate']; ?>"><?php echo $gc['gc_name'];?></option>
        <?php }?>
        <?php }?>
      </select>
      <span id="error_message" style="color:red;"></span></dd>
    <dt class="tit">分佣比例(必须为0-100的整数)</dt>
    <dd class="opt">
      <form id="add_form" action="<?php echo urlAdminShop('store', 'store_bind_class_add');?>" method="post">
        <input name="store_id" type="hidden" value="<?php echo $output['store_info']['store_id'];?>">
        <input id="goods_class" name="goods_class" type="hidden" value="">
        <input id="commis_rate" name="commis_rate" class="w60" type="text" value="" />
        % <span id="error_message1" style="color:red;"></span>
      </form>
      <span class="err"></span>
      <p class="notic"></p>
    </dd>
  </dl>
  <div class="bot"><a id="btn_add_category" class="ncap-btn ncap-btn-green" href="JavaScript:void(0);" />确认</a></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.edit.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script> 
<script type="text/javascript">
$(document).ready(function(){

	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '店铺名称：<?php echo $output['store_info']['store_name'];?>',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false// 不使用列控制
	});
	
	gcategoryInit("gcategory");

    // 提交新添加的类目
    $('#btn_add_category').on('click', function() {
        $('#error_message').hide();
        $('#error_message1').hide();
        var category_id = '';
        var validation = true;
        $('#gcategory').find('select').each(function() {
            if(parseInt($(this).val(), 10) > 0) {
                category_id += $(this).val() + ',';
            } else {
                validation = false;
            }
        });
        if(!validation) {
            $('#error_message').text('请选择分类');
            $('#error_message').show();
            return false;
        }

        var commis_rate = parseInt($('#commis_rate').val(), 10);
        if(isNaN(commis_rate) || commis_rate < 0 || commis_rate > 100) {
            $('#error_message1').text('请填写正确的分佣比例');
            $('#error_message1').show();
            return false;
        }

        $('#goods_class').val(category_id);
        $('#add_form').submit();
    });

    $('#gcategory select').live('change', function() {
        var cr = $(this).children(':selected').attr('data-explain');
        $('#commis_rate').val(parseInt(cr) || '');
    });

    // 删除现有类目
    $('[nctype="btn_del_store_bind_class"]').on('click', function() {
        if(confirm('确认删除？删除后店铺对应分类商品将全部下架')) {
            var bid = $(this).attr('data-bid');
            $this = $(this);
            $.post('<?php echo urlAdminShop('store', 'store_bind_class_del');?>', {bid: bid}, function(data) {
                 if(data.result) {
                     $this.parents('tr').hide();
                 } else {
                     showError(data.message);
                 }
            }, 'json');
        }
    });

    // 修改分佣比例
    $('span[nc_type="commis_rate"]').inline_edit({act: 'store',op: 'store_bind_class_update'});
});

</script> 
