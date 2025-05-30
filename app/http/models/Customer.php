<?php

/**
 * Customer.php
 */

class Customer extends Model
{

	public function getCustomers()
	{
		$query = $this->database->query("SELECT * FROM `customers` ORDER BY `created_date` DESC");		
		return $query->rows;
	}

	public function getCustomer($id)
	{
		$query = $this->database->query("SELECT * FROM `customers` WHERE `id` = ? ORDER BY `created_date` DESC", array((int)$id));
		return $query->row;
	}

	public function getBills($data)
	{
		$query = $this->database->query("SELECT * FROM `medicine_bill` WHERE customer_id = ? OR email = ? ORDER BY bill_date DESC LIMIT 20", array((int)$data['id'], $data['email']));
		return $query->rows;
	}

	public function checkCustomerEmail($mail, $id = NULL)
	{
		if (!empty($id)) {
			$query = $this->database->query("SELECT count(*) AS total FROM `customers` WHERE `email` = ? AND id != ?", array($this->database->escape($mail), (int)$id));
		} else {
			$query = $this->database->query("SELECT count(*) AS total FROM `customers` WHERE `email` = ? ", array($this->database->escape($mail)));
		}

		if ($query->num_rows > 0) {
			return $query->row['total'];
		} else{
			return false;
		}
	}

	public function getCustomerByMail($mail)
	{
		$query = $this->database->query("SELECT * FROM `customers` WHERE `email` = ? ", array($this->database->escape($mail)));
		return $query->row;
	}

	public function createCustomer($data)
	{
		$query = $this->database->query("INSERT INTO `customers` (`firstname`, `lastname`, `email`, `mobile`, `gender`, `status`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['mail']), $this->database->escape($data['mobile']), $this->database->escape($data['gender']), 1, $data['datetime']));
		if ($this->database->error()) {
			return false;
		} else {
			return $this->database->last_id();
		}
	}

	public function updateCustomer($data)
	{
		$query = $this->database->query("UPDATE `customers` SET `firstname` = ?, `lastname` = ?, `email` = ?, `mobile` = ?, `gender` = ?, `status` = ? WHERE `id` = ?" , array($this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['mail']), $this->database->escape($data['mobile']), $this->database->escape($data['gender']), $data['status'], (int)$data['id']));
	}

	public function getSearchedCustomer($data)
	{
		$query = $this->database->query("SELECT id, CONCAT(firstname, ' ', lastname) AS label, email, mobile FROM `customers` WHERE firstname like '%".$data."%' OR lastname like '%".$this->database->escape($data)."%' OR mobile like '%".$this->database->escape($data)."%' LIMIT 7");
		return $query->rows;
	}

	public function deleteCustomer($id)
	{
		$query = $this->database->query("DELETE FROM `customers` WHERE `id` = ?", array((int)$id));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
}