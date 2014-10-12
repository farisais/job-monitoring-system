<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('division_model');
		$this->load->model('customer_model');
		$this->load->model('jo_model');
		$this->load->model('product_model');
		
		if(!$this->session->userdata('jms_userid'))
		{
			redirect(base_url(''));
		}
		
		if(!$this->session->userdata('grid_load'))
		{
			$this->session->set_userdata('grid_load', '');
		}
		
		if(!$this->session->userdata('customer_filter'))
		{
			$this->session->set_userdata('customer_filter', '');
		}
		
		if(!$this->session->userdata('jo_filter'))
		{
			$this->session->set_userdata('jo_filter', '');
		}
        
        $this->session->set_userdata('is_dialog_set', false);
        $this->session->set_userdata('page', 'dahsboard/index');
	}
	
	public function index()
	{
		switch($this->session->userdata('jms_job_type'))
		{
			case 'cable_tray':
				redirect(base_url('dashboard/cable_tray'));
				break;
			case 'panel':
				redirect(base_url('dashboard/panel'));
				break;
			case 'electrical':
				redirect(base_url('dashboard/electrical'));
				break;
			default:
				redirect(base_url());
				break;
		}
	}
	
	public function cable_tray()
	{
		if($this->session->userdata('jms_job_type') != 'cable_tray')
		{
			$this->session->set_userdata('customer_filter', '');
			$this->session->set_userdata('jo_filter', '');
			$this->session->set_userdata('grid_load', '');
		}
		
		$this->session->set_userdata('jms_job_type', 'cable_tray');
		$this->session->set_userdata('jms_job_type_id', '1');
		
		$this->data['title'] = 'JMS | Dashboard';
		$this->data['subtitle'] = 'Cable Tray Dashboard';
		$this->data['content'] = 'Gunakan filter dibawah untuk mengekstrak data JO';
		
        
		$this->init_default('Cable Tray', '1');
		
		$this->template->load('default', 'dashboard/index',$this->data);
	}	
	
	public function panel()
	{
		if($this->session->userdata('jms_job_type') != 'panel')
		{
			$this->session->set_userdata('customer_filter', '');
			$this->session->set_userdata('jo_filter', '');
			$this->session->set_userdata('grid_load', '');
		}
		
		$this->session->set_userdata('jms_job_type', 'panel');
		$this->session->set_userdata('jms_job_type_id', '2');
		
		$this->data['title'] = 'JMS | Dashboard';
		$this->data['subtitle'] = 'Panel Dashboard';
		$this->data['content'] = 'Gunakan filter dibawah untuk mengekstrak data JO';
        
		$this->init_default('Panel', '2');
		
		$this->template->load('default', 'dashboard/index',$this->data);
	}
	
	public function electrical()
	{
		if($this->session->userdata('jms_job_type') != 'electrical')
		{
			$this->session->set_userdata('customer_filter', '');
			$this->session->set_userdata('jo_filter', '');
			$this->session->set_userdata('grid_load', '');
		}
		
		$this->session->set_userdata('jms_job_type', 'electrical');
		$this->session->set_userdata('jms_job_type_id', '3');
		
		$this->data['title'] = 'JMS | Dashboard';
		$this->data['subtitle'] = 'Electrical Dashboard';
		$this->data['content'] = 'Gunakan filter dibawah untuk mengekstrak data JO';
        
		$this->init_default('Electrical', '3');
		
		$this->template->load('default', 'dashboard/index',$this->data);
	}
	
	public function init_default($job_type_string, $job_type_id)
	{
		$this->data['divisions'] = $this->division_model->get_all();
		$this->data['customers'] = $this->customer_model->get_all();
		$this->data['products'] = $this->product_model->get_from_job($job_type_id);
		$this->data['jo'] = $this->jo_model->get_all_job_type($job_type_id);
		$this->data['jo_model'] = $this->jo_model;
		$this->data['myclass'] = $this;
		$dialogforms['new_jo'] = $this->load->view('job/part/new_jo', $this->data, true);
		$this->data['dialogforms'] = $dialogforms;
	}

     public function seq()
    {
        $this->db->select('*');
        $this->db->from('division_activity');
        $divact = $this->db->get()->result_array();
        
        for($i=0;$i<count($divact);$i++)
        {
            $data = array(
                'seq' => $i
            );
            
            $this->db->where('id_division_activity', $divact[$i]['id_division_activity']);
            $this->db->update('division_activity', $data);
        }
    }
}
?>