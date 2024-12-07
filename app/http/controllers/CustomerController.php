<?php

/**
 * CustomerController.php
 */
class CustomerController extends Controller
{
	/**
	* Customer index method
	* This method will be called on @PatientList view
	**/
	public function index()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		/**
		* Get all Customer data from DB using User model 
		**/
		$this->load->controller('common');

		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		/* Set page title */
		$data['page_title'] = 'Customers';
		$data['page_view'] = $this->user_agent->hasPermission('customer/view') ? true : false;
		$data['page_add'] = $this->user_agent->hasPermission('customer/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('customer/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('customer/delete') ? true : false;

		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action_delete'] = URL.DIR_ROUTE.'customer/delete';

		/*Render User list view*/
		$this->response->setOutput($this->load->view('customer/customer_list', $data));
	}

	public function filter() {

		$filterRoute = 'customer/';
		$modelName = 'customer';
		$modelLoad = "model_" . $modelName;

		$page_view = $this->user_agent->hasPermission($filterRoute.'view') ? true : false;
		$page_edit = $this->user_agent->hasPermission($filterRoute.'edit') ? true : false;
		$page_delete = $this->user_agent->hasPermission($filterRoute.'delete') ? true : false;

		$this->load->model($modelName);
		$filteredData = $this->{$modelLoad}->filterData($_POST);

		$response = [
			'draw' => $this->url->post('draw'),
			'recordsFiltered' => $filteredData['recordsFiltered'],
			'recordsTotal' => $filteredData['total'],
			'data' => []
		];
		
		foreach ($filteredData['data'] as $key => $data) {

			
			$id = $data['id'];

			$status = '';

			if($data['status'] == '1') {
				$status = '<span class="label label-success">Active</span>';
			} else if ($data['status'] == '0')  {
				$status = '<span class="label label-danger">InActive</span>';
			}

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
				"name" => $data['firstname'] . ' ' . $data['lastname'], 
				"gender" => $data['gender'], 
				"email" => $data['email'], 
				"mobile" => $data['mobile'], 
				"status" => trim($status), 
				"created_date" => $data['created_date'],
				"action" => trim($action)
			];
		}
		
		// Convert the data array to JSON
		$jsonData = json_encode($response, JSON_PRETTY_PRINT);

		echo $jsonData;
	}

	public function indexView()
	{
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) { $this->url->redirect('customers'); }

		$this->load->model('customer');
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$data['result'] = $this->model_customer->getCustomer($id);
		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Customer does not exist in database!');
			$this->url->redirect('customers');
		}
		$data['bills'] = $this->model_customer->getBills($data['result']);

		$data['page_title'] = 'Customer View';
		$data['page_edit'] = $this->user_agent->hasPermission('customer/edit') ? true : false;
		$data['page_bills'] = $this->user_agent->hasPermission('medicine/billing') ? true : false;
		$data['bill_view'] = $this->user_agent->hasPermission('medicine/billing/view') ? true : false;
				
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		$data['action'] = URL.DIR_ROUTE.'customer/add';

		/*Render User add view*/
		$this->response->setOutput($this->load->view('customer/customer_view', $data));
	}

	public function indexAdd()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$data['result'] = NULL;
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}

		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		
		$data['page_title'] = 'Add Customer';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'customer/add';
		/*Render User add view*/
		$this->response->setOutput($this->load->view('customer/customer_form', $data));
	}

	public function indexEdit()
	{
		$id = (int)$this->url->get('id');
		if (empty($id) || !is_int($id)) { $this->url->redirect('customers'); }

		$this->load->model('customer');
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);

		$data['result'] = $this->model_customer->getCustomer($id);

		if (empty($data['result'])) {
			$this->session->data['message'] = array('alert' => 'warning', 'value' => 'Customer does not exist in database!');
			$this->url->redirect('customers');
		}
		
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		$data['page_title'] = 'Edit Customer';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		$data['action'] = URL.DIR_ROUTE.'customer/edit&id='.$data['result']['id'];
		
		/*Render User add view*/
		$this->response->setOutput($this->load->view('customer/customer_form', $data));
	}

	public function indexAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) { $this->url->redirect('customers'); }

		$data = $this->url->post('customer');

		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->model('commons');
		$data['info'] = $this->model_commons->getSiteInfo();

		$this->load->controller('common');
		if ($validate_field = $this->validateField($data)) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			if (!empty($data['id'])) {
				$this->url->redirect('customer/edit&id='.$data['id']);
			} else {
				$this->url->redirect('customer/add');
			}
		}

		$data['user'] = $this->model_commons->getUserInfo($this->session->data['user_id']);

		if (!empty($data['dob'])) {
			$data['dob'] = DateTime::createFromFormat($data['info']['date_format'], $data['dob'])->format('Y-m-d');
		} else {
			$data['dob'] = '';
		}
		
		$data['address'] = json_encode($data['address']);
		$data['datetime'] = date('Y-m-d H:i:s');
		
		$this->load->model('customer');
		if (!empty($data['id'])) {
			if ($this->model_customer->checkCustomerEmail($data['mail'], $data['id']) >= 1) {
				$this->session->data['message'] = array('alert' => 'error', 'value' => 'Email  \'' . $this->url->post('email') . '\'  already exist in database.');
				$this->url->redirect('customer/edit&id='.$data['id']);
			}
			$result = $this->model_customer->updateCustomer($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Customer updated successfully.');
		} else {
			if ($this->model_customer->checkCustomerEmail($data['mail']) >= 1) {
				$this->session->data['message'] = array('alert' => 'error', 'value' => 'Email  \'' . $this->url->post('email') . '\'  already exist in database.');
				$this->url->redirect('customer/add');
			}
			$data['id'] = $this->model_customer->createCustomer($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Customer created successfully.');
		}
		$this->url->redirect('customer/view&id='.$data['id']);
	}

	public function indexDelete()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('customers');
		}
		$this->load->model('customer');
		$this->model_customer->deleteCustomer($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Customer deleted successfully.');
		$this->url->redirect('customers');
	}

	public function searchCustomer()
	{
		$data = $this->url->get;
		$this->load->model('customer');
		$result = $this->model_customer->getSearchedCustomer($data['term']);
		echo json_encode($result);
	}

	public function validateField($data)
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_common->validateText($data['firstname'])) {
			$error_flag = true;
			$error['firstname'] = 'First Name';
		}
		if ($this->controller_common->validateText($data['lastname'])) {
			$error_flag = true;
			$error['lastname'] = 'Last Name';
		}
		if ($this->controller_common->validateEmail($data['mail'])) {
			$error_flag = true;
			$error['email'] = 'Email Address';
		}
		if (!empty($data['dob'])) {
			if ($this->controller_common->validateDate( $data['dob'], $data['info']['date_format'] )) {
				$error_flag = true;
				$error['date'] = 'Date of Birth';
			}
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}