$(document).ready( function() {
	var validInput = true;
	$("#useremail").blur(function () {
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
	
	$("form").submit(function(e) {
		validInput = true;
		$("#useremail").blur();
		if(!validInput) {
			e.preventDefault();
		}
	});


});
