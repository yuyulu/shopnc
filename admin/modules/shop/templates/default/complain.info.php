<?php defined('In33hao') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['complain_manage_title']; ?></h3>
                <h5><?php echo $lang['complain_manage_subhead']; ?></h5>
            </div>
        </div>
    </div>
    <div class="ncap-order-style">
        <div class="ncap-order-flow">
            <ol class="num5">
                <li id="state_new" class="">
                    <h5><?php echo $lang['complain_state_new']; ?></h5>
                    <i class="fa fa-arrow-circle-right"></i></li>
                <li id="state_appeal" class="">
                    <h5><?php echo $lang['complain_state_appeal']; ?></h5>
                    <i class="fa fa-arrow-circle-right"></i></li>
                <li id="state_talk" class="">
                    <h5><?php echo $lang['complain_state_talk']; ?></h5>
                    <i class="fa fa-arrow-circle-right"></i></li>
                <li id="state_handle" class="">
                    <h5><?php echo $lang['complain_state_handle']; ?></h5>
                    <i class="fa fa-arrow-circle-right"></i></li>
                <li id="state_finish" class="">
                    <h5><?php echo $lang['complain_state_finish']; ?></h5>
                </li>
            </ol>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                var state = <?php echo empty($output['complain_info']['complain_state']) ? 0 : $output['complain_info']['complain_state'];?>;
                if (state <= 10) {
                    $("#state_new").addClass('current');
                }
                if (state == 20) {
                    $("#state_new").addClass('current');
                    $("#state_appeal").addClass('current');
                }
                if (state == 30) {
                    $("#state_new").addClass('current');
                    $("#state_appeal").addClass('current');
                    $("#state_talk").addClass('current');
                }
                if (state == 40) {
                    $("#state_new").addClass('current');
                    $("#state_appeal").addClass('current');
                    $("#state_talk").addClass('current');
                    $("#state_handle").addClass('current');
                }
                if (state == 99) {
                    $("#state_new").addClass('current');
                    $("#state_appeal").addClass('current');
                    $("#state_talk").addClass('current');
                    $("#state_handle").addClass('current');
                    $("#state_finish").addClass('current');
                }
            });
        </script>
        <div class="ncap-order-details">
            <ul class="tabs-nav">
                <li class="current"><a href="javascript:void(0);"><?php echo $lang['order_detail']; ?></a></li>
                <?php if (!empty($output['refund_list']) && is_array($output['refund_list'])) { ?>
                    <li><a href="javascript:void(0);">退款信息</a></li>
                <?php } ?>
            </ul>
            <div class="tabs-panels">
                <div class="misc-info">
                    <dl>
                        <dt><?php echo $lang['order_shop_name']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><a href="<?php echo urlShop('show_store', 'index', array('store_id' => $output['order_info']['store_id'])); ?>" target="_blank"> <?php echo $output['order_info']['store_name']; ?> </a></dd>
                        <dt><?php echo $lang['order_state']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><?php echo $output['order_info']['order_state_text']; ?></dd>
                        <dt>订单号<?php echo $lang['nc_colon']; ?></dt>
                        <dd><a href="index.php?act=order&op=show_order&order_id=<?php echo $output['order_info']['order_id']; ?>"> <?php echo $output['order_info']['order_sn']; ?></a></dd>
                        <dt><?php echo $lang['order_datetime']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><?php echo date('Y-m-d H:i:s', $output['order_info']['add_time']); ?>
                        <dt><?php echo $lang['order_price']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><?php echo $lang['currency'] . $output['order_info']['order_amount']; ?>
                            <?php if ($output['order_info']['refund_amount'] > 0) { ?>
                                (退款:<?php echo $lang['currency'] . $output['order_info']['refund_amount']; ?>)
                            <?php } ?>
                            <?php if (!empty($output['order_info']['voucher_price'])) { ?>
                        <dt><?php echo $lang['order_voucher_price']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><?php echo $lang['currency'] . $output['order_info']['voucher_price'] . '.00'; ?>
                        <dt><?php echo $lang['order_voucher_sn']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd><?php echo $output['order_info']['voucher_code']; ?>
                            <?php } ?>
                    </dl>
                </div>
                <div class="goods-info">
                    <h4><?php echo $lang['complain_goods']; ?></h4>
                    <table>
                        <thead>
                        <tr>
                            <th colspan="2"><?php echo $lang['complain_goods_name']; ?></th>
                            <th><?php echo $lang['complain_text_num']; ?></th>
                            <th><?php echo $lang['complain_text_price']; ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ((array)$output['complain_goods_list'] as $complain_goods) { ?>
                            <tr>
                                <td><a style="text-decoration:none;" href="<?php echo urlShop('goods', 'index', array('goods_id' => $complain_goods['goods_id'])); ?>" target="_blank"> <img width="50" src="<?php echo cthumb($complain_goods['goods_image'], 60, $output['order_info']['store_id']); ?>"/> </a></td>
                                <td><p><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $complain_goods['goods_id'])); ?>" target="_blank"><?php echo $complain_goods['goods_name']; ?> </a></p>
                                    <p><?php echo orderGoodsType($complain_goods['goods_type']); ?></p></td>
                                <td><?php echo $complain_goods['goods_num']; ?></td>
                                <td><?php echo $lang['currency'] . $complain_goods['goods_price']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="100">&nbsp;&nbsp;<?php echo $lang['complain_content']; ?>
                                <div class="complain-intro"><?php echo $output['complain_info']['complain_content']; ?></div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <?php if (!empty($output['refund_list']) && is_array($output['refund_list'])) { ?>
                    <div class="tabs-panels">
                        <div class="goods-info">
                            <?php if ($output['order_info']['refund_amount'] > 0) { ?>
                                <p><?php echo $lang['refund_order_refund']; ?>:<b><?php echo $lang['currency'] . $output['order_info']['refund_amount']; ?></b></p>
                            <?php } ?>
                            <p> 注：下表中订单商品退款在处理中的或已经确认，不能再次退款。</p>
                            <table>
                                <tr>
                                    <th colspan="2"><?php echo $lang['complain_goods_name']; ?></th>
                                    <th>退款金额</th>
                                    <th>实际支付额</th>
                                    <th>商家审核</th>
                                    <th>平台确认</th>
                                    <th>购买数量</th>
                                    <th><?php echo $lang['complain_text_price']; ?></th>
                                </tr>
                                <?php foreach ($output['refund_list'] as $key => $val) { ?>
                                    <tr>
                                        <td width="65" align="center" valign="middle"><a style="text-decoration:none;" href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"> <img width="50" src="<?php echo thumb($val, 60); ?>"/> </a></td>
                                        <td class="intro"><p><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"><?php echo $val['goods_name']; ?> </a></p>
                                            <p><?php echo orderGoodsType($val['goods_type']); ?></p></td>
                                        <td width="10%"><?php echo $lang['currency'] . $val['extend_refund']['refund_amount']; ?></td>
                                        <td width="10%"><?php echo $lang['currency'] . $val['goods_pay_price']; ?></td>
                                        <td width="10%"><?php echo $output['state_array'][$val['extend_refund']['seller_state']]; ?></td>
                                        <td width="10%"><?php echo $val['extend_refund']['seller_state'] == 2 ? $output['admin_array'][$val['extend_refund']['refund_state']] : '无'; ?></td>
                                        <td width="10%"><?php echo $val['goods_num']; ?></td>
                                        <td width="10%"><?php echo $lang['currency'] . $val['goods_price']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="ncap-form-default">
            <div class="title">
                <h3><?php echo $lang['complain_message']; ?></h3>
            </div>
            <dl class="row">
                <dt class="tit"><?php echo $lang['complain_state']; ?><?php echo $lang['nc_colon']; ?></dt>
                <dd class="opt"><?php echo $output['complain_info']['complain_state_text']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit"><?php echo $lang['complain_subject_content']; ?><?php echo $lang['nc_colon']; ?></dt>
                <dd class="opt"><?php echo $output['complain_info']['complain_subject_content']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit"><?php echo $lang['complain_accuser']; ?><?php echo $lang['nc_colon']; ?></dt>
                <dd class="opt"><?php echo $output['complain_info']['accuser_name']; ?></dd>
            </dl>
            <dl class="row">
                <dt class="tit"><?php echo $lang['complain_evidence']; ?><?php echo $lang['nc_colon']; ?></dt>
                <dd class="opt">
                    <?php
                    if (empty($output['complain_info']['complain_pic1']) && empty($output['complain_info']['complain_pic2']) && empty($output['complain_info']['complain_pic3'])) {
                        echo $lang['complain_pic_none'];
                    } else {
                        $pic_link = 'index.php?act=show_pics&type=complain&pics=';
                        if (!empty($output['complain_info']['complain_pic1'])) {
                            $pic_link .= $output['complain_info']['complain_pic1'] . '|';
                        }
                        if (!empty($output['complain_info']['complain_pic2'])) {
                            $pic_link .= $output['complain_info']['complain_pic2'] . '|';
                        }
                        if (!empty($output['complain_info']['complain_pic3'])) {
                            $pic_link .= $output['complain_info']['complain_pic3'] . '|';
                        }
                        $pic_link = rtrim($pic_link, '|');
                        ?>
                        <a href="<?php echo $pic_link; ?>" target="_blank"><?php echo $lang['complain_pic_view']; ?></a>
                    <?php } ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit"><?php echo $lang['complain_datetime']; ?><?php echo $lang['nc_colon']; ?></dt>
                <dd class="opt"><?php echo date('Y-m-d H:i:s', $output['complain_info']['complain_datetime']); ?></dd>
            </dl>
        </div>
        <?php if (!empty($output['complain_info']['appeal_message'])) { ?>
            <div class="ncap-form-default">
                <div class="title">
                    <h3><?php echo $lang['complain_appeal_detail']; ?></h3>
                </div>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['complain_accused']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt"><?php echo $output['complain_info']['accused_name']; ?></dd>
                </dl>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['complain_appeal_evidence']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt">
                        <?php
                        if (empty($output['complain_info']['appeal_pic1']) && empty($output['complain_info']['appeal_pic2']) && empty($output['complain_info']['appeal_pic3'])) {
                            echo $lang['complain_pic_none'];
                        } else {
                            $pic_link = 'index.php?act=show_pics&type=complain&pics=';
                            if (!empty($output['complain_info']['appeal_pic1'])) {
                                $pic_link .= $output['complain_info']['appeal_pic1'] . '|';
                            }
                            if (!empty($output['complain_info']['appeal_pic2'])) {
                                $pic_link .= $output['complain_info']['appeal_pic2'] . '|';
                            }
                            if (!empty($output['complain_info']['appeal_pic3'])) {
                                $pic_link .= $output['complain_info']['appeal_pic3'] . '|';
                            }
                            $pic_link = rtrim($pic_link, '|');
                            ?>
                            <a href="<?php echo $pic_link; ?>" target="_blank"><?php echo $lang['complain_pic_view']; ?></a>
                        <?php } ?>
                    </dd>
                </dl>
                <dl class="row">
                    <dt class="tit"> <?php echo $lang['complain_appeal_datetime']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt"><?php echo date('Y-m-d H:i:s', $output['complain_info']['appeal_datetime']); ?></dd>
                </dl>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['complain_appeal_content']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt"><?php echo $output['complain_info']['appeal_message']; ?></dd>
                </dl>
            </div>
        <?php } ?>
        <?php if (intval($output['complain_info']['complain_state']) > 20) { ?>
            <div class="ncap-form-default">
                <div class="title">
                    <h3><?php echo $lang['talk_detail']; ?></h3>
                </div>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['talk_list']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt">
                        <div id="div_talk" class="div_talk"></div>
                    </dd>
                </dl>
                <?php if (intval($output['complain_info']['complain_state']) !== 99) { ?>
                    <dl cass="row">
                        <dt class="tit"><?php echo $lang['talk_send']; ?><?php echo $lang['nc_colon']; ?></dt>
                        <dd class="opt">
                            <textarea id="complain_talk" class="tarea"></textarea>
                            <div>
                                <a href="JavaScript:void(0);" id="btn_refresh" class="ncap-btn"><?php echo $lang['talk_refresh']; ?></a><a href="JavaScript:void(0);" id="btn_publish" class="ncap-btn"><?php echo $lang['talk_send']; ?></a>
                            </div>
                        </dd>
                    </dl>
                <?php } ?>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {
                    get_complain_talk();
                    $("#btn_publish").click(function () {
                        if ($("#complain_talk").val() == '') {
                            alert("<?php echo $lang['talk_null'];?>");
                        }
                        else {
                            publish_complain_talk();
                        }
                    });
                    $("#btn_refresh").click(function () {
                        get_complain_talk();
                    });
                });

                function get_complain_talk() {
                    $("#div_talk").empty();
                    $.ajax({
                        type: 'POST',
                        url: 'index.php?act=complain&op=get_complain_talk',
                        cache: false,
                        data: "complain_id=<?php echo $output['complain_info']['complain_id'];?>",
                        dataType: 'json',
                        error: function () {
                            $("#div_talk").append("<p class='admin'>" + "<?php echo $lang['talk_none'];?>" + "</p>");
                        },
                        success: function (talk_list) {
                            if (talk_list.length >= 1) {
                                for (var i = 0; i < talk_list.length; i++) {
                                    var link = "<p class='" + talk_list[i].css + "'>" + talk_list[i].talk + "</p>";
                                    $("#div_talk").append(link);
                                }
                            }
                            else {
                                $("#div_talk").append("<p class='admin'>" + "<?php echo $lang['talk_none'];?>" + "</p>");
                            }
                        }
                    });
                }

                function publish_complain_talk() {
                    $.ajax({
                        type: 'POST',
                        url: 'index.php?act=complain&op=publish_complain_talk',
                        cache: false,
                        data: "complain_id=<?php echo $output['complain_info']['complain_id'];?>&complain_talk=" + encodeURIComponent($("#complain_talk").val()),
                        dataType: 'json',
                        error: function () {
                            alert("<?php echo $lang['talk_send_fail'];?>");
                        },
                        success: function (talk_list) {
                            if (talk_list == 'success') {
                                $("#complain_talk").val('');
                                get_complain_talk();
                                alert("<?php echo $lang['talk_send_success'];?>");
                            }
                            else {
                                alert("<?php echo $lang['talk_send_fail'];?>");
                            }
                        }
                    });
                }

                function forbit_talk(talk_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'index.php?act=complain&op=forbit_talk',
                        cache: false,
                        data: "talk_id=" + talk_id,
                        dataType: 'json',
                        error: function () {
                            alert("<?php echo $lang['talk_forbit_fail'];?>");
                        },
                        success: function (talk_list) {
                            if (talk_list == 'success') {
                                get_complain_talk();
                                alert("<?php echo $lang['talk_forbit_success'];?>");
                            }
                            else {
                                alert("<?php echo $lang['talk_forbit_fail'];?>");
                            }
                        }
                    });
                }
            </script>
        <?php } ?>
        <?php if (intval($output['complain_info']['complain_state']) == 99 && !empty($output['complain_info']['final_handle_message'])) { ?>
            <div class="ncap-form-default">
                <div class="title">
                    <h3><?php echo $lang['final_handle_detail']; ?></h3>
                </div>
                <dl class="row">
                    <dt class="tit"><?php echo $lang['final_handle_message']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt"><?php echo $output['complain_info']['final_handle_message']; ?></dd>
                </dl>
                <dl>
                    <dt class="tit"><?php echo $lang['final_handle_datetime']; ?><?php echo $lang['nc_colon']; ?></dt>
                    <dd class="opt"><?php echo date('Y-m-d H:i:s', $output['complain_info']['final_handle_datetime']); ?></dd>
                </dl>
                <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="history.go(-1)"><?php echo $lang['nc_back']; ?></a></div>
            </div>
        <?php } ?>
        <?php if (intval($output['complain_info']['complain_state']) !== 99) { ?>
            <div class="ncap-form-default">
                <div class="title">
                    <h3><?php echo $lang['complain_handle']; ?></h3>
                </div>
                <dl class="row" id="close_complain">
                    <dd class="opt">
                        <div class="bot">
                            <form method='post' id="verify_form" action="index.php?act=complain&op=complain_verify">
                                <input name="complain_id" type="hidden" value="<?php echo $output['complain_info']['complain_id']; ?>"/>
                                <?php if (intval($output['complain_info']['complain_state']) === 10) { ?>
                                    <a id="verify_button" class="ncap-btn-big ncap-btn-green" href="javascript:void(0)"><span><?php echo $lang['complain_text_verify']; ?></span></a>
                                <?php } ?>
                                <?php if (intval($output['complain_info']['complain_state']) !== 99) { ?>
                                    <a id="close_button" class="ncap-btn-big ncap-btn-green" href="javascript:void(0)"><span><?php echo $lang['complain_text_close']; ?></span></a>
                                <?php } ?>
                                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="history.go(-1)"><span><?php echo $lang['nc_back']; ?></span></a>
                            </form>
                        </div>
                    </dd>
                </dl>
                <form method='post' id="close_form" action="index.php?act=complain&op=complain_close">
                    <?php if (!empty($output['refund_goods']) && is_array($output['refund_goods'])) { ?>
                    <dl class="row complain_dialog">
                        <dt class="tit">可退款商品</dt>
                        <dd class="opt">
                            <p> 注：选中下表中订单商品可退款，可退款金额为0的商品不能进行操作。</p>
                            <table>
                                <tr>
                                    <th width="30">&nbsp;</th>
                                    <th colspan="2"><?php echo $lang['complain_goods_name']; ?></th>
                                    <th>可退款金额</th>
                                    <th>实际支付额</th>
                                    <th>购买数量</th>
                                    <th><?php echo $lang['complain_text_price']; ?></th>
                                </tr>
                                <?php foreach ($output['refund_goods'] as $key => $val) { ?>
                                    <tr>
                                        <td width="30"><?php if ($val['goods_refund'] > 0) { ?>
                                                <input class="checkitem" name="checked_goods[<?php echo $val['rec_id']; ?>]" type="checkbox" value="<?php echo $val['rec_id']; ?>"/>
                                            <?php } ?></td>
                                        <td width="65" align="center" valign="middle"><a style="text-decoration:none;" href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"> <img width="50" src="<?php echo thumb($val, 60); ?>"/> </a></td>
                                        <td class="intro"><p><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id'])); ?>" target="_blank"><?php echo $val['goods_name']; ?> </a></p>
                                            <p><?php echo orderGoodsType($val['goods_type']); ?></p></td>
                                        <td width="12%"><?php echo $lang['currency'] . $val['goods_refund']; ?></td>
                                        <td width="12%"><?php echo $lang['currency'] . $val['goods_pay_price']; ?></td>
                                        <td width="9%"><?php echo $val['goods_num']; ?></td>
                                        <td width="8%"><?php echo $lang['currency'] . $val['goods_price']; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                            </td>
                            </tr>
                            <?php } ?>
                            <dl class="row complain_dialog">
                                <dt class="tit"><?php echo $lang['final_handle_message']; ?>
                                    <input name="complain_id" type="hidden" value="<?php echo $output['complain_info']['complain_id']; ?>"/>
                                </dt>
                                <dd class="opt">
                                    <textarea id="final_handle_message" name="final_handle_message" class="tarea"></textarea>
                                </dd>
                            </dl>
                            <div class="bot complain_dialog"><a id="btn_handle_submit" class="ncap-btn-big ncap-btn-green" href="javascript:void(0)"><?php echo $lang['nc_submit']; ?></a> <a id="btn_close_cancel" class="ncap-btn-big ncap-btn-green" href="javascript:void(0)"><?php echo $lang['nc_cancel']; ?></a></div>
                </form>
            </div>
        <?php } ?>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {

            $(".complain_dialog").hide();
            $("#verify_button").click(function () {
                if (confirm("<?php echo $lang['verify_submit_message'];?>")) {
                    $("#verify_form").submit();
                }
            });
            $("#close_button").click(function () {
                $("final_handle_message").text('');
                $(".complain_dialog").show();
                $("#close_complain").hide();
            });
            $("#btn_handle_submit").click(function () {
                if ($("#final_handle_message").val() == '') {
                    alert("<?php echo $lang['final_handle_message_error'];?>");
                }
                else {
                    if (confirm("<?php echo $lang['complain_close_confirm'];?>")) {
                        $("#close_form").submit();
                    }
                }
            });
            $("#btn_close_cancel").click(function () {
                $(".complain_dialog").hide();
                $("#close_complain").show();
            });

        });
    </script>