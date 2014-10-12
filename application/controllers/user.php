<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->AllowedUserRole(array('administrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('user');
		$crud->set_theme('datatables');
		
		$crud->set_subject('User');
		$crud->unset_texteditor('full_name', 'full_text');
		$crud->set_relation('division', 'division', 'name');
		$crud->set_relation('role', 'role', 'name');
		$crud->required_fields('user_name', 'password', 'full_name','email','role');
		$crud->set_rules('email', 'Email', 'required|valid_email');
		if($this->getUserRole() == 'divadministrator')
		{
			$crud->unset_add();
			$crud->unset_edit();
			$crud->unset_delete();
		}
		
		try
		{
			$this->data['output'] = $crud->render();
		}
		catch (Exception $ex)
		{
			redirect('errors/access');
		}
		
		$this->data['title'] = 'JMS | User';
		$this->data['subtitle'] = 'User';
		$this->data['content'] = 'Below is the list of the user data';
		
		$this->template->load('default', 'user/index', $this->data);
	}
	
}