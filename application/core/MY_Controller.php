<?php if(!defined('BASEPATH')) exit('No direct script access is allowed');
class MY_Controller extends CI_Controller
{
	var $GlobalUserURL;
	var $DialogForms;
	var $data;
	function __construct()
	{
		parent::__construct();
		$this->setNavigationMenu();
		$this->DialogForms = $this->AttachDialogFormMenu($this->session->userdata('jms_role'));
		$this->data = null;
		$this->data['dialogforms'] = $this->DialogForms;
	}
	
	function setNavigationMenu()
	{
		if($this->session->userdata('jms_username'))
		{
			switch($this->session->userdata('jms_role'))
			{
				case 'administrator':
					$this->template->setNav('navigation','adminnav');
					break;
				case 'divadministrator':
					$this->template->setNav('navigation','divadminnav');
					break;
				case 'standard':
					$this->template->setNav('navigation','standardnav');
					break;
			}
		}
	}
	
	public function redirectUserLogin($job_type) //tidak perlu
	{
		switch($job_type)
		{
			case 'cable_tray':
				redirect('dashboard/cable_tray');
				break;
			case 'panel':
				redirect('dashboard/panel');
				break;
			case 'electrical':
				redirect('dashboard/electrical');
				break;
		}
	}
	
	public function AllowedUserRole($roles)
	{
		$result = false;
		if($this->session->userdata('jms_username'))
		{
			foreach($roles as &$role)
			{
				if($role == 'all')
				{
					$result = true;
					break;	
				}
								
				if($role == $this->session->userdata('jms_role'))
				{
					$result = true;
					break;
				}
			}
		}
		else
		{
			redirect(site_url('welcome'));
		}
		
		if(!$result)
		{
			redirect('errors/access');
		}
	}
	
	public function getUserRole()
	{
		if($this->session->userdata('jms_username'))
		{
			return $this->session->userdata('jms_role');
		}
		else
		{
			return '';
		}
	}
	
	public function ResolveJobType($job_type)
	{
		switch($job_type)
		{
			case 'cable_tray':
				echo 'Cable Tray';
				break;
			case 'panel':
				echo 'Panel';
				break;
			case 'electrical':
				echo 'Electrical';
				break;
		}
	}
	
	public function AttachDialogFormMenu($role)
	{
		
		$dialogforms = array();
		switch($role)
		{
			case 'administrator':
                $dialogforms = array_merge($dialogforms, array(count($dialogforms) => $this->load->view('filter/filter_dialog','', true)));
				$dialogforms = array_merge($dialogforms, array(count($dialogforms) => $this->load->view('job/part/new_jo','', true)));
                
				break;
			case 'divadminsitrator':
                $dialogforms = array_merge($dialogforms, array(count($dialogforms) => $this->load->view('filter/filter_dialog','', true)));
				$dialogforms = array_merge($dialogforms, array(count($dialogforms) => $this->load->view('job/part/new_jo','', true)));
				break;
			case 'standard':
				
				break;
		}
		return $dialogforms;
	}
	
	public function get_status_activity($plan, $actual)
	{
		if(empty($plan) || $plan == null || $plan == '')
		{
			return '0';
		}
		
		if(empty($actual) || $actual == null || $actual == '')
		{
			if(date('Y-m-d', strtotime($plan)) < date('Y-m-d', time()))
			{
				return '3';
			}
			else 
			{
				return '2';
			}
		}
		
		if(strtotime($plan) >= strtotime($actual))
		{
			return '1';
		}
		else if(strtotime($plan) < strtotime($actual))
		{
			return '4';
		}
	}
	
