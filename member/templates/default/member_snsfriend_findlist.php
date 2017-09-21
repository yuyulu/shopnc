<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="ncm-friend-find"> 
    <!-- 搜索好友start -->
    <form method="post" id="search_form" action="index.php?act=member_snsfriend&op=findlist">
      <div class="search-form">
        <div class="normal"> <?php echo $lang['snsfriend_find_keytip'];?>
          <input type="text" class="text w400" name="searchname" id="searchname" value="<?php echo $_POST['searchname'];?>">
          <a class="ncbtn ncbtn-mint" nctype="search_submit"><?php echo $lang['snsfriend_search'];?></a> <a href="javascript:void(0);" nctype="advanced_search" class="ncbtn"><?php echo $lang['snsfriend_advanced_search'];?></a> </div>
        <div class="complex" nctype="advanced_search"> 所在地：
          <input type="hidden" name="region" id="region" value="">
          <input type="hidden" name="provinceid" id="_area_1" value="">
          <input type="hidden" name="cityid" id="_area_2" value="">
          <input type="hidden" name="areaid" id="_area" value="">
          <?php echo $lang['snsfriend_age'].$lang['nc_colon'];?><select name="age" id="age">
            <option value="0">-请选择-</option>
            <option value="1"><?php echo $lang['snsfriend_age_less_than_18'];?></option>
            <option value="2"><?php echo $lang['snsfriend_age_between_18_to_24'];?></option>
            <option value="3"><?php echo $lang['snsfriend_age_between_25_to_30'];?></option>
            <option value="4"><?php echo $lang['snsfriend_age_between_31_to_35'];?></option>
            <option value="5"><?php echo $lang['snsfriend_age_more_than_35'];?></option>
          </select>
          <?php echo $lang['snsfriend_sex'].$lang['nc_colon'];?><select name="sex" id="sex">
            <option value="">-请选择-</option>
            <option value="1"><?php echo $lang['snsfriend_man'];?></option>
            <option value="2"><?php echo $lang['snsfriend_woman'];?></option>
          </select>
        </div>
      </div>
    </form>
    <div>
      <?php if ($output['memberlist']) { ?>
      <ul class="ncm-friend-list">
        <?php foreach($output['memberlist'] as $k => $v){ ?>
        <li id="recordone_<?php echo $v['member_id']; ?>">
          <div class="avatar"><a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['member_id']));?>" target="_blank"><img src="<?php echo getMemberAvatar($v['member_avatar']);?>" alt="<?php echo $v['member_name']; ?>" data-param="{'id':<?php echo $v['member_id'];?>}" nctype="mcard" /></a></div>
          <dl class="info">
            <dt><a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['member_id']));?>" title="<?php echo $v['friend_tomname']; ?>" target="_blank" data-param="{'id':<?php echo $v['member_id'];?>}" nctype="mcard"><?php echo $v['member_name']; ?></a><i class="<?php echo $v['sex_class']; ?>"></i></dt>
            <dd class="area"><?php echo $v['member_areainfo'];?></dd>
            <dd><a href="<?php echo urlMember('member_message', 'sendmsg', array('member_id' => $v['member_id']));?>" target="_blank"><i class="icon-envelope"></i><?php echo $lang['nc_message'] ;?></a></dd>
          </dl>
          <div class="follow" nc_type="signmodule">
            <p nc_type="mutualsign" style="<?php echo $v['followstate']!=2?'display:none;':'';?>"><i></i><?php echo $lang['snsfriend_follow_eachother'];?></p>
            <p nc_type="followsign" style="<?php echo $v['followstate']!=1?'display:none;':'';?>"><?php echo $lang['snsfriend_followed'];?></p>
            <a href="javascript:void(0)" class="ncbtn-mini ncbtn-mint" nc_type="followbtn" data-param='{"mid":"<?php echo $v['member_id'];?>"}'style="<?php echo $v['followstate']!=0?'display:none;':'';?>"><?php echo $lang['snsfriend_followbtn'];?></a></div>
        </li>
        <?php } ?>
      </ul>
      <?php } else{?>
      <div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
      <?php }?>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('#region').nc_region({show_deep:3});
	$('a[nctype="search_submit"]').click(function(){
		// 验证用户名是否为空
		if($('#searchname').val() != ''){
		    $('#search_form').submit();
		}else{
			$('#searchname').addClass('error').focus();
		}
	});
	
	// 高级搜索显示与隐藏
	$('a[nctype="advanced_search"]').click(function(){
		$('div[nctype="advanced_search"]').toggle('fast');
	});

});
</script> 
