function get_device_status()
	{
		var ids = Array();
		$('.deviceStatus').each(function () {
			ids.push($(this).attr('device_id'));
		});
		
		$.ajax({
			url: url+'/Api/device/' + ids.join(","),
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
					$(one).html('状态未知');
					$(one).addClass('unknown-lable');
				});
				$('.deviceLastUpdate').each(function(i, one) {
					$(one).html('未知');
					$(one).addClass('unknown-lable');
				});
			}
		});
	}
	get_device_status();
	setInterval(get_device_status, 3000);