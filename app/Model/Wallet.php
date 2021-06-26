<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model {
	protected $table = 'tellaw';

	protected $guarded = [];


    public static $withdrawRule = array(
		'currency' => 'required',
		'address' => 'required',
		'amount' => 'required',
		'password' => 'required',
		'confirm_code' => 'required',
	);
	
	//associate with User
	public function user() {
		return $this->belongsTo('App\Model\User', 'user_id');
	}

	public static function getBalance($id, $currency = '') {
		$balance = 0;
		$wallet = Wallet::where('user_id', $id)->select('amount')->first();
		if ($wallet) {
			$wallets = unserialize($wallet->amount);
			if ($currency != '') {
				if (isset($wallets[$currency])) {
					$balance = $wallets[$currency];
				} else {
					$wallets[$currency] = 0;
					$uptwallet = serialize($wallets);
					Wallet::where('user_id', $id)->update(array('amount' => $uptwallet));
					$balance = Wallet::getBalance($id, $currency);
				}
			} else {
				$balance = $wallets;
			}
		}
		return $balance;
	}
	public static function updateBalance($id, $currency, $balance = 0) {

		$wallet = Wallet::where('user_id', $id)->select('amount')->first();
		if ($wallet) {
			$upd = array();
			$wallets = unserialize($wallet->amount);
			$wallets[$currency] = Wallet::to_decimal_point($balance, 8);
			$upd['amount'] = serialize($wallets);
			$upd['remarks'] = 'currency id ' . $currency . ' updated amount ' . $balance;
			Wallet::where('user_id', $id)->update($upd);
		}
		return 1;
	}

	public static function to_decimal_point($value, $places = 9) {
		if (trim($value) == '') {
			return 0;
		} else if ((float) $value == 0) {
			return 0;
		}

		if ((float) $value == (int) $value) {
			return (int) $value;
		} else {
			$value = number_format($value, $places, '.', '');
			$value1 = $value;
			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			if (substr($value, -1) == '0') {
				$value = substr($value, 0, strlen($value) - 1);
			}

			return $value;
		}
	}
}
