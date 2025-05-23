<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {
	 public $load, $session, $input, $userid;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->helper('common_helper');
    }
	public function index() {
		$this->load->view('sign-in');
		$this->checkCustLogin();
	}
	protected function checkCustLogin()
	{
    $userdata = $this->session->userdata('login_cust_info');
    // Prevent redirect loop for login/logout routes
    if (!empty($userdata)) {
        redirect('home');
        exit;
    }
	}
	public function login() {
		$this->checkCustLogin();
		$this->load->view('sign-in');
	}
	public function login_submit() {
		$post = $this->input->post(); 
		$username = $post['username'];
		$pwd = $post['password'];
		if ($this->security->get_csrf_hash() !== $this->security->get_csrf_hash()) {
        	die('CSRF Token mismatch');
    	}
		$result = $this->Common_model->GetDatas('users', '*', ['username' => $username]); 
		$response = ['status' => 0, 'data' => ['role' => 0], 'alert_msg' => ['word' => 'Invalid Phone Number']];
		if(!empty($result)) {
			$response = ['status' => 0, 'data' => ['role' => 0], 'alert_msg' => ['word' => 'Invalid Password']];
			if($this->encryption->decrypt($result[0]['password']) == $pwd) {
				$this->session->set_userdata('login_cust_info', [
					'cust_name' => $result[0]['name'],
					'cust_id' => $result[0]['id'],
					'cust_email' => $result[0]['email_address'],
					'role' => 'admin'
				]);
				$this->session->set_flashdata('wishes', 'Popup');
				$response = ['status' => 1, 'data' => ['role' => 0], 'alert_msg' => alertMsg('log_suc')];
			}
		}
		echo json_encode($response);
	}
	public function forget_password() {
		$this->load->view('forget_pwd');
	}
	public function logout() {
		$this->session->unset_userdata('login_cust_info');
		redirect('login');
	}
}
