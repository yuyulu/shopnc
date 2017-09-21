<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncm-flow-layout">
  <div class="ncm-flow-container">
    <div class="title"><a href="javascript:history.go(-1);" class="ncbtn-mini fr"><i class="icon-reply"></i>返&nbsp;回</a>
      <h3><?php echo $lang['member_evaluation_toevaluategoods'];?></h3>
    </div>
    <form id="evalform" method="post" action="index.php?act=member_evaluate&op=<?php echo $_GET['op'];?>&order_id=<?php echo $_GET['order_id'];?>">
      <input type="hidden" name="form_submit" value="ok" />
      <div class="alert alert-block">
        <h4>操作提示：</h4>
        <ul>
          <li><?php echo $lang['member_evaluation_rule_3'];?></li>
          <li><?php echo $output['ruleexplain'];?></li>
          <li><?php echo $lang['member_evaluation_rule_4'];?></li>
          <li>图片上传请使用jpg\jpeg\png等格式、单张大小不超过1M的图片，最多可发布5张晒图，上传后的图片也将被保存在个人主页相册中以便其它使用。</li>
        </ul>
      </div>
      <div class="tabmenu">
        <ul class="tab">
          <li class="active"><a href="javascript:void(0);">对购买过的商品评价</a></li>
        </ul>
      </div>
      <table class="ncm-default-table mb30">
        <tbody>
          <?php if(!empty($output['order_goods'])){?>
          <?php foreach($output['order_goods'] as $goods){?>
          <tr class="bd-line">
            <td class="w20"></td>
            <td class="pic-mode w200"><div class="pic-thumb"><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id']; ?>" target="_blank"><img src="<?php echo $goods['goods_image_url']; ?>"/></a></div>
              <dl class="goods-name">
                <dt><a href="index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" target="_blank" title="<?php echo $goods['goods_name'];?>"><?php echo $goods['goods_name'];?></a></dt>
                <dd title="<?php echo $goods['goods_spec'];?>"><?php echo $goods['goods_spec'];?></dd>
              </dl></td>
            <td valign="top" class="tl"><div class="ncgeval">商品评分：
                <div class="raty vm">
                  <input nctype="score" name="goods[<?php if ($_GET['op'] != 'add_vr') { echo $goods['rec_id'];} else { echo $goods['goods_id'];}?>][score]" type="hidden">
                </div>
              </div>
              <textarea name="goods[<?php if ($_GET['op'] != 'add_vr') { echo $goods['rec_id'];} else { echo $goods['goods_id'];}?>][comment]" cols="150" class="w450 mt10 mb10 h40" placeholder="请输入要评价的内容，不要超过150个字符。"></textarea>
              <div class="show-pic">
                <?php if ($_GET['op'] != 'add_vr') {?>
                <div class="ncm-upload-btn fl"> <a href="javascript:void(0);"> <span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file<?php echo $goods['rec_id'];?>" multiple>
                  </span>
                  <p><i class="icon-camera" data_type="0"></i>买家晒图</p>
                  </a> </div>
                <div class="ml5 mt5 fl">限5张</div>
                <?php }?>
                <span class="fr mr10 mt5">
                <input type="checkbox" class="checkbox vm" name="goods[<?php if ($_GET['op'] != 'add_vr') { echo $goods['rec_id'];} else { echo $goods['goods_id'];}?>][anony]">
                &nbsp;<?php echo $lang['member_evaluation_modtoanonymous'];?></span>
                <div class="evaluation-image">
                  <ul nctype="ul_evaluate_image<?php echo $goods['rec_id'];?>" data-count='0'>
                  </ul>
                </div>
              </div></td>
          </tr>
          <?php }?>
          <?php }?>
        </tbody>
      </table>
      <?php if (!$output['store_info']['is_own_shop'] && $_GET['op'] != 'add_vr') { ?>
      <div class="tabmenu">
        <ul class="tab">
          <li class="active"><a href="javascript:void(0);">对该店此次服务的评分</a></li>
        </ul>
      </div>
      <?php } ?>
      <div class="ncm-default-form">
        <?php if (!$output['store_info']['is_own_shop'] && $_GET['op'] != 'add_vr') { ?>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalstore_type_1'].$lang['nc_colon'];?></dt>
          <dd>
            <div class="raty-x2">
              <input nctype="score" name="store_desccredit" type="hidden">
            </div>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalstore_type_2'].$lang['nc_colon'];?></dt>
          <dd>
            <div class="raty-x2">
              <input nctype="score" name="store_servicecredit" type="hidden">
            </div>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_evaluation_evalstore_type_3'].$lang['nc_colon'];?></dt>
          <dd>
            <div class="raty-x2">
              <input nctype="score" name="store_deliverycredit" type="hidden">
            </div>
          </dd>
        </dl>
        <?php } ?>
        <div class="bottom">
          <label class="submit-border">
            <input id="btn_submit" type="button" class="submit" value="<?php echo $lang['member_evaluation_submit'];?>"/>
          </label>
        </div>
      </div>
    </form>
  </div>
  <div class="ncm-flow-item">
    <?php if (!$output['store_info']['is_own_shop']) { ?>
    <?php require('evaluation.store_info.php');?>
    <?php } ?>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<?php if ($_GET['op'] != 'add_vr') {?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<?php }?>
