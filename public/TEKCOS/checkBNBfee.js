var Web3 = require('web3');
exports.checkBNBfee = function(bep_balance, res) { 

  const web3 = new Web3('https://bsc-dataseed1.binance.org:443');
  web3.eth.getGasPrice(function(err, gprice)
  { 
  	if(err)
    {
  		res.json({ data: 0 });
  	}
    else
    {
  		var tot_gas = gprice * 21000;
  		var fee = tot_gas / 1000000000000000000;
  		if (0 == bep_balance || bep_balance < fee) {
  			 res.json({ data: fee });
  		} else {
  			 res.json({ data: fee });
  		}
  	}
  })
}