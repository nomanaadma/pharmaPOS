<?php

/**
* Finance
*/
class Finance extends Model
{

	public function getPaymentMethod()
	{
		$query = $this->database->query("SELECT * FROM `payment_method`");
		return $query->rows;
	}

	public function updatePaymentMethod($data)
	{
		$query = $this->database->query("UPDATE `payment_method` SET `name` = ?, `status` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), (int)$data['status'], (int)$data['id']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function createPaymentMethod($data)
	{
		$query = $this->database->query("INSERT INTO `payment_method` (`name`, `status`, `created_date`) VALUES (?, ?, ?)", array($this->database->escape($data['name']), (int)$data['status'], $data['datetime']));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function deletePaymentMethod($id)
	{
		$query = $this->database->query("DELETE FROM `payment_method` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}

}