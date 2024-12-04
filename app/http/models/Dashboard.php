<?php

/**
* Dashboard Model
*/

class Dashboard extends Model
{

	public function getMainStats()
	{
		
		$query = $this->database->query("SELECT SUM(amount) AS amount FROM `" . DB_PREFIX . "medicine_bill`");
		$data['bill'] = $query->row;
		
		$data['amount'] = number_format((float)$data['bill']['amount'], 2, '.', '');

		$query = $this->database->query("SELECT SUM(amount) AS amount FROM `" . DB_PREFIX . "medicine_purchase`");
		$data['purchase'] = number_format((float)$query->row['amount'], 2, '.', '');


		return $data;
	}

	public function getIncomeStats()
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, SUM(discount_value) AS discount, SUM(tax) AS tax FROM `" . DB_PREFIX . "medicine_bill`");
		$data['bill'] = $query->row;

		$data['amount'] = number_format((float)$data['bill']['amount'], 2, '.', '');
		$data['tax'] = number_format((float)$data['bill']['tax'], 2, '.', '');
		$data['discount'] = number_format((float)$data['bill']['discount'], 2, '.', '');
		return $data;
	}

	public function getRevenueStats()
	{
		$query = $this->database->query("SELECT SUM(amount) AS bill FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date > DATE_SUB(now(), INTERVAL 12 MONTH)");
		$data['bill_12'] = $query->row['bill'];

		$query = $this->database->query("SELECT SUM(amount) AS bill FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date > DATE_SUB(now(), INTERVAL 1 MONTH)");
		$data['bill_1'] = $query->row['bill'];

		return $data;
	}


}