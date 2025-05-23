<?php

$this->load->view('pdfs/pdf_head');
$this->load->view($content);
$this->load->view('pdfs/pdf_foot');

?>