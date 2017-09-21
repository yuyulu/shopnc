    var baidu_map = {};
    var map_js_flag = '';
	function local_city(cityResult){
	    baidu_map.centerAndZoom(cityResult.center, 15);
	    select_district();
	}
    function select_district() {//区域筛选
        var point_list = new Array();
        var k = 0;
        baidu_map.clearOverlays();
        for (var i in map_list){
            var obj = map_list[i];
            if (map_index_id == '' || map_index_id == i) {
                var point = new BMap.Point(obj.lng, obj.lat);
                var marker = new BMap.Marker(point);
                marker.setTitle(obj.name_info);
                baidu_map.addOverlay(marker);
                point_list[k++] = obj;
                marker_info(marker,obj);
            }
        }
        baidu_map.setViewport(point_list);
	}
	function marker_info(marker,obj){//开启信息窗口
	    marker.addEventListener("click", function(){
    	    var point = new BMap.Point(obj.lng, obj.lat);
            var opts = {
                'title': "<p style='font-size:12px;'>" + obj.name_info + '</p>'
            }
    	    var infoWindow = new BMap.InfoWindow("<p style='font-size:12px;'>地址："+obj.address_info+"</p>",opts);
    	    baidu_map.openInfoWindow(infoWindow,point);
	    });
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
		baidu_map.enableScrollWheelZoom(true);
		baidu_map.enableDoubleClickZoom(true);
		city.get(local_city);
	}