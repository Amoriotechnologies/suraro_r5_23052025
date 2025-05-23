<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Expense extends CI_Controller {
	public $load, $session, $input, $userid;
	public function __construct() {
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->helper('common_helper');
		$this->checkCustLogin();
	}
	public function index() {
		$data = [
			'content' => 'pages/dashboard',
		];
		$this->load->view('template', $data);
	}
	protected function checkCustLogin() {
    $userdata = $this->session->userdata('login_cust_info');
    $current_controller = $this->router->fetch_class(); 
    $current_method = $this->router->fetch_method();     
    // Allow login page to be accessed freely
    if (empty($userdata) && !($current_controller === 'Login' && $current_method === 'login')) {
        redirect('Login/login'); 
        exit;
    }
    }
	public function expense()
    {
        $company_records = $this->Common_model->get_records('companies');
        $data = [
            'content' => 'pages/expense',
            'company_records' => $company_records,
        ];
        $this->load->view('template', $data);
    }
    public function getlist() {
    $expenses = $this->Common_model->get_records('expenses');
    foreach ($expenses as $row) {
        echo $row['id'] . '--' . urlencode(base64_encode($row['id'])) . "<br>";
    }
    }
	public function add_expense($expenseid='novalue') {
    // Check if expenseid is provided
	if ($expenseid != 'novalue'){
        // Step 1: Decode the URL-encoded string
        $decoded_url = urldecode($expenseid); 
        // Step 2: Decode the Base64 string
        $decodedexpenseid = base64_decode($decoded_url); 
        $expenseid = $myexpenseid = $decodedexpenseid;
        $expensedata = $this->Common_model->get_row('expenses',array('id'=>(int)$expenseid));
        $expensedetails = $this->Common_model->get_records('expense_details',array('expense_id'=>$expenseid));
    }else{
    	$myexpenseid = '';
    	$expensedata = '';
        $expensedetails = '';
        $expensedetails = '';
    } 
	// Get all company records
	$company_records = $this->Common_model->get_records('companies');
	// Get latest expense for each company based on shortname
	$latest_expenses_raw = $this->Custom_model->get_latest_expenses_per_company();
	$latest_expenses = [];
	foreach ($latest_expenses_raw as $row) {
    $shortname = strtoupper(trim($row->shortname)); // Trim spaces and ensure it's uppercase
    $latest_expenses[$shortname] = $row;  // Store the row by shortname key
    }
	$custrecords = $this->Common_model->get_records('customer_information',array('del'=>0),null,null,'customer_name','ASC');
	$data = [
		'content' => 'pages/add_expense',
		'company_records' => $company_records,
		'latest_expenses' => $latest_expenses,
		'customers' => $custrecords,
		'expenseid' => $expenseid,
		'expensedetails' =>$expensedetails,
		'expensedata' =>$expensedata,
        'additionalcosts' => isset($additionalcosts) ? $additionalcosts : []
	];

	$this->load->view('template', $data);
    }
    public function Getexpensetable() {
    $post = $this->input->post();
    $company_records = $this->Common_model->get_records('companies');
    if($post['expenseid'] != 0){
        $expensedetails = $this->Common_model->get_records('expense_details',array('expense_id'=>$post['expenseid']));
        $expensedata = $this->Common_model->get_row('expenses',array('id'=>$post['expenseid']));
        $formtype = "edit";
    }else{
        $formtype = "add";
    }
    $data = [
        'formtype' => $formtype,
        'company_records' => $company_records,
        'expensedetails' => isset($expensedetails) ? $expensedetails : [],
        'expensedata' => isset($expensedata) ? $expensedata : [],
        'additionalcosts' => isset($additionalcost) ? $additionalcost : [],
        'gsttype' => $post['gstbill_type'],
        'gstornongst' => 'Non-GST'
    ];
    $output = $this->load->view('pages/myexpense/expensetable', $data, true);
    echo json_encode(['content' => $output]);
    exit;
    }
    public function newexpense() {
    $post = $this->security->xss_clean($this->input->post());
    if (empty($post)) {
        echo "Invalid Request.";
        return;
    }

    // Handle file uploads
    if (!empty($_FILES['fileupload']['name'][0])) {
       // echo "yes"; exit;
        $files = $_FILES['fileupload'];
        $uploadedFiles = [];
        // Create upload directory if not exists
        $uploadPath = './uploads/expenses/';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        $this->load->library('upload');
        for ($i = 0; $i < count($files['name']); $i++) {
            $_FILES['singleFile']['name']     = $files['name'][$i];
            $_FILES['singleFile']['type']     = $files['type'][$i];
            $_FILES['singleFile']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['singleFile']['error']    = $files['error'][$i];
            $_FILES['singleFile']['size']     = $files['size'][$i];
            $config['upload_path']   = $uploadPath;
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size']      = 2048; // 2MB max
            $config['file_name']     = time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '_', $files['name'][$i]);
            $this->upload->initialize($config);
            if ($this->upload->do_upload('singleFile')) {
                $uploadedFiles[] = $this->upload->data('file_name');
            } else {
                log_message('error', 'Upload error: ' . $this->upload->display_errors());
            }
        }
        // Save uploaded file names (example: JSON format or comma-separated)
        $post['uploaded_files'] = json_encode($uploadedFiles); // Or serialize/join
    }
    $grandTotal = floatval(str_replace(['₹', ','], '', $post['grand_total'] ?? 0));
    $paidAmount = floatval(str_replace(['₹', ','], '', $post['paid_amount'] ?? 0));
    $balanceAmount = ($paidAmount <= 0) ? $grandTotal : $grandTotal - $paidAmount;
    // 1. Insert into expenses table
    $expenseData = [
        'vendor_name'      => $post['vendor_name'],
        'expense_date'     => date('Y-m-d', strtotime($post['expense_date'] ?? date('Y-m-d'))),
        'expense_number'   => $post['expense_number'],
        'company_id'       => $post['company_id'],
        'total_amount'     => floatval(str_replace(['₹', ','], '', $post['subtotal'] ?? 0)),
        'other_charges'    => floatval(str_replace(['₹', ','], '', $post['other_charges'] ?? 0)),
        'additional_cost'  => floatval(str_replace(['₹', ','], '', $post['additional_cost'] ?? 0)),
        'grand_total'      => $grandTotal,
        'paid_amount'      => $paidAmount,
        'balance_amount'   => $balanceAmount,
        'payment_history'   => $post['historyData'],
        'attachment_files' => json_encode($uploadedFiles),
    ];
    $this->db->insert('expenses', $expenseData);
    $expense_insert_id = $this->db->insert_id(); // last inserted expense ID
    // 2. Insert into expense_details table
    $productNames = $post['product_name'] ?? [];
    $descriptions = $post['description'] ?? [];
    $quantities   = $post['quantity'] ?? [];
    $rates        = $post['rate'] ?? [];
    $units        = $post['unit'] ?? [];
    $discounts    = $post['discount_exclusive'] ?? [];
    $amounts      = $post['amount'] ?? [];
    $amountssubtotal      = $post['amount_subtotal'] ?? [];
    foreach ($productNames as $key => $productName) {
        $rate     = preg_replace('/[^\d.]/', '', $rates[$key] ?? 0);
        $amount   = preg_replace('/[^\d.]/', '', $amounts[$key] ?? 0);
        $cleaned_subtotal = preg_replace('/[^\d.]/', '', $amountssubtotal[$key] ?? 0);
        $discount = preg_replace('/[^\d.]/', '', $discounts[$key] ?? 0);
        $expenseDetailsData = [
            'expense_id'         => $expense_insert_id,
            'product_name'       => $productNames[$key] ?? '',
            'product_description'=> $descriptions[$key] ?? '',
            'quantity'           => $quantities[$key] ?? 0,
            'unit'               => $units[$key] ?? '',
            'discount'           => $discount,
            'rate'               => $rate,
            'amount'             => $amount,
            'sub_total'           => $cleaned_subtotal,
            'created_date'       => date('Y-m-d H:i:s'),
            'updated_date'       => date('Y-m-d H:i:s')
        ];
        $this->db->insert('expense_details', $expenseDetailsData);
    }
    echo json_encode(["status" => "success"]);
    }
    public function updateexpense()
    {
    $post = $this->security->xss_clean($this->input->post());
    if (empty($post)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        return;
    }
 if (!empty($_FILES['fileupload']['name'][0])) {
    $files = $_FILES['fileupload'];
    $uploadedFiles = [];
    // Create upload directory if not exists
    $uploadPath = './uploads/expenses/';
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    $this->load->library('upload');
    for ($i = 0; $i < count($files['name']); $i++) {
        $_FILES['singleFile']['name']     = $files['name'][$i];
        $_FILES['singleFile']['type']     = $files['type'][$i];
        $_FILES['singleFile']['tmp_name'] = $files['tmp_name'][$i];
        $_FILES['singleFile']['error']    = $files['error'][$i];
        $_FILES['singleFile']['size']     = $files['size'][$i];
        $config['upload_path']   = $uploadPath;
        $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
        $config['max_size']      = 2048; // 2MB max
        $config['file_name']     = time() . '_' . preg_replace('/[^a-zA-Z0-9_.]/', '_', $files['name'][$i]);
        $this->upload->initialize($config);
        if ($this->upload->do_upload('singleFile')) {
            $uploadedFiles[] = $this->upload->data('file_name');
        } else {
            log_message('error', 'Upload error: ' . $this->upload->display_errors());
        }
    }
    // 📌 Get previously stored JSON images (if any)
    $existingFiles = [];
    if (!empty($post['existing_uploaded_files'])) {
        $existingFiles = json_decode($post['existing_uploaded_files'], true);
        if (!is_array($existingFiles)) {
            $existingFiles = [];
        }
    }
    // Merge existing and new uploads
    $mergedFiles = array_merge($existingFiles, $uploadedFiles);
    // Save to DB
    $post['existing_uploaded_files'] = json_encode($mergedFiles);
}
// Ensure you have a valid expense ID from the post data
$expense_id = $post['expenseid'] ?? null;
if (!$expense_id) {
    // Handle the case where the expense ID is not found
    echo "Invalid expense ID.";
    return;
}
    $expense_id = $post['expenseid'];
    // 1. Update expenses table
    $expenseData = [
        'vendor_name'      => $post['vendor_name'],
        'expense_date'     => date('Y-m-d', strtotime($post['expense_date'] ?? date('Y-m-d'))),
        'expense_number'   => $post['expense_number'],
        'company_id'       => $post['company_id'],
        'total_amount'     => floatval(str_replace(['₹', ','], '', $post['subtotal'] ?? 0)),
        'additional_cost'  => floatval(str_replace(['₹', ','], '', $post['additional_cost'] ?? 0)),
        'grand_total'      => floatval(str_replace(['₹', ','], '', $post['grand_total'] ?? 0)),
        'paid_amount'      => $post['paid_amount'],
        'balance_amount'      => $post['balance_amount'],
        'payment_history'  => $post['historyData'],
        'updated_date'     => date('Y-m-d H:i:s'),
        'attachment_files' => $post['existing_uploaded_files'],
    ];
    $this->db->where('id', $expense_id);
    $this->db->update('expenses', $expenseData);
    // 2. Update or Insert expense_details
    $productNames   = $post['product_name'] ?? [];
    $descriptions   = $post['description'] ?? [];
    $quantities     = $post['quantity'] ?? [];
    $rates          = $post['rate'] ?? [];
    $units          = $post['unit'] ?? [];
    $discounts      = $post['discount_exclusive'] ?? [];
    $amounts        = $post['amount'] ?? [];
    $subtotals      = $post['amount_subtotal'] ?? [];
    $expenseDetailsIds = $post['expense_details_id'] ?? [];
    // Get existing IDs from DB
    $this->db->select('id');
    $this->db->from('expense_details');
    $this->db->where('expense_id', $expense_id);
    $query = $this->db->get();
    $existingIds = array_column($query->result_array(), 'id');
    $submittedIds = [];
    foreach ($productNames as $index => $productName) {
        $data = [
            'expense_id'         => $expense_id,
            'product_name'       => $productName,
            'product_description'=> $descriptions[$index] ?? '',
            'quantity'           => $quantities[$index] ?? 0,
            'unit'               => $units[$index] ?? '',
            'discount'           => floatval(str_replace(['₹', ','], '', $discounts[$index] ?? 0)),
            'rate'               => floatval(str_replace(['₹', ','], '', $rates[$index] ?? 0)),
            'amount'             => floatval(str_replace(['₹', ','], '', $amounts[$index] ?? 0)),
            'sub_total'          => floatval(str_replace(['₹', ','], '', $subtotals[$index] ?? 0)),
            'updated_date'       => date('Y-m-d H:i:s')
        ];
        $detailId = $expenseDetailsIds[$index] ?? null;
        if (!empty($detailId)) {
            // Update
            $this->db->where('id', $detailId);
            $this->db->update('expense_details', $data);
            $submittedIds[] = $detailId;
        } else {
            // Insert
            $data['created_date'] = date('Y-m-d H:i:s');
            $this->db->insert('expense_details', $data);
        }
    }
    // Delete removed rows
    $deletedIds = array_diff($existingIds, $submittedIds);
    if (!empty($deletedIds)) {
        $this->db->where_in('id', $deletedIds);
        $this->db->delete('expense_details');
    }
    // 3. Handle additional_costs (if used)
    $additionalCostIds     = $post['additional_costid'] ?? [];
    $serviceProviders      = $post['service_provider'] ?? [];
    $serviceDescriptions   = $post['service_description'] ?? [];
    $serviceQty            = $post['service_qty'] ?? [];
    $serviceRate           = $post['service_rate'] ?? [];
    // Get existing additional_costs IDs
    $this->db->select('id');
    $this->db->from('additional_costs');
    $this->db->where('invoice_id', $expense_id);
    $query = $this->db->get();
    $existingAdditionalIds = array_column($query->result_array(), 'id');
    $submittedAdditionalIds = [];
    foreach ($serviceProviders as $key => $provider) {
        if (empty(trim($provider))) continue;
        $costData = [
            'invoice_id'       => $expense_id,
            'service_provider' => $provider ?? '',
            'description'      => $serviceDescriptions[$key] ?? '',
            'qty'              => $serviceQty[$key] ?? 0,
            'rate'             => $serviceRate[$key] ?? 0
        ];
        $costId = $additionalCostIds[$key] ?? null;
        if (!empty($costId)) {
            $this->db->where('id', $costId);
            $this->db->update('additional_costs', $costData);
            $submittedAdditionalIds[] = $costId;
        } else {
            $costData['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('additional_costs', $costData);
        }
    }
    $deletedAdditionalIds = array_diff($existingAdditionalIds, $submittedAdditionalIds);
    if (!empty($deletedAdditionalIds)) {
        $this->db->where_in('id', $deletedAdditionalIds);
        $this->db->delete('additional_costs');
    }
    echo json_encode(["status" => "success"]);
    }
     public function delete_expense()
    {
        $post = $this->input->post();
         $decoded_id = urldecode($post['id']); // This decodes any URL encoding
        // Step 2: Decode the Base64 string
        $decodedinvoiceid = base64_decode($decoded_id); 
        $data = [
            'del' => 1
        ];
        $del = $this->Common_model->update('expenses', $data, array('id' => $decodedinvoiceid));
        if ($del) {
            echo json_encode(["status" => "success"]);
            exit;
        } else {
            echo json_encode(["status" => "error"]);
            exit;
        }
    }
    public function getexpense()
    {
    $limit = $this->input->post('length');  // Records per page
    $start = $this->input->post('start');   // Starting record
    $search = $this->input->post('search')['value'];  // Search term
    // Ordering
    $orderField = $this->input->post('columns')[$this->input->post('order')[0]['column']]['data'];
    $orderDirection = $this->input->post("order")[0]["dir"];
    // Filters
    $datefilter = $this->input->post('datefilter');
    $cmpyname = $this->input->post('cmpyname');
    // Date range parsing
    $split = explode(' to ', $datefilter);
    $startDate = date('Y-m-d', strtotime($split[0]));
    $endDate = date('Y-m-d', strtotime($split[1]));
    // Build WHERE conditions
    $where = [];
    $where['expenses.del ='] = 0;
    $where['expense_date >='] = $startDate;
    $where['expense_date <='] = $endDate;
    if (!empty($cmpyname)) {
        $where['expenses.company_id'] = $cmpyname;
    }
    // Searchable columns
    $search_value = [
        'expense_number' => $search,
        'expense_date' => $search,
        'vendor_name' => $search
    ];
    // Total count (for pagination metadata)
     $all_items = $this->Common_model->GetDatas(
        'expenses',  // Only the 'expenses' table
        '*, expenses.id as exp_id',  // Columns to select
        $where,  // WHERE conditions
        '',  // No ordering yet
        '',  // No grouping
        $search_value  // Search conditions
    );
    $totalRow = count($all_items);  // Count the total records
    // Paginated data
    $items = $this->Common_model->GetDatas(
        'expenses',  // Only the 'expenses' table
        '*, expenses.id as exp_id',  // Columns to select
        $where,  // WHERE conditions
        "$orderField $orderDirection",  // Ordering
        '',  // No grouping
        $search_value,  // Search conditions
        $limit,  // Pagination limit
        $start  // Pagination offset
    );
    // Prepare data for DataTables
    $data = [];
    $i = $start + 1;
    foreach ($items as $item) {
        $encoded_id = urlencode(base64_encode($item['exp_id']));
        $action = '<div class="hstack gap-2 fs-1">
            <a aria-label="anchor" class="btn btn-icon btn-sm btn-info-light btn-wave waves-effect waves-light send_emailclick" onclick="sendEmail(' . $item['exp_id'] . ')" data-toggle="tooltip" title="Send Email" data-bs-toggle="modal" data-bs-target="#sendEmailmodal"><i class="ri-mail-send-line"></i></a>
            <a aria-label="anchor" href="edit_expense/' . $encoded_id . '" class="btn btn-icon btn-sm btn-success-light btn-wave waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="ri-edit-line"></i></a>
            <a aria-label="anchor" href="view_expense/' . $encoded_id . '" class="btn btn-icon btn-sm btn-success-light btn-wave waves-effect waves-light" data-toggle="tooltip" title="View"><i class="ri-eye-line"></i>
</a>
            <a class="btn btn-icon btn-sm btn-danger-light btn-wave waves-effect waves-light delete_expenseid" data-id="'.$encoded_id.'"data-bs-toggle="tooltip" title="Delete"><i class="ri-delete-bin-5-line"></i></a>
            <input type="hidden" class="delete_id" name="delete_id" value="' . $encoded_id . '">
            <input type="hidden" class="click_emailid" name="click_emailid" value="' . $item['expense_number'] . '">
        </div>';
        $data[] = [
            "exp_id" => $i,
            "expense" => $item['expense_number'],
            "expense_date" => date('d-m-Y', strtotime($item['expense_date'])),
            "vendor_name" => $item['vendor_name'],
            "grand_total" => '₹' . $item['grand_total'],
            "paid" => '₹' . $item['paid_amount'],
            "balance" => '₹' . $item['balance_amount'],
            "history" => $item['payment_history'],
            'action' => $action,
        ];
        $i++;
    }
    // Return result in JSON format
    $response = [
        "draw" => $this->input->post('draw'),
        "recordsTotal" => $totalRow,
        "recordsFiltered" => $totalRow,
        "data" => $data,
    ];
    echo json_encode($response);
    exit;
    }
}