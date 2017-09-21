<link rel="stylesheet" type="text/css" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog/dialog.css" />
<script charset="utf-8" type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/jquery.plugins/jquery.validate.js" ></script>
<style>
/* eject_layer */

#html { width: 100%; position: absolute; top: 0; left: 0; z-index: 10; background: #000; -moz-opacity: 0.4; opacity: .40; filter: alpha(opacity=40); }

.dialog_wrapper { background: #595959; padding-bottom: 4px; position: absolute; top: 50%; left: 50%; z-index: 20; }
.dialog_body { padding: 6px; border: 4px solid #dadada; background: #fff; }

.dialog_close_button { display: block; width: 21px; height: 21px; background: url(<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/images/member/ico.gif) 0 -761px; position: absolute; top: 10px; right: 10px; }
.dialog_close_button:hover { background: url(<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/images/member/ico.gif) 0 -783px; }

.eject_con { border: 1px solid #e2e2e2; border-top: 0; }

.dialog_body {
padding: 6px;
border: 4px solid #DADADA;
background: white;
}
.dialog_head {
height: 0px;
}

.tab { width: 100%; height: 28px; line-height: 26px; background: url(<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/images/member/tab.gif) repeat-x bottom; }
.tab li { float: left; margin-right: 2px; cursor: pointer; font-size: 14px; }
.tab .active { height: 26px; color: #ff4f01; font-weight: bold; padding: 0 20px; border: 1px solid #e2e2e2; border-bottom: 1px solid #fff; background: #fff; }
.tab .normal { height: 26px; color: #3e3e3e; font-weight: bold; padding: 0px; border: 1px solid #e2e2e2; background: #f9f9f9; }
.tab .active a { color: #ff4f01; text-decoration: none; float: left; height: 26px; padding: 0 20px; }
.tab .normal a { color: #3e3e3e; text-decoration: none; float: left; height: 26px; padding: 0 20px; }

/* 5 */
.eject_con .adds { width: auto; padding: 20px; overflow: hidden; }
.eject_con .adds ul { width: 398px; overflow: hidden; }
.eject_con .adds li { width: 398px; overflow: hidden; float: left; padding-bottom: 10px; }
.eject_con .adds li h3 { float: left; width: 80px; color: #646665; font-weight: normal; font-size: 12px; line-height: 26px; }
.eject_con .adds li p { float: left; }
.eject_con .adds li p span { color: #646665; padding-left: 10px; }
.eject_con .adds .strong { padding-left: 10px; color: #ff4e00; }

.eject_con .adds .submit { padding: 10px 0 0 80px; }
.eject_con .adds .submit .btn { border: 0; width: 120px; height: 32px; background: url(<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/images/member/btn.gif) no-repeat 0 -253px; cursor: pointer; font-weight: bold; color: #3f3d3e; }

.sign_box { float: left; width: 300px; }
.eject_con .adds .sign { float: left; width: 150px; height: 50px; background: url(<?php echo SHOP_RESOURCE_SITE_URL;?>/keyword/images/member/default.gif); margin-right: 5px; }
</style>
<script type="text/javascript">
$(function(){
    $('#category_form').validate({

        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        onfocusout : false,
        onkeyup    : false,
        rules : {
            cate_name : {
                required : true
              
            },
            sort_order : {
                number   : true
            }
        },
        messages : {
            cate_name : {
                required : '分类名称不能为空'

            },
            sort_order  : {
                number   : '排序仅能为数字'
            }
        }
    });
});
</script>

<script>
$(function(){
	if($("input[type=radio][checked]").val()==0)
	{
		$('#key_value').html('KEY值:');
		$('#zhuce').html('用字母或数字组成。');
	}
	else
	{
		
		$('#key_value').html('url链接:');
		$('#zhuce').html('请填写您的链接');
	}
})
function get_value(key)
{
	if(key==0)
	{
		$('#key_value').html('KEY值:');
		$('#zhuce').html('用字母或数字组成。');
		
	}else
	{
		$('#key_value').html('url链接:');
		$('#zhuce').html('请填写您的链接');
	}
}
</script>
<style>
li {list-style:none;}
</style>
<ul class="tab">
    <li class="active"><?php if($output['op'] == 'add'){ ?>添加分类<?php }else{ ?>编辑分类<?php } ?></li>
</ul>
<div class="eject_con">
 <div class="adds">
        <div id="warning"></div>
        <form id="category_form" method="post" target="pop_warning" action="index.php?act=seller_wechat_menu&amp;op=<?php echo $output['op'];?><?php if($output['id']){ ?>&amp;id=<?php echo $output['id'];?><?php } ?>">
        <ul>
            <li>
                <h3>分类名称:</h3>
                <p><input class="text width_normal" type="text" name="cate_name" id="cate_name" value="<?php echo $output['gcategory']['cate_name'];?>" /><label class="field_notice"></label></p>
            </li>
            <li>
                <h3>分类上级:</h3>
                <p><select name="parent_id" id="parent_id">
                <option>请选择</option>
				<?php foreach($output['parents'] as $key => $val){ ?>
				<option value="<?php echo $key;?>" <?php if($output['gcategory']['parent_id'] == $key){?> selected <?php } ?>><?php echo $val;?></option>
				<?php } ?>
                </select></p>
            </li>
            <li>
                <h3>类型:</h3>
                <p>
                 按钮: <input  onclick="get_value(0);"  <?php if($output['gcategory']['type'] == 0){ ?> checked <?php } ?> type="radio" value="0" name="type"> URL类型:<input  onclick="get_value(1);"  <?php if($output['gcategory']['type'] == 1){ ?> checked <?php } ?>  value="1" type="radio" name="type" ><br>
                 <b style="color:red;">选择URL类型：关键词不用填,key值直接填链接<br>微商城 首页 先选择URL类型  URL链接地址：例如http://www.513nc.com/index.php?act=show_store&op=index&store_id=1<br>
获取账户信息  按钮  KEY值：getuser<br>
我的二维码 按钮 KEY值：qrcode  关键词：qrcode</b>
                </p>
            </li>
            <li>
                <h3 id='key_value'>KEY值:</h3>
                <p><input type="text" name="keyvalue" value="<?php echo $output['gcategory']['keyvalue'];?>" style="width:176px;"  class="text "/><font style="color:red;" id='zhuce'>用字母或数字组成</font></p>
            </li>
           <li>
                <h3>关键词:</h3>
                <p><input type="text" name="keyword" value="<?php echo $output['gcategory']['keyword'];?>"  style="width:176px;"  class="text "/></p>
            </li>
            
            
            <li>
                <h3>排序:</h3>
                <p><input type="text" name="sort_order" value="<?php echo $output['gcategory']['sort_order'];?>"  class="text width_short"/></p>
            </li>
            <li>
                <h3>显示:</h3>
                <p><label>
                 <input type="radio" name="if_show" value="1" {if $gcategory.if_show}checked="checked"{/if} />
                是</label>
                <label>
                <input type="radio" name="if_show" value="0" {if !$gcategory.if_show}checked="checked"{/if} />
                否</label></p>
            </li>
        </ul>
        <div class="submit"><input type="submit" class="btn" value="提交" /></div>
        </form>
    </div>
</div>