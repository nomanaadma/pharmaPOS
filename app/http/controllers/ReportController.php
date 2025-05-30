<?php

/**
 * ReportController
 */
class ReportController extends Controller
{
	public function index()
	{
		$report = $this->url->get('name');
		
		if ($report == "inventory") {
			$this->reportInventory();
		} elseif ($report == "income_expenses") {
			$this->reportIncome();
		} else {
			$this->reports();
		}
	}

	public function reports()
	{
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		$data['page_title'] = 'Reports';
		$this->response->setOutput($this->load->view('report/reports', $data));
	}

	public function reportInventory()
	{
		$this->load->controller('common');
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$this->load->model('report');

		$data['result'] = $this->model_report->getMedicines();

		$data['page_title'] = 'Inventory Report';
		$this->response->setOutput($this->load->view('report/report_inventory', $data));
	}

	public function reportIncome()
	{
		$this->load->controller('common');
		$this->load->model('commons');
		$data['common'] = $this->model_commons->getCommonData($this->session->data['user_id']);
		
		$data['period']['start'] = $this->url->get('start');
		$data['period']['end'] = $this->url->get('end');

		if (!empty($data['period']['start']) && !empty($data['period']['end']) && !$this->controller_common->validateDate($data['period']['start']) && !$this->controller_common->validateDate($data['period']['end'])) {
			$data['period']['start'] = date_format(date_create($data['period']['start'].'00:00:00'), "Y-m-d H:i:s");
			$data['period']['end'] = date_format(date_create($data['period']['end'].'23:59:59'), "Y-m-d H:i:s");
		} else {
			$data['period']['start'] = date('Y-m-d '.'00:00:00', strtotime("-1 month"));
			$data['period']['end'] = date('Y-m-d '.'23:59:59');
		}

		$this->load->model('report');
		$data['income'] = $this->model_report->getAllIncome($data['period']);
		$data['expense'] = $this->model_report->getAllExpense($data['period']);

		$data['page_title'] = 'Stats Report';
		$this->response->setOutput($this->load->view('report/report_income_expense', $data));
	}

	public function formatChartDataWithMonth($data)
	{
		$months = array();
		$result['label'] = array();
		$result['value'] = array();
		for ($i = 0; $i < 12; $i++) {
			$month = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
			$month_name = date("M", strtotime( date( 'Y-m-01' )." -$i months"));

			if (!empty($data)) {
				foreach ($data as $key => $value) {
					if ($value['month'] == $month) {
						$result['value'][$i] = (float)$value['amount'];
						$result['label'][$i] = $month_name;
					}
				}
			}

			if (!isset($result['value'][$i])) {
				$result['value'][$i] = 0;
				$result['label'][$i] = $month_name;
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['value'] = json_encode(array_reverse($result['value']));
		return $result;
	}

	public function formatChartData($data)
	{
		$arr = array('Paid', 'Partially Paid', 'Unpaid', 'Pending', 'In Process', 'Cancelled');
		$result['label'] = array();
		$result['value'] = array();
		foreach ($arr as $key => $value) {
			if (!empty($data)) {
				foreach ($data as $k => $v) {
					if ($v['label'] == $value) {
						$result['value'][$key] = (float)$v['value'];
						$result['label'][$key] = $v['label'];
					}
				}
			}

			if (!isset($result['value'][$key])) {
				$result['value'][$key] = 0;
				$result['label'][$key] = $value;
			}
		}
		
		$result['label'] = json_encode(array_reverse($result['label']));
		$result['value'] = json_encode(array_reverse($result['value']));
		return $result;
	}
}