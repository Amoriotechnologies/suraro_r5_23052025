<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* Home Controller*/
$route['home'] = 'Home';
$route['dashboard'] = 'Home';
$route['getDashboardData'] = 'Home/getDashboardData';

$route['invoice'] = 'Invoice/invoice';
$route['getinvoicedata'] = 'Invoice/getinvoicedata';
$route['add_invoice'] = 'Invoice/add_invoice';
$route['edit_invoice/(:any)'] = 'Invoice/add_invoice/$1';
$route['invoicepdf/(:any)'] = 'Invoice/pdfinvoice/$1';

$route['expense'] = 'Expense/expense';
$route['getexpense'] = 'Expense/getexpense';
$route['add_expense'] = 'Expense/add_expense';
$route['edit_expense/(:any)'] = 'Expense/add_expense/$1';
$route['view_expense/(:any)'] = 'Expense/add_expense/$1';
$route['viewexpense/(:any)/(:any)'] = 'Expense/add_expense/$1/$2';

$route['proforma_invoice'] = 'Home/proforma_invoice';
$route['add_proforma_invoice'] = 'Home/add_proforma_invoice';
$route['add_proforma_invoice/(:any)'] = 'Home/add_proforma_invoice/$1';

$route['customers'] = 'Customer';
$route['add_customer'] = 'Customer/add_customer';
$route['customer_table'] = 'Customer/customer_table';
$route['editcustomer/(:any)'] = 'Customer/add_customer/$1';

$route['logout'] = 'Login/logout';
$route['login'] = 'Login/login';
$route['login_submit'] = 'Login/login_submit';
$route['forget_password'] = 'Login/forget_password';

/* Pdf Controller*/
$route['GetAppPdf'] = 'Pdf/GetAppPdf';
$route['GetAppPdf/(:any)'] = 'Pdf/GetAppPdf/$1';

$route['default_controller'] = 'Home';
$route['404_override'] = 'error404.html';
$route['translate_uri_dashes'] = FALSE;