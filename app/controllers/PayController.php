<?php

/*
 *  app/controllers/PayController.php
 */

class PayController extends BaseController {
	
	public $hasError = false;
	public $errors = array();
	
	private function checkOrder ($orderNum) {
		if (preg_match("/^[0-9]+$/",$orderNum)) {
			return $orderNum;
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 10, 'message' => 'Некорректный номер заказа.');
		}
	}
	
	private function checkValue ($orderValue) {
		$orderValue = str_replace(' ','',$orderValue);
		if (preg_match("/^[0-9]+\.[0-9]{2}$/",$orderValue)) {
			return intval(floatval($orderValue)*100);
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 11,'message' => 'Некорректная стоимость заказа.');
		}
	}
	
	private function checkCard ($cardNum) {
		$cardNum = str_replace('-','',$cardNum);
		$sum = 0;
		if (preg_match("/^[0-9]{16}$/",$cardNum)) {
			for ($i = 0; $i < 16; $i++) { // Luhn check for card number
				if ($i % 2) {
					$sum+=$cardNum[$i];
				}
				else {
					$tmp=$cardNum[$i]*2;
					$sum+=($tmp > 9) ? $cardNum[$i]*2-9 : $cardNum[$i]*2;
				}
			}
			if ($sum % 10) {
				$this->hasError = true;
				$this->errors[] = array('code' => 12, 'message' => 'Номер карты некорректен.'); // Card number is wrong
			}
			else
				return $cardNum;
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 12, 'message' => 'Номер карты некорректен.');
		}
	}
	
	private function checkDate ($validDate) {
		$validDate = str_replace(' ','',$validDate);
		if (preg_match("/^(0[1-9]|1[012])\/[0-9]{2}$/",$validDate)) {
			$tmp = explode('/',$validDate);
			if ((intval($tmp[0]) >= intval(date('m'))) && (intval($tmp[1]) >= intval(date('y')))) {
				return $validDate;
			}
			else {
				$this->hasError = true;
				$this->errors[] = array('code' =>19, 'message' => 'Истек срок годности карты.');
			}
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 13, 'message' => 'Некорректный формат даты.');
		}
	}
	
	private function checkName ($cardHolder) {
		$cardHolder = strtoupper($cardHolder);
		$cardHolder = trim($cardHolder);
		if (preg_match("/^[A-Z ]+$/",$cardHolder)) {
			return $cardHolder;
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 14, 'message' => 'Некорректно введено имя.');
		}
	}
	
	private function checkCv ($cardCv) {
		if (preg_match("/^[0-9]{3}$/",$cardCv)) {
			return $cardCv;
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 15, 'message' => 'CVC/CVV должен содержать 3 цифры.');
		}
	}
	
	private function checkCurrency ($orderCurrency) {
		if (preg_match("/^(rub|usd)$/",$orderCurrency)) {
			return $orderCurrency;
		}
		else {
			$this->hasError = true;
			$this->errors[] = array('code' => 16, 'message' => 'Валюта платежа задана неправильно.');
		}
	}

	public function commit () {
		// Creating new Payment
		$param = Input::all();
		$orderCurrency = $this->checkCurrency($param['orderCurrency']);
		$orderNum = $this->checkOrder($param['orderNum']);
		$orderValue = $this->checkValue($param['orderValue']);
		$cardNum = $this->checkCard($param['cardNum']);
		$validDate = $this->checkDate($param['validDate']);
		$cardHolder = $this->checkName($param['cardHolder']);
		$cardCv = $this->checkCv($param['cardCv']);
		
		if ($this->hasError) {
			return View::make ('result',array('err' => $this->errors));
		}
		else {
			$payment = new Payment();
			$currency = Currency::select('id')->where('name',$orderCurrency)->first();
			
			$payment->order = $orderNum;
			$payment->currency_id = $currency->id;
			$payment->value = $orderValue;
			$payment->cardnum = $cardNum;
			$payment->valid = $validDate;
			$payment->name = $cardHolder;
			$payment->cv = $cardCv; // There's nothing to do with CVC/CVV, we'll write it to DB :-)
			
			$payment->save();
			
			return View::make ('result',array('orderNum' => $orderNum,'message' => 'Упешно оплачен'));
		}
	}
	
	public function update () {
		// Updating existing Payment
		$param = Input::all();
		$paymentId = $param['id'];
		$orderCurrency = $this->checkCurrency($param['orderCurrency']);
		$orderNum = $this->checkOrder($param['orderNum']);
		$orderValue = $this->checkValue($param['orderValue']);
		$cardNum = $this->checkCard($param['cardNum']);
		$validDate = $this->checkDate($param['validDate']);
		$cardHolder = $this->checkName($param['cardHolder']);
		$cardCv = $this->checkCv($param['cardCv']);
		
		if ($this->hasError) {
			return array('err' => $this->errors);
		}
		else {
			$payment = Payment::find($paymentId);
			$currency = Currency::select('id')->where('name',$orderCurrency)->first();
			
			$payment->order = $orderNum;
			$payment->currency_id = $currency->id;
			$payment->value = $orderValue;
			$payment->cardnum = $cardNum;
			$payment->valid = $validDate;
			$payment->name = $cardHolder;
			$payment->cv = $cardCv; // There's nothing to do with CVC/CVV, we'll write it to DB :-)
			
			$payment->save();
			
			// Getting payment to array
			$curPayment=$payment->toArray();
			// Formattion order value to "$ 1 000.00"
			$curPayment['formattedValue'] = $payment->currency->sign.' '.number_format(($payment->value/100),2,'.'," ");
			// Formatting card number to xxxx-xxxx-xxxx-xxxx
			preg_match("/([0-9]{4})([0-9]{4})([0-9]{4})([0-9]{4})/",$payment->cardnum,$matches);
			$curPayment['formattedcardnum'] = implode('-',array_slice($matches,1));
			
			return array('message' => 'Сохранено','payment' => $curPayment);
		}
	}
	
	public function delete () {
		// Soft deletion
		$paymentId = Input::get('id');
		
		$payment = Payment::find($paymentId);
		$payment->delete();
		
		return array('id' => $paymentId , 'message' => 'Удален');
	}
	
	public function undelete () {
		// Restoring
		$paymentId = Input::get('id');
		
		$payment = Payment::withTrashed()->find($paymentId);
		$payment->restore();
		
		return array('id' => $paymentId , 'message' => 'Восстановлен');
	}
	
}
