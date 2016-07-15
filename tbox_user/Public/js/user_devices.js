	function tab(data){

	$(".tab-content").hide();
	$("#tab"+data).show();
}
$(document).ready( function() {

	//chart interval change
	$('.chart_interval_select').change(function() {
		var interval = $(this).val();	
		var sensor_id = $(this).attr('sensor_id');
		tmp = findSWF('data_chart' + sensor_id);
		var url = '/user/chart_data_v2?sensor='+sensor_id+'&time='+interval+'&'+(new Date().getTime());
		tmp.reload(encodeURI(url));
	});
	
	$('.refresh_data').click(function() {
		var sensor_id = $(this).attr('sensor_id');
		$('.chart_interval_select[sensor_id=' + sensor_id + ']').change();
		return;
	});

	function findSWF(movieName) {
		if (navigator.appName.indexOf("Microsoft Internet Explorer")!= -1) { //IE8,IE9
			//return window["ie_" + movieName];
			return window[movieName];
		} else {
			return document[movieName];
		}
	}
	
	//Add new trigger
	$('.addNewDIYAction').click(function(){
		$('.newDIYActionTR[sensor_id=\'' + $(this).attr('sensor_id') + '\']').attr('trigger_id', 0);
		$('.saveAction[sensor_id=\'' + $(this).attr('sensor_id') + '\']').val('√ 保存为一个新的触发动作');
		$('form#sensor'+$(this).attr('sensor_id')+' h5').html('一个新的触发动作');
		$('form#sensor'+$(this).attr('sensor_id')).each(function(){
			this.reset();
		});
		$('form#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_value\']').show();
		$('form#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_text\']').hide();
		$('.newDIYActionTR[sensor_id=\'' + $(this).attr('sensor_id') + '\']').show();
	});
	$('.tri_weibo_event').change(function(){
		var tri_weibo_event = $(this).val();
		
		switch(tri_weibo_event)
		{
			case "1":
			case "2":
			case "3":
				$('#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_value\']').show();
				$('#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_text\']').hide();
			break;
			case "4":
				$('#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_text\']').show();
				$('#sensor'+$(this).attr('sensor_id')+' input[id=\'tri_value\']').hide();
			break;
			default:
			break;
		}
	});

	//trigger_id = 0 for saveNewAction, trigger_id !=0 for saveEditAction
	$('.saveAction').click(function() {
		var sensor_id = $(this).attr('sensor_id');
		var trigger_id = $('.newDIYActionTR[sensor_id=\'' + sensor_id + '\']').attr('trigger_id');
		var id = $('.newDIYActionTR[sensor_id=\'' + sensor_id + '\']').attr('trigger_id');
		var tri_operator = $('#sensor'+sensor_id+' select#tri_operator').val();
		var tri_operator_text = $('#sensor'+sensor_id+' select#tri_operator>option:selected').html();
		var tri_weibo_event = $('#sensor'+sensor_id+' select#tri_weibo_event>option:selected').val();
		var tri_value = $('#sensor'+sensor_id+' input[id=\'tri_value\']').val();
		var tri_text = $('#sensor'+sensor_id+' input[id=\'tri_text\']').val();
		var act_value = $('#sensor'+sensor_id+' select#act_id').val();
		var act_text = $('#sensor'+sensor_id+' select#act_id>option:selected').html();
		var tri_msg = $('#sensor'+sensor_id+' textarea').val();
		var sensor_data_type = $('#sensor'+sensor_id+' input[id=\'sensor_data_type\']').val();
		var info_msg = $('#sensor'+sensor_id+' div.notification');
		var loading = $('#sensor'+sensor_id+' span.loading');
		if(act_value.indexOf("||||") >0){
			var actArray = act_value.split("||||");
			var act_id = actArray['0'];
		}
		else{
			var act_id = act_value;
		}
		
		
		if(isNaN(parseFloat(tri_value))) {
			info_msg.show();
			info_msg.children("div").html('请输入正确的触发条件值');
			return;
		}
		else if(tri_weibo_event == 4 && tri_text == '' ) {
			info_msg.show();
			info_msg.children("div").html('请输入正确的触发条件值');
			return;
		}
		else if (tri_msg.length <=0 || tri_msg.length > 120) {
			info_msg.show();
			info_msg.children("div").html('消息内容长度不符合要求');
			return;
		} else if (act_id == null) {
			info_msg.show();
			info_msg.children("div").html('没有选择动作，请先在<a href=\'/user/actions\'>管理动作</a>中添加，然后在这里选择');
			return;
		} else if (typeof(actArray) != "undefined"){
			var reg = /^({"value":[0-1]})$/;
			if(!reg.test(tri_msg)) {
				info_msg.show();
				info_msg.children("div").html('您指定的动作为开关，请按提示输入正确的格式');
				return;
			}
		} else {
			info_msg.hide();
		}
		
		loading.show();
		$.ajax({
			url: '/user/new_trigger',
			async: true,
			type: 'POST',
			data: "sensor_id=" + sensor_id + "&trigger_id=" + trigger_id + "&tri_operator=" + tri_operator + "&tri_weibo_event=" + tri_weibo_event + "&tri_value=" + tri_value + "&tri_text=" + tri_text + "&act_id=" + act_id + "&tri_msg=" + tri_msg,
			success: function(data, textStatus)
			{
				var ret = JSON.parse(data);
				if(ret.status == 'success')
				{
					if(trigger_id == 0)
					{
						trigger_id = ret.id;
					}
					var newDIYTR = $('.newDIYActionTR[sensor_id=\''+sensor_id+'\']');
					var operator = '';
					switch(tri_operator)
					{
						case '1':
							operator = '大于';
							break;
						case '2':
							operator = '大于等于';
							break;
						case '3':
							operator = '小于';
							break;
						case '4':
							operator = '小于等于';
							break;
						case '5':
							operator = '等于';
							break;
						default:
							operator = '??';
							break;
					}
					switch(sensor_data_type)
					{
						case "0":
						var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
											<td>当值' + operator + tri_value + '时，' + act_text +'</td> \
											<td nowrap="nowrap"> \
							<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
							<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
							<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/></a> \
						  </div> \
						</td> \
										</tr>';
						break;
						case "1":
							switch(tri_value)
							{
								case "1":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当有人走动时， ' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/</a> \
								  </div> \
								</td> \
												</tr>';
								break;
								case "2":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当有人开门时， ' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/></a> \
								  </div> \
								</td> \
												</tr>';
								break;
							}
						break;
						case "3":
							switch(tri_weibo_event)
							{
								case "1":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当未读微博数大于' + tri_value +'时， ' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/</a> \
								  </div> \
								</td> \
												</tr>';
								break;
								case "2":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当新粉丝数大于' + tri_value + '时， ' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/</a> \
								  </div> \
								</td> \
												</tr>';
								break;
								case "3":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当新消息数大于' + tri_value + '时， ' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/</a> \
								  </div> \
								</td> \
												</tr>';
								break;
								case "4":
									var html = '<tr class="trigger_tr" trigger_id="' + trigger_id + '"> \
									<td>当微博内容包含 "' + tri_text + '" 时，' + act_text +'</td> \
									<td nowrap="nowrap"> \
									<div class="trigger_operation align-right" trigger_id="' + trigger_id + '" sensor_id="' + sensor_id + '"> \
									<a href="javascript:void(0);"><img class="edit_trigger" src="/images/icons/24.png" title="编辑"/></a>  \
									<a href="javascript:void(0);"><img class="delete_trigger" src="/images/icons/12.png" title="删除"/</a> \
								  </div> \
								</td> \
												</tr>';
								break;	
							}
						break;
					}
					if(id == 0)
					{
						newDIYTR.before(html);  //new html
					}
					else
					{
						$('.trigger_tr[trigger_id=' + trigger_id + ']').replaceWith(html);  //just replace primary html
					}
					
					$('.trigger_operation[trigger_id=' + trigger_id + '] img.delete_trigger').click(function(){
						delete_trigger($(this));
					});
					$('.trigger_operation[trigger_id=' + trigger_id + '] img.edit_trigger').click(function(){
						get_trigger_data($(this));
						$('.newDIYActionTR[sensor_id=\'' + sensor_id + '\']').show();
					});
					newDIYTR.hide();
				}
				else
				{
					info_msg.show();
					info_msg.children("div").html(ret.message);
				}
			},
			
			error: function (data, textStatus)
			{
				info_msg.show();
				info_msg.children("div").html('出错了');
			},
			
			complete: function()
			{
				loading.hide();
			}
		});
		
	});
	
	$('.giveupNewAction').click(function(){
		$('.newDIYActionTR[sensor_id=\'' + $(this).attr('sensor_id') + '\']').hide();
	});

	function get_trigger_data(self)
	{
		var trigger_id = self.parent().parent().attr('trigger_id');
		var sensor_id = self.parent().parent().attr('sensor_id');

		$.ajax({
			url: '/user/get_trigger_data',
			async: true,
			type: 'POST',
			data: "trigger_id=" + trigger_id,
			success: function(data, textStatus)
			{
				var ret = JSON.parse(data);
				if(ret.status == 'success')
				{			
					if(ret.tri_operator == 10) {
						$('#sensor'+sensor_id+' select#tri_weibo_event').val("4");
						$('#sensor'+sensor_id+' input[id=\'tri_text\']').show();
						$('#sensor'+sensor_id+' input[id=\'tri_value\']').hide();
						$('#sensor'+sensor_id+' #tri_text').val(ret.tri_text);
					}else {
						if(ret.tri_param != '' && ret.tri_param != null) {
							var triParam = JSON.parse(ret.tri_param);
							switch(triParam.type)
							{
								case "status":
									$('#sensor'+sensor_id+' select#tri_weibo_event').val("1");
								break;
								case "follower":
									$('#sensor'+sensor_id+' select#tri_weibo_event').val("2");
								break;
								case "msg":
									$('#sensor'+sensor_id+' select#tri_weibo_event').val("3");
								break;
								default:
									
								break;
							}
							$('#sensor'+sensor_id+' input[id=\'tri_text\']').hide();
							$('#sensor'+sensor_id+' input[id=\'tri_value\']').show();
						}else {
							$('#sensor'+sensor_id+' select#tri_operator').val(ret.tri_operator);	
						}
						$('#sensor'+sensor_id+' #tri_value').val(ret.tri_value);
					}
					
					if(typeof(ret.act_api_key) == "undefined"){
						$('#sensor'+sensor_id+' select#act_id').val(ret.act_id);
					}
					else{
						$('#sensor'+sensor_id+' select#act_id').val(ret.act_id+'||||'+ret.act_api_key);
					}
					$('#sensor'+sensor_id+' textarea').val(ret.tri_msg);
					$('.newDIYActionTR[sensor_id=\'' + sensor_id + '\']').attr('trigger_id', trigger_id);
					$('#sensor'+sensor_id+' .saveAction').val('√ 保存');
					$('form#sensor'+sensor_id+' h5').html('编辑触发动作');
				}
				else
				{
					alert(ret.message);
				}
			},
			
			error: function (data, textStatus)
			{
				alert('访问出错, 请稍后重试');
			}
		});
	}
	$('.edit_trigger').click(function(){
		var sensor_id = $(this).parent().parent().attr('sensor_id');
		get_trigger_data($(this));
		$('.newDIYActionTR[sensor_id=\'' + sensor_id + '\']').show();
	});
	
	function delete_trigger (self)
	{
		if(!window.confirm("请确认是否删除？")) return;
		
		var trigger_id = self.parent().parent().attr('trigger_id');
		var sensor_id = self.parent().parent().attr('sensor_id');
		$.ajax({
			url: '/user/delete_trigger',
			async: true,
			type: 'POST',
			data: "trigger_id=" + trigger_id,
			success: function(data, textStatus)
			{
				var ret = JSON.parse(data);
				if(ret.status == 'success')
				{
					var trigger_tr = $('.trigger_tr[trigger_id=\''+trigger_id+'\']');
					trigger_tr.remove();
				}
				else
				{
					alert(ret.message);
				}
			},
						
			error: function (data, textStatus)
			{
				alert('删除出错, 请稍后重试');
			}
		});
		$('.newDIYActionTR[sensor_id=\''+sensor_id+'\']').hide();
	}	
	
	$('.delete_trigger').click(function(){
		delete_trigger($(this));
	});
	
	function delete_sensor (self)
	{
		if(!window.confirm("请确认是否删除")) return;
		var sensor_id = self.parent().parent().attr('sensor_id');
		$.ajax({
			url: '/user/delete_sensor',
			async: true,
			type: 'POST',
			data: "sensor_id=" + sensor_id,
			success: function(data, textStatus)
			{
				var ret = JSON.parse(data);
				if(ret.status == 'success')
				{
					var sensor_block = $('.sensor-block[sensor_id=\''+sensor_id+'\']');
					sensor_block.remove();
				}
				else
				{
					alert(ret.message);
				}
			},
						
			error: function (data, textStatus)
			{
				alert('删除出错, 请稍后重试');
			}
		});
	}
	
	$('.delete_sensor').parent().click(function(){
		delete_sensor($(this));
	});
	
	$('.delete_device').parent().click(function(){
		return delconfirm();
	});

	function delconfirm(){
		 if(window.confirm("请确认是否删除？")){
		  return true;
		 }
		 return false;
	}
	//智能插座
	$('.powerUp').click(function(){
		var dev_id = $(this).attr('dev_id');
		var sensor_id = $(this).attr('sensor_id');
		$('.powerUpDownStatus[sensor_id=\'' + sensor_id + '\']').show();
		apiSendControlCmd(dev_id, sensor_id, 'open');
	});
	
	$('.powerDown').click(function(){
		var dev_id = $(this).attr('dev_id');
		var sensor_id = $(this).attr('sensor_id');
		$('.powerUpDownStatus[sensor_id=\'' + sensor_id + '\']').show();
		apiSendControlCmd(dev_id, sensor_id, 'close');
	});
	
	//开关
	$('.switch_button').click(function(){
		var dev_id = $(this).attr('dev_id');
		var sensor_id = $(this).attr('sensor_id');
		var current_status = $(this).attr('status');
		$('.switchUpDownStatus[sensor_id=\'' + sensor_id + '\']').show();
		sendSwitchControl(dev_id, sensor_id, current_status);
	});
	
	//图像传感器拍摄
	var shootPhotoFile = Array();
	var interval = Array();
	$('input.shoot').click(function(){
		var dev_id = $(this).attr('dev_id');
		var sensor_id = $(this).attr('sensor_id');
		shootPhotoFile[sensor_id] = null;
		if(interval[sensor_id]) clearInterval(interval[sensor_id]);
		interval[sensor_id] = null;
		//alert(sensor_id);
		apiSendControlCmd(dev_id, sensor_id, 'shoot');
		$('.shootStatus[sensor_id=\'' + sensor_id + '\']').show();
		$('.shootErrorMessage[sensor_id=\'' + sensor_id + '\']').hide();
		interval[sensor_id] = setInterval(function(){get_shoot_photo(sensor_id)}, 2000);
		setTimeout(function(){
			if(interval[sensor_id])
			{
				clearInterval(interval[sensor_id]);
				interval[sensor_id] = null;
				$('.shootStatus[sensor_id=\'' + sensor_id + '\']').hide();
				$('.shootErrorMessage[sensor_id=\'' + sensor_id + '\']').html('拍摄超时, 可能由于网速过慢, 请稍候重试.');
				$('.shootErrorMessage[sensor_id=\'' + sensor_id + '\']').show();
			}
		}, 15000);
	});
	
	function get_shoot_photo(sensor_id)
	{
		$.ajax({
			url: '/upload/photocam/' + sensor_id + '/file.json',
			async: true,
			dataType: 'json',
			success: function(data, textStatus)
			{
				if(!shootPhotoFile[sensor_id]) //first time get photo
				{
					shootPhotoFile[sensor_id] = data.file;
					//$('.shootPhoto').attr('src', '/upload/photocam/' + sensor_id + '/' + shootPhotoFile[sensor_id]);
				}
				else
				{
					if(shootPhotoFile[sensor_id] != data.file)
					{
						shootPhotoFile[sensor_id] = data.file;
						$('.shootPhoto[sensor_id=\'' + sensor_id + '\']').attr('src', '/upload/photocam/'  + sensor_id + '/' + shootPhotoFile[sensor_id]);
						$('.shootStatus[sensor_id=\'' + sensor_id + '\']').hide();
						clearInterval(interval[sensor_id]);
						interval[sensor_id] = null;
					}
				}
			},
			error: function (data, textStatus)
			{
				$('.shootStatus[sensor_id=\'' + sensor_id + '\']').hide();
				$('.shootErrorMessage[sensor_id=\'' + sensor_id + '\']').html('网络原因导致拍摄失败, 请稍候重试.');
				$('.shootErrorMessage[sensor_id=\'' + sensor_id + '\']').show();
				clearInterval(interval[sensor_id]);
				interval[sensor_id] = null;
			}
		});
	}
	
	//common
	
	function apiSendControlCmd(dev_id, sensorId, cmd)
	{
		$.ajax({
			url: '/v1.0/device/' + dev_id + '/sensor/' + sensorId + '?method=put&cmd=' + cmd,
			async: true,
			dataType: 'json',
			headers: {'U-ApiKey': api_key},
			success: function(data, textStatus)
			{
				$('.powerUpDownStatus[sensor_id=\'' + sensorId + '\']').hide();
			},
			
			error: function (data, textStatus)
			{
				$('.powerUpDownStatus[sensor_id=\'' + sensorId + '\']').hide();
			}
		});
	}
	
	function sendSwitchControl(dev_id, sensor_id, current_status)
	{
		var send_status = current_status == 1? 0 : 1;
		var value = {value: send_status};
		$.ajax({
			url: '/v1.0/device/' + dev_id + '/sensor/' + sensor_id + '/datapoints',
			async: true,
			dataType: 'json',
			type: 'POST',
			data: JSON.stringify(value),
			headers: {'U-ApiKey': api_key},
			success: function(data, textStatus)
			{
				$('.switchUpDownStatus[sensor_id=\'' + sensor_id + '\']').hide();
				if(send_status == 1)
				{
					//$('.switchStatus[sensor_id=\'' + sensor_id + '\']').html('开开开');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').removeClass('off');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').addClass('on');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').attr('status', send_status);
				}
				else
				{
					//$('.switchStatus[sensor_id=\'' + sensor_id + '\']').html('关');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').removeClass('on');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').addClass('off');
					$('.switch_button[sensor_id=\'' + sensor_id + '\']').attr('status', send_status);
				}
			},
			
			error: function (data, textStatus)
			{
				$('.switchUpDownStatus[sensor_id=\'' + sensor_id + '\']').hide();
			}	
		});	
	}
//Device status And device new data

	function get_device_status()
	{
		var ids = Array();
		$('.deviceStatus').each(function () {
			ids.push($(this).attr('device_id'));
		});
		
		$.ajax({
			url: root+'/Api/device/' + ids.join(","),
			type: 'GET',
			async: true,
			dataType: 'json',
			headers: {
            "apikey":apikey
        	},
			success: function(data, textStatus)
			{
				$.each(data, function (i, one) {
					switch(one.network)
					{
						case '0':
							$('.deviceStatus[device_id=' + one.device_id + ']').html('网络未连接');
							$('.deviceStatus[device_id=' + one.device_id + ']').removeClass('unknown-label');
							$('.deviceStatus[device_id=' + one.device_id + ']').removeClass('normal-label');
							$('.deviceStatus[device_id=' + one.device_id + ']').addClass('error-label');
							break;
						case '1':
							$('.deviceStatus[device_id=' + one.device_id + ']').html('网络正常');
							$('.deviceStatus[device_id=' + one.device_id + ']').removeClass('unknown-label');
							$('.deviceStatus[device_id=' + one.device_id + ']').removeClass('error-label');
							$('.deviceStatus[device_id=' + one.device_id + ']').addClass('normal-label');
							break;
						default:
							break;
					}
					switch(one.activity)
					{
						case '0':
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').html('不活跃');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').removeClass('unknown-label');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').removeClass('normal-label');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').addClass('error-label');
						break;
						case '1':
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').html('活跃');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').removeClass('unknown-label');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').removeClass('error-label');
							$('.lastUpdateStatus[device_id=' + one.device_id + ']').addClass('normal-label');
							break;
						default:
							break;
						
						
					}
					
					var time = new Date();
					time.setTime(one.timestamp*1000);
					$('.deviceLastUpdate[device_id=' + one.deviceId + ']').html(time.format("yyyy-MM-dd hh:mm:ss"));
				})
			},
			
			error: function (data, textStatus)
			{
				$('.deviceStatus').each(function(i, one) {
					one.html('状态未知');
					one.addClass('unknown-lable');
				});
				$('.deviceLastUpdate').each(function(i, one) {
					one.html('状态未知');
					one.addClass('unknown-lable');
				});
			}
		});
	}
	
	//data
	for(var i=0; i < chart_id.length; i++)
	{
		drawDataChart(chart_id[i]);
	}
	//gen
	for(var i=0; i < map_id.length; i++)
	{
		$("#from"+ map_id[i]).datetimepicker();
		$("#to"+ map_id[i]).datetimepicker();
		drawMap(map_id[i]);
	}
	
	get_device_status();
	setInterval(get_device_status, 3000);
	
	//delete trigger
	
	//图片上传 
	$('.upload_img_file').click(function(){
		$('.uimg[device_id=' + $(this).attr('device_id') + ']').click();
	});
	
	//$('.del_img_file').click(function(){
	//});
	
	$('.dev_img_box').mouseenter(function(){
		var dev_img_id = $(this).attr('dev_img_id');
		$('.dev_img_mask[dev_img_id=' + dev_img_id + ']').show();
	});
	$('.dev_img_box').mouseleave(function(){
		var dev_img_id = $(this).attr('dev_img_id');
		$('.dev_img_mask[dev_img_id=' + dev_img_id + ']').hide();
	});
	
	$(".uimg").change(function(e){
		var device_id = $(this).attr('device_id');
		var v = ($(this).val());
		if ($(this).val()){						
			//<<< bbb  以下部分图片类型、图片大小、本地图片预览						

			if(!/.(gif|jpg|jpeg|png|gif|jpg|png)$/.test(v.toLowerCase())){ //检测图片类型
				alert("只允许上传.gif,jpeg,jpg,png类型的图片");
				return false;
			}
			/*var img = new Image();
			img.src = v;
			img.onreadyState = function()
			{
				if (img.readyState == "complete")
				 {
					if(img.fileSize > 1024*1024)
					{
						alert('chaolechaole');	
					}
				 }
			}*/
			var s;
			//$.browser.msie for IE
			/*if ($.browser.msie) {*/
				/*var img=new Image();
				navigator.userAgent.indexOf("MSIE")>0
				img.src=v;
				while(img.readyState != "complete"){									
					if(img.fileSize>0){
						s = img.fileSize;
						var w = img.width,
							h = img.height;
						if(s>2048*1024){											
							alert("图片不大于2M.");
							return false;
						}
						break;
					}
				}*/
			  /*var filePath = $(this).val();    
			  var fileSystem = new ActiveXObject("Scripting.FileSystemObject");       
			  var file = fileSystem.GetFile(filePath);    
			  var fileSize = file.Size; 
			  alert(fileSize); 
			  			  var img = new Image();
			  //$(this).select();
			  img.src = $(this).val();
			  img.onload = function() {
			  	var fileSize = img.fileSize;
				alert(fileSize);
				if(fileSize>1024*1024)
				{
					alert("图片不大于2M");
					return false;					
				}
				 }
			   */
				var img = new Image();
				img.src = $(this).val();
				img.onreadystatechange = function()
				{
					if(img.readyState == "complete")
					{
						alert(img.fileSize);	
					}	
				}
				/*var aa = true;
				while(img.readyState != "complete")
				{
					aa=false;
					continue;	
				}
				if(aa)
				{
					alert(img.fileSize);
				}*/			 
		/*	}else{*/
				s = this.files[0].size;
				if(s>2048*1024){											
					alert("图片不大于2M");
					return false;
				}
			/*}*/

			$('#upimgform'+device_id).ajaxSubmit({

					dataType:'json',
					data: { device_id: device_id },
					type:'post',
					url: url+"/upload_dev_img",
					beforeSubmit: function(){
						$('.dev_img_box[device_id=' + device_id + ']').show();
					},
					success: function(data){
						if(data.status == 'success')
						{				

							$('.dev_img_box[device_id=' + device_id + ']').hide();
							$('.dev_img_box[dev_img_id=' + device_id + ']').remove();
							//alert(data);
					
							var a= "<div class=\"dev_img_box\" dev_img_id=\"" + data.id + "\">";
           		a += "<a href=\"" + data.file_dir + "\"  target=\"_blank\"><img src=\"" + data.tn_dir + "\" width=\"100%\" height=\"100%\" border=\"0\"></a>";
            	a += "<div class=\"dev_img_mask\" dev_img_id=\"" + data.id + "\">";
            	a += "<div class=\"dev_img_desc\" dev_img_id=\"" + data.id + "\"><strong>图片描述: </strong><span>无</span></div>";
              a += "<div class=\"dev_img_action\">";
              a += "<a href=\"javascript:void(0);\"><div class=\"dev_img_edit\" dev_img_id=\"" + data.id + "\">编辑描述</div></a>";
              a += "<a href=\"javascript:void(0);\"><div class=\"dev_img_del\" dev_img_id=\"" + data.id + "\">删除图片</div></a>";
              a += "</div>";
            	a += "</div>";
          		a += "</div>";
							$('.dev_img_box[device_id=' + device_id + ']').after(a);
							//给新增加的图片绑定事件
							$('.dev_img_box[dev_img_id=' + data.id + ']').mouseenter(function(){
								var dev_img_id = $(this).attr('dev_img_id');
								$('.dev_img_mask[dev_img_id=' + data.id + ']').show();
							});
							$('.dev_img_box[dev_img_id=' + data.id + ']').mouseleave(function(){
								var dev_img_id = $(this).attr('dev_img_id');
								$('.dev_img_mask[dev_img_id=' + data.id + ']').hide();
							});
							$('.dev_img_edit').click(function() {
								var dev_img_id = $(this).attr('dev_img_id');
								var init_val = $('.dev_img_desc[dev_img_id=' + dev_img_id + '] span').html();
								var desc = window.prompt('请输入图片描述', init_val);
								
								edit_dev_img(dev_img_id, desc);
								if(desc == '') { desc = '无' };
								$('.dev_img_desc[dev_img_id=' + dev_img_id + '] span').html(desc);
							});
							$('.dev_img_del[dev_img_id=' + data.id + ']').click(function() {
								if(window.confirm('是否要删除所选图片?'))
								{
									del_dev_img(data.id);
								}
							});
						}
						else
						{
							alert(data.message);	
						}
					},
					error: function(data){
						alert('上传文件失败，请稍后重新上传');
					},
					//resetForm: false,
					//clearForm: false,
					//timeout: 80000
			});			
			return false;
		}
	});
	
	$('.dev_img_edit').click(function() {
		var dev_img_id = $(this).attr('dev_img_id');
		var init_val = $('.dev_img_desc[dev_img_id=' + dev_img_id + '] span').html();
		var desc = window.prompt('请输入图片描述', init_val);
		
		edit_dev_img(dev_img_id, desc);
		if(desc == '') { desc = '无' };
		$('.dev_img_desc[dev_img_id=' + dev_img_id + '] span').html(desc);
	});

	$('.dev_img_del').click(function() {
		if(window.confirm('是否要删除所选图片?'))
		{
			del_dev_img($(this).attr('dev_img_id'));
		}
	});
	
	function edit_dev_img(dev_img_id, desc)
	{		
		$.ajax({
			url: url+'/edit_dev_img',
			async: true,
			type: 'POST',
			data: "dev_img_id=" + dev_img_id + "&img_desc=" + desc,
			success: function(data, textStatus)
			{
				if(data.status == 'error')
				{
					alert(data.message);	
				}
			},
			error: function (data, textStatus)
			{
				alert('编辑出错, 请稍后重试.');
			}	
		});		
	}

	function del_dev_img(dev_img_id)
	{
		$.ajax({
			url: url+'/del_dev_img',
			async: true,
			type: 'POST',
			data: "dev_img_id=" + dev_img_id,
			success: function(data, textStatus)
			{
				if(data.status == 'error')
				{
					alert(data.message);	
				}
				else
				{
					$('.dev_img_box[dev_img_id=' + dev_img_id + ']').remove();
				}
			},
			error: function (data, textStatus)
			{
				alert('删除图片出错, 请稍后重试.');
			}	
		});
	}
	
	for(var i=0; i < photo_id.length; i++)
	{
		photoShow(photo_id[i]);
	}
	
	
});