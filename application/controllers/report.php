<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Report extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('division_model');
		$this->load->model('jo_model');
		$this->load->model('report_model');
	}
	
	public function index()
	{
		$this->data['title'] = 'JMS | Report';
		$this->data['subtitle'] = 'Report for JMS';
		$this->data['divisions'] = $this->division_model->get_all();
		$this->template->load('default', 'report/index', $this->data);
	}
	
	public function load_total()
	{
		$this->data['currency'] = $this->input->post('currency');
		$this->data['total_jo'] = $this->calculate_total_jo($this->input->post('currency'), $this->uri->segment(4));
        $this->data['year'] = $this->uri->segment(4);
		$this->load->view('report/total_wrapper', $this->data);
	}
    
   	public function load_total_ajax()
	{
		$this->data['currency'] = $this->input->post('currency');
		$this->data['total_jo'] = $this->calculate_total_jo($this->input->post('currency'), $this->input->post('year'));
        $this->data['year'] = $this->input->post('year');
		$this->load->view('report/total_wrapper', $this->data);
	}
	
	public function cummulative()
	{
	   $this->session->set_userdata('page', 'report/cummulative');
		$this->data['title'] = 'JMS | Report';
		$this->data['subtitle'] = 'Cummulative Report ' . $this->get_job_type_name($this->session->userdata('jms_job_type'));
		$this->data['divisions'] = $this->division_model->get_all();
		$this->template->load('default', 'report/cummulative', $this->data);
	}
	
	public function summary($currency = 9000)
	{
		$this->data['title'] = 'JMS | Report';
		$this->data['subtitle'] = 'Summary Report ' . $this->get_job_type_name($this->session->userdata('jms_job_type'));
		$this->data['divisions'] = $this->division_model->get_all();
		$this->data['currency'] = $currency;
		$this->data['total_jo'] = $this->calculate_total_jo($currency, $this->uri->segment(4));
		$this->template->load('default', 'report/summary', $this->data);
	}
    
    public function incremental()
    {
        $this->data['title'] = 'JMS | Report';
		$this->data['subtitle'] = 'Incremental Report ' . $this->get_job_type_name($this->session->userdata('jms_job_type'));
		$this->data['divisions'] = $this->division_model->get_all();
		$this->template->load('default', 'report/incremental', $this->data);
    }
	
	public function get_nilai_us()
	{
		$result = array();
		$jo = $this->report_model->get_jo_currency($this->session->userdata('jms_job_type_id'), 'us', $this->uri->segment(3));
		$i=0;
		for($in=1;$in<13;$in++)
		{
			$nilai_sum = 0;
			foreach($jo as $j)
			{
				$month = date('m', strtotime($j['po_date']));
				if($month == $in)
				{
					$nilai_sum += $j['nilai_us'];
				}
			}
			$result[$i] = array('month' => $in, 'nilai' => $nilai_sum / 1000);
			$i++;
		}
		
		echo json_encode($result);
	}
	
	public function get_nilai_rp()
	{
		$result = array();
		$jo = $this->report_model->get_jo_currency($this->session->userdata('jms_job_type_id'), 'rp', $this->uri->segment(3));
		$i=0;
		for($in=1;$in<13;$in++)
		{
			$nilai_sum = 0;
			foreach($jo as $j)
			{
				$month = date('m', strtotime($j['po_date']));
				if($month == $in)
				{
					$nilai_sum += $j['nilai_rp'];
				}
			}
			$result[$i] = array('month' => $in, 'nilai' => $nilai_sum / 1000000);
			$i++;
		}
		
		echo json_encode($result);
	}
	
	public function calculate_total_jo($currency, $year)
	{
		$result = array();
		$jo = $this->jo_model->get_all_job_in_year($this->session->userdata('jms_job_type_id'), $year);
		$nilai_sum = 0;
		$i=0;
		for($in=1;$in<13;$in++)
		{
			foreach($jo as $j)
			{
				$month = date('m', strtotime($j['po_date']));
				if($month == $in)
				{
					if($j['nilai_rp'] > 0)
					{
						$nilai_sum += $j['nilai_rp'];
					}
					else
					{
						$nilai_sum += $j['nilai_us'] * $currency;
					}
				}
			}
			$result[$i] = array('month' => $in, 'nilai' => $nilai_sum / 1000000);
			$i++;
		}
		return $nilai_sum;
	}
	
	public function get_nilai_total($currency)
	{
		$result = array();
		$jo = $this->jo_model->get_all_job_in_year($this->session->userdata('jms_job_type_id'),  $this->uri->segment(4));
		
		$i=0;
		for($in=1;$in<13;$in++)
		{
			$nilai_sum = 0;
			foreach($jo as $j)
			{
				$month = date('m', strtotime($j['po_date']));
				if($month == $in)
				{
					if($j['nilai_rp'] > 0)
					{
						$nilai_sum += $j['nilai_rp'];
					}
					else
					{
						$nilai_sum += $j['nilai_us'] * $currency;
					}
				}
			}
			$result[$i] = array('month' => $in, 'nilai' => $nilai_sum / 1000000);
			$i++;
		}
		
		echo json_encode($result);
	}
	
	public function get_data_pie($id_division)
	{
		$result = array();
		$divisions = $this->division_model->get_all();
        if($this->session->userdata('is_filtered') == true)
        {
            $jo = $this->jo_model->apply_filter($this->session->userdata('filter'));
        }
        else
        {
            $jo = $this->jo_model->get_all_job_type($this->session->userdata('jms_job_type_id'));
        }
		$ontime = 0;
		$delayed = 0;
		$closed_late = 0;
        $on_progress = 0;
		foreach($jo as $j)
		{			
			switch($this->get_status_division($j['jo_no'], $id_division))
			{
				case '0':
					break;
				case '1':
					$ontime++;
					break;
				case '2':
                    $on_progress++;
					break;
				case '3':
					$delayed++;
					break;
				case '4':
					$closed_late++;
					break;
			}
		}
		
		$result_data[0] = array('status' => 'ontime', 'value' => $ontime);
		$result_data[1] = array('status' => 'delayed', 'value' => $delayed);
		$result_data[2] = array('status' => 'closed_late', 'value' => $closed_late);
        $result_data[3] = array('status' => 'on_progress', 'value' => $on_progress);
		echo json_encode($result_data);
	}
	
	public function get_job_type_name($jt)
	{
		$result = '';
		switch($jt)
		{
			case 'cable_tray':
				$result = 'Cable Tray';
				break;
			case 'panel':
				$result = 'Panel';
				break;
			case 'electrical':
				$result = 'Electrical';
				break;
		}
		return $result;
	}
    
    public function get_incremental_jo_data()
    {   
        $result = array();
        $jo = $this->jo_model->get_all_job_in_year($this->session->userdata('jms_job_type_id'), $this->uri->segment(4));
        $time_query = '';
        switch($this->uri->segment(3))
        {
            case 'week':
                $time_domain_limit = 53;
                $time_query = 'W';
                break;
            case 'month':
                $time_query = 'm';
                $time_domain_limit = 12;
                break;
            default:
                echo 'Wrong input time domain';
                break;
        }
        
        for($i=0;$i< $time_domain_limit ;$i++)
        {
            $qty = 0;
            foreach($jo as $j)
            {
                if($i + 1 == date($time_query, strtotime($j['po_date'])))
                {
                    $qty++;
                }
            }
            $result[$i] = array('time' => $i + 1, 'qty' => $qty);
        }
        echo json_encode($result);
    }
    
    public function init_result_array_incremental($type)
    {
        $result = array();
        $time_domain = null;
        switch($type)
        {
            case 'week':
                $time_domain = date("W", mktime(0,0,0,12,31,date('Y')));
                break;
            case 'month':
                $time_domain = 12;
                break;
        }
        
        for($i=1;$i<= $time_domain ;$i++)
        {
            $result[$i] = array('time' => $i, 'qty' => 0);
        }
        return $result;
    }
}
?>