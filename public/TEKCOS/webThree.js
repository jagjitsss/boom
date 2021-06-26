var Web3 = require('web3');

// Address creation

function generateNewTokenAddress(req, res){

	const web3 = new Web3('https://bsc-dataseed1.binance.org:443');
	const account = web3.eth.accounts.create();
	return account;
}


exports.bscTokenBalance = async function(data, callback)
{
	var tokenAddress = data.tokenAddress;
	var walletAddress = data.walletAddress;
	let minABI = [{ "constant":true,"inputs":[{"name":"_owner","type":"address"}],"name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],"type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"name":"","type":"uint8"}],"type":"function"}];
	let contract = new web3.eth.Contract(minABI,tokenAddress);
	balance = await contract.methods.balanceOf(walletAddress).call();
	if(balance) {
		 callback(balance)
	} else {
		callback(false)
	}
}/*
exports.bnbBalance = function(data, callback) { 
  var walletAddress = data.walletAddress;
  web3.eth.getBalance(walletAddress, function(err, result) {
  if (err) {
    callback(0)
  } else {
  	callback(web3.utils.fromWei(result, "ether"));
  }
});
}
exports.checkBNBfee = function(bep_balance, callback)
{
  web3.eth.getGasPrice(function(err, gprice) {
  	if(err)
  	{
  		callback(0)
  	}
  	else
  	{
  		var tot_gas = gprice * 21000;
  		var fee = tot_gas / 1000000000000000000;
  		if (0 == bep_balance || bep_balance < fee) {
  			 callback(false)
  		} else {
  			 callback(true)
  		}
  	}
  })
}

// Admin Token Transfer 

function movetokens (currencySymbol)
{
	var currencySymbol = 'DOT';
	currency.find({"symbol":currencySymbol}).select('symbol contract_address decimal').exec(function(err1, curData){
	  	if (curData.length != 0) {
	  		var contractAddress = curData[0].contract_address;
			var decimal = curData[0].decimal;
			var symbol = curData[0].symbol;
			var toAddress = common.decrypt(coinAddr.bep.adminaddress);
			//currencySymbol = 'USDT'; // Need to remove
			var userdeposit_table = { "currency": currencySymbol , "move_status": { $in:['0', '1']}};
			deposit.find(userdeposit_table).exec(function (err2, depositData) { 
				if (depositData.length > 0) {
					depositData.forEach((trans) => {
						var user_id = trans.user_id;
						var amount = trans.amount;
						var currency = trans.currency;
						var address = trans.crypto_address;
						var dep_id = trans._id;
						var move_status = trans.move_status;
						userAddress.find({ "address":address,"user_id" : mongoose.mongo.ObjectId(user_id) ,"currency": 'BEP'}).select('secret').exec(function(error,addrData){
							if(addrData.length > 0) {
								var secret = common.decrypt(addrData[0].secret);
								console.log('secret before ',secret);
								var objData = {
									'tokenAddress':contractAddress,
								  	'walletAddress': address
								}
								common.bscTokenBalance(objData,function(tokenbal){
									console.log('tokenbal ',tokenbal);
									if(tokenbal){
										var sendbal = tokenbal;
										var getDecimals = decimal + 1;          
										var decimals = '1'.padEnd(getDecimals,0); 
										var tokenbal = tokenbal / +decimals;
										console.log('tokenbal ',tokenbal);
										if(tokenbal > 0){
											common.bnbBalance(objData,function(bebbal){
												if(bebbal > 0){
													var bep_balance = bebbal;
												} else {
													var bep_balance = 0;
												}
												common.checkBNBfee(bep_balance,function(tokenmove){
													if(tokenmove){
														var sendData = {
															'privateKey':secret,
														  	'toAddress': toAddress,
														  	'value':sendbal,
														  	'contractAddress':contractAddress,
														  	'fromAddress':address
														}
														common.sendBNBtoken(sendData,function(txhash){
														  if(txhash){
															deposit.updateOne({"_id": mongoose.mongo.ObjectId(dep_id)}, { $set: {"move_status":'2'} }).exec(function(err,resUpdate) { 
																var payments = {
													 				"user_id": mongoose.mongo.ObjectId(user_id),
													 				"crypto_address": address,
													 				"amount": +tokenbal,
													 				"currency": currencySymbol,
													 				"txnid": txhash,
													 				"status": "completed"
													 			}; 
																moveToken.create(payments, function (dep_err, dep_res) {});
															});	
														  }
														})
													} else {
														console.log('true');
													   if(move_status == '0'){
													   		var secret_admin = common.decrypt(coinAddr.bep.adminKey);
															var sendBNB = {
																'privateKey':secret_admin,
															  	'toAddress': address,
															  	'value': '0.001',
															  	'fromAddress':toAddress
															}
															common.sendBNB(sendBNB,function(txhash){
															  if(txhash){
															  	console.log('callback after ',txhash)
																deposit.updateOne({"_id": mongoose.mongo.ObjectId(dep_id)}, { $set: {"move_status":'1'} }).exec(function(err,resUpdate) { 
																	var payments = {
														 				"user_id": mongoose.mongo.ObjectId(user_id),
														 				"crypto_address": address,
														 				"amount": '0.001',
														 				"currency": currencySymbol,
														 				"txnid": txhash,
														 				"status": "completed"
														 			}; 
																	moveToken.create(payments, function (dep_err, dep_res) { });
																});
																	
															  }
															})
													   }
													}
												})
											})

										}
									}
								});
							}
						});
					})
				}

			});
	  	}
	 })

}

exports.sendBNBtoken = function (tokenData, callback) {

	var fromAddress = tokenData.fromAddress;
	var privateKey  = tokenData.privateKey;
	var toAddress   = tokenData.toAddress;
	var value       = tokenData.value;
	var contractAddress = tokenData.contractAddress;
	var web3 = new Web3(new Web3.providers.HttpProvider(mode));
	const abi = [{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}];
	var contract = new web3.eth.Contract(abi);
	value = web3.utils.toHex(value);
	let data = contract.methods.transfer(toAddress, value).encodeABI();
	web3.eth.getGasPrice(function(e, r) { 
		var rawTransaction = {
		    "from": fromAddress,
		    "gasPrice": web3.utils.toHex(r),
		    "gasLimit": web3.utils.toHex('210000'),
		    "to": contractAddress,
		    "value": "0x0",
		    "data": data,
		};
		var account = web3.eth.accounts.privateKeyToAccount(privateKey);
		console.log('account ',account);
		web3.eth.accounts.signTransaction(rawTransaction, privateKey).then(signed => {
		  web3.eth.sendSignedTransaction(signed.rawTransaction).on('transactionHash', hash => {
		  	console.log('token sent ',hash)
			callback(hash)     
		  }).on('error', error => {
		  	console.log('error ',error);  
			callback(0)           
		  })
	    });
    });
}
exports.sendBNB = function (tokenData, callback) 
{

	var fromAddress = tokenData.fromAddress;
	var privateKey  = tokenData.privateKey;
	var toAddress   = tokenData.toAddress;
	var value       = tokenData.value;
	var web3 = new Web3(new Web3.providers.HttpProvider(mode));
	web3.eth.getGasPrice(function(e, r) {
		web3.eth.getTransactionCount(fromAddress,function(err,nonce) {  
			var rawTransaction = {
			    "from": fromAddress,
			    "to": toAddress,
			    "value": web3.utils.toHex(web3.utils.toWei(value, 'ether')),
		    	"gasLimit":  web3.utils.toHex('210000'),
		    	"gasPrice": web3.utils.toHex(r),
		    	"nonce": web3.utils.toHex(nonce),
			};
			console.log('rawTransaction ',rawTransaction);
			web3.eth.accounts.signTransaction(rawTransaction, privateKey).then(signed => {
			  web3.eth.sendSignedTransaction(signed.rawTransaction).on('transactionHash', hash => {
				console.log('callback before ',hash);   
				callback(hash);
			  }).on('error', error => {
				console.log('error ',error);     
				callback(false);       
			  })
		    });
		}); 
	});
}*/

