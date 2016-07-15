	 function add_attr_product(name,obj){
var div=$(obj).parent("#"+name).find(".col-sm-4").last().clone();

if($(div).val()==undefined)
{
  $("#"+name).append("<div class='col-sm-4'><select class='form-control "+name+"' name='"+name+"_product_id[]'></select></div>");
  if(name=="main")
  { 
  var type={type:2};
  }
else{
  var type={type:3};
    }
    $.ajax({
    url : select_url,
    type : 'post',
    dataType : 'json',
    data:type,
    success : function(result) {
      $(result).each(
          function(n) {
            $("<option/>").html(this.name).val(this.id)
                .appendTo("."+name);
          });
    }
  });
}
else{
var value=$(div).find('select').val();
$(div).find("option[value='"+value+"']").remove();

if(!$(div).find("option").length==0)
{
$("#"+name).append($(div));
}
}
 
 }
    function recommend(obj,id){
		var type=$(obj).parents('tr').eq(0).find('td').eq(5).text();
	
		if(type=='是')
			type=0;
		else{
			type=1;
		}
        $.ajax({
              url: saveFormURL+"/recommend",
              type: 'post',
              data: {id:id,is_recommend:type},
              success: function(res) {
              	if(res!=1 && res!=0){
              		alert(res);
              	}else{
              		if(res==0){
					       res="否";
				      }else{
					       res="是";
				      }
              		 $(obj).parents('tr').eq(0).find('td').eq(5).text(res);
              	}
                 
              }
          });

}
function change_line(obj,id,type){
if(type==0)
{
	var url="/Online_is_off_all";
}
else
	var url="/Online_is_on_all";

    $.ajax({
          url: saveFormURL+url,
          type: 'get',
          data: {ids:id},
          success: function(res) {
          	if(res==1){
          		 $(obj).parents('tr').eq(0).remove();
            }
          	else{
          		alert(res);         		
          	}
             
          }
    });
}
function change_screen(obj,id,type){
if(type==0)
{
    var url="/off_screen";
}
else
    var url="/on_screen";

            $.ajax({
                url: saveFormURL+url,
                type: 'post',
                data: {id:id,is_screen:type},
                success: function(res) {
                    if(res==1)
                    {
                         $(obj).parents('tr').eq(0).remove();                        
                    }
                    else{
                        alert(res);                      
                    }
                   
                }
            });
}

function realdel(id){
      if( confirm('确定删除') ) {
        $.get(saveFormURL+"/delById_all",'ids='+id,function(data) {
          if(data['status'] == 1) {
            $("#row_"+id).hide();
          } else {
            alert(data);
          }
        });
    }
}
//批量删除 .serialize()
function realdel_all(obj_name){
    var obj = $('input:checkbox[name="'+obj_name+'"]:checked').serialize();

      if( confirm('确定删除')){
        $.get(saveFormURL+"/delById_all",obj,function(data) {
          if(data['status'] == 1) {
                refresh();
          } else {
            alert(data);
          }

        });
     }
        
   return false;

}
//批量加入回收站
function recycle_on_all(obj_name){
    var obj = $('input:checkbox[name="'+obj_name+'"]:checked').serialize();
    //alert(obj);
    //console.log(obj);
      if( confirm('是否放入回收站')){
        $.get(saveFormURL+"/Recycle_on_all",obj,function(data) {
          if(data['status'] == 1) {
                refresh();
          } else {
            alert(data);
          }

        });
     }

    }

//批量还原回收站
function recycle_off_all(obj_name){
    var obj = $('input:checkbox[name="'+obj_name+'"]:checked').serialize();

      if( confirm('是否还原回收站')){
        $.get(saveFormURL+"/Recycle_off_all",obj,function(data) {
          if(data['status'] == 1) {
                refresh();
          } else {
            alert(data);
          }

        });
     }

    }  

//批量上线
 function online_on_all(obj_name){
    var obj = $('input:checkbox[name="'+obj_name+'"]:checked').serialize();

      if( confirm('是否上线')){
        $.get(saveFormURL+"/Online_is_on_all",obj,function(data) {
          if(data == 1) {
                refresh();
          } else {
            alert(data);
          }
        });
     }
 }

 //批量下线
 function online_off_all(obj_name){
    var obj = $('input:checkbox[name="'+obj_name+'"]:checked').serialize();

      if( confirm('是否下线')){
        $.get(saveFormURL+"/Online_is_off_all",obj,function(data) {
          if(data == 1) {
                refresh();
          } else {
            alert(data);
          }
        });
     }
 }



function reduction(id){
	      $.get(saveFormURL+"/Recycle_off_all",{ids:id},function(data) {
          if(data['status'] == 1) {
            $("#row_"+id).hide();
          } else {
            alert(data);
          }
        });
}

//刷新页面
function refresh(){
    window.location.reload();//刷新当前页面.
    
    //或者下方刷新方法
    //parent.location.reload()刷新父亲对象（用于框架）--需在iframe框架内使用
    // opener.location.reload()刷新父窗口对象（用于单开窗口
    //top.location.reload()刷新最顶端对象（用于多开窗口）
}  