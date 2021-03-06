<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		if($this->session->userdata('jms_userid'))
		{
			$this->redirectUserLogin($this->session->userdata('jms_job_type'));
		}
		else
		{
			$this->data['login_msg'] = '';
			if($this->session->userdata('login_state') == 'failed')
			{
				$msg = '<font color=red>Invalid username and/or password.</font>';
				$this->data['login_msg'] = $msg;
			}
			$this->session->unset_userdata('login_state');
			$this->load->view('welcome', $this->data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */