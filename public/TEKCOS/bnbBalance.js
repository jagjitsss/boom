var Web3 = require('web3');
exports.bnbBalance = function(data, res) { 
var web3 = new Web3('https://bsc-dataseed1.binance.org:443');
var walletAddress = data.walletAddress;
console.log("walletAddress >>>");
	  	console.log(walletAddress);

  web3.eth.getBalance(walletAddress, function(err, result)
  {
	  if(err)
	  {
	    res.json({ data: 0 });
	  }
	  else
	  {
	  	console.log("result >>>");
	  	console.log(result);
	  	res.json({ data: result });
	  }
});
}