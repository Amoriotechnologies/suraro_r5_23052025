<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public $load, $session, $input, $userid;
	public function __construct() {
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->helper('common_helper');
		$this->checkCustLogin();
	}

	public function index() {

		$companies = $this->Common_model->GetDatas('companies', '`id`, `companyname`');
		$data = [
			'content' => 'pages/dashboard',
			'cmpys' => $companies
		];
		$this->load->view('template', $data);
	}

	public function getDashboardData() {
		$calfilter = trim($this->input->post('calfilter'));
		$split = explode(' to ', $calfilter);
		$startDate = date('Y-m-d', strtotime($split[0]));
		$endDate = date('Y-m-d', strtotime($split[1]));
		
		$salesrecord = $this->Common_model->GetDatas(
            'invoice',
            'SUM(`grand_total`) as `salecnt`, `company_id`',
            ['del ='=>0, 'invoice_date >=' => $startDate, 'invoice_date <=' => $endDate],
            'invoice.company_id ASC',
            'invoice.company_id'
        );

		$expenserecord = $this->Common_model->GetDatas(
            'expenses',
            'SUM(`grand_total`) as `expcnt`, `company_id`',
            ['del ='=>0, 'expense_date >=' => $startDate, 'expense_date <=' => $endDate],
            'expenses.company_id ASC',
            'expenses.company_id'
        );

		$salesData = [];
		foreach($salesrecord as $key => $value) {
			if (!isset($salesData[$value['company_id']])) { $salesData[$value['company_id']] = 0; }
			if (!isset($salesData['total'])) { $salesData['total'] = 0; }
			$salesData[$value['company_id']] += (float)$value['salecnt'];
			$salesData['total'] += (float)$value['salecnt'];
		}
		$expData = [];
		foreach($expenserecord as $key => $expvalue) {
			if (!isset($expData[$expvalue['company_id']])) { $expData[$expvalue['company_id']] = 0; }
			if (!isset($expData['total'])) { $expData['total'] = 0; }
			$expData[$expvalue['company_id']] += (float)$expvalue['expcnt'];
			$expData['total'] += (float)$expvalue['expcnt'];
		}
		$response = [
			'sales' => $salesData, 
			'expense' => $expData
		];
		echo json_encode($response);
		exit;
	}

	protected function checkCustLogin() {
    $userdata = $this->session->userdata('login_cust_info');
    // Get current controller and method
    $current_controller = $this->router->fetch_class(); // e.g., 'auth'
    $current_method = $this->router->fetch_method();     // e.g., 'login'
    // Allow login page to be accessed freely
    if (empty($userdata) && !($current_controller === 'Login' && $current_method === 'login')) {
        redirect('Login/login'); // Update this to your actual login route
        exit;
    }
	}

	public function invoice() {
		$data = [
			'content' => 'pages/invoice',
		];
		$this->load->view('template', $data);
	}

	public function add_invoice() {
		$company_records = $this->Common_model->get_records('companies');
		$data = [
			'content' => 'pages/add_invoice',
			'company_records' => $company_records
		];
		$this->load->view('template', $data);
	}

	public function Getinvoiceformtable() {
		$company_records = $this->Common_model->get_records('companies');
		$data = [
			'content'=>$this->load->view('pages/myinvoice/exclusivegsttable', $data, true)
		];
		echo json_encode($data); exit;
	}

	public function proforma_invoice() {
		$data = [
			'content' => 'pages/proforma_invoice',
		];
		$this->load->view('template', $data);
	}

	public function add_proforma_invoice() {
		$data = [
			'content' => 'pages/add_proforma_invoice',
		];
		$this->load->view('template', $data);
	}

	public function customers() {
		$data = [
			'content' => 'pages/customers',
		];
		$this->load->view('template', $data);
	}
	
	public function add_customer() {
		$data = [
			'content' => 'pages/add_customer',
		];
		$this->load->view('template', $data);
	}
}
