
$(document).ready(function() {
		
	var validInput = true;
	
	$("#device_title").blur(function () {
		var title = $("#device_title").val();
		if(title.length<1 || title.length>30)
		{
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("设备名长度1-30");
			validInput = false;	
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	$("#device_tags").blur(function () {
		var tags = $(this).val();
		var reg = /^(([^\,]+[\,])*)([^\,]*)$/;
		if(!reg.test(tags) || tags.length<1) {
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("格式错误");
			validInput = false;
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	$('#device_loc_name').blur(function () {
		var loc_name = $(this).val();
		if(loc_name.length<1)
		{
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("位置名称不能为空");
			validInput = false;	
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	
	$("#device_about").blur(function(){
		if($.trim($(this).val()).length>30){
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("描述不能超过30个字符");
			validInput = false;
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	
	//judge whether device_lat_lng is NULL
	function lat()
	{
		var device_lat = $("#device_lat").val();
		if(device_lat =="") {
			$("#device_lat").next().removeClass('success');
			$("#device_lat").next().addClass('error');
			$("#device_lat").next().html("经纬度信息不能为空");
			validInput = false;
		}
		else
		{
			$("#device_lat").next().removeClass('error');
			$("#device_lat").next().addClass('success');
			$("#device_lat").next().html('');
		}
	}
	
	$("#device_manufacturer").change(function(){
		switch($(this).val())
		{
			case "0": //yeelink
				$("span.yeelink").show();
				$("span.diy").hide();
				break;
			case "1":	//diy
				$("span.yeelink").hide();
				$("span.diy").show();
				break;
			default:
				break;
		}
	});
	
/*	Gmap();
	function Gmap() {
		var centerMarker = null; // 中心标记
		var center = new google.maps.LatLng(36.071883,120.4339423);
		var initOptions = {
			zoom: 10,
			center: center,
			mapTypeControl: false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		var map = new google.maps.Map(document.getElementById("locmap"), initOptions);
		
		google.maps.event.addListener(map, "click", function(event) {
			if(centerMarker == null)
			{
				centerMarker = new google.maps.Marker( {position:new google.maps.LatLng(event.latLng.lat(), event.latLng.lng()), draggable:true, map:map} );			
				google.maps.event.addListener(centerMarker, "dragend", function(event) {
					var centerMarkerPos = centerMarker.getPosition();
					$("#device_lng").val(centerMarkerPos.lng());
					$("#device_lat").val(centerMarkerPos.lat());
				});
			}
			else
			{
				centerMarker.setPosition(new google.maps.LatLng(event.latLng.lat(),event.latLng.lng()));
			}
			$("#device_lng").val(event.latLng.lng());
			$("#device_lat").val(event.latLng.lat());
		});
		
		if($(".locmap").attr('edit_device') == "true")
		{
			centerMarker = new google.maps.Marker( {position:new google.maps.LatLng($("#device_lat").val(), $("#device_lng").val()), draggable:true, map:map} );
			google.maps.event.addListener(centerMarker, "dragend", function(event) {
				var centerMarkerPos = centerMarker.getPosition();
				$("#device_lng").val(centerMarkerPos.lng());
				$("#device_lat").val(centerMarkerPos.lat());
			});
		}
	}*/
	
	$("form").submit(function(e) {
		validInput = true;
		
		$("#device_title").blur();

		switch($("#device_manufacturer").val())
		{
			case "0":
				$("#device_tags").blur();
				$("#device_loc_name").blur();
			break;
			case "1":	
				$("#device_tags").blur();
				$("#device_loc_name").blur();
				lat();
			break;
		}
		$("#device_about").blur();
		if(!validInput) {
			e.preventDefault();
		}
	});
});