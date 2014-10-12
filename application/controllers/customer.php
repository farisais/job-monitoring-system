<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Customer extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->AllowedUserRole(array('administrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('customer');
		//$crud->set_theme('datatables');
		$crud->set_subject('Customer');
		$crud->unset_jquery();
		$crud->unset_jquery_ui();
		$crud->unset_texteditor('name', 'full_text');
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
		
		$this->data['title'] = 'JMS | Customer';
		$this->data['subtitle'] = 'Customer';
		$this->data['content'] = 'Below is the list of the customer data';
		
		$this->template->load('default', 'customer/index', $this->data);
	}
}
?>
