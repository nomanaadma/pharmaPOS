<?php

/**
* Commmons Model
*/
class Commons extends Model
{
	public function getCommonData($user_id)
	{
		$data['user'] = $this->getUserInfo($user_id);
		$data['info'] = $this->getSiteInfo();
		$data['theme'] = $this->getTheme();
		$data['page_search'] = $this->user_agent->hasPermission('customers');
		$data['new_bill'] = $this->user_agent->hasPermission('medicine/billing/add');
		$data['new_purchase'] = $this->user_agent->hasPermission('medicine/purchase/add');
		$data['new_transaction'] = $this->user_agent->hasPermission('account/transaction/add');
		$data['new_customer'] = $this->user_agent->hasPermission('customer/add');
		$data['live_stock'] = $this->user_agent->hasPermission('medicine/stock');
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['admin_menu'] = $this->createAdminMenu();
		return $data;
	}

	public function getUserInfo($user_id)
	{
		$data = $this->user_agent->getUserData();
		if (!empty($data['picture']) && file_exists(DIR.'public/uploads/'.$data['picture'])) {
			$data['picture'] = 'public/uploads/'.$data['picture'];
		} else {
			$data['picture'] = false;
		}
		return $data;
	}

	public function getTheme()
	{
		$data = $this->user_agent->getTheme();
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = 'public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = 'public/images/logo.png';
		}

		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo_icon'])) {
			$data['logo_icon'] = 'public/uploads/'.$data['logo_icon'];
		} else {
			$data['logo_icon'] = 'public/images/icon.png';
		}
		if (!empty($data['favicon']) && file_exists(DIR.'public/uploads/'.$data['favicon'])) {
			$data['favicon'] = 'public/uploads/'.$data['favicon'];
		} else {
			$data['favicon'] = 'public/images/logo.png';
		}

		return $data;
	}

	public function getSiteInfo()
	{
		$data = $this->user_agent->getInfo();
		if (!empty($data['logo']) && file_exists(DIR.'public/uploads/'.$data['logo'])) {
			$data['logo'] = 'public/uploads/'.$data['logo'];
		} else {
			$data['logo'] = 'public/images/logo.png';
		}
		if (!empty($data['favicon']) && file_exists(DIR.'public/uploads/'.$data['favicon'])) {
			$data['favicon'] = 'public/uploads/'.$data['favicon'];
		} else {
			$data['favicon'] = 'public/images/logo.png';
		}

		$data['picker_date_format'] = str_replace(['d', 'm', 'Y'], ['dd', 'mm', 'yy'], $data['date_format']);
		$data['picker_my_format'] = str_replace(['m', 'Y'], ['mm', 'yy'], $data['date_my_format']);
		$data['range_my_format'] = str_replace(['m', 'Y'], ['MM', 'YYYY'], $data['date_my_format']);
		$data['range_date_format'] = str_replace(['d', 'm', 'Y'], ['DD', 'MM', 'YYYY'], $data['date_format']);
		
		$data['url'] = URL;
		$data['dir_route'] = DIR_ROUTE;
		$data['url_route'] = URL.DIR_ROUTE;

		return $data;
	}

	public function getUserData($id)
	{
		$query = $this->database->query("SELECT `firstname`, `lastname` FROM `users` WHERE `user_id` = ?", array((int)$id));
		if ($query->num_rows > 0) {
			return $query->row['firstname'].' '.$query->row['lastname'];
		} else {
			return '';
		}
	}

	public function createAdminMenu()
	{
		$tree = array();
		$query = $this->database->query("SELECT * FROM `menu` WHERE `status` = ? ORDER BY `priority` DESC", array(1));
		$list = $query->rows;
		if (!empty($list)) {
			$active = $this->activeMenuList($this->url->get('route'));
			foreach ($list as $key => $value) {
				if ($value['link'] == '#' || $this->user_agent->hasPermission($value['link'])) {
					if ($value['parent'] == 0) {
						$tree[$value['id']] = $value;
						if ($value['active'] == $active) {
							$tree[$value['id']]['active_menu'] = 1;
						}
					} else {
						$tree[$value['parent']]['child'][$value['id']] = $value;
					}
				}
			}
		}

		$menu = '<ul>';
		if (!empty($tree)) {
			foreach ($tree as $key => $value) { 
				if (isset($value['child']) && isset($value['link']) && $value['link'] == '#') {
					if (isset($value['active_menu'])) { $active = ' active'; } else { $active = ''; }
					$menu .= '<li class="has-sub'.$active.'"><a><i class="'.$value['icon'].'"></i><span>'.$value['name'].'</span><i class="arrow"></i></a><ul class="sub-menu">';
					foreach ($value['child'] as $s_key => $s_value) {
						$menu .= '<li><a href="'.URL.DIR_ROUTE.$s_value['link'].'"><span>'.$s_value['name'].'</span></a></li>';
					}
					$menu .= '</ul></li>';
				} elseif (isset($value['link']) && $value['link'] != '#') {
					if (isset($value['active_menu'])) { $active = 'active'; } else { $active = ''; }
					$menu .= '<li class="'.$active.'"><a href="'.URL.DIR_ROUTE.$value['link'].'"><i class="'.$value['icon'].'"></i><span>'.$value['name'].'</span></a></li>';
				}
			}
		}
		$menu .= '</ul>';
		
		return $menu;
	}

	public function activeMenuList($key)
	{
		$data = array(
			'dashboard' => 'dashboard',
			'customers' => 'customers',
			'customer/view' => 'customers',
			'customer/add' => 'customers',
			'customer/edit' => 'customers',
			'users' => 'users',
			'user/add' => 'users',
			'user/edit' => 'users',
			'role' => 'users',
			'role/add' => 'users',
			'role/edit' => 'users',
			'medicines' => 'medicine',
			'reports' => 'reports',
			'medicine/view' => 'medicine',
			'medicine/add' => 'medicine',
			'medicine/edit' => 'medicine',
			'medicine/billing' => 'billing',
			'medicine/billing/add' => 'billing',
			'medicine/billing/edit' => 'billing',
			'medicine/billing/view' => 'billing',
			'medicine/purchase' => 'purchase',
			'medicine/purchase/add' => 'purchase',
			'medicine/purchase/edit' => 'purchase',
			'medicine/purchase/view' => 'purchase',
			'medicine/category' => 'settings',
			'medicine/stock' => 'stockadjustment',
			'info' => 'settings',
			'paymentmethod' => 'settings',
			'suppliers' => 'settings',
		);
		if (isset($data[$key])) {
			return $data[$key];
		} else {
			return false;
		}
	}
}