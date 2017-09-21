<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if ($output['follow_list']) { ?>
  <ul class="ncm-friend-list">
    <?php foreach($output['follow_list'] as $k => $v){ ?>
    <li id="recordone_<?php echo $v['friend_tomid']; ?>">
      <div class="avatar"><a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['friend_tomid']));?>" target="_blank" data-param="{'id':<?php echo $v['friend_tomid'];?>}" nctype="mcard"><img src="<?php echo getMemberAvatar($v['member_avatar']);?>" alt="<?php echo $v['friend_tomname']; ?>"/></a></div>
      <dl class="info">
        <dt> <a href="<?php echo urlShop('member_snshome', 'index', array('mid' => $v['friend_tomid']));?>" target="_blank" title="<?php echo $v['friend_tomname']; ?>" data-param="{'id':<?php echo $v['friend_tomid'];?>}" nctype="mcard"><?php echo $v['friend_tomname']; ?></a><i class="<?php echo $v['sex_class'];?>"></i></dt>
        <dd><?php echo $v['member_areainfo'];?></dd>
        <dd><a href="<?php echo urlMember('member_message', 'sendmsg', array('member_id' => $v['friend_tomid']));?>" target="_blank" title="<?php echo $lang['nc_message'] ;?>"><i class="icon-envelope"></i><?php echo $lang['nc_message'] ;?></a> </dd>
      </dl>
      <div class="follow">
        <?php if($v['friend_followstate']==2){?>
        <p><i></i><?php echo $lang['snsfriend_follow_eachother'];?></p>
        <?php }?>
        <a href="javascript:void(0)" nc_type="cancelbtn" class="ncbtn-mini" data-param='{"mid":"<?php echo $v['friend_tomid'];?>"}'><?php echo $lang['snsfriend_cancel_follow'];?></a></div>
    </li>
    <?php } ?>
  </ul>
  <?php } else { ?>
  <div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div>
  <?php } ?>
  <?php if ($output['follow_list']) { ?>
  <div class="tc">
    <div class="pagination"> <?php echo $output['show_page']; ?> </div>
  </div>
  <?php } ?>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns_friend.js"></script> 
