<?php defined('BASEPATH') or exit('No direct script access allowed');
class Customer extends CI_Controller
{
	public $load, $session, $input, $userid;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->helper('common_helper');
		$this->checkCustLogin();
	}
	public function index()
	{
	    $records = $this->Common_model->get_records('customer_information', ['del' => 0], null, null, 'id', 'DESC');
	    $company_records = $this->Common_model->get_records('companies');

	    $startDate = '2024-01-01'; // Set your range dynamically if needed
	    $endDate   = date('Y-m-d');

	    // Loop through each customer to get their payment summary
	    foreach ($records as &$customer) {
	        $payment_summary = $this->Common_model->GetDatas(
	            'invoice',
	            'SUM(total_amount) as total_sum, SUM(paid_amount) as paid_sum, SUM(balance_amount) as balance_sum',
	            [
	                'del ='            => 0,
	                'invoice_date >='  => $startDate,
	                'invoice_date <='  => $endDate,
	                'customer_id'      => $customer['id']
	            ]
	        );

	        // Safely attach payment summary to the customer
	        $customer['total_sum']    = $payment_summary[0]['total_sum'] ?? 0;
	        $customer['paid_sum']     = $payment_summary[0]['paid_sum'] ?? 0;
	        $customer['balance_sum']  = $payment_summary[0]['balance_sum'] ?? 0;
	    }

	    $data = [
	        'content'         => 'pages/customers',
	        'customers'       => $records,
	        'company_records' => $company_records
	    ];
	    $this->load->view('template', $data);
	}

	protected function checkCustLogin()
	{
		$userdata = $this->session->userdata('login_cust_info');
		$current_url = uri_string();
		// Allow login page to be accessed freely
		if (empty($userdata) && $current_url !== 'login') {
			redirect('login');
			exit;
		}
	}
	public function add_customer($id = 0)
	{
		if ($id !== 0) {
			$id = base64_decode(rawurldecode($id));
			$customer_data = $this->Common_model->get_row('customer_information', array('id' => $id));
		}
		$states = $this->Custom_model->get_states(); // Fetch states
		$data = [
			'content' => 'pages/add_customer',
			'states' => $states,
			'id' => $id,
			'customer_data' => isset($customer_data) ? $customer_data : null
		];
		$this->load->view('template', $data);
	}
	public function getcustomer()
	{
		$post = $this->input->post();
		$data['customer_data'] = $this->Common_model->get_row('customer_information', array('id' => $post['custid']));
		echo json_encode($data);
		exit;
	}
	public function insert_customer()
	{
		$post = $this->security->xss_clean($this->input->post());
		$mobile = trim($post['customer_mobilenumber']);
	    // Check if mobile number already exists
	    $existing = $this->Common_model->get_row('customer_information', ['customer_mobilenumber' => $mobile]);
	    if ($existing && $mobile != '') {
	        echo json_encode(["status"=>"show error",'message'=>'Mobile number Already Exists']); exit;
	    }
	    $data = [
			'customer_name' => trim($post['customer_name']),
			'company_name' => trim($post['company_name']),
			'customer_email' => trim($post['customer_email']),
			'customer_mobilenumber' => trim($mobile),
			'customer_address' => trim($post['customer_address']),
			'customer_city' => trim($post['customer_city']),
			'customer_state' => trim($post['customer_state']),
			'admin' => $this->session->userdata('login_cust_info')['cust_id'] ?? ''
		];
		$insert = $this->Common_model->insert('customer_information', $data);
		if($insert){
			   echo json_encode(["status"=>"success",'message']); exit;
	    } else {
	        echo json_encode(["status"=>"failed"]); exit;
	    }
	}
	public function insert_customer_formodal()
	{
		$post = $this->security->xss_clean($this->input->post());
		$data = [
			'customer_name' => trim($post['customer_name']),
			'company_name' => trim($post['company_name']),
			'customer_email' => trim($post['customer_email']),
			'customer_mobilenumber' => trim($post['customer_mobilenumber']),
			'customer_address' => trim($post['customer_address']),
			'customer_city' => trim($post['customer_city']),
			'customer_state' => trim($post['customer_state']),
			'admin' => $this->session->userdata('login_cust_info')['cust_id'] ?? ''
		];
		$insert = $this->Common_model->insert('customer_information', $data);
		$custrecords = $this->Common_model->get_records('customer_information', array('del' => 0), null, null, 'id', 'DESC');
		if ($insert) {
			echo json_encode([
				'status' => 'success',
				'customers' => $custrecords
			]);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}
	public function update_customer()
	{
		$post = $this->security->xss_clean($this->input->post());
		$mobile = trim($post['customer_mobilenumber']);
	    // Check if mobile number already exists
	    $existing = $this->Common_model->get_row('customer_information', ['customer_mobilenumber' => $mobile,'id !='=>$post['thisid']]);
	    if ($existing && $mobile != '') {
	        echo json_encode(["status"=>"show error",'message'=>'Mobile number Already Exists']); exit;
	    }
		$data = [
			'customer_name' => trim($post['customer_name']),
			'company_name' => trim($post['company_name']),
			'customer_email' => trim($post['customer_email']),
			'customer_mobilenumber' => trim($post['customer_mobilenumber']),
			'customer_address' => trim($post['customer_address']),
			'customer_city' => trim($post['customer_city']),
			'customer_state' => trim($post['customer_state'])
		];
		$update = $this->Common_model->update('customer_information', $data, ['id' => $post['thisid']]);
		if($update){
			   echo json_encode(["status"=>"success",'message']); exit;
	    } else {
	        echo json_encode(["status"=>"failed"]); exit;
	    }
	}
	public function load_customeraddmodal()
	{
		$states = $this->Custom_model->get_states(); // Fetch states
		$data = ['states' => $states];
		$loaddata = array('content' => $this->load->view('modals/customer_add_modal', $data, true));
		echo json_encode($loaddata);
		exit;
	}
	public function delete_customer()
	{
		$post = $this->input->post();
		$data = [
			'del' => 1
		];
		$del = $this->Common_model->update('customer_information', $data, array('id' => $post['id']));
		if ($del) {
			echo "success";
			exit;
		} else {
			echo "error";
			exit;
		}
	}
	public function customer_table()
{
    $limit = $this->input->post('length');
    $start = $this->input->post('start');
    $search = $this->input->post('search')['value'];

    $columnMap = [
        'cust_id' => 'id',
        'expense' => 'customer_name',
        'vendor_name' => 'customer_email',
        'grand_total' => 'customer_mobilenumber',
        'paid' => 'paid_amount',
        'balance' => 'balance_amount',
        'history' => 'payment_history'
    ];

    $orderFieldInput = $this->input->post('columns')[$this->input->post('order')[0]['column']]['data'];
    $orderField = isset($columnMap[$orderFieldInput]) ? $columnMap[$orderFieldInput] : 'customer_name';
    $orderDirection = $this->input->post("order")[0]["dir"];
    $orderby = "$orderField $orderDirection";

    $datefilter = $this->input->post('datefilter');
    $cmpyname = $this->input->post('cmpyname');
    $where = ['del' => 0];

    if (!empty($cmpyname)) {
        $where['customer_information.company_name'] = $cmpyname;
    }

    $search_value = [
        'customer_name' => $search,
        'customer_mobilenumber' => $search,
        'customer_email' => $search
    ];

    // Full data for count
    $all_items = $this->Common_model->GetDatas('customer_information', '*', $where, '', '', $search_value);
    $totalRow = count($all_items);

    // Paginated customer list
    $items = $this->Common_model->GetDatas('customer_information', '*', $where, $orderby, '', $search_value, $limit, $start);

    $data = [];
    $i = $start + 1;
    $startDate = '2024-01-01'; // Optional: customize or get from filter
    $endDate = date('Y-m-d');

    foreach ($items as $item) {
        // Get sum of payment data for this customer
        $payment_summary = $this->Common_model->GetDatas(
            'invoice',
            'SUM(total_amount) as total_sum, SUM(paid_amount) as paid_sum, SUM(balance_amount) as balance_sum',
            [
                'del =' => 0,
                'invoice_date >=' => $startDate,
                'invoice_date <=' => $endDate,
                'customer_id' => $item['id']
            ]
        );

        $totalAmount = $payment_summary[0]['total_sum'] ?? 0;
        $paidAmount = $payment_summary[0]['paid_sum'] ?? 0;
        $balanceAmount = $payment_summary[0]['balance_sum'] ?? 0;

        $encoded_id = urlencode(base64_encode($item['id']));
        $action = '<div class="hstack gap-2 fs-1">
            <a href="editcustomer/' . $encoded_id . '" class="btn btn-icon btn-sm btn-success-light btn-wave waves-effect waves-light" data-bs-toggle="tooltip" title="Edit"><i class="ri-edit-line"></i></a>
            <a class="btn btn-icon btn-sm btn-danger-light btn-wave waves-effect waves-light delete_customerid" data-id="' . $item['id'] . '" data-bs-toggle="tooltip" title="Delete"><i class="ri-delete-bin-5-line"></i></a>
            <input type="hidden" class="delete_id" value="' . $encoded_id . '">
            <input type="hidden" class="click_emailid" value="">
        </div>';

        $data[] = [
            "cust_id" => $i,
            "custname" => '<a style="text-decoration: underline;" href="javascript:void(0);" class="viewCustomerDetail text-primary fw-bold" data-id="' . $item['id'] . '">' . htmlspecialchars(ucwords($item['customer_name'])) . '</a>',
            "vendor_name" => $item['customer_email'],
            "mobile_number" => $item['customer_mobilenumber'],
            "grand_total" => '₹' . number_format((float)$totalAmount, 2),
            "paid" => '₹' . number_format((float)$paidAmount, 2),
            "balance" => '₹' . number_format((float)$balanceAmount, 2),
            "history" => $item['payment_history'],
            "action" => $action,
        ];
        $i++;
    }

    $response = [
        "draw" => $this->input->post('draw'),
        "recordsTotal" => $totalRow,
        "recordsFiltered" => $totalRow,
        "data" => $data,
    ];
    echo json_encode($response);
    exit;
}

public function view_customer_details()
{
    $customer_id = $this->input->post('id');

    // Fetch customer info
    $data['customer'] = $this->Common_model->get_row('customer_information', ['id' => $customer_id]);
    $data['name'] = $data['customer']['customer_name'];
    // Fetch invoice details with company name
    $this->db->select('invoice.invoice_number, invoice.grand_total, invoice.paid_amount, invoice.balance_amount, companies.companyname');
    $this->db->from('invoice');
    $this->db->join('companies', 'companies.id = invoice.company_id', 'left');
    $this->db->where([
        'invoice.customer_id' => $customer_id,
        'invoice.del' => 0
    ]);
    $data['invoices'] = $this->db->get()->result_array();

    // Return modal HTML wrapped in JSON
    $response['html'] = $this->load->view('modals/customer_detail_modal', $data, true);
    echo json_encode($response);
    exit;
}

}