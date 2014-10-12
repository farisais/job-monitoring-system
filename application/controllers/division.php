<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Division extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->AllowedUserRole(array('administrator', 'divadministrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('division');
		$crud->set_theme('datatables');
		$crud->set_subject('Division');

		
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
		
		$this->data['title'] = 'JMS | Division';
		$this->data['subtitle'] = 'Division';
		$this->data['content'] = 'Below is the list of the division data';
		
		$this->template->load('default', 'division/index', $this->data);
	}
	
	public function activity()
	{
		$this->AllowedUserRole(array('administrator', 'divadministrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('division_activity');
		$crud->set_theme('datatables');
		$crud->set_subject('Division Activity');
		$crud->set_relation('division', 'division', 'name');
		$crud->field_type('po_delivery_init', 'dropdown', array( 'po' => 'PO', 'delivery' => 'Delivery'));
        //$crud->field_type('pb_default_complete', 'dropdown', array( 'True' => true, 'False' => false));
		$crud->unset_texteditor('name', 'full_text');
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
		
		$this->data['title'] = 'JMS | Division Activity';
		$this->data['subtitle'] = 'Division Activity';
		$this->data['content'] = 'Below is the list of the division data';
		
		$this->template->load('default', 'division/activity', $this->data);
	}
}
?>