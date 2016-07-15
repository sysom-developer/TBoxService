/*	$(document).ready(function(){
	var sex=$('input[name=sex]:checked').val();
	var old=$('select[name=old]').val();
	var years=$('select[name=years]').val();
	$("input[name=sex]").change(function(){
	sex=$('input[name=sex]:checked').val();
	$.get(url+"/getbaofei",{"years":years,"sex":sex,"old":old,"form":form}, function(data) {
		var baoe=$("input[name=baoe]").val();
		
		if(baoe==null || baoe=="")
		{
			js=data;
		}
		else{
			js=data;
			$("input[name=year_money]").val(parseInt(parseInt($("input[name=baoe]").val())*js));
		}});
	});
	$("select[name=old]").change(function(){
		old=$('select[name=old]').val();
	$.get(url+"/getbaofei",{"years":years,"sex":sex,"old":old,"form":form}, function(data) {
		if(data!=null)
		var baoe=$("input[name=baoe]").val();
		
		if(baoe==null || baoe=="")
		{
			js=data;
		}
		else{
			js=data;
			$("input[name=year_money]").val(parseInt(parseInt($("input[name=baoe]").val())*js));
		}
	});
	});
	$("select[name=years]").change(function(){
		years=$('select[name=years]').val();
	$.get(url+"/getbaofei",{"years":years,"sex":sex,"old":old,"form":form}, function(data) {
		var baoe=$("input[name=baoe]").val();
		
		if(baoe==null || baoe=="")
		{
			js=data;
		}
		else{
			js=data;
			$("input[name=year_money]").val(parseInt(parseInt($("input[name=baoe]").val())*js));
		}
		
		});
	});
	$("input[name=baoe]").change(function(){
		if($(this).val()!="")
		$("input[name=year_money]").val(parseInt(parseInt($("input[name=baoe]").val())*js));
	}) 
});*/