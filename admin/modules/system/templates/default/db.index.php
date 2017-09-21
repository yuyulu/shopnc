<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['db_index_db'];?></h3>
        <h5>数据库恢复与备份</h5>
      </div>
      
      <ul class="tab-base nc-row"><li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['db_index_backup'];?></span></a></li><li><a href="index.php?act=db&op=db_restore"><span><?php echo $lang['db_index_restore'];?></span></a></li></ul> </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span> </div>
    <ul>
      <li><?php echo $lang['db_index_help1'];?></li>
            <li><?php echo $lang['db_index_help2'];?></li>
    </ul>
  </div>
  <form method="post" id="db_form">
    <input type="hidden" name="form_submit" value="ok" />
     <div class="ncap-form-all">
      <dl class="row">
      <dt class="tit"><span>备份数据库</span></dt>
        <dd class="opt nobg nopd nobd nobs">
          <div class="ncap-account-container">
            <h4>
            <label><?php echo $lang['db_index_backup_method'];?>:</label>
            </h4><ul class="ncap-account-container-list">
              <li>
                <input type="radio" checked="checked" value="all" id="backup_all" name="backup_type">
                <label for="backup_all"><?php echo $lang['db_index_all_data'];?></label>
              </li>
              <li>
                <input type="radio" value="custom" id="backup_custom" name="backup_type">
                <label for="backup_custom"><?php echo $lang['db_index_spec_table'];?></label>
              </li>
            </ul>          </div>
        </dd>
      </dl>
            <dl class="row"  style="display:none;"  id="tables">
      <dt class="tit"><span>选择需要备份的表</span></dt>
        <dd class="opt nobg nopd nobd nobs">
          <div class="ncap-account-container">
            <h4>
            <input type="checkbox" class="checkall" id="checkall">
            &nbsp;
            <label for="checkall"><?php echo $lang['nc_select_all'];?></label>
            </h4>
            <ul class="ncap-account-container-list">
                          <?php if(!empty($output['table_list']) && is_array($output['table_list'])){ ?>
              <?php foreach($output['table_list'] as $k => $v){ ?>
              <li>
                <input type="checkbox" value="<?php echo $v;?>" class="checkitem" name="tables[]">
                <label><?php echo $v;?></label>
              </li>
              <?php } ?>
              <?php } ?>
            </ul>
          </div>
        </dd>
      </dl></div>
      <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['db_index_size'];?>(kb)</dt>
        <dd class="opt">
          <input type="text" value="2048" name="file_size" class="input-txt">
          <p class="notic"><?php echo $lang['db_index_min_size'];?></p>
        </dd>
      </dl>
            <dl class="row">
        <dt class="tit"><?php echo $lang['db_index_name'];?></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['back_dir'];?>" name="backup_name" class="input-txt">
          <p class="notic"><?php echo $lang['db_index_name_tip'];?></p>
        </dd>
      </dl><div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="btn"><?php echo $lang['nc_submit'];?></a></div></div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#backup_all').click(function(){
		$('#tables').css('display','none');
		$(".checkitem").attr("checked",true);
	});
	$('#backup_custom').click(function(){
		$('#tables').css('display','');
		$(".checkitem").attr("checked",false);
	});
	$('#btn').click(function(){
		if(confirm('<?php echo $lang['db_index_backup_tip'];?>?')){
			$("#db_form").submit();
		}else{
			return false;
		}
	});
});
</script>