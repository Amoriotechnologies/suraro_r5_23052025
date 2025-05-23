<?php defined('BASEPATH') or exit('No direct script access allowed');
class Invoice extends CI_Controller
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
        $data = [
            'content' => 'pages/dashboard',
        ];
        $this->load->view('template', $data);
    }
     public function pdfinvoice($invoiceid='novalue')
    {
        if ($invoiceid != 'novalue'){
        // Step 1: Decode the URL-encoded string
        $decoded_url = urldecode($invoiceid); // This decodes any URL encoding
        // Step 2: Decode the Base64 string
        $decodedinvoiceid = base64_decode($decoded_url); 
        $myinvoiceid = $decodedinvoiceid;
        $invoicedata = $this->Common_model->get_row('invoice',array('id'=>$myinvoiceid));
        $invoicedetails = $this->Common_model->get_records('invoice_details',array('invoice_id'=>$myinvoiceid));
        $additionalcosts = $this->Common_model->get_records('additional_costs',array('invoice_id'=>$myinvoiceid));
        $customer_details = $this->Common_model->get_row('customer_information',array('id'=>$invoicedata['customer_id']));
    }else{
        $myinvoiceid = '';
        $invoicedata = '';
        $invoicedetails = '';
        $invoicedetails = '';
    } 
    $data = [
        'content' => 'pages/add_invoice',
        'company_records' => $company_records,
        'latest_invoices' => $latest_invoices,
        'customers' => $custrecords,
        'invoiceid' => $myinvoiceid,
        'invoicedetails' =>$invoicedetails,
        'invoicedata' =>$invoicedata,
        'additionalcosts' => isset($additionalcosts) ? $additionalcosts : [],
        'customer_details' => isset($customer_details) ? $customer_details : [],
        'companies' => $this->Common_model->get_records('companies'),
        'content' => 'pdfs/gst_pdf',
    ];
    $this->load->view('template', $data);
    }
    protected function checkCustLogin() {
    $userdata = $this->session->userdata('login_cust_info');
    // Get current controller and method
    $current_controller = $this->router->fetch_class(); 
    $current_method = $this->router->fetch_method();     
    // Allow login page to be accessed freely
    if (empty($userdata) && !($current_controller === 'Login' && $current_method === 'login')) {
        redirect('Login/login'); 
        exit;
    }
    }
	 public function invoice()
    {
        $company_records = $this->Common_model->get_records('companies');
        $data = [
            'content' => 'pages/invoice',
            'company_records' => $company_records,
        ];
        $this->load->view('template', $data);
    }
    public function getlist() {
    $invoices = $this->Common_model->get_records('invoice');
    foreach ($invoices as $row) {
        echo $row['id'] . '--' . urlencode(base64_encode($row['id'])) . "<br>";
    }
    }
	public function add_invoice($invoiceid='novalue') {
		   // Check if invoiceid is provided
	if ($invoiceid != 'novalue'){
        // Step 1: Decode the URL-encoded string
        $decoded_url = urldecode($invoiceid); // This decodes any URL encoding
        // Step 2: Decode the Base64 string
        $decodedinvoiceid = base64_decode($decoded_url); 
        $myinvoiceid = $decodedinvoiceid;
        $invoicedata = $this->Common_model->get_row('invoice',array('id'=>$myinvoiceid));
        $invoicedetails = $this->Common_model->get_records('invoice_details',array('invoice_id'=>$myinvoiceid));
        $additionalcosts = $this->Common_model->get_records('additional_costs',array('invoice_id'=>$myinvoiceid));
    }else{
    	$myinvoiceid = '';
    	$invoicedata = '';
        $invoicedetails = '';
        $invoicedetails = '';
    } 
	// Get all company records
	$company_records = $this->Common_model->get_records('companies');
	// Get latest invoice for each company based on shortname
	$latest_invoices_raw = $this->Custom_model->get_latest_invoices_per_company();
	$latest_invoices = [];
	foreach ($latest_invoices_raw as $row) {
    $shortname = strtoupper(trim($row->shortname)); // Trim spaces and ensure it's uppercase
    $latest_invoices[$shortname] = $row;  // Store the row by shortname key
}

    if($invoiceid =='novalue'){
        $custrecords = $this->Common_model->get_records('customer_information',array('del'=>0),null,null,'customer_name','ASC');
    }else{
        $custrecords = $this->Common_model->get_records('customer_information',array(),null,null,'customer_name','ASC');
        
    }
	
	$data = [
		'content' => 'pages/add_invoice',
		'company_records' => $company_records,
		'latest_invoices' => $latest_invoices,
		'customers' => $custrecords,
		'invoiceid' => $myinvoiceid,
		'invoicedetails' =>$invoicedetails,
		'invoicedata' =>$invoicedata,
        'additionalcosts' => isset($additionalcosts) ? $additionalcosts : []
	];
	$this->load->view('template', $data);
    }
	public function Getinvoiceformtable() {
	$post = $this->input->post();  
    $company_records = $this->Common_model->get_records('companies');
    if($post['invoiceid'] != 0){
    	$invoicedetails = $this->Common_model->get_records('invoice_details',array('invoice_id'=>$post['invoiceid']));
        $invoicedata = $this->Common_model->get_row('invoice',array('id'=>$post['invoiceid']));
        $additionalcost = $this->Common_model->get_row('additional_costs',array('invoice_id'=>$post['invoiceid']));
    	$formtype = "edit";
    }else{
    	$formtype = "add";
    }
    if($post['customerstate'] != ''){
        $state = $this->Common_model->get_row('states',array('id'=>$post['customerstate']));
    }
    
    if(!empty($invoicedata) && $post['customerstate'] == 'null'){  
        
        $custrecords = $this->Common_model->get_row('customer_information',array('id'=>$invoicedata['customer_id'])); 
         $state = $this->Common_model->get_row('states',array('id'=>$custrecords['customer_state']));
        
    }
    $data = [
        'formtype' => $formtype,
        'company_records' => $company_records,
        'invoicedetails' => isset($invoicedetails) ? $invoicedetails : [],
        'invoicedata' => isset($invoicedata) ? $invoicedata : [],
        'additionalcosts' => isset($additionalcost) ? $additionalcost : [],
        'gsttype' => isset($post['gstbill_type']) ? $post['gstbill_type'] : '',
        'gstornongst' => isset($post['gstornongst']) ? $post['gstornongst'] : '',
        'state_name' => isset($state['name']) ? $state['name'] : ''
    ];
        $output = $this->load->view('pages/myinvoice/gsttable', $data, true);
        echo json_encode(['content' => $output]);
        exit;
    }
	public function newinvoice() {  
    $post = $this->security->xss_clean($this->input->post());  //print_r($post); exit;
    if (empty($post)) {
        echo "Invalid Request.";
        return;
    }
    $grandTotal = floatval(str_replace(['₹', ','], '', $post['grand_total'] ?? 0));
    $paidAmount = floatval(str_replace(['₹', ','], '', $post['paid_amount'] ?? 0));
    $balanceAmount = ($paidAmount <= 0) ? $grandTotal : $grandTotal - $paidAmount;
   $post = $this->input->post();
    // Your input invoice number (e.g., "SM-2526-003")
    $inputInvoiceNumber = $post['invoice_number'];
    // Extract prefix and suffix from invoice number
    // Assuming suffix is always the last '-' separated part and numeric
    $parts = explode('-', $inputInvoiceNumber);
    if (count($parts) < 2) {
        // Invalid format, handle accordingly
        $invoice_number = $inputInvoiceNumber;
    } else {
        // Get prefix (all except last part)
        $suffix = array_pop($parts);
        $prefix = implode('-', $parts) . '-';
        // Query max suffix for same prefix in DB (ignoring non-numeric suffix)
        $this->db->select_max('invoice_number');
        $this->db->like('invoice_number', $prefix, 'after'); // 'after' = prefix matching at start
        $maxInvoice = $this->db->get('invoice')->row();
        if ($maxInvoice && !empty($maxInvoice->invoice_number)) {
            // Extract suffix number from max invoice number
            $maxParts = explode('-', $maxInvoice->invoice_number);
            $maxSuffix = intval(end($maxParts));
            // Increment suffix by 1
            $newSuffix = str_pad($maxSuffix + 1, strlen($suffix), '0', STR_PAD_LEFT);
            // Compose new invoice number
            $invoice_number = $prefix . $newSuffix;
        } else {
            // No existing invoice, use original or start at 001
            $invoice_number = $inputInvoiceNumber;
        }
    }
    $invoiceData = [
        'invoice_id'      => uniqid('INV-'),
        'invoice_date'    => date('Y-m-d', strtotime($post['invoice_date'] ?? date('Y-m-d'))),
        'invoice_number'  => $invoice_number,
        'company_id'      => $post['company_id'],
        'customer_id'     => $post['customer_id'],
        'gst_number'      => $post['gst_number'] ?? '',
        'total_amount'    => floatval(str_replace(['₹', ','], '', $post['subtotal'] ?? 0)),
        'additional_cost' => floatval(str_replace(['₹', ','], '', $post['additional_cost'] ?? 0)),
        'grand_total'     => $grandTotal,
        'paid_amount'     => $paidAmount,
        'balance_amount'  => $balanceAmount,
        'others'          => '',
        'billtype'        => $post['bill_type'],
        'gstbilltype'     => $post['gstbill_type'] ?? '',
        'terms_conditions'=> '',
        'payment_history' => $post['historyData'],
        'admin_id'        => $this->session->userdata('login_cust_info')['cust_id'] ?? '',
        'created_date'    => date('Y-m-d H:i:s'),
        'updated_date'    => date('Y-m-d H:i:s')
    ];
    $this->db->insert('invoice', $invoiceData);
    $invoice_insert_id = $this->db->insert_id();  // get the last inserted invoice id
        // 3. Insert into additional_costs table
    $serviceProviders = $post['service_provider'] ?? [];
    $serviceDescriptions = $post['service_description'] ?? [];
    $serviceQty = $post['service_qty'] ?? [];
    $serviceRate = $post['service_rate'] ?? [];
    $serviceTotal = $post['service_total'] ?? [];
    foreach ($serviceProviders as $key => $provider) {
        $additionalCostData = [
            'invoice_id'        => $invoice_insert_id,
            'service_provider'  => $provider ?? '',
            'description'       => $serviceDescriptions[$key] ?? '',
            'qty'               => $serviceQty[$key] ?? 0,
            'rate'              => $serviceRate[$key] ?? 0,
            'created_at'        => date('Y-m-d H:i:s')
        ];
        $this->db->insert('additional_costs', $additionalCostData);
    }
    if ($invoice_insert_id) {
        // 2. Insert into invoice_details table
        $productNames = $post['product_name'] ?? [];
        $descriptions = $post['description'] ?? [];
        $quantities   = $post['quantity'] ?? [];
        $rates        = $post['rate'] ?? [];
        $units        = $post['unit'] ?? [];
        $discounts    = $post['discount_exclusive'] ?? [];
        $amounts              = $post['amount'] ?? [];
         $amount_subtotals     = $post['amount_subtotal'] ?? [];
        $gstPercents  = $post['GST'] ?? [];
        $IgstValue  = $post['IGST'] ?? [];
        foreach ($productNames as $key => $productName) {
        $rate = preg_replace('/[^\d.]/', '', $rates[$key] ?? 0);
        $igst = preg_replace('/[^\d.]/', '', $IgstValue[$key] ?? 0);
        $amount = preg_replace('/[^\d.]/', '', $amounts[$key] ?? 0);
       $cleaned_subtotal = preg_replace('/[^\d.]/', '', $amount_subtotals[$key] ?? 0);
        $discount = preg_replace('/[^\d.]/', '', $discounts[$key] ?? 0);
         $isInterstate = isset($post['is_interstate']) && $post['is_interstate'] === 'yes';
        // Calculate IGST amount if applicable
        $igstValue = 0;
        if ($isInterstate) {
            $igstValue = $gstPercents[$key] ? $gstPercents[$key] : 0;
        }
        $invoiceDetailsData = [
            'invoice_id'        => $invoice_insert_id,
            'product_name'      => $productNames[$key] ?? '',
            'product_description'=> $descriptions[$key] ?? '',
            'gst_per'           => $gstPercents[$key] ?? 0,
            'cgst'              => isset($gstPercents[$key]) ? ($gstPercents[$key] / 2) : 0,
            'sgst'              => isset($gstPercents[$key]) ? ($gstPercents[$key] / 2) : 0,
            'igst'               => isset($igst) ? ($igst) : 0,
            'quantity'          => $quantities[$key] ?? 0,
            'unit'              => $units[$key] ?? '',
            'discount'          => $discount,
            'rate'              => $rate,
            'amount'            => $amount,
            'sub_total' => $cleaned_subtotal,
            'created_date'      => date('Y-m-d H:i:s'),
            'updated_date'      => date('Y-m-d H:i:s')
        ];
        $this->db->insert('invoice_details', $invoiceDetailsData);
    }
        echo json_encode(["status"=>"success"]); exit;
    } else {
        echo json_encode(["status"=>"failed"]); exit;
    }
    }
    public function updateinvoice($invoiceid = 0)
    {
    $post = $this->security->xss_clean($this->input->post()); 
    $invoice_id = $post['invoiceid'];
    $product_names        = $post['product_name'] ?? [];
    $descriptions         = $post['description'] ?? [];
    $rates                = $post['rate'] ?? [];
    $units                = $post['unit'] ?? [];
    $quantities           = $post['quantity'] ?? [];
    $discounts            = $post['discount_exclusive'] ?? [];
    $amounts              = $post['amount'] ?? [];
    $gsts                 = $post['GST'] ?? [];
    $sgsts                = $post['SGST'] ?? [];
    $cgsts                = $post['CGST'] ?? [];
    $amount_subtotals     = $post['amount_subtotal'] ?? [];
    $invoice__ids         = $post['invoice__id'] ?? [];
    $invoice_details_ids  = $post['invoice_details_id'] ?? [];
    // Update invoice table
    $invoicedata = [
          'grand_total'     => floatval(str_replace(['₹', ','], '', $post['grand_total'] ?? 0)),
         'total_amount'    => floatval(str_replace(['₹', ','], '', $post['subtotal'] ?? 0)),
        'company_id'      => $post['company_id'],
        'payment_history'   => $post['historyData'],
        'paid_amount'       => $post['paid_amount'],
        'balance_amount'    => $post['balance_amount'],
        'additional_cost'   => floatval(str_replace(['₹', ','], '', $post['additional_cost'] ?? 0))
    ];
    $this->db->where('id', $invoice_id);
    $this->db->update('invoice', $invoicedata);
    // Step 1: Get existing invoice_detail IDs
    $this->db->select('id');
    $this->db->from('invoice_details');
    $this->db->where('invoice_id', $invoice_id);
    $query = $this->db->get();
    $existingInvoiceDetailIds = array_column($query->result_array(), 'id');
    // Step 2: Track submitted IDs
    $submittedInvoiceDetailIds = [];
    foreach ($product_names as $index => $product_name) {
        $data = [
            'invoice_id'          => $invoice__ids[$index] ?? '',
            'product_name'        => $product_name,
            'product_description' => $descriptions[$index] ?? '',
            'rate'                => floatval(str_replace(['₹', ','], '', $rates[$index] ?? 0)),
            'unit'                => $units[$index] ?? '',
            'quantity'            => intval($quantities[$index] ?? 0),
            'discount'            => floatval(str_replace(['₹', ','], '', $discounts[$index] ?? 0)),
            'amount'              => floatval(str_replace(['₹', ','], '', $amounts[$index] ?? 0)),
            'sub_total'           => floatval(str_replace(['₹', ','], '', $amount_subtotals[$index] ?? 0)),
            'gst_per'             => floatval(str_replace(['₹', ','], '', $gsts[$index] ?? 0)),
            'sgst'                => floatval(str_replace(['₹', ','], '', $sgsts[$index] ?? 0)),
            'cgst'                => floatval(str_replace(['₹', ','], '', $cgsts[$index] ?? 0)),
            'updated_date'        => date('Y-m-d H:i:s')
        ];
        if (!empty($invoice_details_ids[$index])) {
            $this->db->where('id', $invoice_details_ids[$index]);
            $this->db->update('invoice_details', $data);
            $submittedInvoiceDetailIds[] = $invoice_details_ids[$index];
        } else {
            $this->db->insert('invoice_details', $data);
        }
    }
    // Step 3: Delete removed invoice_details
    $deletedInvoiceDetailIds = array_diff($existingInvoiceDetailIds, $submittedInvoiceDetailIds);
    if (!empty($deletedInvoiceDetailIds)) {
        $this->db->where_in('id', $deletedInvoiceDetailIds);
        $this->db->delete('invoice_details');
    }
    // ADDITIONAL COSTS
    $additionalCostIds     = $post['additional_costid'] ?? [];
    $serviceProviders      = $post['service_provider'] ?? [];
    $serviceDescriptions   = $post['service_description'] ?? [];
    $serviceQty            = $post['service_qty'] ?? [];
    $serviceRate           = $post['service_rate'] ?? [];
    // Step 1: Get existing additional_costs IDs
    $this->db->select('id');
    $this->db->from('additional_costs');
    $this->db->where('invoice_id', $invoice_id);
    $query = $this->db->get();
    $existingAdditionalCostIds = array_column($query->result_array(), 'id');
    // Step 2: Track submitted IDs
    $submittedAdditionalCostIds = [];
    foreach ($serviceProviders as $key => $provider) {
        $costId = $additionalCostIds[$key] ?? null;
        $additionalCostData = [
            'invoice_id'        => $invoice_id,
            'service_provider'  => $provider ?? '',
            'description'       => $serviceDescriptions[$key] ?? '',
            'qty'               => $serviceQty[$key] ?? 0,
            'rate'              => $serviceRate[$key] ?? 0
        ];
        if (!empty($costId)) {
            $this->db->where('id', $costId);
            $this->db->update('additional_costs', $additionalCostData);
            $submittedAdditionalCostIds[] = $costId;
        } else {
            $additionalCostData['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('additional_costs', $additionalCostData);
        }
    }
    // Step 3: Delete removed additional_costs
    $deletedAdditionalCostIds = array_diff($existingAdditionalCostIds, $submittedAdditionalCostIds);
    if (!empty($deletedAdditionalCostIds)) {
        $this->db->where_in('id', $deletedAdditionalCostIds);
        $this->db->delete('additional_costs');
    }
    echo json_encode(["status" => "success"]);
    exit;
    }
    public function delete_invoice()
    {
        $post = $this->input->post();
         $decoded_id = urldecode($post['id']); // This decodes any URL encoding
        // Step 2: Decode the Base64 string
        $decodedinvoiceid = base64_decode($decoded_id); 
        $data = [
            'del' => 1
        ];
        $del = $this->Common_model->update('invoice', $data, array('id' => $decodedinvoiceid));
        if ($del) {
            echo json_encode(["status" => "success"]);
            exit;
        } else {
            echo json_encode(["status" => "error"]);
            exit;
        }
    }
    public function updateinvoiceold($invoiceid = 0)
    {
    // Example: Getting your POST data
    $post = $this->security->xss_clean($this->input->post());
    $invoice_id = $post['invoiceid'];
    $product_names        = $post['product_name'] ?? [];
    $descriptions         = $post['description'] ?? [];
    $rates                = $post['rate'] ?? [];
    $units                = $post['unit'] ?? [];
    $quantities           = $post['quantity'] ?? [];
    $discounts            = $post['discount_exclusive'] ?? [];
    $amounts              = $post['amount'] ?? [];
    $gsts                 = $post['GST'] ?? [];
    $sgsts                = $post['SGST'] ?? [];
    $cgsts                = $post['CGST'] ?? [];
    $amount_subtotals     = $post['amount_subtotal'] ?? [];
    $invoice__ids         = $post['invoice__id'] ?? [];
    $invoice_details_ids  = $post['invoice_details_id'] ?? [];
    $invoicedata = [
        'payment_history'   => $post['historyData'],
        'paid_amount'       => $post['paid_amount'],
        'balance_amount'     => $post['balance_amount'],
        'additional_cost'   => floatval(str_replace(['₹', ','], '', $post['additional_cost'] ?? 0))
    ];
    $this->db->where('id', $invoice_id);
    $this->db->update('invoice', $invoicedata);
    $this->db->select('id');
    $this->db->from('invoice_details');
    $this->db->where('invoice_id', $invoice_id);
    $query = $this->db->get();
    $existingInvoiceDetailIds = array_column($query->result_array(), 'id');
    // Step 2: Track submitted IDs
    $submittedInvoiceDetailIds = [];
    foreach ($product_names as $index => $product_name) {
        $data = [
            'invoice_id'          => $invoice__ids[$index] ?? '',
            'product_name'        => $product_name,
            'product_description' => $descriptions[$index] ?? '',
            'rate'                => floatval(str_replace(['₹', ','], '', $rates[$index] ?? 0)),
            'unit'                => $units[$index] ?? '',
            'quantity'            => intval($quantities[$index] ?? 0), // Ensuring only int if needed
            'discount'            => floatval(str_replace(['₹', ','], '', $discounts[$index] ?? 0)),
            'amount'              => floatval(str_replace(['₹', ','], '', $amounts[$index] ?? 0)),
            'sub_total'           => floatval(str_replace(['₹', ','], '', $amount_subtotals[$index] ?? 0)),
            'gst_per'             => floatval(str_replace(['₹', ','], '', $gsts[$index] ?? 0)),
            'sgst'                => floatval(str_replace(['₹', ','], '', $sgsts[$index] ?? 0)),
            'cgst'                => floatval(str_replace(['₹', ','], '', $cgsts[$index] ?? 0)),
            'updated_date'        => date('Y-m-d H:i:s')
        ];
        if (!empty($invoice_details_ids[$index])) {
            // Update existing
            $this->db->where('id', $invoice_details_ids[$index]);
            $this->db->update('invoice_details', $data);
            $submittedInvoiceDetailIds[] = $invoice_details_ids[$index];
        } else {
            // Insert new
            $this->db->insert('invoice_details', $data);
        }
    }
    // Step 3: Delete removed items
    $deletedInvoiceDetailIds = array_diff($existingInvoiceDetailIds, $submittedInvoiceDetailIds);
    if (!empty($deletedInvoiceDetailIds)) {
        $this->db->where_in('id', $deletedInvoiceDetailIds);
        $this->db->delete('invoice_details');
    }
    // Step 3: Delete removed items
    $deletedIds = array_diff($existingIds, $submittedIds);
    if (!empty($deletedIds)) {
        $this->db->where_in('id', $deletedIds);
        $this->db->delete('invoice_details');
    }
     $additionalCostIds     = $post['additional_costid'] ?? []; // hidden inputs for IDs
    $serviceProviders      = $post['service_provider'] ?? [];
    $serviceDescriptions   = $post['service_description'] ?? [];
    $serviceQty            = $post['service_qty'] ?? [];
    $serviceRate           = $post['service_rate'] ?? [];
    $serviceTotal          = $post['service_total'] ?? [];
    // Step 1: Get existing IDs from DB
    $this->db->select('id');
    $this->db->from('additional_costs');
    $this->db->where('invoice_id', $invoice_id);
    $query = $this->db->get();
    $existingIds = array_column($query->result_array(), 'id');
    // Step 2: Track submitted IDs
    $submittedIds = [];
    foreach ($serviceProviders as $key => $provider) {
        $costId = $additionalCostIds[$key] ?? null;
        $additionalCostData = [
            'invoice_id'        => $invoice_id,
            'service_provider'  => $provider ?? '',
            'description'       => $serviceDescriptions[$key] ?? '',
            'qty'               => $serviceQty[$key] ?? 0,
            'rate'              => $serviceRate[$key] ?? 0
        ];
        if (!empty($costId)) {
            // Update existing record
            $this->db->where('id', $costId);
            $this->db->update('additional_costs', $additionalCostData);
            $submittedIds[] = $costId;
        } else {
            // Insert new record
            $additionalCostData['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('additional_costs', $additionalCostData);
            // Note: No ID to track here because it's a new insert
        }
    }
    // Step 3: Delete removed entries (in DB but not in form)
    $deletedIds = array_diff($existingIds, $submittedIds);
    if (!empty($deletedIds)) {
        $this->db->where_in('id', $deletedIds);
        $this->db->delete('additional_costs');
    }
     echo json_encode(["status"=>"success"]); exit;
}
  //Index Tabel
   public function getinvoicedata()
    {
    $limit = $this->input->post('length');  // Number of records per page
    $start = $this->input->post('start');   // Starting point for pagination
    $search = $this->input->post('search')['value'];  // Search term
    // Order and filter handling
    $orderField = $this->input->post('columns')[$this->input->post('order')[0]['column']]['data'];
    $orderDirection = $this->input->post("order")[0]["dir"];
    // Date filter and GST filter
    $datefilter = $this->input->post('datefilter');
    $gst_filter = $this->input->post('gst_type');
    $cmpyname = $this->input->post('cmpyname');
    // Date filter logic
    $split = explode(' to ', $datefilter);
    $startDate = date('Y-m-d', strtotime($split[0]));
    $endDate = date('Y-m-d', strtotime($split[1]));
    // Build WHERE conditions based on filters
    $where['invoice.del ='] = 0;
    $where['invoice_date >='] = $startDate;
    $where['invoice_date <='] = $endDate;
    if (!empty($cmpyname)) {
        $where['invoice.company_id'] = $cmpyname;
    }
    if ($gst_filter != 'All' && $gst_filter != null) {
        $where['billtype'] = $gst_filter;
    }
    // Search conditions
    $search_value = [
        'invoice_number' => $search,
        'invoice_date' => $search,
        'customer_name' => $search
    ];
    // Calculate the total rows without limit (for pagination)
    $all_items = $this->Common_model->GetJoinDatasCustom(
    'invoice',
    'customer_information',
    '`invoice`.`customer_id` = `customer_information`.`id`',
    '*, invoice.id as inv_id',
    $where,
    '', '', $search_value
);
    $totalRow = count($all_items);
        $totalRow = count($all_items);
        // Get the actual data with pagination
       $items = $this->Common_model->GetJoinDatasCustom(
        'invoice',
        'customer_information',
        '`invoice`.`customer_id` = `customer_information`.`id`',
        '*, invoice.id as inv_id',
        $where,
        "$orderField $orderDirection",
        '',
        $search_value,
        $limit,
        $start
    );
    $data = [];
    $i = $start + 1;  // For displaying the serial number
    foreach ($items as $item) {
        $company = $this->Common_model->get_row('companies',array('id'=>$item['company_id']));
        $encoded_id = urlencode(base64_encode($item['inv_id']));
        $action = '<div class="hstack gap-2 fs-1">
            <a aria-label="anchor" class="btn btn-icon btn-sm btn-info-light btn-wave waves-effect waves-light send_emailclick" onclick="sendEmail(' . $item['invoice_id'] . ')" data-toggle="tooltip" title="Send Email" data-bs-toggle="modal" data-bs-target="#sendEmailmodal"><i class="ri-mail-send-line"></i></a>
            <a aria-label="anchor" href="edit_invoice/' . $encoded_id . '" class="btn btn-icon btn-sm btn-success-light btn-wave waves-effect waves-light" data-toggle="tooltip" title="Edit"><i class="ri-edit-line"></i></a>
            <a aria-label="anchor" href="invoicepdf/' . $encoded_id . '" class="btn btn-icon btn-sm btn-success-light btn-wave waves-effect waves-light" data-toggle="tooltip" title="PDF"><i class="ri-file-pdf-line"></i></a>
            <a class="btn btn-icon btn-sm btn-danger-light btn-wave waves-effect waves-light delete_invoiceid" data-id="'.$encoded_id.'"data-bs-toggle="tooltip" title="Delete"><i class="ri-delete-bin-5-line"></i></a>
            <input type="hidden" class="delete_id" name="delete_id" value="' . $encoded_id . '">
            <input type="hidden" class="click_emailid" name="click_emailid" value="' . $item['invoice_id'] . '">
        </div>';
        $data[] = [
            "inv_id" => $i,
            "invoice" => $item['invoice_number'],
            "invoice_date" => date('d-m-Y', strtotime($item['invoice_date'])),
            "customer_name" => $item['customer_name'],
            "company_name" => isset($company['companyname']) ? $company['companyname'] : '',
            "gst" => $item['billtype'],
            "grand_total" => '₹' . $item['grand_total'],
            "paid" => '₹' . $item['paid_amount'],
            "balance" => '₹' . $item['balance_amount'],
            "history" => $item['payment_history'],
            'action' => $action,
        ];
        $i++;
    }
    // Prepare the response data
    $response = [
        "draw" => $this->input->post('draw'),
        "recordsTotal" => $totalRow,  // Total number of records (before pagination)
        "recordsFiltered" => $totalRow,  // Filtered records (same as total in this case)
        "data" => $data,  // Paginated data to return to DataTables
    ];
    // Return the response in JSON format
    echo json_encode($response);
    exit;
    }
}