<script type="text/javascript">
$(function(){
    $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
        click: function(score) {
            $(this).find('[nctype="score"]').val(score);
        }
    });

    $('.raty-x2').raty({
        path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
        starOff: 'star-off-x2.png',
        starOn: 'star-on-x2.png',
        width: 150,
        click: function(score) {
            $(this).find('[nctype="score"]').val(score);
        }
    });


    $('#btn_submit').on('click', function() {
		ajaxpost('evalform', '', '', 'onerror')
    });
    <?php if ($_GET['op'] != 'add_vr') {?>
    <?php if(!empty($output['order_goods'])){?>
    <?php foreach($output['order_goods'] as $goods){?>
    <?php $param_id = $_GET['op'] != 'add_vr' ? $goods['rec_id'] : $goods['goods_id'];?>
    // 图片上传
    $('#file<?php echo $param_id;?>').fileupload({
        dataType: 'json',
        url: '<?php echo urlShop('sns_album', 'swfupload');?>',
        formData: '',
        add: function (e,data) {
            var $count = parseInt($('ul[nctype="ul_evaluate_image<?php echo $param_id;?>"]').attr('data-count'));
            if ($count >= 5) {
                return false;
            }
            $('ul[nctype="ul_evaluate_image<?php echo $param_id;?>"]').attr('data-count', $count +1);
            data.formData = {category_id:<?php echo $output['ac_id'];?>};
            data.submit();
        },
        done: function (e,data) {
            if(data.result.state == 'true') {
                $('<li>' +
                        '<div class="upload-thumb" nctype="image_item">' +
                        '<img src="' + data.result.file_url + '"><input type="hidden" nctype="input_image" name="goods[<?php echo $param_id;?>][evaluate_image][]" value=" ' + data.result.file_name + ' " >' +
                        '<a href="javascript:;" nctype="del" data-file-id="' + data.result.file_id + '" class="del" title="移除">X</a>' +
                        '</div></li>').appendTo('ul[nctype="ul_evaluate_image<?php echo $param_id;?>"]');
            } else {
                showError(data.result.message);
            }
        }
    });
    <?php }?>
    <?php }?>
    $('ul[nctype^="ul_evaluate_image"]').on('click', '[nctype="del"]', function() {
        album_pic_del($(this).attr('data-file-id'));
        var $item_li = $(this).parents('li:first');
        var $item_ul = $item_li.parents('ul:first');
        $item_li.find('[nctype="input_image"]').val('');
        $item_li.remove();
        $item_ul.attr('data-count', $item_ul.attr('data-count') -1);
    });

    var album_pic_del = function(file_id) {
        var del_url = "<?php echo urlShop('sns_album', 'album_pic_del');?>";
        del_url += '&id=' + file_id;
        $.get(del_url);
    }
    <?php }?>
});
</script> 
