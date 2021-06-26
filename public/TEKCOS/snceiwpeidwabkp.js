/*var https=require("https");const fs=require("fs");var server=https.createServer({key:fs.readFileSync("resewqolsaoe.key").toString(),cert:fs.readFileSync("resewqolsaoe.crt").toString(),NPNProtocols:["http/2.0","spdy","http/1.1","http/1.0"]}),socket=require("socket.io"),io=socket.listen(server);io.sockets.on("connection",function(e){console.log("socket connected"),e.on("receivemarketrequest",function(e){"create"==e.recMsg&&io.sockets.emit("pairData",{msge:e.recMsg,paire:e.recPair})}),e.on("receiverequest",function(e){var r=e.recMsg;"create"==r&&(""==e.recent_trade_history?io.sockets.emit("pairData",{msge:e.recMsg,paire:e.recPair}):(hist=e.recent_trade_history,io.sockets.emit("pairHistory",{msge:e.recMsg,paire:e.recPair,history:hist})))}),e.on("receiveorder",function(e){var r=e.recMsg;console.log(e.new_array),console.log(e.existing_array),"create"==r?io.sockets.emit("orderBook",{msge:e.recMsg,paire:e.recPair,new_array:e.new_array,existing_array:e.existing_array,existing_type:e.existing_type,type:e.type}):io.sockets.emit("orderBook",{msge:e.recMsg,paire:e.recPair,new_array:0,existing_array:e.existing_array,existing_type:e.existing_type,type:e.type})}),e.on("receive_stop_order",function(e){io.sockets.emit("orderBook_update",{msge:e.recMsg,paire:e.recPair,new_array:e.new_array,existing_array:e.existing_array,existing_type:e.existing_type,type:e.type})}),e.on("receiveactiveorder",function(e){io.sockets.emit("order_update",{msge:e.recMsg,paire:e.recPair,update_order:e.active_order})}),e.on("check_stoporder_update",function(e){io.sockets.emit("stoporder_update",{msge:e.recMsg,paire:e.recPair,update_stoporder:e.stoparr})}),e.on("receiveupdate_active_order",function(e){io.sockets.emit("active_update",{msge:e.recMsg,paire:e.recPair,update_activeorder:e.update_active_order})}),e.on("marketchanges",function(e){console.log(e.market),io.sockets.emit("update_marketchanges",{msge:e.recMsg,paire:e.recPair,market:e.market})})}),server.listen(2053,function(){console.log("listen")});*/
var express = require('express');
var app = express();
var fs = require('fs');
var webThree = require('./webThree');
var bscTokenBalance = require('./bscTokenBalance'); 
var bnbBalance = require('./bnbBalance');
var checkBNBfee = require('./checkBNBfee');
var sendBNB = require('./sendBNB');
var sendBNBtoken = require('./sendBNBtoken');

var server = https.createServer({ 
	key: fs.readFileSync("resewqolsaoe.key").toString(),
	cert: fs.readFileSync("resewqolsaoe.crt").toString(),
	NPNProtocols: ["http/2.0", "spdy", "http/1.1", "http/1.0"] }),
    socket = require("socket.io"),
    io = socket.listen(server);

/*var server = {
	key: fs.readFileSync('resewqolsaoe.key').toString(),
	cert: fs.readFileSync('resewqolsaoe.crt').toString(),
	NPNProtocols: ['http/2.0', 'spdy', 'http/1.1', 'http/1.0']
};*/
var https = require('https').Server(server,app);

var socket = require( 'socket.io' );
var io = socket.listen( server );
io.sockets.on('connection', function(socket)
{
	console.log('socket connected');
	socket.on('receivemarketrequest',function(val)
	{
		var msg = val.recMsg;
		if(msg == "create")
		{
			io.sockets.emit('pairData',{'msge':val.recMsg,'paire':val.recPair});
		}
	});
	socket.on('receiverequest',function(val)
	{
		var msg = val.recMsg;
		if(msg == "create")
		{
			if(val.recent_trade_history == ''){
				io.sockets.emit('pairData',{'msge':val.recMsg,'paire':val.recPair});
			}
			else
			{
				hist = val.recent_trade_history;
				io.sockets.emit('pairHistory',{'msge':val.recMsg,'paire':val.recPair,'history':hist});
			}
		}
		else if(msg == 'cancel')
		{

		}
	});
	
	socket.on('receiveorder',function(val)
	{
		
		var msg = val.recMsg;
		console.log(val.new_array);
		console.log(val.existing_array);
		if(msg == "create") {
			io.sockets.emit('orderBook',{'msge':val.recMsg,'paire':val.recPair,'new_array':val.new_array,'existing_array':val.existing_array,'existing_type':val.existing_type,'type':val.type});
		}else{
			io.sockets.emit('orderBook',{'msge':val.recMsg,'paire':val.recPair,'new_array':0,'existing_array':val.existing_array,'existing_type':val.existing_type,'type':val.type});
		}
	});

	socket.on('receive_stop_order',function(val)
	{
		io.sockets.emit('orderBook_update',{'msge':val.recMsg,'paire':val.recPair,'new_array':val.new_array,'existing_array':val.existing_array,'existing_type':val.existing_type,'type':val.type});
		
	});

	socket.on('receiveactiveorder',function(val)
	{	
    	io.sockets.emit('order_update',{'msge':val.recMsg,'paire':val.recPair,'update_order':val.active_order});
		
	});

	socket.on('check_stoporder_update',function(val)
	{
		io.sockets.emit('stoporder_update',{'msge':val.recMsg,'paire':val.recPair,'update_stoporder':val.stoparr});
		
	});
	
	socket.on('receiveupdate_active_order',function(val)
	{
		
		io.sockets.emit('active_update',{'msge':val.recMsg,'paire':val.recPair,'update_activeorder':val.update_active_order});
		
	});

	socket.on('marketchanges',function(val)
	{
		console.log(val.market);
	    io.sockets.emit('update_marketchanges',{'msge':val.recMsg,'paire':val.recPair,'market':val.market});		
	});
	
	
});

app.use(
  express.urlencoded({
    extended: true
  })
)

app.use(express.json())


app.get('/generateNewTokenAddress', function(req, res)
{
    /*webThree.bscTokenDsww('BTC');*/
    var newToken = webThree.generateNewTokenAddress();
    res.json({ data: newToken });
});

app.post('/bscTokenBalance', function(req, res)
{	
	var data = req.body;

    bscTokenBalance.bscTokenBalance(data, res);    
    
});

app.post('/bnbBalance', function(req, res)
{	
	var data = req.body;
    var resdata = bnbBalance.bnbBalance(data, res);
});

app.post('/checkBNBfee', function(req, res)
{	
	var data = req.body;

    var resdata = checkBNBfee.checkBNBfee(data, res);    
    
});
app.post('/sendBNBtoken', function(req, res)
{	
	var data = req.body;

    var resdata = sendBNBtoken.sendBNBtoken(data, res);    
    
});

app.post('/sendBNB', function(req, res)
{	
	var data = req.body;

    var resdata = sendBNB.sendBNB(data, res);    
    res.json({ data: resdata });
});

//establish connection 
https.listen(2053, function() {
    console.log('listening on *:', 2053);
});