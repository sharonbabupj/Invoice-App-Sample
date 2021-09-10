<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InvoiceController extends CI_Controller {


	public function index() {
		$this->load->view('invoice_create');
	}

	public function get_invoice_data() {
		$data = [];
		$data['name'] = json_decode($this->input->post('item_name'));
		$data['qty_arr'] = json_decode($this->input->post('qty_arr'));
		$data['unit_price_arr'] = json_decode($this->input->post('unit_price_arr'));
		$data['tax_arr'] = json_decode($this->input->post('tax_arr'));
		$data['line_total_arr'] = json_decode($this->input->post('line_total_arr'));
		$data['total_without_tax'] = json_decode($this->input->post('total_without_tax'));
		$data['total_with_tax'] = json_decode($this->input->post('total_with_tax'));
		$data['discount'] = json_decode($this->input->post('discount'));
		$data['total'] = json_decode($this->input->post('total'));
		$this->load->view('invoice_view',$data);
		
	}


}
