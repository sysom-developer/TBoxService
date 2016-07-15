$(document).ready(function() {
	var validInput = true;
	$("#actName").blur(function () {
		var actName = $("#actName").val();
		if(actName.length<1 || actName.length>15)
		{
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("动作名长度1-15");
			validInput = false;	
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});
	
	$("#actType").change(function(){
		switch($(this).val())
		{
			case "1": //message
				$("span.phoneNumber").show();
				$("span.email").hide();
				$("span.weibo").hide();
				$("span.url").hide();
				$("span.mobile_push").hide();
				$("span.weixin").hide();
				break;
			case "2":	//email
				$("span.phoneNumber").hide();
				$("span.weibo").hide();
				$("span.email").show();
				$("span.url").hide();
				$("span.mobile_push").hide();
				$("span.weixin").hide();
				break;
			case "3":
				$("span.phoneNumber").hide();
				$("span.weibo").hide();
				$("span.email").hide();
				$("span.url").show();
				$("span.mobile_push").hide();
				$("span.weixin").hide();
				break;
			case "4":	//weibo
				$("span.email").hide();
				$("span.phoneNumber").hide();
				$("span.weibo").show();
				$("span.url").hide();
				$("span.mobile_push").hide();
				$("span.weixin").hide();
				break;
			case "5":	//IM-QQ
				break;
			case "6":	//Mobile Push
				$("span.email").hide();
				$("span.phoneNumber").hide();
				$("span.weibo").hide();
				$("span.url").hide();
				$("span.mobile_push").show();
				$("span.weixin").hide();
				break;
            case "7":
				$("span.email").hide();
				$("span.phoneNumber").hide();
				$("span.weibo").hide();
				$("span.url").hide();
				$("span.mobile_push").hide();
				$("span.weixin").show();
                break;
			default:
				break;
		}
	});
	
	$('#weiboProvider').change(function(){
		$('#access_token').val('');
		$('#access_token_show').html('');
		$('#expires_time').html('');
	});
	
	$('#weiboGetAuth').click(function(){
		var url = "";
		switch($("#weiboProvider").val())
		{
			//新浪微博
			case "sina":
				url = 'https://api.weibo.com/oauth2/authorize?client_id=3439134722&response_type=token&redirect_uri=';
				url += encodeURI('http://www.yeelink.net/user/weibo_callback?provider=sina');
				break;
			case "tencent":
				url = 'https://open.t.qq.com/cgi-bin/oauth2/authorize?client_id=801175626&response_type=token&redirect_uri=';
				url += encodeURI('http://www.yeelink.net/user/weibo_callback?provider=tencent');
				break;
			default:
				break;
		}
		window.open(url, '微博认证', 'height=450,width=600,toolbar=no,menubar=no,scrollbars=no,resizeable=no,status=no');
	});

	
	$("#number").blur(function () {
		var number = $("#number").val();
		var reg = /^1[358][0-9]{9}$/
		if(!reg.test(number))
		{
			$("#number").next().removeClass('success');
			$("#number").next().addClass('error');
			$("#number").next().html("手机号码格式错误");
			validInput = false;	
		}
		else
		{
			$("#number").next().removeClass('error');
			$("#number").next().addClass('success');
			$("#number").next().html('');
		}
	});
	
	$("#emailAddress").blur(function () {
		var email = $(this).val();
		var reg = /^[a-z0-9_+.-]+\@([a-z0-9-]+\.)+[a-z0-9]{2,4}$/;
		if(!reg.test(email)) {
			$(this).next().removeClass('success');
			$(this).next().addClass('error');
			$(this).next().html("电子邮件格式错误");
			validInput = false;
		}
		else
		{
			$(this).next().removeClass('error');
			$(this).next().addClass('success');
			$(this).next().html('');
		}
	});

	$("#postUrl_2,#postUrl_1").blur(function (){
		//var url = $(this).val();
		switch ($("input[name='chooseUrl']:checked").val())
		{
			case "1":
				var url = $("#postUrl_1").val();
				var reg = /^(http\:\/\/)([\w\.]+)(\/[\w- \.\/\?%&=#:+]*)?$/;
				if(!reg.test(url)){
					$(this).next().removeClass('success');
					$(this).next().addClass('error');
					$(this).next().html("请添加开关。");
					validInput = false;
				}
				else{
					$(this).next().removeClass('error');
					$(this).next().addClass('success');
					$(this).next().html('');
				}
				break;
			case "2":
				var url = $("#postUrl_2").val();
				var reg = /^(http\:\/\/)([\w\.]+)(\/[\w- \.\/\?%&=#:+]*)?$/;
				if(!reg.test(url)) {
					$(this).next().removeClass('success');
					$(this).next().addClass('error');
					$(this).next().html("网址格式错误");
					validInput = false;
				}
				else
				{
					$(this).next().removeClass('error');
					$(this).next().addClass('success');
					$(this).next().html('');
				}
				break;
			default:
				break;
		}
	});		
	$('.chooseUrl').change(function(e){
		e.preventDefault();
		 switch ($("input[name='chooseUrl']:checked").val())
		 {
			 case "1":
				$("span.chooseSwitch").show();
				$("span.writeUrl").hide();
				$("#postUrl_1").next().removeClass('error');
				$("#postUrl_1").next().html('');
				//$("#api_key").val(api_key);
				break;
			case "2":
				$("span.chooseSwitch").hide();
				$("span.writeUrl").show();
				$("#postUrl_2").next().removeClass('error');
				$("#postUrl_2").next().html('');
				if($("#act_apt_key").val() == api_key){
					$("#postUrl_2").val("");
				}
			default:
				break;
		}
		 	
	});
	
	$("#access_token").blur(function() {
		var access_token = $(this).val();
		var notify = $(".weibo_notify");
		if(access_token == "")
		{
			notify.removeClass('success');
			notify.addClass('error');
			notify.html("没有授权信息");
			validInput = false;
		}
		else
		{
			notify.removeClass('error');
			notify.addClass('success');
			notify.html('');
		}
	});

	$("form").submit(function(e) {
		validInput = true;
		$("#actName").blur();
		switch($("#actType").val())
		{
			case "1":
				$("#number").blur();
			  break;
			case "2":
				$("#emailAddress").blur();
			  break;
			case "3":
				$(",#postUrl_2").blur();
				$("#postUrl_1").blur();
				break;
			case "4":
				$("#access_token").blur();
				break;
			default:
				break;
		}

		if(!validInput) {
			e.preventDefault();
		}
	});
});
