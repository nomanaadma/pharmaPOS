<?php

/**
* Medicine Model
*/

include_once('Search.php');

class Medicine extends Search
{

	public function getSuppliers()
	{
		$query = $this->database->query("SELECT id, name FROM `suppliers`");
		return $query->rows;
	}

	public function getMedicines()
	{
		$query = $this->database->query("SELECT m.*, mc.name AS category_name, SUM(mb.qty) AS qty, SUM(mb.qty) - SUM(mb.sold) AS livestock FROM `medicines` AS m LEFT JOIN `medicine_category` AS mc ON mc.id = m.category LEFT JOIN `medicine_batch_view` AS mb ON mb.medicine_id = m.id AND mb.expiry > '".date('Y-m')."' GROUP BY m.id ORDER BY m.created_date DESC");
		return $query->rows;
	}

	public function getMedicine($id)
	{
		$query = $this->database->query("SELECT m.*, mc.name AS category_name FROM `medicines` AS m LEFT JOIN `medicine_category` AS mc ON mc.id = m.category WHERE m.id = ? LIMIT 1", array((int)$id));
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getMedicineLiveStock($id)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE medicine_id = ? AND expiry > ? ORDER BY medicine_id", array($id, date('Y-m')));
		return $query->rows;
	}

	public function getMedicineBadStock($id)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE medicine_id = ? AND expiry < ? AND qty > sold",
			array($id, date('Y-m')));
		return $query->rows;
	}

	public function updateMedicine($data)
	{
		$this->database->query("UPDATE `medicines` SET `name` = ?, `company` = ?, `generic` = ?, `medicine_group` = ?, `category` = ?, `storebox` = ?, `minlevel` = ?, `reorderlevel` = ?, `unit` = ?, `unitpacking` = ?, `note` = ? WHERE `id` = ?", array($this->database->escape($data['name']), $data['company'], $data['generic'], $data['medicine_group'], (int)$data['category'], $data['storebox'], $data['minlevel'], $data['reorderlevel'], $data['unit'], $data['unitpacking'], $data['note'], (int)$data['id']));
		return true;
	}

	public function createMedicine($data)
	{
		$query = $this->database->query("INSERT INTO `medicines` (`name`, `company`, `generic`, `medicine_group`, `category`, `storebox`, `minlevel`, `reorderlevel`, `unit`, `unitpacking`, `note`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $data['company'], $data['generic'], $data['medicine_group'], (int)$data['category'], $data['storebox'], $data['minlevel'], $data['reorderlevel'], $data['unit'], $data['unitpacking'], $data['note'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteMedicine($id)
	{
		$query = $this->database->query("DELETE FROM `medicines` WHERE `id` = ?", array((int)$id));
		if ($query->num_rows > 0) { return true; }
		else { return false; }
	}

	public function getSearchedMedicine($data)
	{
		$query = $this->database->query("SELECT id, name AS label, generic FROM `medicines` WHERE name like '%".$data."%' LIMIT 10");
		return $query->rows;
	}


	public function getLiveStocks()
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE expiry > ? AND status = 1 ORDER BY medicine_id", array(date('Y-m')));
		return $query->rows;
	}

	public function getExpiredStocks()
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE expiry < ? AND status = 1 ORDER BY medicine_id", array(date('Y-m')));
		return $query->rows;
	}

	public function getWillExpireStocks($date)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE expiry < ? AND expiry > ? AND status = 1 ORDER BY medicine_id", array($date, date('Y-m')));
		return $query->rows;
	}

	public function updateStock($data)
	{
		$this->database->query("UPDATE `medicine_batch` SET sold = qty - ? WHERE `id` = ? AND `medicine_id` = ? ", array($data['available'], $data['id'], $data['medicine_id']));
		return true;
	}

	public function deleteStock($id)
	{
		$this->database->query("UPDATE `medicine_batch` SET status = 0 WHERE `id` = ?", array((int)$id));
		return true;
	}

	public function getMedicineBills($period)
	{
		$query = $this->database->query("SELECT * FROM `medicine_bill` WHERE bill_date BETWEEN ? AND ? ORDER BY bill_date DESC", array($period['start'], $period['end']));
		return $query->rows;
	}

	public function getMedicineBill($id)
	{
		$query = $this->database->query("SELECT mb.*, pm.name AS payment_method FROM `medicine_bill` AS mb LEFT JOIN `payment_method` AS pm ON pm.id = mb.method WHERE mb.id = ?", array((int)$id));
		return $query->row;
	}

	public function filterData($options)
	{

		$sqlQuery = "SELECT * FROM `medicine_bill` WHERE 1=?";
		
		$search = $options['search']['value'];

		if($search != '') {
			$sqlQuery .= " AND (name like '%".$search."%'";
			$sqlQuery .= " OR subtotal like '%".$search."%'";
			$sqlQuery .= " OR tax like '%".$search."%'";
			$sqlQuery .= " OR discount_value like '%".$search."%'";
			$sqlQuery .= " OR amount like '%".$search."%'";
			$sqlQuery .= " OR bill_date like '%".$search."%')";
		}

        $queries = $this->queryBuilder($options, $sqlQuery);

		$countQuery = $this->database->query("SELECT COUNT(*) as total FROM `medicine_bill`");
		
		return [
			'data' => $queries['dataQuery']->rows,
			'recordsFiltered' => $queries['filteredQuery']->num_rows,
			'total' => (int)$countQuery->row['total']
		];
	}

	public function getBillItems($id)
	{
		$query = $this->database->query("
		SELECT
			bill_items.*, 
			medicines.`name`,
			medicine_batch.`expiry`, 
			medicines.`name` as batch_name
		FROM
			bill_items
			INNER JOIN
			medicines
			ON 
				bill_items.medicine_id = medicines.id
			INNER JOIN
			medicine_batch
			ON 
				bill_items.medicine_id = medicine_batch.medicine_id AND
				bill_items.batch = medicine_batch.id
		WHERE
			bill_id = ?
		", array((int)$id));

		return $query->rows;
	}

	public function getPaymentMethods()
	{
		$query = $this->database->query("SELECT id, name FROM `payment_method` WHERE status = ?", array(1));
		return $query->rows;
	}

	public function updateMedicineBatchSold($data)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE `id` = ? AND `medicine_id` = ?", array($data['batch'], $data['medicine_id']));

		if ($query->num_rows > 0) {
			$count = $query->row['sold'];
			$count = $count + (float)$data['qty'];
			
			$this->database->query("UPDATE `medicine_batch` SET sold = ? WHERE `id` = ? AND `medicine_id` = ? ", array($count, $data['batch'], $data['medicine_id']));
			return true;
		} else {
			return false;
		}
	}

	public function updateMedicineBatchSoldOnDelete($data)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE `id` = ? AND `medicine_id` = ?", array($data['batch'], $data['medicine_id']));

		if ($query->num_rows > 0) {
			$count = $query->row['sold'];
			$count = $count - (float)$data['qty'];
			
			$this->database->query("UPDATE `medicine_batch` SET `sold` = ? WHERE `id` = ? AND `medicine_id` = ? ", array($count, $data['batch'], $data['medicine_id']));
			return true;
		} else {
			return false;
		}
	}

	public function updateMedicineBill($data)
	{

		$id = (int)$data['id'];

		$this->database->query("UPDATE `medicine_bill` SET `name` = ?, `email` = ?, `mobile` = ?, `method` = ?, `bill_date` = ?, `subtotal` = ?, `tax` = ?, `discount_value` = ?, `amount` = ?, `note` = ?, `customer_id` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $this->database->escape($data['email']), $this->database->escape($data['mobile']), (int)$data['method'], $data['bill_date'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], (int)$data['customer_id'], $id));

		$this->database->query("DELETE FROM `bill_items` WHERE `bill_id` = ?", array((int)$id));

		$this->createBillItems($id, $data['items']);

		return true;
	}

	public function createMedicineBill($data)
	{

		$query = $this->database->query("INSERT INTO `medicine_bill` (`name`, `email`, `mobile`, `method`, `bill_date`, `subtotal`, `tax`, `discount_value`, `amount`, `note`, `customer_id`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $this->database->escape($data['email']), $this->database->escape($data['mobile']), $data['method'], $data['bill_date'], $data['subtotal'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], (int)$data['customer_id'], $data['datetime']));

		if ($query->num_rows > 0) {

			$bill_id = $this->database->last_id();

			$this->createBillItems($bill_id, $data['items']);

			return $bill_id;

		} else {
			return false;
		}
	}

	public function createBillItems($bill_id, $items) {
		
		foreach ($items as $key => $item) {
	
			$this->database->query("INSERT INTO `bill_items` (`bill_id`, `medicine_id`, `batch`, `qty`, `saleprice`, `gross`, `discounttype`, `discount`, `discountvalue`, `tax`, `taxprice`, `price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array(
				$bill_id, 
				$item['medicine_id'], 
				$this->database->escape($item['batch']),
				$this->database->escape($item['qty']),
				$this->database->escape($item['saleprice']),
				$this->database->escape($item['gross']),
				$this->database->escape($item['discounttype']),
				$this->database->escape($item['discount']),
				$this->database->escape($item['discountvalue']),
				$this->database->escape($item['tax']),
				$this->database->escape($item['taxprice']),
				$this->database->escape($item['price']),
			));

		}

	}

	public function deleteMedicineBill($id)
	{
		$this->database->query("DELETE FROM `medicine_bill` WHERE `id` = ?", array((int)$id));
	}

	public function getPurchases($period)
	{
		$query = $this->database->query("SELECT mp.*, s.name AS supplier FROM `medicine_purchase` AS mp LEFT JOIN `suppliers` AS s ON s.id = mp.supplier WHERE mp.date between '".$period['start']."' AND '".$period['end']."' ORDER BY created_date DESC");
		return $query->rows;
	}

	public function getPurchaseView($id)
	{
		$query = $this->database->query("SELECT mp.*, s.name, s.email, s.phone FROM `medicine_purchase` AS mp LEFT JOIN `suppliers` AS s ON s.id = mp.supplier WHERE mp.id = ?", array((int)$id));
		return $query->row;
	}

	public function getPurchase($id)
	{
		$query = $this->database->query("SELECT * FROM `medicine_purchase` WHERE id = ?", array((int)$id));
		return $query->row;
	}

	public function getBatches($id)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE purchase_id = ?", array((int)$id));

		return $query->rows;
	}

	public function getSearchedBatchWithMedicine($data)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE medicine_id = ? AND id = ?", array($data['medicine'], $data['batch']));
		return $query->row;
	}

	public function getSearchedBatch($data)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE medicine_id = ? AND expiry > ? AND status = 1", array($data['id'], $data['monthyear']));
		return $query->rows;
	}

	public function getBatchNameFromId($id)
	{
		$query = $this->database->query("SELECT * FROM `medicine_batch_view` WHERE id = ?", array((int)$id));
		return $query->row;
	}

	public function updateMedicinePurchase($data)
	{
		$this->database->query("UPDATE `medicine_purchase` SET `supplier` = ?, `date` = ?, `total` = ?, `tax` = ?, `discount_value` = ?, `amount` = ?, `note` = ? WHERE `id` = ? ", array($this->database->escape($data['supplier']), $data['date'], $data['total'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], (int)$data['id']));
		return true;
	}

	public function createMedicinePurchase($data)
	{
		$query = $this->database->query("INSERT INTO `medicine_purchase` (`supplier`, `date`, `total`, `tax`, `discount_value`, `amount`, `note`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['supplier']), $data['date'], $data['total'], $data['tax'], $data['discount_value'], $data['amount'], $data['note'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function updateMedicinebatch($data)
	{
		$this->database->query("UPDATE `medicine_batch` SET `batch` = ?, `expiry` = ?, `pqty` = ?, `qty` = ?, `saleprice` = ?, `purchaseprice` = ?, `gross` = ?, `discounttype` = ?, `discount` = ?, `discountvalue` = ?, `tax` = ?, `taxprice` = ?, `price` = ?, `medicine_id` = ? WHERE `id` = ? AND `purchase_id` = ?", array($data['batch'], $data['expiry'], $data['pqty'], (int)$data['qty'], $data['saleprice'], $data['purchaseprice'], $data['gross'], $data['discounttype'], $data['discount'], $data['discountvalue'], $data['tax'], $data['taxprice'], $data['price'], $data['medicine_id'], (int)$data['id'], (int)$data['purchase_id']));
		
		return true;
	}

	public function createMedicinebatch($data)
	{

		$query = $this->database->query("INSERT INTO `medicine_batch` (`batch`, `expiry`, `pqty`, `qty`, `saleprice`, `purchaseprice`, `gross`, `discounttype`, `discount`, `discountvalue`, `tax`, `taxprice`, `price`, `medicine_id`, `purchase_id`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($data['batch'], $data['expiry'], $data['pqty'], (int)$data['qty'], $data['saleprice'], $data['purchaseprice'], $data['gross'], $data['discounttype'], $data['discount'], $data['discountvalue'], $data['tax'], $data['taxprice'], $data['price'], $data['medicine_id'], $data['purchase_id'], $data['datetime']));
		
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
			return false;
		}
	}

	public function deleteMedicinePurchase($id)
	{
		$this->database->query("DELETE FROM `medicine_purchase` WHERE `id` = ?", array((int)$id));
		return true;
	}

	public function deletePurchaseBatche($id)
	{
		$this->database->query("DELETE FROM `medicine_batch` WHERE `purchase_id` = ?", array((int)$id));
		return true;
	}

	public function deleteBatche($id)
	{
		$this->database->query("DELETE FROM `medicine_batch` WHERE `id` = ?", array((int)$id));
		return true;
	}



	public function getMCategory()
	{
		$query = $this->database->query("SELECT * FROM `medicine_category`");
		return $query->rows;
	}

	public function updateMCategory($data)
	{
		$this->database->query("UPDATE `medicine_category` SET `name` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), (int)$data['id']));
		return true;
	}

	public function createMCategory($data)
	{
		$this->database->query("INSERT INTO `medicine_category` (`name`, `created_date`) VALUES (?, ?)", array($this->database->escape($data['name']), $data['datetime']));
		return true;
	}

	public function deleteMCategory($id)
	{
		$query = $this->database->query("DELETE FROM `medicine_category` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}