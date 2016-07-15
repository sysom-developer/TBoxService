$(document).ready( function() {
	var validInput = true;
		
	$("#password1").blur(function () {
		check_password();
	});
	
	$("#password2").blur(function () {
		check_password();
	});

	function check_password()
	{
		var password1 = $("#password1").val();
		var password2 = $("#password2").val();
		if(password1.length <6 || password1.length >30 || ! (/\d+/.test(password1) && /[A-Za-z]+/.test(password1)) ) {
			$("#password1").next().removeClass('success');
			$("#password1").next().addClass('error');
			$("#password1").next().html("密码长度6-30, 且包含数字和字母");
			validInput = false;
		}
		else
		{
			$("#password1").next().removeClass('error');
			$("#password1").next().addClass('success');
			$("#password1").next().html("");
		}
				
		if(password2!= "" && password2!=password1)
		{
			$("#password2").next().removeClass('success');
			$("#password2").next().addClass('error');
			$("#password2").next().html("两次密码不匹配");
			validInput = false;
		} 
		else if(password2!="" && password2==password1)
		{
			$("#password2").next().removeClass('error');
			$("#password2").next().addClass('success');
			$("#password2").next().html("");
		}
		
	}
	 $("form").submit(function(event) {
	 	   $("form").ajaxSubmit({
      url : url+'/change_password',
      beforeSubmit:function(){
      	return validInput;
      },
      success : function(result) {
        if(result!='1')
            alert(result);
          else
          {
            alert("修改成功");
             $("form").resetForm();
          }
           
      }
    });
	 	   return false;
	 });



});
