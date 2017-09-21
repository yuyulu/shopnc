<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['nc_picture_set'];?></h3>
        <h5><?php echo $lang['nc_picture_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dt class="tit">
        <label for="site_name"><?php echo $lang['image_dir_type'];?></label>
      </dt>
      <dd class="opt">
        <ul class="list">
          <li>
            <input id="image_dir_type_0" name="image_dir_type" type="radio" value="1"<?php echo $output['list_setting']['image_dir_type'] == '1' ? ' checked="checked"' : '' ;?>/>
            <label for="image_dir_type_0"><?php echo $lang['image_dir_type_0'];?></label>
          </li>
          <li>
            <input id="image_dir_type_1" name="image_dir_type" type="radio" value="2"<?php echo $output['list_setting']['image_dir_type'] == '2' ? ' checked="checked"' : '' ;?>/>
            <label for="image_dir_type_1"><?php echo $lang['image_dir_type_1'];?></label>
          </li>
          <li>
            <input id="image_dir_type_2" name="image_dir_type" type="radio" value="3"<?php echo $output['list_setting']['image_dir_type'] == '3' ? ' checked="checked"' : '' ;?>/>
            <label for="image_dir_type_2"><?php echo $lang['image_dir_type_2'];?></label>
          </li>
          <li>
            <input id="image_dir_type_3" name="image_dir_type" type="radio" value="4"<?php echo $output['list_setting']['image_dir_type'] == '4' ? ' checked="checked"' : '' ;?>/>
            <label for="image_dir_type_3"><?php echo $lang['image_dir_type_3'];?></label>
          </li>
        </ul>
        <span class="err"></span>
        <p class="notic"></p>
      </dd>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
