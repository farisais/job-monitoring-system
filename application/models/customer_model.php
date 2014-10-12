<?php
class Customer_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_all()
	{
		$this->db->select('*');
		$this->db->from('customer');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function get_customer_from_name($name)
	{
		$this->db->select('*');
		$this->db->from('customer');
		$this->db->where('name', $name);
		
		return $this->db->get()->result_array();
	}
}
?>