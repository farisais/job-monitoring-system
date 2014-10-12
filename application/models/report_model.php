<?php
class Report_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_jo_summary_data()
	{
		$this->db->select('jo.*, customer.*');
		$this->db->from('jo');
		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
		$this->db->where('job_type', $job_type);
		
		return $this->db->get()->result_array();
	}
	
	public function get_jo_currency($job_type, $curr, $year)
	{
		$this->db->select('jo.*, customer.*');
		$this->db->from('jo');
		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
		$this->db->where('job_type', $job_type);
        $this->db->where('YEAR(po_date)', $year);
		$this->db->where('nilai_' . $curr . ' >', 0);
		
		return $this->db->get()->result_array();
	}
}
?>