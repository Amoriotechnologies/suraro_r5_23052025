<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Custom_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	public function get_states() {
		$this->db->select('*');
		$this->db->from('states');
		$this->db->order_by('name', 'ASC');
		return $this->db->get()->result();
	}
	public function get_latest_invoices_per_company() {
	$query = $this->db->query("
		SELECT *
		FROM invoice inv
		JOIN (
			SELECT 
				SUBSTRING_INDEX(invoice_number, '-', 1) AS shortname,
				MAX(CAST(SUBSTRING_INDEX(invoice_number, '-', -1) AS UNSIGNED)) AS max_suffix
			FROM invoice
			GROUP BY shortname
		) latest ON 
			SUBSTRING_INDEX(inv.invoice_number, '-', 1) = latest.shortname AND
			CAST(SUBSTRING_INDEX(inv.invoice_number, '-', -1) AS UNSIGNED) = latest.max_suffix
	");
	return $query->result();
}
public function get_latest_expenses_per_company() {
	$query = $this->db->query("
		SELECT *
		FROM expenses exp
		JOIN (
			SELECT 
				SUBSTRING_INDEX(expense_number, '-', 1) AS shortname,
				MAX(CAST(SUBSTRING_INDEX(expense_number, '-', -1) AS UNSIGNED)) AS max_suffix
			FROM expenses
			GROUP BY shortname
		) latest ON 
			SUBSTRING_INDEX(exp.expense_number, '-', 1) = latest.shortname AND
			CAST(SUBSTRING_INDEX(exp.expense_number, '-', -1) AS UNSIGNED) = latest.max_suffix
	");
	return $query->result();
}
}