<?php 

/**
* Report
*/
class Report extends Model
{

	public function getPurchases($period)
	{
		$query = $this->database->query("SELECT mp.*, s.name AS supplier FROM `" . DB_PREFIX . "medicine_purchase` AS mp LEFT JOIN `" . DB_PREFIX . "suppliers` AS s ON s.id = mp.supplier WHERE mp.date between '".$period['start']."' AND '".$period['end']."' ORDER BY date DESC");
		return $query->rows;
	}
	
	public function getPurchaseStats($period)
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, IF(amount > 0, 100, 0) AS p_amount, SUM(discount_value) AS discount, (SUM(discount_value) / SUM(amount)) * 100 AS p_discount,  SUM(tax) AS tax, (SUM(tax) / SUM(amount)) * 100 AS p_tax FROM `" . DB_PREFIX . "medicine_purchase` WHERE date between '".$period['start']."' AND '".$period['end']."'");
		$data = $query->row;

		$data['p_discount'] = number_format((float)$query->row['p_discount'], 2, '.', '');
		$data['p_tax'] = number_format((float)$query->row['p_tax'], 2, '.', '');
		return $data;
	}

	public function getBills($period)
	{
		$query = $this->database->query("SELECT * FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date BETWEEN ? AND ? ORDER BY bill_date DESC", array($period['start'], $period['end']));
		return $query->rows;
	}

	public function getBillsStats($period)
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, IF(amount > 0, 100, 0) AS p_amount, SUM(discount_value) AS discount, (SUM(discount_value) / SUM(amount)) * 100 AS p_discount,  SUM(tax) AS tax, (SUM(tax) / SUM(amount)) * 100 AS p_tax FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date between '".$period['start']."' AND '".$period['end']."'");
		$data = $query->row;
		$data['p_discount'] = number_format((float)$query->row['p_discount'], 2, '.', '');
		$data['p_tax'] = number_format((float)$query->row['p_tax'], 2, '.', '');
		return $data;
	}

	public function getMedicines()
	{
		$query = $this->database->query("SELECT m.*, mc.name AS category_name, SUM(mb.qty) AS qty, SUM(mb.qty) - SUM(mb.sold) AS livestock FROM `" . DB_PREFIX . "medicines` AS m LEFT JOIN `" . DB_PREFIX . "medicine_category` AS mc ON mc.id = m.category LEFT JOIN `" . DB_PREFIX . "medicine_batch` AS mb ON mb.medicine_id = m.id AND mb.expiry > '".date('Y-m')."' GROUP BY m.id ORDER BY m.date_of_joining DESC");
		return $query->rows;
	}

	public function getOutofStock()
	{
		$query = $this->database->query("SELECT m.*, mc.name AS category_name, SUM(mb.qty) - SUM(mb.sold) AS livestock FROM `" . DB_PREFIX . "medicines` AS m LEFT JOIN `" . DB_PREFIX . "medicine_category` AS mc ON mc.id = m.category LEFT JOIN `" . DB_PREFIX . "medicine_batch` AS mb ON mb.medicine_id = m.id AND mb.expiry > '".date('Y-m')."' GROUP BY m.id ORDER BY m.date_of_joining DESC");
		return $query->rows;
	}


	public function getAllIncome($period)
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, SUM(discount_value) AS discount, SUM(tax) AS tax FROM `" . DB_PREFIX . "medicine_bill` WHERE bill_date between '".$period['start']."' AND '".$period['end']."'");
		$data['bill'] = $query->row;
		
		$data['amount'] = number_format((float)$data['bill']['amount'], 2, '.', '');
		$data['paid'] = number_format((float)$data['bill']['amount'], 2, '.', '');
		$data['tax'] = number_format((float)$data['bill']['tax'], 2, '.', '');
		$data['discount'] = number_format((float)$data['bill']['discount'], 2, '.', '');
		return $data;
	}

	public function getAllExpense($period)
	{
		$query = $this->database->query("SELECT SUM(amount) AS amount, SUM(discount_value) AS discount,  SUM(tax) AS tax FROM `" . DB_PREFIX . "medicine_purchase` WHERE date between '".$period['start']."' AND '".$period['end']."'");
		$data['purchase'] = number_format((float)$query->row['amount'], 2, '.', '');

		return $data;
	}

}