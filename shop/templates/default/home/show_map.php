<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="ncs-store-map-content">
  <div class="ncs-store-map-baidu" id="baidu_map" style="width:<?php echo $_GET['w']; ?>px;height:<?php echo $_GET['h']; ?>px;"></div>
  <div class="ncs-store-map-info">
    <div class="store-district">线下店区域选择&nbsp;
      <select class="select" name="baidu_district" id="baidu_district" onchange="select_district();">
        <option value="">所有城区</option>
      </select>
    </div>
    <div class="address-box" id="address_list">
      <div class="address-list" id="item_list"> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    var baidu_map = {};
    var map_list = new Array();
    var district_list = new Array();

    <?php if (is_array($output['map_list']) && !empty($output['map_list'])) { ?>
        <?php foreach ($output['map_list'] as $key => $val) { ?>
            map_list[<?php echo $key; ?>] = {
            'key': <?php echo $key; ?>,
            'map_id': <?php echo $val['map_id']; ?>,
            'name_info': "<?php echo $val['name_info']; ?>",
            'address_info': "<?php echo $val['address_info']; ?>",
            'phone_info': "<?php echo $val['phone_info']; ?>",
            'bus_info': "<?php echo $val['bus_info']; ?>",
            'city': "<?php echo $val['baidu_city']; ?>",
            'district': "<?php echo $val['baidu_district']; ?>",
            'lng': <?php echo $val['baidu_lng']; ?>,
            'lat': <?php echo $val['baidu_lat']; ?>
            };
            set_district(map_list[<?php echo $key; ?>]);
        <?php } ?>
    <?php } ?>
	if (typeof BMap == 'object') {
	    baidu_init();
	} else {
	    load_script();
	}
	function local_city(cityResult){
	    baidu_map.centerAndZoom(cityResult.center, 15);
	    select_district();
	}
    function select_district() {//区域筛选
        var district = $("#baidu_district").val();
        var point_list = new Array();
        var k = 0;
        baidu_map.clearOverlays();
        $('#item_list').html('');
        for (var i in map_list){
            var obj = map_list[i];
            if (district == '' || district == obj.district) {
                var point = new BMap.Point(obj.lng, obj.lat);
                var marker = new BMap.Marker(point);
                marker.setTitle(obj.name_info);
                baidu_map.addOverlay(marker);
                point_list[k++] = obj;
                set_address(obj);
                marker_info(marker,obj);
            }
        }
        baidu_map.setViewport(point_list);
	    $('#address_list').perfectScrollbar('destroy');
	    $('#address_list').perfectScrollbar({suppressScrollX:true});
	}
	function marker_info(marker,obj){//开启信息窗口
	    marker.addEventListener("click", function(){
    	    var point = new BMap.Point(obj.lng, obj.lat);
            var opts = {
                'title': obj.name_info,
                'width' : 220,
                'height': 60
            }
    	    var infoWindow = new BMap.InfoWindow("地址："+obj.address_info,opts);
    	    baidu_map.openInfoWindow(infoWindow,point);
	    });
	}
	function set_address(obj){//地址信息
	    var text_append = '';
	    text_append += '<dl class="map-store-info">';
	    text_append += '<dt class="map-store-name">'+obj.name_info+'</dt>';
	    text_append += '<dd class="map-store-address">地址：'+obj.address_info+'</dd>';
	    text_append += '<dd class="map-store-phone">电话：'+obj.phone_info+'</dd>';
	    text_append += '<dd class="map-store-bus">公交：'+obj.bus_info+'</dd>';
	    text_append += '</dl>';
	    $('#item_list').append(text_append);
	}
	function set_district(obj){//区域信息
        if ( typeof district_list[obj.district] == "undefined" ) {
            $("#baidu_district").append('<option value="'+obj.district+'">'+obj.district+'</option>');
            district_list[obj.district] = 1;
        } else {
            district_list[obj.district] += 1;
        }
	}
    function load_script() {//异步加载地图
		var script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "http://api.map.baidu.com/api?v=1.4&callback=baidu_init";
		document.body.appendChild(script);
    }
	function baidu_init() {//初始化地图
		baidu_map = new BMap.Map("baidu_map", {enableMapClick:false});
		var city = new BMap.LocalCity();;
		var top_left_navigation = new BMap.NavigationControl();
		var overView = new BMap.OverviewMapControl();
		baidu_map.addControl(top_left_navigation);
		baidu_map.addControl(overView);
		baidu_map.enableScrollWheelZoom(true);
		city.get(local_city);
	}
</script>