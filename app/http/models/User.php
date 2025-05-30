<?php
/**
* User Model
*/
class User extends Model
{
	public function allUsers()
	{
		$query = $this->database->query("SELECT u.*, u.user_id AS id, ur.name AS role FROM `users` As u LEFT JOIN `user_role` As ur ON u.user_role = ur.id ORDER BY `created_date` DESC");
		return $query->rows;
	}

	public function getUser($id)
	{
		$query = $this->database->query("SELECT * FROM `users` WHERE user_id = ? LIMIT 1", array($this->database->escape($id)));
		if ($query->num_rows > 0) {
			return $query->row;
		} else {
			return false;
		}
	}

	public function getDepartments()
	{
		$query = $this->database->query("SELECT `id` , `name` FROM `departments`");
		return $query->rows;
	}

	public function getUserRoles()
	{
		$query = $this->database->query("SELECT `id` , `name` FROM `user_role`");
		return $query->rows;
	}

	public function checkUserName($username, $id)
	{
		if (!empty($id)) {
			$query = $this->database->query("SELECT count(*) AS total FROM `users` WHERE `user_name` = ? AND `user_id` != ?", array($this->database->escape($username), (int)$id));
		} else {
			$query = $this->database->query("SELECT count(*) AS total FROM `users` WHERE `user_name` = ?", array($this->database->escape($username)));
		}
		if ( $query->num_rows > 0 ) {
			return $query->row['total'];
		} else {
			return false;
		}
	}

	public function checkUserEmail($email, $id)
	{
		if (!empty($id)) {
			$query = $this->database->query("SELECT count(*) AS total FROM `users` WHERE `email` = ? AND `user_id` != ?", array($this->database->escape($email), (int)$id));
		} else {
			$query = $this->database->query("SELECT count(*) AS total FROM `users` WHERE `email` = ?", array($this->database->escape($email)));
		}
		if ($query->num_rows > 0) {
			return $query->row['total'];
		} else{
			return false;
		}
	}

	public function updateUser($data)
	{
		$query = $this->database->query("UPDATE `users` SET `user_role` = ?, `user_name` = ?, `firstname` = ?, `lastname` = ?, `email` = ?, `mobile` = ?, `gender` = ?, `status` = ? WHERE `user_id` = ? " , array((int)$data['user_role'], $this->database->escape($data['user_name']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['mail']), $this->database->escape($data['mobile']), $this->database->escape($data['gender']), (int)$data['status'], (int)$data['user_id']));

		if ($query->num_rows > 0) {
			return true;
		} else { 
			return false;
		}
	}

	public function createUser($data)
	{
		$query = $this->database->query("INSERT INTO `users` (`user_role`, `user_name`, `firstname`, `lastname`, `email`, `mobile`,`gender`, `password`, `temp_hash`, `created_date`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($this->database->escape($data['user_role']), $this->database->escape($data['user_name']), $this->database->escape($data['firstname']), $this->database->escape($data['lastname']), $this->database->escape($data['mail']), $this->database->escape($data['mobile']), $this->database->escape($data['gender']), $data['password'], $data['hash'], $data['datetime']));
		if ($query->num_rows > 0) {
			return $this->database->last_id();
		} else {
		    return false;	
		}
	}

	public function deleteUser($id)
	{
		$query = $this->database->query("DELETE FROM `users` WHERE `user_id` = ?", array((int)$id));
		if ($query->num_rows > 0) { 
			return true;
		} else {
			return false;
		}
	}
	
	public function userRole()
	{
		$query = $this->database->query("SELECT `id`, `name` FROM `user_role`");
		return $query->rows;
	}

	public function getRoles()
	{
		$query = $this->database->query("SELECT `id`, `name`, `description`, `created_date` FROM `user_role`");
		return $query->rows;
	}

	public function getRole($id)
	{
		$query = $this->database->query("SELECT * FROM `user_role` WHERE `id` = ?", array((int)$id));
		return $query->row;
	}

	public function addUserRole($data)
	{
		$query = $this->database->query("INSERT INTO `user_role` (`name`, `description` ,`permission`) VALUES (?, ?, ?)", 
			array($this->database->escape($data['name']), $data['description'], $data['role']));
		return $this->database->last_id();
	}

	public function updateUserRole($data)
	{
		$query = $this->database->query("UPDATE `user_role` SET `name` = ?, `description` = ?, `permission` = ? WHERE `id` = ?", array($this->database->escape($data['name']), $data['description'], $data['role'], (int)$data['id']));
		return true;
	}

	public function deleteRole($id)
	{
		$query = $this->database->query("DELETE FROM `user_role` WHERE `id` = ?", array((int)$id));
		if ($query->num_rows > 0) { 
			return true;
		} else {
			return false;
		}
	}
}