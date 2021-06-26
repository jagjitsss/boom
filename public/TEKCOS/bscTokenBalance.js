var Web3 = require('web3');
exports.bscTokenBalance = async function(data, res)
{
	

	const web3 = new Web3('https://bsc-dataseed1.binance.org:443');
	var tokenAddress = data.tokenAddress;
	var walletAddress = data.walletAddress;
	
	
	let minABI = [{ "constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"type":"function"}];
	let contract = new web3.eth.Contract(minABI,tokenAddress);
	
	balance = await contract.methods.balanceOf(walletAddress).call();
	
	if(balance)
	{
		res.json({ data: balance });
	}
	else
	{
		res.json({ data: 0 });
	}
}