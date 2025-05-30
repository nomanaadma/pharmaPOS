<?php

/**
* Blog Controller
*/
class MedicineController extends Controller
{
	/**
	* Medicine index edit method
	* This method will be called on Medicine list view
	**/
	public function index()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Medicine data from DB using Medicine model 
		**/
		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMedicines();

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'Medicines';
		$data['page_view'] = $this->user_agent->hasPermission('medicine/view') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('medicine/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/delete') ? true : false;
		
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/delete';
		
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/medicine_list', $data));
	}
	/**
	* Medicine index view method
	* This method will be called on Medicine view
	**/
	public function indexView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicines');
		}
		/**
		* Call getBlog method from Blog model to get data from DB for single Medicine
		* If Medicine does not exist then redirect it to Medicine list view
		**/
		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMedicine($id);
		if (!$data['result']) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Medicine does not exist in database!');
			$this->url->redirect('medicines');
		}
		$data['category'] = $this->model_medicine->getMCategory();
		$data['livestock'] = $this->model_medicine->getMedicineLiveStock($id);
		$data['badstock'] = $this->model_medicine->getMedicineBadStock($id);

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'Medicine View';
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/delete') ? true : false;

		$data['page_edit_stock'] = $this->user_agent->hasPermission('medicine/stock') ? true : false;
		$data['page_purchase_view'] = $this->user_agent->hasPermission('medicine/purchase/view') ? true : false;
		$data['page_purchase_edit'] = $this->user_agent->hasPermission('medicine/purchase/edit') ? true : false;
		$data['page_delete_stock'] = $this->user_agent->hasPermission('medicine/stock/delete') ? true : false;

		$data['action'] = URL.DIR_ROUTE.'medicine/stock';
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/stock/delete';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/medicine_view', $data));	
	}
	/**
	* Medicine index add method
	* This method will be called on Medicine add
	**/
	public function indexAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$this->load->model('medicine');
		$data['category'] = $this->model_medicine->getMCategory();
		/* Set page title */
		$data['page_title'] = 'Add Medicine';
		/* Set empty data to array */
		$data['result'] =  NULL;
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'medicine/add';
		/*Render Medicine add view*/
		$this->response->setOutput($this->load->view('medicine/medicine_form', $data));
	}
	/**
	* Medicine index edit method
	* This method will be called on Medicine edit view
	**/
	public function indexEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicines');
		}
		/**
		* Call getBlog method from Blog model to get data from DB for single Medicine
		* If Medicine does not exist then redirect it to Medicine list view
		**/
		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMedicine($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Medicine does not exist in database!');
			$this->url->redirect('medicines');
		}
		$data['category'] = $this->model_medicine->getMCategory();

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/* Set Edit Medicine page title */
		$data['page_title'] = 'Edit Medicine';
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'medicine/edit';
		/*Render Medicine edit view*/
		$this->response->setOutput($this->load->view('medicine/medicine_form', $data));
	}
	/**
	* Medicine index action method
	* This method will be called on Medicine submit/save view
	**/
	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('medicines');
		}
		$data = $this->url->post;
		$this->load->controller('common');
		if ($validate_field = $this->validateField($data['medicine'])) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['medicine']['id'])) {
				$this->url->redirect('medicine/edit&id='.$data['medicine']['id']);
			} else {
				$this->url->redirect('medicine/add');
			}
		}
		$data['medicine']['datetime'] =  date('Y-m-d H:i:s');
		$data['medicine']['user_id'] = $this->session->data['user_id'];
		
		$this->load->model('medicine');
		if (!empty($data['medicine']['id'])) {
			$this->model_medicine->updateMedicine($data['medicine']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine updated successfully.');
			
		} else {
			$data['medicine']['id'] = $this->model_medicine->createMedicine($data['medicine']);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine created successfully.');
		}
		$this->url->redirect('medicine/edit&id='.$data['medicine']['id']);
	}
	/**
	* Medicine index delete method
	* This method will be called on Medicine delete action
	**/
	public function indexDelete()
	{
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('medicines');
		}
		
		$this->load->model('medicine');
		$result = $this->model_medicine->deleteMedicine($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine deleted successfully.');
		$this->url->redirect('medicines');
	}

	public function validateField($data)
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_common->validateText($data['name'])) {
			$error_flag = true;
			$error['name'] = 'Name';
		}
		if ($this->controller_common->validateNumber($data['category'])) {
			$error_flag = true;
			$error['category'] = 'Category';
		}
		if ($this->controller_common->validateText($data['company'])) {
			$error_flag = true;
			$error['company'] = 'Company';
		}
		if ($this->controller_common->validateText($data['generic'])) {
			$error_flag = true;
			$error['generic'] = 'Generic';
		}
		if ($this->controller_common->validateNumber($data['unit'])) {
			$error_flag = true;
			$error['unit'] = 'Unit';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function getMedicine()
	{
		$data = $this->url->get;
		$this->load->model('medicine');
		$result = $this->model_medicine->getSearchedMedicine($data['term']);
		echo json_encode($result);
		exit();
	}

	public function stockList()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Medicine data from DB using Medicine model 
		**/
		$url_get = $this->url->get;
		$this->load->model('medicine');
		$date = new DateTime('now');
		if (!empty($url_get['type']) && $url_get['type'] == 'live') {
			$data['page_title'] = 'Live Stock';
			$data['type'] = 'live';
			$data['result'] = $this->model_medicine->getLiveStocks();
		} elseif (!empty($url_get['type']) && $url_get['type'] == 'expired') {
			$data['page_title'] = 'Expired Stock';
			$data['type'] = 'expired';
			$data['result'] = $this->model_medicine->getExpiredStocks($date);
		} elseif (!empty($url_get['type']) && $url_get['type'] == 'willexpirein1') {
			$data['page_title'] = 'Expire in 1 Month';
			$date->modify('+1 month');
			$date = $date->format('Y-m');
			$data['result'] = $this->model_medicine->getWillExpireStocks($date);
		}  elseif (!empty($url_get['type']) && $url_get['type'] == 'willexpirein2') {
			$data['page_title'] = 'Expire in 2 Month';
			$date->modify('+2 month');
			$date = $date->format('Y-m');
			$data['result'] = $this->model_medicine->getWillExpireStocks($date);
		}  elseif (!empty($url_get['type']) && $url_get['type'] == 'willexpirein3') {
			$data['page_title'] = 'Expire in 3 Month';
			$date->modify('+3 month');
			$date = $date->format('Y-m');
			$data['result'] = $this->model_medicine->getWillExpireStocks($date);
		}  elseif (!empty($url_get['type']) && $url_get['type'] == 'willexpirein6') {
			$data['page_title'] = 'Expire in 6 Month';
			$date->modify('+6 month');
			$date = $date->format('Y-m');
			$data['result'] = $this->model_medicine->getWillExpireStocks($date);
		} else {
			$data['type'] = 'live';
			$data['page_title'] = 'Live Stock';
			$data['result'] = $this->model_medicine->getLiveStocks();
		}

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/stock') ? true : false;
		$data['page_purchase_view'] = $this->user_agent->hasPermission('medicine/purchase/view') ? true : false;
		$data['page_purchase_edit'] = $this->user_agent->hasPermission('medicine/purchase/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/stock/delete') ? true : false;

		$data['action'] = URL.DIR_ROUTE.'medicine/stock';
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/stock/delete';
		$data['delete_msg'] = 'Stock will be removed from Inventory.';

		
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/stock_list', $data));
	}

	public function stockUpdate()
	{
		$data = $this->url->post;

		$data['id'] = (int)$data['id'];
		$data['medicine_id'] = (int)$data['medicine_id'];
		
		if (empty($data['id']) || empty($data['medicine_id']) || !isset($data['available'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Stock does not exist in database!');
			$this->url->abs_redirect($_SERVER['HTTP_REFERER']);
		}

		$this->load->model('medicine');
		$this->model_medicine->updateStock($data);
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Stock updated successfully!');
		$this->url->abs_redirect($_SERVER['HTTP_REFERER']);
	}

	public function stockDelete()
	{
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('medicines');
		}
		$this->load->model('medicine');
		$result = $this->model_medicine->deleteStock($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine Stock deleted successfully.');
		$this->url->abs_redirect($_SERVER['HTTP_REFERER']);
	}

	public function medicineFilter() {

		$filterRoute = 'medicine/';
		$modelName = 'medicine';
		$modelLoad = "model_" . $modelName;

		$page_view = $this->user_agent->hasPermission($filterRoute.'view') ? true : false;
		$page_edit = $this->user_agent->hasPermission($filterRoute.'edit') ? true : false;
		$page_delete = $this->user_agent->hasPermission($filterRoute.'delete') ? true : false;

		$this->load->model($modelName);
		$filteredData = $this->{$modelLoad}->filterMedicine($_POST);

		$response = [
			'draw' => $this->url->post('draw'),
			'recordsFiltered' => $filteredData['recordsFiltered'],
			'recordsTotal' => $filteredData['total'],
			'data' => []
		];
		
		foreach ($filteredData['data'] as $key => $data) {

			$id = $data['id'];

			$action = '';

			if($page_view || $page_edit) {
				$action .= '<div class="dropdown d-inline-block">
											<a class="text-primary edit dropdown-toggle" data-toggle="dropdown"><i class="las la-ellipsis-h"></i></a>
											<ul class="dropdown-menu dropdown-menu-right">';
				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'view&id='.$id.'"><i class="las la-laptop pr-2"></i>View</a></li>';
				}

				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'edit&id='.$id.'"><i class="las la-edit pr-2"></i>Edit</a></li>';
				}

				$action .= '</ul></div>';

			}

			if($page_delete) {
				$action .= '<a class="table-delete text-danger delete" data-toggle="tooltip" title="Delete"><i class="las la-trash-alt"></i><input type="hidden" value="'.$id.'"></a>';
			}

			$response['data'][] = [
				"id" => $id,
				"name" => $data['name'],
				"generic" => $data['generic'],
				"company" => $data['company'],
				"medicine_group" => $data['medicine_group'],
				"unit" => $data['unit'],
				"unitpacking" => $data['unitpacking'],
				"category" => $data['category'],
				"storebox" => $data['storebox'],
				"reorderlevel" => $data['reorderlevel'],
				"livestock" => $data['livestock'],
				"status" => $data['status'],
				"action" => trim($action)
			];
		}
		
		// Convert the data array to JSON
		$jsonData = json_encode($response, JSON_PRETTY_PRINT);

		echo $jsonData;
	}

	public function billingFilter() {

		$filterRoute = 'medicine/billing/';
		$modelName = 'medicine';
		$modelLoad = "model_" . $modelName;

		$page_view = $this->user_agent->hasPermission($filterRoute.'view') ? true : false;
		$page_edit = $this->user_agent->hasPermission($filterRoute.'edit') ? true : false;
		$page_delete = $this->user_agent->hasPermission($filterRoute.'delete') ? true : false;

		$this->load->model($modelName);
		$filteredData = $this->{$modelLoad}->filterBilling($_POST);

		$response = [
			'draw' => $this->url->post('draw'),
			'recordsFiltered' => $filteredData['recordsFiltered'],
			'recordsTotal' => $filteredData['total'],
			'data' => []
		];
		
		foreach ($filteredData['data'] as $key => $data) {

			$id = $data['id'];

			$action = '';

			if($page_view || $page_edit) {
				$action .= '<div class="dropdown d-inline-block">
											<a class="text-primary edit dropdown-toggle" data-toggle="dropdown"><i class="las la-ellipsis-h"></i></a>
											<ul class="dropdown-menu dropdown-menu-right">';
				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'view&id='.$id.'"><i class="las la-laptop pr-2"></i>View</a></li>';
				}

				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'edit&id='.$id.'"><i class="las la-edit pr-2"></i>Edit</a></li>';
				}

				$action .= '</ul></div>';

			}

			if($page_delete) {
				$action .= '<a class="table-delete text-danger delete" data-toggle="tooltip" title="Delete"><i class="las la-trash-alt"></i><input type="hidden" value="'.$id.'"></a>';
			}

			$response['data'][] = [
				"id" => $id,
				"name" => $data['name'],
				"subtotal" => 'Rs '.$data['subtotal'],
				"tax" => 'Rs '.$data['tax'],
				"discount_value" => 'Rs '.$data['discount_value'],
				"amount" => 'Rs '.$data['amount'],
				"bill_date" => $data['bill_date'],
				"action" => trim($action)
			];
		}
		
		// Convert the data array to JSON
		$jsonData = json_encode($response, JSON_PRETTY_PRINT);

		echo $jsonData;
	}


	public function purchaseFilter() {

		$filterRoute = 'medicine/purchase/';
		$modelName = 'medicine';
		$modelLoad = "model_" . $modelName;

		$page_view = $this->user_agent->hasPermission($filterRoute.'view') ? true : false;
		$page_edit = $this->user_agent->hasPermission($filterRoute.'edit') ? true : false;
		$page_delete = $this->user_agent->hasPermission($filterRoute.'delete') ? true : false;

		$this->load->model($modelName);
		$filteredData = $this->{$modelLoad}->filterPurchase($_POST);

		$response = [
			'draw' => $this->url->post('draw'),
			'recordsFiltered' => $filteredData['recordsFiltered'],
			'recordsTotal' => $filteredData['total'],
			'data' => []
		];
		
		foreach ($filteredData['data'] as $key => $data) {

			$id = $data['id'];

			$action = '';

			if($page_view || $page_edit) {
				$action .= '<div class="dropdown d-inline-block">
											<a class="text-primary edit dropdown-toggle" data-toggle="dropdown"><i class="las la-ellipsis-h"></i></a>
											<ul class="dropdown-menu dropdown-menu-right">';
				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'view&id='.$id.'"><i class="las la-laptop pr-2"></i>View</a></li>';
				}

				if($page_view) {
					$action .= '<li><a href="index.php?route='.$filterRoute.'edit&id='.$id.'"><i class="las la-edit pr-2"></i>Edit</a></li>';
				}

				$action .= '</ul></div>';

			}

			if($page_delete) {
				$action .= '<a class="table-delete text-danger delete" data-toggle="tooltip" title="Delete"><i class="las la-trash-alt"></i><input type="hidden" value="'.$id.'"></a>';
			}

			$response['data'][] = [
				"id" => $id,
				"name" => $data['name'],
				"total" => 'Rs '.$data['total'],
				"tax" => 'Rs '.$data['tax'],
				"discount_value" => 'Rs '.$data['discount_value'],
				"amount" => 'Rs '.$data['amount'],
				"date" => $data['date'],
				"action" => trim($action)
			];
		}
		
		// Convert the data array to JSON
		$jsonData = json_encode($response, JSON_PRETTY_PRINT);

		echo $jsonData;
	}

	/**
	* Medicine Purchase index List method
	* This method will be called on Medicine Purchase list
	**/
	public function medicineBilling()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->load->controller('common');

		/**
		* Get all Medicine data from DB using Medicine model 
		**/
		$this->load->model('medicine');
		
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'POS/Bill';
		$data['page_view'] = $this->user_agent->hasPermission('medicine/billing/view') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('medicine/billing/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/billing/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/billing/delete') ? true : false;

		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/billing/delete';
		$data['delete_msg'] = 'Inventory will be updated and those Stock will be added in Inventory.';
		
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/billing_list', $data));
	}
	/**
	* Medicine Billing index List method
	* This method will be called on Medicine Billing list
	**/
	public function medicineBillingView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicine/billing');
		}

		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMedicineBill($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Bill does not exist in database!');
			$this->url->redirect('medicine/billing');
		}
		
		$bill_items = $this->model_medicine->getBillItems($id);

		$data['result']['items'] = $bill_items;
		
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/* Set page title */
		$data['page_title'] = 'Bill View';
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/billing/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/billing/delete') ? true : false;
		//$data['action'] = URL.DIR_ROUTE.'medicine/billing/edit';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/billing_view', $data));
	}
	
	public function medicineBillingAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$this->load->model('medicine');
		/* Set empty data to array */
		$data['result'] =  NULL;
		$data['methods'] = $this->model_medicine->getPaymentMethods();

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'New Bill';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'medicine/billing/add';
		/*Render Medicine add view*/
		$this->response->setOutput($this->load->view('medicine/billing_form', $data));
	}
	/**
	* Medicine Billing index Add method
	* This method will be called on Medicine Billing Add
	**/
	public function medicineBillingBatch()
	{
		$data = $this->url->post;
		$data['monthyear'] = date('Y-m');

		$this->load->model('medicine');
		$batches = $this->model_medicine->getSearchedBatch($data);
		
		if (empty($batches)) {
			echo json_encode(array('error' => true, 'message' => 'Medicine Stock not found.'));
			exit();
		}

		$result = '<option value="">Select</option>';;
		foreach ($batches as $key => $value) {
			$result .= '<option value="'.$value['id'].'">'.$value['batch'].'</option>';
		}

		echo json_encode(array('error' => false, 'result' => $result));
		exit();	
	}
	/**
	* Medicine Batch Data
	* This method will be called on Medicine Batch 
	**/
	public function medicineBillingBatchData()
	{
		$data = $this->url->post;
		$this->load->model('medicine');
		
		$result = $this->model_medicine->getSearchedBatchWithMedicine($data);
		
		if (empty($result)) { 
			echo json_encode(array('error' => true, 'message' => 'Medicine Stock not found.'));
			exit(); 
		}

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();
		$result['expiry'] = DateTime::createFromFormat('Y-m', $result['expiry'])->format($data['info']['date_my_format']);
		$result['available_quantity'] = $result['qty'] - $result['sold'];
		$result['tax'] = '';
		echo json_encode(array('error' => false, 'result' => $result));
		exit();
	}
	/**
	* Medicine Batch Edit
	* This method will be called on Medicine Billing Edit
	**/
	public function medicineBillingEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicine/billing');
		}

		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMedicineBill($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Bill does not exist in database!');
			$this->url->redirect('medicine/billing');
		}

		$bill_items = $this->model_medicine->getBillItems($id);

		$data['result']['items'] = $bill_items;
		
		foreach ($data['result']['items'] as $key => $value) {
			$value['monthyear'] = date('Y-m');
			$value['id'] = $value['medicine_id'];
			$batches = $this->model_medicine->getSearchedBatch($value);
			$data['result']['items'][$key]['batches'] = $batches;
		}

		$data['methods'] = $this->model_medicine->getPaymentMethods();

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'Edit Bill';
		$data['action'] = URL.DIR_ROUTE.'medicine/billing/edit';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/billing_form', $data));
	}
	/**
	* Medicine Billing index Action method
	* This method will be called on Medicine Billing Action
	**/
	public function medicineBillingAction()
	{	
		$data = $this->url->post;		
		$this->load->controller('common');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo($this->session->data['user_id']);
		if ($validate_field = $this->validateBillingField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['billing']['id'])) {
				$this->url->redirect('medicine/billing/edit&id='.$data['billing']['id']);
			} else {
				$this->url->redirect('medicine/billing/add');	
			}
		}

		$data['billing']['bill_date'] = DateTime::createFromFormat($data['info']['date_format'], $data['billing']['bill_date'])->format('Y-m-d');
		$data['billing']['datetime'] =  date('Y-m-d H:i:s');

		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		if(empty($data['billing']['customer_id'])) {

			$this->load->model('customer');

			$nameParts = explode(' ', $data['billing']['name']);
			$lastName = array_pop($nameParts);
			$firstName = implode(' ', $nameParts);

			if($firstName == '') {
				$firstName = $lastName;
			}

			$customer_data = [
				'firstname' => $firstName,
				'lastname' => $lastName,
				'mail' => $data['billing']['email'],
				'mobile' => $data['billing']['mobile'],
				'datetime' => date('Y-m-d H:i:s'),
				'gender' => null,
			];

			$existingCustomer = $this->model_customer->getCustomerByMail($customer_data['mail']);

			if( isset( $existingCustomer['id'] ) ) {
				$data['billing']['customer_id'] = $existingCustomer['id'];	
			} else {
				$data['billing']['customer_id'] = $this->model_customer->createCustomer($customer_data);
			}
		}

		$this->load->model('medicine');
		if (!empty($data['billing']['id'])) {
			if (!empty($data['billing']['items'])) {
				
				$old = $this->model_medicine->getBillItems($data['billing']['id']);

				$oldid = $this->array2Dto1D($old, 'batch');
				$newid = $this->array2Dto1D($data['billing']['items'], 'batch');

				foreach ($old as $key => $value) {
					if (!in_array($value['batch'], $newid)) {
						$this->model_medicine->updateMedicineBatchSoldOnDelete($value);
					}
				}

				foreach ($data['billing']['items'] as $key => $value) {
					$value['expiry'] = DateTime::createFromFormat($data['info']['date_my_format'], $value['expiry'])->format('Y-m');
					$data['billing']['items'][$key]['expiry'] = $value['expiry'];

					if (!in_array($value['batch'], $oldid)) {
						$this->model_medicine->updateMedicineBatchSold($value);
					}

					foreach ($old as $k => $v) {
						if ($v['batch'] == $value['batch'] && (float)$v['qty'] > (float)$value['qty']) {
							$value['qty'] = $v['qty'] - $value['qty'];
							$this->model_medicine->updateMedicineBatchSoldOnDelete($value);
						} elseif ($v['batch'] == $value['batch'] && (float)$v['qty'] < (float)$value['qty']) {
							$value['qty'] = $value['qty'] - $v['qty'];
							$this->model_medicine->updateMedicineBatchSold($value);
						}
					}
				}
				
				$this->model_medicine->updateMedicineBill($data['billing']);
			}
		} else {
			if (!empty($data['billing']['items'])) {
				foreach ($data['billing']['items'] as $key => $value) {
					$value['expiry'] = DateTime::createFromFormat($data['info']['date_my_format'], $value['expiry'])->format('Y-m');
					$data['billing']['items'][$key]['expiry'] = $value['expiry'];
					$this->model_medicine->updateMedicineBatchSold($value);
				}

				$data['billing']['id'] = $this->model_medicine->createMedicineBill($data['billing']);
			}
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Bill created successfully.');
		}
		$this->url->redirect('medicine/billing/view&id='.$data['billing']['id']);
	}
	/**
	* Medicine Billing index Delete method
	* This method will be called on Medicine Billing Delete
	**/
	public function medicineBillingDelete()
	{
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('medicine/billing');
		}

		$this->load->model('medicine');
	
		$items = $this->model_medicine->getBillItems($this->url->post('id'));
		if( count($items) ) {
			foreach ($items as $key => $value) {
				$this->model_medicine->updateMedicineBatchSoldOnDelete($value);
			}
		}

		$this->model_medicine->deleteMedicineBill($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Bill deleted successfully.');
		$this->url->redirect('medicine/billing');

	}
	/**
	* Validate Medicine Field
	* This method will be called from validateBillingField
	* @data
	**/
	public function validateBillingField($data)
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_common->validateText($data['billing']['name'])) {
			$error_flag = true;
			$error['name'] = 'Name';
		}

		if ($this->controller_common->validateDate( $data['billing']['bill_date'], $data['info']['date_format'] )) {
			$error_flag = true;
			$error['bill_date'] = 'Date';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	/**
	* Medicine Purchase index List method
	* This method will be called on Medicine Purchase list
	**/
	public function medicinePurchaseList()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$this->load->controller('common');
		/**
		* Get all Medicine data from DB using Medicine model 
		**/
		$this->load->model('medicine');

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		/* Set page title */
		$data['page_title'] = 'Purchase';
		$data['page_view'] = $this->user_agent->hasPermission('medicine/purchase') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('medicine/purchase/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/purchase/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/purchase/delete') ? true : false;
		
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/purchase/delete';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/purchase_list', $data));
	}
	/**
	* Medicine Purchase index List method
	* This method will be called on Medicine Purchase List
	**/
	public function medicinePurchaseView()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicine/purchase');
		}

		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getPurchaseView($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Purchase does not exist in database!');
			$this->url->redirect('medicine/purchase');
		}
		$data['batches'] = $this->model_medicine->getBatches($id);

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/* Set page title */
		$data['page_title'] = 'Purchase View';

		$data['page_view'] = $this->user_agent->hasPermission('medicine/purchase') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('medicine/purchase/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/purchase/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/purchase/delete') ? true : false;
		$data['action'] = URL.DIR_ROUTE.'medicine/purchase/edit';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/purchase_view', $data));
	}
	
	public function medicinePurchaseAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Medicine data from DB using Medicine model 
		**/
		$this->load->model('medicine');
		$data['result'] = NULL;
		$data['suppliers'] = $this->model_medicine->getSuppliers();

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		/* Set page title */
		$data['page_title'] = 'Purchase Add';
		$data['action'] = URL.DIR_ROUTE.'medicine/purchase/add';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/purchase_form', $data));
	}
	/**
	* Medicine Purchase index Add method
	* This method will be called on Medicine Purchase Add
	**/
	public function medicinePurchaseEdit()
	{
		/**
		* Check if id exist in url if not exist then redirect to Medicine list view 
		**/
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) {
			$this->url->redirect('medicine/purchase');
		}

		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getPurchase($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Purchase does not exist in database!');
			$this->url->redirect('medicine/purchase');
		}
		$data['batches'] = $this->model_medicine->getBatches($id);
		$data['suppliers'] = $this->model_medicine->getSuppliers();

		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/* Set page title */
		$data['page_title'] = 'Purchase Edit';
		$data['action'] = URL.DIR_ROUTE.'medicine/purchase/edit';
		/*Render Medicine view*/
		$this->response->setOutput($this->load->view('medicine/purchase_form', $data));
	}
	/**
	* Medicine Purchase index Edit method
	* This method will be called on Medicine Purchase Edit
	**/
	public function medicinePurchaseAction()
	{
		$data = $this->url->post;

		$this->load->controller('common');
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		if ($validate_field = $this->validatePurchaseField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['purchase']['id'])) {
				$this->url->redirect('medicine/purchase/edit&id='.$data['purchase']['id']);
			} else {
				$this->url->redirect('medicine/purchase/add');	
			}
		}

		$data['purchase']['date'] = DateTime::createFromFormat($data['info']['date_format'], $data['purchase']['date'])->format('Y-m-d');
		$data['purchase']['datetime'] =  date('Y-m-d H:i:s');

		$this->load->model('medicine');
		if (!empty($data['purchase']['id'])) {
			$batches = $this->model_medicine->getBatches($data['purchase']['id']);
			$this->model_medicine->updateMedicinePurchase($data['purchase']);
			
			$newitems = $this->array2Dto1D($data['purchase']['items']);
			foreach ($batches as $key => $value) {
				if (!in_array($value['id'], $newitems)) {
					$this->model_medicine->deleteBatche($value['id']);
				}
			}
			if (!empty($data['purchase']['items'])) {
				foreach ($data['purchase']['items'] as $key => $value) {
					$batch = array();
					$batch = $value;
					$batch['expiry'] = DateTime::createFromFormat($data['info']['date_my_format'], $value['expiry'])->format('Y-m');
					
					$batch['purchase_id'] = $data['purchase']['id'];
					$batch['datetime'] =  date('Y-m-d H:i:s');

					if (empty($value['id'])) {
						$this->model_medicine->createMedicinebatch($batch);
					} else {
						$this->model_medicine->updateMedicinebatch($batch);
					}
				}
			}
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine Purchase updated successfully.');
		} else {
			if (!empty($data['purchase']['items'])) {
				$data['purchase']['items'] = json_encode($data['purchase']['items']);
				$data['purchase']['id'] = $this->model_medicine->createMedicinePurchase($data['purchase']);
				$data['purchase']['items'] = json_decode($data['purchase']['items'], true);

				foreach ($data['purchase']['items'] as $key => $value) {
					$batch = array();
					$batch = $value;
					
					$batch['expiry'] = DateTime::createFromFormat($data['info']['date_my_format'], $value['expiry'])->format('Y-m');
					$batch['purchase_id'] = $data['purchase']['id'];
					$batch['datetime'] =  date('Y-m-d H:i:s');
					$this->model_medicine->createMedicinebatch($batch);
				}
			}
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine Purchase created successfully.');
		}
		
		$this->url->redirect('medicine/purchase/view&id='.$data['purchase']['id']);
	}
	/**
	* Medicine Purchase index Action method
	* This method will be called on Medicine Purchase Action
	**/
	public function medicinePurchaseDelete()
	{
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('medicine/purchase');
		}

		$this->load->model('medicine');
		$this->model_medicine->deletePurchaseBatche($this->url->post('id'));
		$this->model_medicine->deleteMedicinePurchase($this->url->post('id'));

		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Medicine Purchase deleted successfully.');
		$this->url->redirect('medicine/purchase');
	}
	/**
	* Medicine Purchase index Delete method
	* This method will be called on Medicine Purchase Delete
	**/
	public function validatePurchaseField($data)
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_common->validateNumber($data['purchase']['supplier'])) {
			$error_flag = true;
			$error['supplier'] = 'Supplier';
		}
		if ($this->controller_common->validateDate( $data['purchase']['date'], $data['info']['date_format'] )) {
			$error_flag = true;
			$error['date'] = 'Date';
		}
		if ($this->controller_common->validateText($data['purchase']['amount'])) {
			$error_flag = true;
			$error['amount'] = 'Amount';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}

	public function array2Dto1D($data, $keyvalue = 'id')
	{
		$result = array();
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				array_push($result, $value[$keyvalue]);
			}
		}
		return $result;
	}
	
	/**
	* Medicine Category index edit method
	* This method will be called on Medicine Category list
	**/
	public function mCategory()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all info data from DB using info model's method
		**/
		$this->load->model('medicine');
		$data['result'] = $this->model_medicine->getMCategory();
		
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['page_title'] = 'Medicine Category';
		$data['page_add'] = $this->user_agent->hasPermission('medicine/category/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('medicine/category/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('medicine/category/delete') ? true : false;
		/*Set action method for form submit call*/
		$data['action'] = URL.DIR_ROUTE.'medicine/category/add';
		$data['action_delete'] = URL.DIR_ROUTE.'medicine/category/delete';
		/*Render Info view*/
		$this->response->setOutput($this->load->view('medicine/medicine_category_list', $data));
	}

	public function mCategoryAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('medicine/category');
		}
		
		$data = $this->url->post;

		$this->load->controller('common');
		if ($validate_field = $this->validateMCategory($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('medicine/category');
		}
		
		$data = $this->url->post;
		$data['datetime'] =  date('Y-m-d H:i:s');
		$data['user_id'] =  $this->session->data['user_id'];
		
		$this->load->model('medicine');
		if (!empty($data['id'])) {
			$result = $this->model_medicine->updateMCategory($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Category updated successfully.');
		} else {
			$result = $this->model_medicine->createMCategory($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Category created successfully.');
		}

		$this->url->redirect('medicine/category');
	}

	public function mCategoryDelete()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('medicine/category');
		}
		$this->load->model('medicine');
		$this->model_medicine->deleteMCategory($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Category deleted successfully.');
		$this->url->redirect('medicine/category');
	}

	public function validateMCategory($data)
	{
		$error = [];
		$error_flag = false;
		if (!empty($data['name']) && $this->controller_common->validateText($data['name'])) {
			$error_flag = true;
			$error['name'] = 'Name';
		}
		
		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}