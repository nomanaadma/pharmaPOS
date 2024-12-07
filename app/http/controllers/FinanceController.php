<?php

/**
* FinanceController
*/
class FinanceController extends Controller
{
	public function paymentMethod()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		/**
		* Get all Tax data from DB using Tax model 
		**/
		$this->load->model('finance');
		$data['result'] = $this->model_finance->getPaymentMethod();
		/* Set confirmation message if page submitted before */
		if (isset($this->session->data['message'])) {
			$data['message'] = $this->session->data['message'];
			unset($this->session->data['message']);
		}
		/* Set page title */
		$data['page_title'] = 'Payment Method';
		$data['page_add'] = $this->user_agent->hasPermission('paymentmethod/add') ? true : false;
		$data['page_edit'] = $this->user_agent->hasPermission('paymentmethod/edit') ? true : false;
		$data['page_delete'] = $this->user_agent->hasPermission('paymentmethod/delete') ? true : false;

		$data['action'] = URL.DIR_ROUTE.'paymentmethod/add';
		$data['action_delete'] = URL.DIR_ROUTE.'paymentmethod/delete';
		$data['token'] = hash('sha512', TOKEN . TOKEN_SALT);
		/*Render User list view*/
		$this->response->setOutput($this->load->view('finance/payment_method', $data));
	}

	public function paymentMethodAction()
	{
		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['submit'])) {
			$this->url->redirect('paymentmethod');
			exit();
		}
		/**
		* Validate form data
		* If some data is missing or data does not match pattern
		* Return to info view 
		**/
		$this->load->controller('common');
		if ($validate_field = $this->validateField()) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Please enter valid '.implode(", ",$validate_field).'!');
			$this->url->redirect('paymentmethod');
		}
		if ($this->controller_common->validateToken($this->url->post('_token'))) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Security token is missing!');
			$this->url->redirect('paymentmethod');
		}
		$data = $this->url->post;
		$data['datetime'] =  date('Y-m-d H:i:s');
		$this->load->model('finance');
		if (!empty($this->url->post('id'))) {
			$result = $this->model_finance->updatePaymentMethod($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method updated successfully.');
			$this->url->redirect('paymentmethod');
		}
		else {
			$result = $this->model_finance->createPaymentMethod($data);
			$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method created successfully.');
			$this->url->redirect('paymentmethod');
		}
	}

	public function paymentMethodDelete()
	{
		$this->load->controller('common');
		if ($this->controller_common->validateToken($this->url->post('_token'))) {
			$this->session->data['message'] = array('alert' => 'error', 'value' => 'Security token is missing!');
			$this->url->redirect('paymentmethod');
		}

		/**
		* Check if from is submitted or not 
		**/
		if (!isset($_POST['delete']) || empty($this->url->post('id')) ) {
			$this->url->redirect('paymentmethod');
		}
		$this->load->model('finance');
		$result = $this->model_finance->deletePaymentMethod($this->url->post('id'));
		$this->session->data['message'] = array('alert' => 'success', 'value' => 'Payment Method deleted successfully.');
		$this->url->redirect('paymentmethod');
	}

	public function validateField()
	{
		$error = [];
		$error_flag = false;
		if ($this->controller_common->validateText($this->url->post('name'))) {
			$error_flag = true;
			$error['title'] = 'Name!';
		}

		if ($error_flag) {
			return $error;
		} else {
			return false;
		}
	}
}