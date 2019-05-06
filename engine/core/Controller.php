<?

namespace Engine\Core;

class Controller
{
	public $view;
	public $Auth;
	
	function __construct()
	{
		$this->Auth = new Auth;
		$this->view = new View;
		$this->view->data_user = $this->Auth;
	}
	
	function view($tpl, $data)
	{
		$this->view->generate($tpl, $data);
	}
}
