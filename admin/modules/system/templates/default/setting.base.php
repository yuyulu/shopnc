<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['web_set'];?></h3>
        <h5><?php echo $lang['web_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>网站全局基本设置，商城及其他模块相关内容在其各自栏目设置项内进行操作。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="site_name"><?php echo $lang['web_name'];?></label>
        </dt>
        <dd class="opt">
          <input id="site_name" name="site_name" value="<?php echo $output['list_setting']['site_name'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['web_name_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="icp_number"><?php echo $lang['icp_number'];?></label>
        </dt>
        <dd class="opt">
          <input id="icp_number" name="icp_number" value="<?php echo $output['list_setting']['icp_number'];?>" class="input-txt" type="text" />
          <p class="notic"><?php echo $lang['icp_number_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="statistics_code"><?php echo $lang['flow_static_code'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="statistics_code" rows="6" class="tarea" id="statistics_code"><?php echo $output['list_setting']['statistics_code'];?></textarea>
          <p class="notic"><?php echo $lang['flow_static_code_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="time_zone"> <?php echo $lang['time_zone_set'];?></label>
        </dt>
        <dd class="opt">
          <select id="time_zone" name="time_zone">
            <option value="-12">(GMT -12:00) Eniwetok, Kwajalein</option>
            <option value="-11">(GMT -11:00) Midway Island, Samoa</option>
            <option value="-10">(GMT -10:00) Hawaii</option>
            <option value="-9">(GMT -09:00) Alaska</option>
            <option value="-8">(GMT -08:00) Pacific Time (US &amp; Canada), Tijuana</option>
            <option value="-7">(GMT -07:00) Mountain Time (US &amp; Canada), Arizona</option>
            <option value="-6">(GMT -06:00) Central Time (US &amp; Canada), Mexico City</option>
            <option value="-5">(GMT -05:00) Eastern Time (US &amp; Canada), Bogota, Lima, Quito</option>
            <option value="-4">(GMT -04:00) Atlantic Time (Canada), Caracas, La Paz</option>
            <option value="-3.5">(GMT -03:30) Newfoundland</option>
            <option value="-3">(GMT -03:00) Brassila, Buenos Aires, Georgetown, Falkland Is</option>
            <option value="-2">(GMT -02:00) Mid-Atlantic, Ascension Is., St. Helena</option>
            <option value="-1">(GMT -01:00) Azores, Cape Verde Islands</option>
            <option value="0">(GMT) Casablanca, Dublin, Edinburgh, London, Lisbon, Monrovia</option>
            <option value="1">(GMT +01:00) Amsterdam, Berlin, Brussels, Madrid, Paris, Rome</option>
            <option value="2">(GMT +02:00) Cairo, Helsinki, Kaliningrad, South Africa</option>
            <option value="3">(GMT +03:00) Baghdad, Riyadh, Moscow, Nairobi</option>
            <option value="3.5">(GMT +03:30) Tehran</option>
            <option value="4">(GMT +04:00) Abu Dhabi, Baku, Muscat, Tbilisi</option>
            <option value="4.5">(GMT +04:30) Kabul</option>
            <option value="5">(GMT +05:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
            <option value="5.5">(GMT +05:30) Bombay, Calcutta, Madras, New Delhi</option>
            <option value="5.75">(GMT +05:45) Katmandu</option>
            <option value="6">(GMT +06:00) Almaty, Colombo, Dhaka, Novosibirsk</option>
            <option value="6.5">(GMT +06:30) Rangoon</option>
            <option value="7">(GMT +07:00) Bangkok, Hanoi, Jakarta</option>
            <option value="8">(GMT +08:00) Beijing, Hong Kong, Perth, Singapore, Taipei</option>
            <option value="9">(GMT +09:00) Osaka, Sapporo, Seoul, Tokyo, Yakutsk</option>
            <option value="9.5">(GMT +09:30) Adelaide, Darwin</option>
            <option value="10">(GMT +10:00) Canberra, Guam, Melbourne, Sydney, Vladivostok</option>
            <option value="11">(GMT +11:00) Magadan, New Caledonia, Solomon Islands</option>
            <option value="12">(GMT +12:00) Auckland, Wellington, Fiji, Marshall Island</option>
          </select>
          <p class="notic"><?php echo $lang['set_sys_use_time_zone'];?>+8</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['site_state'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="site_status1" class="cb-enable <?php if($output['list_setting']['site_status'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['open'];?></label>
            <label for="site_status0" class="cb-disable <?php if($output['list_setting']['site_status'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['close'];?></label>
            <input id="site_status1" name="site_status" <?php if($output['list_setting']['site_status'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="site_status0" name="site_status" <?php if($output['list_setting']['site_status'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['site_state_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="closed_reason"><?php echo $lang['closed_reason'];?></label>
        </dt>
        <dd class="opt">
          <textarea name="closed_reason" rows="6" class="tarea" id="closed_reason" ><?php echo $output['list_setting']['closed_reason'];?></textarea>
          <p class="notic"><?php echo $lang['closed_reason_notice'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="document.form1.submit()"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
		$('#time_zone').attr('value','<?php echo $output['list_setting']['time_zone'];?>');	
});
</script> 
