<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Email extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->AllowedUserRole(array('administrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('email_notification');
		$crud->set_theme('datatables');
		$crud->set_subject('Email');
		$crud->set_rules('email', 'Email', 'required|valid_email');
		$crud->unset_texteditor('email', 'full_text');
		/*if($this->getUserRole() == 'divadministrator')
		{
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_delete();
		}*/
		try
		{
			$this->data['output'] = $crud->render();
		}
		catch (Exception $ex)
		{
			redirect('errors/access');
		}
		
		$this->data['title'] = 'JMS | Email';
		$this->data['subtitle'] = 'Email';
		$this->data['content'] = 'Below is the list of the email address data';
		
		$this->template->load('default', 'email/index', $this->data);
	}
}
?>
