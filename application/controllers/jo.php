<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Jo extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('division_model');
		$this->load->model('jo_model');
		$this->load->model('customer_model');
		$this->load->model('product_model');
	}
	
	public function get()
	{
		
	}
	
	public function get_detail()
	{
		$row = $this->input->post('row');
		$result = 'Ini adalah baris ke-'. $row;
		$this->data['result'] = $result;
		
		$this->data['jo_product'] = $this->jo_model->get_jo_product($this->input->post('jo_no'));
		$this->data['divisions'] = $this->division_model->get_all();
		$this->data['current_division'] = $this->data['divisions'][0]['id_division'];
		$this->data['division_activity'] = $this->division_model->get_detail_jo($this->input->post('jo_no'), $this->data['current_division']);
		$this->data['jo_no'] = $this->input->post('jo_no');
		$this->data['jo_model'] = $this->jo_model;
		$this->data['myclass'] = $this;
		
		$this->load->view('dashboard/part/jo_modal', $this->data);
	}
	
	public function insert_jo()
	{
		$this->AllowedUserRole(array('administrator'));
		$this->jo_model->insert_jo();
		$this->data['jo'] = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
		$this->reload_grid_all();
	}
	
	//source : {jo_modal => $(".division-navigator").click()}
	public function get_division_detail()
	{
		$this->data['jo_product'] = $this->jo_model->get_jo_product($this->input->post('jo_no'));
		$this->data['division_activity'] = $this->division_model->get_detail_jo($this->input->post('jo_no'), $this->input->post('division'));
		$this->data['current_division'] = $this->input->post('division');
		$this->data['jo_model'] = $this->jo_model;
		$this->data['myclass'] = $this;
		$this->load->view('dashboard/part/division_activity_table', $this->data);
	}
	
	public function check_button_update()
	{
		$data['myclass'] = $this;
		$data['current_division'] = $this->input->post('division');
		$this->load->view('dashboard/part/button_update_wrapper', $data);
	}
	
	public function create_jo_no()
	{
		$jo_no = $this->jo_model->create_new_jo($this->session->userdata('jms_job_type_id'));
		echo $jo_no;
	}
	
	//source : {division_activity_table => $("#update-jo").click()}
	public function update_jo_detail()
	{
		$this->data['jo_product'] = $this->jo_model->get_jo_product($this->input->post('jo_no'));
		$this->jo_model->update_jo_detail();
		$this->data['division_activity'] = $this->division_model->get_detail_jo($this->input->post('jo_no'), $this->input->post('division'));
		$this->data['current_division'] = $this->input->post('division');
		$this->data['jo_model'] = $this->jo_model;
		$this->data['myclass'] = $this;
		$this->load->view('dashboard/part/division_activity_table', $this->data);
	}
	
	public function reload_grid()
	{
		if($this->session->userdata('grid_load') == 'all')
		{
			$this->reload_grid_all();
		}
		else
		{
			if($this->session->userdata('customer_filter') == '' && $this->session->userdata('jo_filter') == '')
			{
				$this->hide_grid();
			}
			else
			{
				$this->reload_grid_specific();
			}
		}
	}
    
    public function apply_year()
    {
        $this->session->set_userdata('year', $this->input->post('year'));
        
   	    $this->session->set_userdata('grid_load', 'all');
        
        if($this->session->userdata('is_filtered') == true)
        {
            $data = $this->session->userdata('filter');
            $this->data['jo'] = $this->jo_model->apply_filter($data);
        }
        else
        {
            $this->data['jo'] = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
        }
		$this->init_grid_default();
    }
    
	public function reload_grid_all()
	{
		$this->session->set_userdata('grid_load', 'all');
        
        if($this->session->userdata('is_filtered') == true)
        {
            $data = $this->session->userdata('filter');
            $this->data['jo'] = $this->jo_model->apply_filter($data);
        }
        else
        {
            $this->data['jo'] = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
        }
		$this->init_grid_default();
	}
	
	public function reload_grid_specific()
	{
		$this->session->set_userdata('grid_load', '');
		
		if($this->input->post('filter_customer'))
		{
			$this->session->set_userdata('customer_filter', $this->input->post('filter_customer'));
		}
		
		if($this->input->post('filter_jo_no'))
		{
			$this->session->set_userdata('jo_filter', $this->input->post('filter_jo_no'));
		}
		
		$this->data['jo'] = $this->jo_model->get_specific_job_type($this->session->userdata('jms_job_type_id'), 
				$this->session->userdata('jo_filter'), $this->session->userdata('customer_filter'));
		
		$this->init_grid_default();
	}
	
	public function hide_grid()
	{
		$this->session->set_userdata('customer_filter', '');
		$this->session->set_userdata('jo_filter', '');
		$this->session->set_userdata('grid_load', '');
		echo '';
	}
	
	public function init_grid_default()
	{
		$this->data['customers'] = $this->customer_model->get_all();
		$this->data['divisions'] = $this->division_model->get_all();
		$this->data['products'] = $this->product_model->get_from_job('4');
		$this->data['jo_model'] = $this->jo_model;
		$this->data['myclass'] = $this;
		$dialogforms['new_jo'] = $this->load->view('job/part/new_jo', $this->data, true);
        $dialogforms['filter'] = $this->load->view('filter/filter_dialog', $this->data, true);
		$this->data['dialogforms'] = $dialogforms;
		$this->load->view('dashboard/part/grid', $this->data);
        $this->session->set_userdata('is_dialog_set', true);
	}
	
	public function load_comment()
	{
		$data['comments'] = $this->jo_model->get_comment_detail($this->input->post('id_detail_jo_activity'));
		$data['detail_comments'] = $this->jo_model->get_comment_attachment($data['comments']);
		$data['id_detail'] = $this->input->post('id_detail_jo_activity');
		$this->load->view('dashboard/part/comment_wrapper', $data);
	}
	
	public function update_comment()
	{
		$id_comment = $this->jo_model->update_comment($this->input->post('detail_jo_activity_comment'), $this->input->post('comment_text'));
		$data['comments'] = $this->jo_model->get_comment_detail($this->input->post('detail_jo_activity_comment'));
		$data['id_detail'] = $this->input->post('detail_jo_activity_comment');
		
		//exit(print_r($_FILES));
		if(isset($_FILES['userfile']['name']))
		{
			$count = count($_FILES['userfile']['name']);
			
			$uploads = $_FILES['userfile'];
			
			for($i=0;$i<$count;$i++)
			{
				if($uploads['error'][$i] == 0)
				{
					$filename = $this->resolve_image_name($this->resolve_image_type($uploads['type'][$i]), $id_comment, $i);
					copy($uploads['tmp_name'][$i], $_SERVER['DOCUMENT_ROOT'] . '/images/upload/' . $filename);
					$this->jo_model->upload_image_comment($id_comment, $filename);
				}
			}
		}
		$data['detail_comments'] = $this->jo_model->get_comment_attachment($data['comments']);
		$this->load->view('dashboard/part/comment_wrapper', $data);
	}
	
	public function resolve_image_type($type)
	{
		$result = '';
		switch($type)
		{
			case 'image/jpeg':
				$result = '.jpeg';
				break;
			case 'image/png':
				$result = '.png';
				break;
			case 'image/jpg':
				$result = '.jpg';
				break;
		}
		
		return $result;
	}
	
	public function resolve_image_name($type, $id_comment, $inc)
	{
		$result = $inc . '_' .$this->session->userdata('jms_userid') . '_' . $id_comment . '_' . date('Y-m-d_H-i-s') . $type;
		return $result; 
	}
	
	public function reload_comment()
	{
		
	}
	
	public function delete_comment()
	{
		$attachment = $this->jo_model->get_comment_attachmentById($this->input->post('id_comment'));
		if(count($attachment) > 0)
		{
			foreach($attachment as $att)
			{
				unlink($_SERVER['DOCUMENT_ROOT'] . '/images/upload/' . $att['filepath']);
			}
		}
		if($this->input->post('id_comment'))
		{
			$this->jo_model->delete_comment($this->input->post('id_comment'));
		}
		$this->load_comment();
	}
	
	public function calculate_weeks()
	{
		$po_date = strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('po_date'))));
		$day_po = date('l', $po_date);
		$del_date = strtotime(str_replace(' ','',str_replace('/', '-', $this->input->post('delivery_date'))));
		$day_delivery = date('l', $del_date);
		$less = 0;
		switch(strtolower($day_po))
		{
			case 'friday':
				$less += 3;
				break;
			case 'saturday':
				$less += 2;
				break;
			case 'sunday':
				$less += 1;
				break;
		}
		
		switch(strtolower($day_delivery))
		{
			case 'sunday':
				$less += 2;
				break;
			case 'saturday':
				$less += 1;
				break;
		}

		echo ceil(((($del_date - $po_date)/86400) - $less)/7);
	}
	
	public function delete_jo()
	{
		$this->jo_model->delete_jo($this->input->post('jo_no_delete'));
	}
	
	public function edit_jo()
	{
		$result = array(
			'jo' => $this->jo_model->edit_jo($this->input->post('jo_no')),
			'product' => $this->jo_model->get_jo_product($this->input->post('jo_no'))
		);
		echo json_encode($result);
	}
    
    public function update_jo()
    {
        $this->AllowedUserRole(array('administrator'));
        if($this->input->post('action') == 'edit')
        {
            $this->jo_model->update_jo($this->input->post('jo_no'));
    		$this->data['jo'] = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
    		$this->reload_grid_all();
        }
    }
    
    public function filter_jo()
    {
        $data_post = $this->input->post();
   	    
        if($data_post != null)
        {
            $this->data['jo'] = $this->jo_model->apply_filter($data_post);
            $this->session->set_userdata('is_filtered', true);
            $this->session->set_userdata('filter', $data_post);
        }
        else
        {
            if($this->session->userdata('is_filtered') == true)
            {
                $this->session->set_userdata('is_filtered', false);
                $this->session->unset_userdata('filter');
            }
            $this->data['jo'] = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
        }
        
        //exit($this->session->userdata('page'));
        if(strpos($this->session->userdata('page'), $this->session->userdata('page')) !== false)
        {
            $this->init_grid_default();
        }
        else
        {
            redirect(base_url() . $this->session->userdata('page'));
        }
        
    }
}
?>