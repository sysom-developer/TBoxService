$(document).ready(function() {
	var validInput = true;
	$("#sensor_title").blur(function () {
		var title = $.trim($(this).val()); 
		if(title.length<1 || title.length>30)
		{
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("传感器名称长度1-30");
			validInput = false;	
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	
	$("#sensor_type").change(function(){
		switch($(this).val())
		{
			case "0": //data sensor
				$("span.data_sensor").show();
				$("span.weibo").hide();
				break;
			case "10":
				$("span.weibo").show();
				$("span.data_sensor").hide();
				break;
			case "5":	//control sensor
			case "6": //GPS sensor
			case "8":	//GEN sensor
			case "9":
			$("span.weibo").hide();
				$("span.data_sensor").hide();
				break;
			default:
				break;
		}
	});
	
	$("#sensor_unit").blur(function () {
		var unit = $.trim($(this).val()); 
		if(unit.length<1 || unit.length>15)
		{
			$(this).next().next().removeClass('success');
			$(this).next().next().addClass('error');
			$(this).next().next().html("单位为1-10个字符");
			validInput = false;	
		}
		else
		{
			$(this).next().next().removeClass('error');
			$(this).next().next().addClass('success');
			$(this).next().next().html('');
		}
	});

    $("#sensor_unit_symbol").focus(function() {
        $("#sensor_unit_symbol_sel").css('display', '');
    });

    /*
    $(".sensor_unit_symbol_item").mouseenter(function() {
        $(this).css('background-color', '#00ccff');
    });

    $(".sensor_unit_symbol_item").mouseleave(function() {
        $(this).css('background-color', '');
    });
    */

    $(".sensor_unit_symbol_item").mousedown(function() {
        $("#sensor_unit_symbol").val($(this).text());
    });

	$("#sensor_unit_symbol").blur(function () {
		var unitSymbol = $.trim($(this).val()); 
		var unit = $.trim($("#sensor_unit").val()); 

        //setTimeout();
        $("#sensor_unit_symbol_sel").css('display', 'none');

		if(unitSymbol.length<1 || unitSymbol.length>6) {
			$(this).next().next().removeClass('success');
			$(this).next().next().addClass('error');
			$(this).next().next().html("符号为1-6个字符");
			validInput = false;
		} else if(unit.length<1 || unit.length>15) {
			$(this).next().next().removeClass('error');
			$(this).next().next().addClass('success');
			$(this).next().next().html('');

			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("单位为1-10个字符");
			validInput = false;	
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');

			$(this).next().next().removeClass('error');
			$(this).next().next().addClass('success');
			$(this).next().next().html('');
		}
	});
    
	
	$("#sensor_about").blur(function(){
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
     
/*   $("#sensor_min_value").blur(function () {
		var minValue = $.trim($(this).val()); 
		var reg = /^(-?([1-9]\d*\.\d*|0\.\d*|0))|(-?[1-9]\d*)$/;
		if(!reg.test(minValue) || minValue.length<1)
		{
			$(this).next().next().removeClass('success');
			$(this).next().next().addClass('error');
			$(this).next().next().html("输入出错，请重新输入");
			validInput = false;	
		}
		else
		{
			$(this).next().next().removeClass('error');
			$(this).next().next().html('');
		}
	});
	
	$("#sensor_max_value").blur(function () {
		var maxValue = $.trim($(this).val()); 
		var reg = /^(-?([1-9]\d*\.\d*|0\.\d*|0))|(-?[1-9]\d*)$/;
		if(!reg.test(maxValue) || maxValue.length<1 || (max_value.value<=min_value.value)) {
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("输入出错，请重新输入");
			validInput = false;
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
*/	
	$("#sensor_tags").blur(function () {
		var tags = $.trim($(this).val()); 
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

	$("form").submit(function(e) {
		validInput = true;
		$("#sensor_title").blur();
		switch($("#sensor_type").val())
		{
			case "0":
				$("#sensor_unit").blur();
				$("#sensor_unit_symbol").blur();
				//$("#sensor_max_value").blur();
				//$("#sensor_min_value").blur();
				break;
			case "10":
				if($('#access_token').val() == ''){
					$('#weiboGetAuth').next().removeClass('success');
					$('#weiboGetAuth').next().addClass('error');
					$('#weiboGetAuth').next().html("请获取授权");
					validInput = false;
				}
				break;
			default:
				break;
		}
		$("#sensor_tags").blur();
		$("#sensor_about").blur();
		if(!validInput) {
			e.preventDefault();
		}
	});
});