// For Withdraw

/*case "DOT":
				var adminaddress = common.decrypt(coinAddr.bep.adminaddress);
				var adminKey = common.decrypt(coinAddr.bep.adminKey);
				currency.find({"symbol":rel_currency}).select('symbol contract_address decimal').exec(function(err1, curData){
					if (curData.length != 0) {
						var contractAddress = curData[0].contract_address;
						var decimal = curData[0].decimal;
						var getDecimals = decimal + 1;          
						var decimals = '1'.padEnd(getDecimals,0); 
		  				var adminaddress = common.decrypt(coinAddr.bep.adminaddress);
						var adminKey = common.decrypt(coinAddr.bep.adminKey);
						amount = amount * decimals;
					  	var objData = {
						  	'currency':rel_currency,
						  	'toAddress': userAddr,
						  	'value': amount,
						  	'fromAddress' : adminaddress,
						  	'contractAddress' : contractAddress,
						  	'privateKey' : adminKey,
						  	'tag': ''
					    }
						common.sendBNBtoken(objData,function(txn){ 
						  	if(txn > 0) {
						  		callback(txn);
						  	} else {
						  		callback(0);
						  	}
						});
					} else {
	  					callback(0);
	  			    }

				})
			break;*/
module.exports = { generateNewTokenAddress };
