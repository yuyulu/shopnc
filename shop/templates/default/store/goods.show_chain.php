<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncs-chain-show">
  <dl>
    <dt>门店所在地区：</dt>
    <dd>
      <input type="hidden" value="" name="region" id="region">
    </dd>
  </dl>
  <div class="ncs-chain-list">
    <ul nctype="chain_see">
    </ul>
  </div>
</div>
<script>
$(function(){
    $('#region').nc_region({last_click:function (area_id){
        var _chain_list = <?php echo $output['chain_list']?>;
        _chain_check = eval('_chain_list[' +area_id +' ]');
        $('ul[nctype="chain_see"]').html('');
        $('.ncs-chain-no-date').remove();
        if (typeof(_chain_check) == 'undefined') {
            $('<div class="ncs-chain-no-date">很抱歉，该区域暂无门店有货，正努力补货中•••</div>').insertAfter('ul[nctype="chain_see"]');
            return false;
        }
        for (var i=0;i < _chain_check.length;i++) {
            _chain = eval('_chain_check[' + i +']');
            $('<li><div class="handle"><a href="javascript:;" onclick="buynow(<?php echo $_GET['goods_id'];?>,1,'+_chain.chain_id+','+area_id+',\''+_chain.chain_name+'（'+_chain.area_info+_chain.chain_address+'）\','+_chain.area_id_2+')">马上自提></a></div><h5><i></i><a target="_blank" href="<?php echo urlShop('show_chain', 'index');?>&chain_id=' + _chain.chain_id + '">' + _chain.chain_name + '</a></h5><p>' + _chain.area_info + ' ' + _chain.chain_address + '</p></li>').appendTo('ul[nctype="chain_see"]');
        }
        return false;
    }});
});
</script>