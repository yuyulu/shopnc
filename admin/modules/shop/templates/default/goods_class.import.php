<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['goods_class_index_class'];?></h3>
        <h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="charset" value="gbk" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['goods_class_import_choose_file'];?></label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"><span class="type-file-box">
            <input type="file" name="csv" id="csv" class="type-file-file"  size="30"  />
            </span></div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['goods_class_import_file_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['goods_class_import_file_type'];?></label>
        </dt>
        <dd class="opt">
          <table border="1" cellpadding="3" cellspacing="3" bordercolor="#CCC">
            <tbody>
              <tr>
                <td bgcolor="#EFF8F8"><?php echo $lang['nc_sort'];?></td>
                <td bgcolor="#FFFFEC"><?php echo $lang['goods_class_import_first_class'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#EFF8F8"><?php echo $lang['nc_sort'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
                <td bgcolor="#FFFFEC"><?php echo $lang['goods_class_import_second_class'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#EFF8F8"><?php echo $lang['nc_sort'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
                <td bgcolor="#FFFFEC"><?php echo $lang['goods_class_import_second_class'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
              </tr>
              <tr>
                <td bgcolor="#EFF8F8"><?php echo $lang['nc_sort'];?></td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
                <td bgcolor="#FFFFEC">&nbsp;</td>
                <td bgcolor="#FFFFEC"><?php echo $lang['goods_class_import_third_class'];?></td>
              </tr>
              <tr>
                <td bgcolor="#EFF8F8"><?php echo $lang['nc_sort'];?></td>
                <td bgcolor="#FFFFEC"><?php echo $lang['goods_class_import_first_class'];?></td>
                <td bgcolor="#FFFFEC"></td>
                <td bgcolor="#FFFFEC"></td>
              </tr>
            </tbody>
          </table>
          <p class="notic"><a href="<?php echo RESOURCE_SITE_URL;?>/examples/goods_class.csv" class="ncap-btn"><?php echo $lang['goods_class_import_example_tip'];?></a></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:document.form1.submit();" class="ncap-btn-big ncap-btn-green"><?php echo $lang['goods_class_import_import'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
	$(function(){
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
	$(textButton).insertBefore("#csv");
	$("#csv").change(function(){
	$("#textfield1").val($("#csv").val());
	});
});
</script> 
