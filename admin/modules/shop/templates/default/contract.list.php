<?php defined('In33hao') or exit('Access Invalid!');?>
<div class="page">
  <!-- 页面导航 -->
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>消费者保障服务</h3>
        <h5>消费者保障服务查看与管理</h5>
      </div>
        <ul class="tab-base nc-row">
            <?php   foreach($output['menu'] as $menu) {  if($menu['menu_key'] == $output['menu_key']) { ?>
                <li><a href="JavaScript:void(0);" class="current"><?php echo $menu['menu_name'];?></a></li>
            <?php }  else { ?>
                <li><a href="<?php echo $menu['menu_url'];?>" ><?php echo $menu['menu_name'];?></a></li>
            <?php  } }  ?>
        </ul>
    </div>
  </div>

  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>列表为店铺各项消费者保障服务信息以及其当下的状态</li>
      <li>点击“编辑”可以进入店铺加入服务的详情页面，并可以关闭店铺使用的保障服务</li>
    </ul>
  </div>
  <div id="flexigrid"></div>

    <div class="ncap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
    <div class="ncap-search-bar">
        <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
        <div class="title">
            <h3>高级搜索</h3>
        </div>
        <form method="get" name="formSearch" id="formSearch">
            <input type="hidden" name="advanced" value="1" />
            <div id="searchCon" class="content">
                <div class="layout-box">
                    <dl>
                        <dt>店铺名称</dt>
                        <dd>
                            <input type="text" name="search_storename" class="s-input-txt" placeholder="请输入店铺名称关键字" />
                        </dd>
                    </dl>
                    <dl>
                        <dt>保障服务</dt>
                        <dd>
                            <select name="search_itemid" class="s-select">
                                <option value="0" selected>全部</option>
                                <?php if ($output['item_list']){ ?>
                                    <?php foreach ($output['item_list'] as $k=>$v){ ?>
                                        <option value="<?php echo $v['cti_id'];?>"><?php echo $v['cti_name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                        <dt>加入状态</dt>
                        <dd>
                            <select name="search_state" class="s-select">
                                <option value="" selected>全部</option>
                                <?php if ($output['contract_joinstate_arr']){ ?>
                                    <?php foreach ($output['contract_joinstate_arr'] as $k=>$v){ ?>
                                        <option value="<?php echo $k;?>"><?php echo $v['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                    <dl>
                        <dt>禁用状态</dt>
                        <dd>
                            <select name="search_closestate" class="s-select">
                                <option value="" selected>全部</option>
                                <?php if ($output['contract_closestate_arr']){ ?>
                                    <?php foreach ($output['contract_closestate_arr'] as $k=>$v){ ?>
                                        <option value="<?php echo $k;?>"><?php echo $v['name'];?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="bottom">
                <a href="javascript:void(0);" id="ncsubmit" class="ncap-btn ncap-btn-green">提交查询</a>
                <a href="javascript:void(0);" id="ncreset" class="ncap-btn ncap-btn-orange" title="撤销查询结果，还原列表项所有内容"><i class="fa fa-retweet"></i><?php echo $lang['nc_cancel_search'];?></a>
            </div>
        </form>
    </div>
</div>

<script>
$(function(){
    var flexUrl = 'index.php?act=contract&op=contractlist_xml';
    $("#flexigrid").flexigrid({
        url: flexUrl,
        colModel: [
            {display: '操作', name: 'operation', width: 120, sortable: false, align: 'center', className: 'handle'},
            {display: '店铺名称', name: 'ct_storename', width: 200, sortable: false, align: 'center'},
            {display: '保障服务', name: 'ct_itemname', width: 250, sortable: false, align: 'left'},
            {display: '保证金余额(<?php echo $lang['currency_zh'];?>)', name: 'ct_cost', width: 120, sortable: false, align: 'right'},
            {display: '状态', name: 'ct_state_text', width: 220, sortable: false, align: 'left'}
        ],
        searchitems: [
            {display: '店铺名称', name: 'ct_storename'}
        ],
        sortname: "ct_id",
        sortorder: "desc",
        title: '店铺保障服务列表'
    });

    // 高级搜索提交
    $('#ncsubmit').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl + '&' + $("#formSearch").serialize(),query:'',qtype:''}).flexReload();
    });

    // 高级搜索重置
    $('#ncreset').click(function(){
        $("#flexigrid").flexOptions({url: flexUrl}).flexReload();
        $("#formSearch")[0].reset();
    });
});
</script>