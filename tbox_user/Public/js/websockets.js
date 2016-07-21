

var wsServer = 'ws://192.168.1.42:8283'; //服务器地址
var websocket = new WebSocket(wsServer); //创建WebSocket对象
/*websocket.send("hello");//向服务器发送消息*/
alert(websocket.readyState);//查看websocket当前状态
alert($.cookie('the_cookie'); // 读取 cookie )
websocket.onopen = function (evt) {
	alert(evt);//已经建立连接
};
websocket.onclose = function (evt) {
	alert(websocket.readyState);//已经关闭连接
};
websocket.onmessage = function (evt) {
	alert(evt.data)//收到服务器消息，使用evt.data提取
};
websocket.onerror = function (evt) {
//产生异常
}; 
alert(websocket.readyState);