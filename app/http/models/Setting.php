<?php

/**
* Info Model
*/

class Setting extends Model
{
	public function getSetting()
	{
		$query = $this->database->query("SELECT * FROM `setting` WHERE `name` IN ('siteinfo')");
		return $query->rows;
	}

	public function updateSetting($data)
	{
		$this->database->query("UPDATE `setting` SET `data` = ? WHERE `name` = ?", array($data['info'], 'siteinfo'));
	}

	public function getSuppliers()
	{
		$query = $this->database->query("SELECT * FROM `suppliers`");
		return $query->rows;
	}

	public function updateSupplier($data)
	{
		$this->database->query("UPDATE `suppliers` SET `name` = ?, `email` = ?, `phone` = ?, `address` = ? WHERE `id` = ? ", array($this->database->escape($data['name']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $data['address'], (int)$data['id']));
	}

	public function createSupplier($data)
	{
		$this->database->query("INSERT INTO `suppliers` (`name`, `email`, `phone`, `address`, `created_date`) VALUES (?, ?, ?, ?, ?)", array($this->database->escape($data['name']), $this->database->escape($data['email']), $this->database->escape($data['phone']), $data['address'], $data['datetime']));
	}

	public function deleteSupplier($id)
	{
		$query = $this->database->query("DELETE FROM `suppliers` WHERE `id` = ?", array((int)$id ));
		if ($query->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	}
	
}