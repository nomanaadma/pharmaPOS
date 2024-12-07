<?php

/**
 * 
 */
class Upload extends Model
{

	public function getMedia()
	{
		$query = $this->database->query("SELECT `id`, `media` FROM `media`");
		if ($query->num_rows > 0) {
			return $query->rows;
		} else {
			return false;
		}
	}

	public function isMedia($data)
	{
		$query = $this->database->query("SELECT id FROM `media` WHERE id = ? AND media = ?", array((int)$data['id'], $data['name']));
		if ($query->row > 0) { return true; }
		else { return false; }
	}

	public function createMedia($data)
	{
		$query = $this->database->query("INSERT INTO `media` (`media`, `ext`, `created_date`) VALUES (?, ?, ?) ", array($this->database->escape($data['file']), $data['ext'], $data['datetime']));
		if ($query->row > 0) {
			return $this->database->last_id(); 
		} else {
			return false;
		}
	}

	public function deleteMedia($data)
	{
		$this->database->query("DELETE FROM `media` WHERE `media` = ? AND `id` = ?" , array($this->database->escape($data['name']), (int)$data['id']));
	}

}