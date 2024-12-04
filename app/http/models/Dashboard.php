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

		$query = $this->database->query("SELECT SUM(amount) AS amount FROM `" . DB_PREFIX . "staff_payment`");
		$data['salary'] = number_format((float)$query->row['amount'], 2, '.', '');

		$query = $this->database->query("SELECT SUM(amount) AS amount FROM `" . DB_PREFIX . "medicine_purchase`");
		$data['purchase'] = number_format((float)$query->row['amount'], 2, '.', '');


		return $data;
	}

	public function getChartBill()
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, MONTH(bill_date) AS month FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(bill_date)");
		return $query->rows;
	}

	public function getChartPurchase()
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, MONTH(date) AS month FROM `" . DB_PREFIX . "medicine_purchase` WHERE date > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(date)");
		return $query->rows;
	}

	public function getChartCustomer()
	{
		$query = $this->database->query("SELECT COUNT(id) AS amount, MONTH(date_of_joining) AS month FROM `" . DB_PREFIX . "customers` WHERE date_of_joining > DATE_SUB(now(), INTERVAL 12 MONTH) GROUP BY MONTH(date_of_joining)");
		return $query->rows;
	}

	public function getChartSalary()
	{
		$month = date("Y-m", strtotime( date( 'Y-m-01' )." -12 months"));

		$query = $this->database->query("SELECT SUM(amount) AS amount, month FROM `" . DB_PREFIX . "staff_payment` WHERE month_year > '".$month."' GROUP BY month");
		return $query->rows;
	}

	public function getIncomeStats()
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, SUM(discount_value) AS discount, SUM(tax) AS tax FROM `" . DB_PREFIX . "medicine_bill`");
		$data['bill'] = $query->row;
		
		$data['amount'] = number_format((float)$data['bill']['amount'], 2, '.', '');
		$data['paid'] = number_format((float)$data['bill']['amount'], 2, '.', '');
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

	public function getLatestPurchase()
	{
		$query = $this->database->query("SELECT mp.*, s.name AS supplier FROM `" . DB_PREFIX . "medicine_purchase` AS mp LEFT JOIN `" . DB_PREFIX . "suppliers` AS s ON s.id = mp.supplier ORDER BY date_of_joining DESC LIMIT 5");
		return $query->rows;
	}
}