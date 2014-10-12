<?php
class Product_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_from_job($job_type)
	{
		$this->db->select('*');
		$this->db->from('product');
		$this->db->where('job_type', $job_type);
		$this->db->order_by('name', 'asc');
		
		return $this->db->get()->result_array();
	}
}