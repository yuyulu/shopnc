<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=inform&op=inform_list" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['inform_manage_title'];?> - 处理举报“<?php echo $output['inform_goods_name'];?>”</h3>
        <h5><?php echo $lang['inform_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="handle_form" method="post"  action="index.php?act=inform&op=inform_handle" name="form1">
    <input id="inform_id" name="inform_id" type="hidden" value="<?php echo $output['inform_id'];?>"/>
    <div class="ncap-form-default">
    <dl class="row">
      <dt class="tit">
        <label> <?php echo $lang['inform_goods_name'];?></label>
      </dt>
      <dd class="opt" id="goods_name"><?php echo $output['inform_goods_name'];?>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label><?php echo $lang['inform_handle_type'];?></label>
      </dt>
      <dd class="opt">
        <ul>
          <li>
            <label>
              <input type="radio" value="1" name="inform_handle_type">
              <?php echo $lang['inform_handle_type_unuse_message'];?> </label>
          </li>
          <li>
            <label>
              <input type="radio" value="2" name="inform_handle_type">
              <?php echo $lang['inform_handle_type_venom_message'];?> </label>
          </li>
          <li>
            <label>
              <input type="radio" value="3" name="inform_handle_type">
              <?php echo $lang['inform_handle_type_valid_message'];?> </label>
          </li>
        </ul>
        <p class="notic"></p>
      </dd>
    </dl>
    <dl class="row">
      <dt class="tit">
        <label><?php echo $lang['inform_handle_message'];?></label>
      </dt>
      <dd class="opt">
        <textarea class="tarea" name="inform_handle_message" rows="6" id="inform_handle_message"></textarea>
        <p class="notic"></p>
      </dd>
    </dl>
    <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn_handle_submit"><?php echo $lang['nc_submit'];?></a></div>
    </dl>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    //默认选中第一个radio
    $(":radio").first().attr("checked",true);
    $("#btn_handle_submit").click(function(){
        if($("#inform_handle_message").val()=='') {
            alert("<?php echo $lang['inform_handle_message_null'];?>");
        }
        else {
            if(confirm("<?php echo $lang['inform_handle_confirm'];?>")) {
                $("#handle_form").submit();
            }
        }
    });
});
</script> 