	public function get_status_division($jo, $division)
	{
		$this->db->select('detail_jo_activity.*, division_activity.*, jo.type');
		$this->db->from('detail_jo_activity');
		$this->db->join('division_activity', 'detail_jo_activity.division_activity=division_activity.id_division_activity', 'INNER');
        $this->db->join('jo', 'jo.jo_no=detail_jo_activity.jo_no', 'INNER');
		$this->db->where('division_activity.division', $division);
		$this->db->where('detail_jo_activity.jo_no', $jo);
		
		$divact = $this->db->get()->result_array();
		$result = '0';
		if(count($divact) > 1)
		{
			$result = (($divact[0]['pb_default_complete'] == true && $divact[0]['type'] == 'pb' ) ? '1': $this->get_status_activity($divact[0]['plan'], $divact[0]['actual']));
			if($result == '3')
			{
				return $result;
			}
			else if($result == '2')
			{
				return $result;
			}
			
			for($i=1;$i<count($divact);$i++)
			{
				$status = (($divact[$i]['pb_default_complete'] == true && $divact[$i]['type'] == 'pb' ) ? '1': $this->get_status_activity($divact[$i]['plan'], $divact[$i]['actual']));
				if($status == '3')
				{					
					$result = '3';
					return $result;
				}
				else if($status == '2')
				{
					$result = '2';
					return $result;
				}
				else
				{
					if(intval($status) > intval($result))
					{
						$result = $status;
					}
				}
			}
		}
		else 
		{
			$result = (($divact[0]['pb_default_complete'] == true && $divact[0]['type'] == 'pb' ) ? '1': $this->get_status_activity($divact[0]['plan'], $divact[0]['actual']));
		}
		
		return $result;
	}
	
	public function authorize_general_user()
	{
		if(!$this->session->userdata('jms_userid'))
		{
			return false;
		}
		return true;
	}
	
	public function authorize_division_user($div)
	{
		if($div == $this->session->userdata('jms_div_id'))
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function authorize_admin_user()
	{
		if('1' == $this->session->userdata('jms_role_id'))
		{
			return true;
			
		}
		else
		{
			return false;
		}
	}
	
	public function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
		/*
		 $interval can be:
		yyyy - Number of full years
		q - Number of full quarters
		m - Number of full months
		y - Difference between day numbers
		(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		d - Number of full days
		w - Number of full weekdays
		ww - Number of full weeks
		h - Number of full hours
		n - Number of full minutes
		s - Number of full seconds (default)
		*/
	
		if (!$using_timestamps) {
			$datefrom = strtotime($datefrom, 0);
			$dateto = strtotime($dateto, 0);
		}
		$difference = $dateto - $datefrom; // Difference in seconds
		 
		switch($interval) {
			 
			case 'yyyy': // Number of full years
				$years_difference = floor($difference / 31536000);
				if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
					$years_difference--;
				}
				if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
					$years_difference++;
				}
				$datediff = $years_difference;
				break;
			case "q": // Number of full quarters
				$quarters_difference = floor($difference / 8035200);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$quarters_difference--;
				$datediff = $quarters_difference;
				break;
			case "m": // Number of full months
				$months_difference = floor($difference / 2678400);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$months_difference--;
				$datediff = $months_difference;
				break;
			case 'y': // Difference between day numbers
				$datediff = date("z", $dateto) - date("z", $datefrom);
				break;
			case "d": // Number of full days
				$datediff = floor($difference / 86400);
				break;
			case "w": // Number of full weekdays
				$days_difference = floor($difference / 86400);
				$weeks_difference = floor($days_difference / 7); // Complete weeks
				$first_day = date("w", $datefrom);
				$days_remainder = floor($days_difference % 7);
				$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
				if ($odd_days > 7) { // Sunday
					$days_remainder--;
				}
				if ($odd_days > 6) { // Saturday
					$days_remainder--;
				}
				$datediff = ($weeks_difference * 5) + $days_remainder;
				break;
			case "ww": // Number of full weeks
				$datediff = floor($difference / 604800);
				break;
			case "h": // Number of full hours
				$datediff = floor($difference / 3600);
				break;
			case "n": // Number of full minutes
				$datediff = floor($difference / 60);
				break;
			default: // Number of full seconds (default)
				$datediff = $difference;
				break;
		}
		return $datediff;
	}
    
    
    public function get_latest_seq($jo)
    {
        $this->load->model('division_model');
        return $this->division_model->get_latest_seq($jo);
    }
    
    public function get_init_seq()
    {
        $this->load->model('division_model');

        return $this->division_model->get_initial_seq();
    }
    
    public function get_next_seq($jo, $latest_seq)
    {
        $this->load->model('division_model');
        
        return $this->division_model->get_next_seq($jo, $latest_seq);
    }
}
?>