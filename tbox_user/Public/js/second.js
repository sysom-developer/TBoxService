
//改变价格显示
           function modifyPrice() {            
                totalprice = 80;
                $("[id^=maintain_category_id_]").each(function() {
                    //获取第一个价格
                    var price = $(this).children("option:selected").attr("data-price");
                    if( $(this).siblings("input").prop("checked") && price != undefined ) {
                        totalprice += parseInt(price);
                    }
                });
                $("#currentprice").html(totalprice);
                $("#total").val(totalprice);
           }

        $(document).ready(function() {
            //弹出信息
            $(".main-btn").on('click',function() {
                $(this).find('.sub-btn').slideToggle('100');
            });
        });
        $(document).ready(function() {
            //控制服务选项
            $(".other").on('click',function() {
                var checked = $(this).prop('checked');
                    $(".other").prop('checked',checked);
                    $(".mechanism").prop('checked',!checked);
                    modifyPrice();
            });
            $(".mechanism").on('click',function() {
                $(".other").prop('checked',false);
            });
            $('select').on("focus",function() {
                    $(this).addClass('focus-on');
            });
            $("select").on("blur",function() {
                    $(this).removeClass('focus-on');
            });


            //保存原始节点
            var series = $("#series").clone();
            var models = $("#models").clone();
            $("#series").children("option:gt(0)").remove();
            $("#models").children("option:gt(0)").remove();
            /*品牌系列型号筛选*/
            //筛选正确的选项
            function displayNone(obj,id) {
                var Html = ( obj == '#series' ) ? series : models;
                var option = $(Html).children("option[data-id = "+id+"]").clone();
                $(obj).append(option);
            }
            //绑定改变事件
           function selectObj(obj,subobj) {
                $(obj).on('change',function() {
                    //初始化
                    $(subobj).children("option:gt(0)").remove();
                    if(subobj == "#series") {
                        $("#models").children("option:gt(0)").remove();
                    }
                    var id = $(this).val();
                    newObj = obj.substr(1);
                    id = newObj +id;
                    //筛选正确的选项
                    displayNone(subobj,id);
               });
           }
           selectObj("#brands","#series");
           selectObj("#series","#models");

           
           var bigmaintain;
           var big = 1;
           function getData(model_id) {            
                $.get(deviceURL,'model_id='+model_id,function(res) {
                    if(res.status == 'ok') {
                        $(".common-div select").children("option").remove();
                        $(res.info).each(function() {
                            $("#maintain_category_id_"+$(this)[0].maintain_category_id).append("<option data-price="+$(this)[0].price+" value="+$(this)[0].id+">"+$(this)[0].name+"  [￥"+$(this)[0].price+"]"+"</option>");
                        });

                       //保存节点
                       if( big ) {
                            bigmaintain = $("#device-type").children(".common-div").clone();
                       }
                       modifyPrice();
                    } else {
                        alert(res.status);
                    }
                },'json');
           }
           $("#models").on('change',function() {
                var model_id = $(this).val();
                if(model_id == 0 ) {
                    return false;
                }
                getData(model_id);
           });
           $(".auto_id").on('click',function() {
                var model_id = $(this).attr("model_id");
                getData(model_id);
           });
           //保存最初4个节点
            bigmaintain = $("#device-type").children(".common-div").clone();
               $("#maintain").on('change',function() {
                    var val = $(this).val();
                    if( val == '1' ) {
                        big = 1;
                        $("#models").trigger('change');
                        $(".auto_id:checked").trigger('click');
                        $("#device-type").children(".common-div").remove();
                        $("#device-type").append(bigmaintain);
                        $(".other").prop('checked',false);
                    } else {
                        big = 0;
                        $("#device-type").children(".common-div:gt(1)").remove();
                        modifyPrice();
                    }
               });

                var carinfo = new Array();
                var devicestr = '';
               $("#next").on("click",function() {
                    // var car = {brand:$("#brands option:selected").text(),serie:$("#series option:selected").text(),model:$("#models option:selected").text()};
                    var tmp = $(".auto_id:checked").siblings(".main-btn").children(".sub-btn");
                    var car = {brand:tmp.children("p:eq(0)").text().split(':')[1],serie:tmp.children("p:eq(1)").text().split(':')[1],model:tmp.children("p:eq(2)").text().split(':')[1]};
                    carinfo.push(car);
                    $(".mechanism[type='checkbox']:checked").each(function() {
                        var option = $(this).siblings('select').children("option:selected");
                        if(option.val() != undefined) {
                            var dname = option.text();
                            dname = dname.split('[')[0];
                            var device = {name:dname,price:option.attr("data-price"),id:option.val()};
                            carinfo.push(device);
                            devicestr +=  option.val() + ',';                               
                        }
                    });
                    carinfo.totalprice = $("#currentprice").html();
                    // console.log(carinfo);
                    var len = carinfo.length;
                    if( len > 1 ) {
                        $("tbody").append("<tr><td rowspan="+len+1+"  >"+carinfo[0].brand+carinfo[0].serie+carinfo[0].model+"</td><td>"+carinfo[1].name+"</td><td>"+carinfo[1].price+"</td><td rowspan="+len+1+">"+carinfo.totalprice+"</td><tr>");
                        for(var i=2 ; i< len;i++) {
                            $("tbody ").append("<tr><td>"+carinfo[i].name+"</td><td>"+carinfo[i].price+"</td></tr>");
                        }
                        $("tbody ").append("<tr><td>服务费</td><td>80元</td></tr>");
                    } else {
                        $("tbody").append("<tr><td>"+carinfo[0].brand+carinfo[0].serie+carinfo[0].model+"</td><td>服务费</td><td>80元</td><td>80元</tr>");
                    }
                    $("#totalprice2").html(carinfo.totalprice);
                    document.documentElement.scrollTop = document.body.scrollTop =0;
                    $("#first-page").css("display",'none');
                    $("#second-page").css("display",'block');
               });

               //发票优惠券选择
            $(".btn-switch").on('click',function() {
                    var tar = $(this).attr('for');
                    var method = tar.split('_')[1]; 
                    tar = tar.split('_')[0];
                    $(this).parent().find('.btn-switch').removeClass('btn-on');
                    $(this).addClass('btn-on');
                    if(method == 'on') {
                        $('#'+tar).show();
                    } else {
                        $('#'+tar).hide();
                    }
            });
            //日期表
            // $("#dtBox").DateTimePicker();

            //提交表单
            $("#submit").on('click',function(event) {
                    var phone = $("#phone").val();
                    var isPhone = /^1[3|4|5|8]\d{9}$/;
                    var date = $(".second-page-submit").val();
                    var truename = $("#truename").val();
                    if(phone==""||phone==null)
                {
                    alert("请填写手机号");
                    $("#next2").attr('disabled' , false);
                    return false;
                }
               if(truename==""||truename==null)
                {
                    alert("请填写姓名");
                    $("#next2").attr('disabled' , false);
                    return false;
                }
                    if( !isPhone.test(phone) ) {
                        alert('请填写正确的手机号码');
                        return false;
                    }
                    event.preventDefault();
                    $("#next2").attr('disabled' , true);
                    var formdata = new FormData($("#form")[0]);
                    formdata.append('data',devicestr);
                    $.ajax({
                        url: saveFormURL,
                        type: 'post',
                        data: formdata,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            $("#next2").attr('disabled' , false);
                            if(res == 'ok'){
                                alert('恭喜您下单成功，我们的客服人员会马上和您联系！');
                             $.ajax({
                                url: callURL,
                                type: 'post'
                            });
                                window.location.href = orderURL;
                            } else {
                                console.log(res);
                            }
                        }
                    });
            });

          //保养须知切换
          $("header div").on('click',function() {
              $("header div").removeClass('focus-bottom');
              $(this).addClass('focus-bottom');
              if( $(this).text() == '预约保养' ) {
                $("#first-page").css('display','block');
                $("#fourth-page").css('display','none');
              } else {
                $("#first-page").css('display','none');
                $("#fourth-page").css('display','block');
              }
          });

          //判断一天是否超过10单
          $("#checkdate").on('change',function() {
                    var val = $(this).val();
                    str = val.replace(/-/g,'/'); 

                    var date = new Date(str); 
                    var now = new Date();

                 /*now.setDate(now.getDate()+1); */
                    if(date.getTime()<now.getTime())
                    {
                         alert("只能预订明天及以后的时间");
                        return;
                    }
                     $.ajax({
                                url: dateURL,
                                data:{'serviced_at':val},
                                type: 'post',
                                success:function(res) {
                    if(res == 'full') {
                    alert(val+' 预定人数过多,服务时间也许会延后几天,为保证准时,请您重新选择预定时间,谢谢');
                    $("#checkdate").trigger('click');
                    }
                    }
                            });
          });

          
        });