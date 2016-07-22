

var wsServer = 'ws://192.168.1.42:8283'; //服务器地址
var websocket = new WebSocket(wsServer); //创建WebSocket对象
/*websocket.send("hello");//向服务器发送消息*/


websocket.onopen = function (evt) {
//已经建立连接
websocket.send( $.cookie('token') );
};

websocket.onclose = function (evt) {
//已经关闭连接
};
websocket.onmessage = function (evt) {
	alert( evt.data );//收到服务器消息，使用evt.data提取
/*	var objJsonData = JSON.parse(evt.data);
    var strMethodName = objJsonData.id;
    var objData = objJsonData.name;
	alert(strMethodName);alert(objData);*/
};
websocket.onerror = function (evt) {
//产生异常
}; 
