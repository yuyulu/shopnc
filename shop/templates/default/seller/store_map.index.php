<?php defined('In33hao') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <?php if (is_array($output['map_list']) && count($output['map_list']) < 20) { ?>
  <a class="ncbtn ncbtn-mint" nc_type="dialog" dialog_title="添加地址" dialog_id="add_map" dialog_width="480" uri="<?php echo urlShop('store_map', 'add_map');?>"><i class="icon-plus-sign"></i>添加地址</a>
  <?php } ?>
</div>
<div class="alert alert-block mt10">
  <ul>
    <li>1、系统借助“百度地图”进行定位，使用时要确保网络能正常访问。</li>
    <li>2、由于地图的窗口大小限制，最多可添加20个地址。可在“列表显示”中修改和删除已添加的地址。</li>
  </ul>
</div>

<div id="baidu_map" style="height:600px;border:1px solid gray"></div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
<script type="text/javascript">
    var map = new BMap.Map("baidu_map", {enableMapClick:false});
    var geo = new BMap.Geocoder();
	var city = new BMap.LocalCity();
	var top_left_navigation = new BMap.NavigationControl();
	var overView = new BMap.OverviewMapControl();
	var currentArea = '';//当前地图中心点的区域对象
	var currentCity = '';//当前地图中心点的所在城市
	var idArray = new Array();

	map.addControl(top_left_navigation);
	map.addControl(overView);
	map.enableScrollWheelZoom(true);
	city.get(local_city);
	function local_city(cityResult){
	    map.centerAndZoom(cityResult.center, 15);
	    currentCity = cityResult.name;
	    <?php if (is_array($output['map_list']) && !empty($output['map_list'])) { ?>
	        var pointArray = new Array();
	        var point = '';
	        var marker = '';
	        var label = '';
	        var k = 0;
	        <?php foreach ($output['map_list'] as $key => $val) { ?>
	            point = new BMap.Point(<?php echo $val['baidu_lng']; ?>, <?php echo $val['baidu_lat']; ?>);
	            pointArray[k++] = point;
	            label = new BMap.Label("<?php echo $val['name_info']; ?>",{offset:new BMap.Size(20,-10)});
	            marker = new BMap.Marker(point);
	            marker.setTitle('地址-'+k);
	            marker.setLabel(label);
	            marker.enableDragging();
	            marker.addEventListener("dragend",getMarkerPoint);
	            map.addOverlay(marker);
	            idArray['地址-'+k] = <?php echo $val['map_id']; ?>;
	        <?php } ?>

	        map.setViewport(pointArray);
	    <?php } ?>
	}
	function getMarkerPoint(e){//拖拽结束时通过点找到地区
	    var marker = e.target;
	    var point = marker.getPosition();
	    var title = marker.getTitle();
	    var map_id = idArray[title];

	    getPointArea(point,function(pointArea){
	        var obj = {
	            'map_id': map_id,
	            'province': pointArea.province,
	            'city': pointArea.city,
	            'district': pointArea.district,
	            'street': pointArea.street,
	            'lng': point.lng,
	            'lat': point.lat
	            };

    		$.ajax({
    			type: "POST",
    			url: 'index.php?act=store_map&op=update_map',
    			data: obj,
    			async: false,
    		    success: function(rs){
    		    }
    		});
	    });
	}
	function getPointArea(point,callback){//通过点找到地区
	    geo.getLocation(point, function(rs){
	        var addComp = rs.addressComponents;
	        if(addComp.province != '') callback(addComp);
	    }, {numPois:1});
	}
</script>
