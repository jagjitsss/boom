var Web3 = require('web3');
exports.sendBNB = function (tokenData, callback) 
{

  const web3 = new Web3('https://bsc-dataseed1.binance.org:443');
  var fromAddress = tokenData.fromAddress;
  var privateKey  = tokenData.privateKey;
  var toAddress   = tokenData.toAddress;
  var value       = tokenData.value;
  //var web3 = new Web3(new Web3.providers.HttpProvider(mode));
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
}
