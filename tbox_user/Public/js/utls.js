Date.prototype.format = function(format) {   
	var o = {    
		"M+" :this.getMonth() + 1, // month    
		"d+" :this.getDate(), // day    
		"h+" :this.getHours(), // hour    
		"m+" :this.getMinutes(), // minute    
		"s+" :this.getSeconds(), // second    
		"q+" :Math.floor((this.getMonth() + 3) / 3), // quarter    
		"S" :this.getMilliseconds()  // millisecond    
	}    
   
	if (/(y+)/.test(format)) {    
		format = format.replace(RegExp.$1, (this.getFullYear() + "")    
		.substr(4 - RegExp.$1.length));    
	}  
   
	for ( var k in o) {    
		if (new RegExp("(" + k + ")").test(format)) {    
			format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]    
			: ("00" + o[k]).substr(("" + o[k]).length));    
		}    
	}
	
	return format;
}
// example
//var testDate = new Date();  
//alert(testDate.format("yyyy-MM-dd hh:mm:ss"));//yyyy 一定得是小写  
//alert(testDate.format("yyyy年MM月dd日hh小时mm分ss秒"));  
//alert(testDate.format("yyyy年MM月dd日"));  
//alert(testDate.format("MM/dd/yyyy"));  
//alert(testDate.format("yyyyMMdd"));  
//alert(testDate.format("hh:mm:ss")); 