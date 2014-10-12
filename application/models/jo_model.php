<?php
class Jo_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function get_all()
	{
		$this->db->select('jo.*, customer.*');
		$this->db->from('jo');
		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
		
		return $this->db->get()->result_array();
	}
	
	public function get_all_job_type($job_type)
	{
	   if(!$this->session->userdata('year') || $this->session->userdata('year') == 'all')
       {		
            $this->db->select('jo.*, customer.*');
    		$this->db->from('jo');
    		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
    		$this->db->where('job_type', $job_type);
        }
        else
        {
            $this->db->select('jo.*, customer.*');
    		$this->db->from('jo');
    		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
    		$this->db->where('job_type', $job_type);
            $this->db->where('YEAR(po_date)', $this->session->userdata('year'));
        }
		
		return $this->db->get()->result_array();
	}
    
    public function get_all_job_in_year($job_type, $year)
    {
        $this->db->select('jo.*, customer.*');
		$this->db->from('jo');
		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
		$this->db->where('job_type', $job_type);
        $this->db->where('YEAR(jo.po_date)', $year);
		
		return $this->db->get()->result_array();
    }
	
	public function get_specific_job_type($job_type, $jo_no, $customer)
	{
		$CI =& get_instance();
		$CI->load->model('customer_model');
		
		if(!empty($customer) || $customer != '')
		{
			$cust = $CI->customer_model->get_customer_from_name($customer);
		}
		
		
		$this->db->select('jo.*, customer.*');
		$this->db->from('jo');
		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
		$this->db->where('job_type', $job_type);
		
		if(isset($cust))
		{
			if(count($cust) == 0)
			{
				$this->db->where('customer', '0');
			}
			else
			{
				$this->db->where('customer', $cust[0]['id_customer']);
			}
		}
		
		if(!empty($jo_no) || $jo_no != '')
		{
			$this->db->where('jo_no', $jo_no);
		}
		
		return $this->db->get()->result_array();
	}
	
	
	public function get_division_status($jo_no)
	{
		$this->db->select('*');
		$this->db->from('detail_jo_status');
		$this->db->where('jo', $jo_no);		
		return $this->db->get()->result_array();
	}
	
	public function insert_jo()
	{
		$this->db->trans_start();
		$field = '';
		if($this->input->post('currency') == 'rp')
		{
			$field = 'nilai_rp';
		}
		else
		{
			$field = 'nilai_us';
		}
		
		$partial_jo = '0';
		if($this->input->post('jo_type') == 'partial')
		{
			$partial_jo = '1';
		}
		
		$plan_date = date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('po_date')))));
		$delivery_date = date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('del_date')))));
		$data = array(
			'jo_no' => $this->input->post('jo_no'),
			'po_no' => $this->input->post('po_no'),
			'po_date' => date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('po_date'))))),
			'delivery_date' => date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('del_date'))))),
			'customer' => $this->input->post('customer'),
			'pekan' => $this->input->post('pekan'),
			$field => $this->input->post('nilai'),
			'id_user' => $this->session->userdata('jms_userid'),
			'job_type' => $this->session->userdata('jms_job_type_id'),
			'partial_jo' => $partial_jo,
            'type' => $this->input->post('type')
		);
		$this->db->insert('jo', $data);
		$last_id = $this->input->post('jo_no');
		
		$this->insert_jo_product($last_id);
		$this->insert_detail_jo($last_id, $plan_date, $delivery_date);

		$this->db->trans_complete();
	}
	
	public function insert_jo_product($jo_no)
	{
		$CI =& get_instance();
		$CI->load->model('division_model');
		foreach(json_decode($this->input->post('products')) as $pro)
		{
			$data = array(
					'jo' => $jo_no,
					'product' => $pro[1],
					'quantity' => $pro[0]
			);
			$this->db->insert('jo_product', $data);
			$last_id = $this->db->insert_id();
			
			if($this->input->post('jo_type') == 'partial')
			{
				$divisions = $CI->division_model->get_all();
				foreach($divisions as $div)
				{
					$activities = $CI->division_model->get_activity($div['id_division']);
					foreach($activities as $act)
					{
						for($i=0;$i<intval($this->input->post('jumlah_pekan'));$i++)
						{
							$data_week = array(
							 	'jo' => $jo_no,
								'week' => ($i + 1),
								'jo_product' => $last_id,
								'division_activity' => $act['id_division_activity']
							);
							$this->db->insert('week_value_entry', $data_week);
						}
					}
				}
			}
		}
	}
	
	public function insert_week_entry($jo_no, $total_week)
	{
		
	}
	
	//Masukan data ke tabel detail_jo_activity
	public function insert_detail_jo($jo_no, $plan_date, $delivery_date)
	{
		$CI =& get_instance();
		$CI->load->model('division_model');
		
		$divisions = $CI->division_model->get_all();
        
		foreach($divisions as $div)
		{
			$activities = $CI->division_model->get_activity($div['id_division']);
            
			foreach($activities as $act)
			{
				$plan = $plan_date;
				if($act['po_delivery_init'] == 'delivery')
				{
					$plan = $delivery_date;
				}
				
				$data = array(
						'jo_no' => $jo_no,
						'division_activity' => $act['id_division_activity'],
						'plan' => $plan,
						'status' => 0,
				);
				//Masukan data status tiap aktivitas di divisi
				$this->db->insert('detail_jo_activity', $data);
			}
			
			//Masukan data status tiap divisi
			//$this->db->insert('detail_jo_status', $data_status);			
		}
	}
	
	public function get_detail_jo($idj, $division)
	{
		
	}
	
	public function create_new_jo($job_type)
	{
		$jo_no = '';
		$this->db->select('*');
		$this->db->from('jo');
		$this->db->order_by('jo_no', 'desc');
		$this->db->where('job_type', $job_type);
		$this->db->not_like('jo_no', 'IO');
		
		$result = $this->db->get()->result_array();
		if(count($result) > 0)
		{
			$jo_no = intval($result[0]['jo_no']) + 1;
		}
		else
		{
			$jo_no = $job_type . substr(date('Y'), 2) . '0001';
		}
		
		return $jo_no;
	}
	
	public function update_jo_detail()
	{
		//$string = '<h1>Update JO ' . $this->input->post('jo_no') . '</h1></br></br>';
		
		foreach(json_decode($this->input->post('input')) as $act)
		{
			//$detail_act = $this->get_detail_activity($act[2]);
			//$string = $string . '<h2>'. $detail_act[0]['name'] .'</h2>';
			$data = array(
					'plan' => (empty($act[0])) ? NULL : date('Y-m-d', strtotime(str_replace('/', '-', $act[0]))),
					'actual' =>(empty($act[1])) ? NULL : date('Y-m-d', strtotime(str_replace('/', '-', $act[1])))
			);
			
			$this->db->where('id_detail_jo_activity', $act[2]);
			$this->db->update('detail_jo_activity', $data);
			
			
			//$string = $string . '<p> Plan from '. $detail_act[0]['plan'] . ' to ' . date('Y-m-d', strtotime(str_replace('/', '-', $act[0]))) . '</p>';
			//$string = $string . '<p> Actual from '. $detail_act[0]['actual'] . ' to ' . date('Y-m-d', strtotime(str_replace('/', '-', $act[1]))) . '</p></br></br>';
		}
		
		//$string = $string . '<p>Updated By: '. $this->session->userdata('jms_fullname') . ' At ' . date('Y-m-d H:i:s', now()) . '</p>';
		
		//return $string;
	}
	
	public function get_detail_activity($detail_activity)
	{
		$this->db->select('detail_jo_activity.*, division_activity.name');
		$this->db->from('detail_jo_activity');
		$this->db->join('division_activity', 'division_activity.id_division_activity=detail_jo_activity.division_activity', 'INNER');
		$this->db->where('id_detail_jo_activity', $detail_activity);
		
		return $this->db->get()->result_array();
	}
	
	public function get_comment_detail($id_detail)
	{
		$this->db->select('comment_jo_detail.*, user.full_name');
		$this->db->from('comment_jo_detail');
		$this->db->join('user', 'user.id_user=comment_jo_detail.user', 'INNER');
		$this->db->where('comment_jo_detail.detail_jo_activity', $id_detail);
		
		return $this->db->get()->result_array();
	}
	
	public function update_comment($id_detail, $comment)
	{
		$data = array(
				'detail_jo_activity' => $id_detail,
				'comment' => $comment,
				'user' => $this->session->userdata('jms_userid'),
				'time' => date('Y-m-d H:i:s', now())
		);
		
		$this->db->insert('comment_jo_detail', $data);
		return $this->db->insert_id();
	}
	
	public function upload_image_comment($id_comment, $filename)
	{
		$data = array(
				'comment_jo_detail' => $id_comment,
				'filepath' => $filename
		);
		
		$this->db->insert('comment_attachment', $data);
	}
	
	public function delete_comment($id_comment)
	{
		$this->db->where('id_comment_jo_detail', $id_comment);
		$this->db->delete('comment_jo_detail');
	}
	
	public function get_jo_product($jo_no)
	{
		$this->db->select('jo_product.*, product.name');
		$this->db->from('jo_product');
		$this->db->join('product', 'product.id_product=jo_product.product', 'INNER');
		$this->db->where('jo', $jo_no);
		$this->db->order_by('jo_product.product', 'asc');
		
		return $this->db->get()->result_array();
	}
	
	public function delete_jo($jo_no)
	{
		$this->db->where('jo_no', $jo_no);
		$this->db->delete('jo');
	}
	
	public function get_comment_attachment($comment)
	{
		$result = array();
		foreach($comment as $com)
		{
			$this->db->select('*');
			$this->db->from('comment_attachment');
			$this->db->where('comment_jo_detail', $com['id_comment_jo_detail']);
			array_push($result, $this->db->get()->result_array());
		}
		return $result;
	}
	
	public function get_comment_attachmentById($id_comment)
	{
		$this->db->select('*');
		$this->db->from('comment_attachment');
		$this->db->where('comment_jo_detail', $id_comment);
		
		return $this->db->get()->result_array();
	}
	
	public function edit_jo($jo)
	{
		$this->db->select('*');
		$this->db->from('jo');
		$this->db->where('jo_no', $jo);
		
		return $this->db->get()->result_array();
	}
    
    public function update_jo($jo_no)
    {
        $this->db->trans_start();
		$field = '';
		if($this->input->post('currency') == 'rp')
		{
			$field = 'nilai_rp';
		}
		else
		{
			$field = 'nilai_us';
		}
		
		$partial_jo = '0';
		if($this->input->post('jo_type') == 'partial')
		{
			$partial_jo = '1';
		}
		
		$plan_date = date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('po_date')))));
		$delivery_date = date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('del_date')))));
		$data = array(
			'po_no' => $this->input->post('po_no'),
			'po_date' => date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('po_date'))))),
			'delivery_date' => date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('del_date'))))),
			'customer' => $this->input->post('customer'),
			'pekan' => $this->input->post('pekan'),
			 $field => $this->input->post('nilai'),
			'id_user' => $this->session->userdata('jms_userid'),
			'job_type' => $this->session->userdata('jms_job_type_id'),
			'partial_jo' => $partial_jo,
            'type' => $this->input->post('type')
		);
        $this->db->where('jo_no', $jo_no);
		$this->db->update('jo', $data);
		
        $this->delete_jo_product($jo_no);
		$this->insert_jo_product($jo_no);
 
		$this->update_delivery_date($jo_no, $delivery_date);

		$this->db->trans_complete();
    }
    
    public function delete_jo_product($jo_no)
    {
        $this->db->where('jo', $jo_no);
        $this->db->delete('jo_product');
    }
    
    public function update_delivery_date($jo_no, $delivery_date)
    {
        $data_update = array(
            'plan' => $delivery_date
        );
        
        $div_act_update = $this->get_division_po_delivery_init('delivery');

        foreach($div_act_update as $divact)
        {
            $this->db->where('division_activity', $divact['id_division_activity']);
            $this->db->where('jo_no', $jo_no);
            $this->db->update('detail_jo_activity', $data_update);
        }
    }
    
    public function get_division_po_delivery_init($status)
    {
        $this->db->select('*');
        $this->db->from('division_activity');
        $this->db->where('po_delivery_init', $status);
        
        return $this->db->get()->result_array();
    }
    
    public function apply_filter($data_post)
    {
        if(!$this->session->userdata('year') || $this->session->userdata('year') == 'all')
       {		
            $this->db->select('jo.*, customer.*');
    		$this->db->from('jo');
    		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
    		$this->db->where('job_type', $this->session->userdata('jms_job_type_id'));
        }
        else
        {
            $this->db->select('jo.*, customer.*');
    		$this->db->from('jo');
    		$this->db->join('customer', 'customer.id_customer=jo.customer', 'INNER');
    		$this->db->where('job_type', $this->session->userdata('jms_job_type_id'));
            $this->db->where('YEAR(po_date)', $this->session->userdata('year'));
        }

        $i = 0;
        foreach($data_post as $data)
        {
            
            $field = $data['field'];
            $value = $data['value'];
            $condition = $data['condition'];
            
            if($field == 'po_date' || $field == 'delivery_date')
            {
                $value = date('Y-m-d', strtotime(str_replace(' ','',str_replace('/', '-', $value))));
                //exit($value);
            }
            
            if($i == 0)
            {
                $this->db->where($field . ' ' .html_entity_decode($condition), $value);
                //exit($value);
            }
            else
            {
                if($data['relation'] == 'OR')
                {
                    $this->db->or_where($field . ' ' .html_entity_decode($condition) , $value);
                }
                else
                {
                    $this->db->where($field . ' ' .html_entity_decode($condition) , $value);
                }
            }
            $i++;
        }
        
        return $this->db->get()->result_array();
    }
}
?>