<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Product extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->AllowedUserRole(array('administrator'));
		$crud = new grocery_CRUD();
		$crud->set_table('product');
		$crud->set_theme('datatables');
		$crud->set_subject('Product');
		$crud->set_relation('job_type', 'job_type', 'name');
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
		
		$this->data['title'] = 'JMS | Product';
		$this->data['subtitle'] = 'Product';
		$this->data['content'] = 'Below is the list of the product data';
		
		$this->template->load('default', 'product/index', $this->data);
	}
}
?>
