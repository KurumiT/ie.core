<?

namespace Engine\Core;

class View
{
	public $data_arr = Array();
	public $data_user = Array();
	public $settings = Array();
	
	public function generate($tpl, $data = null)
	{
		if($data != -1)
		{
			$data['User'] = (object)$this->data_user->data;
			$data['Settings'] = $this->settings;
			$this->data_arr = $data;
		}

        $tpl = 'views/'.$tpl.'.html';
        
		extract($this->data_arr, EXTR_PREFIX_SAME, "wddx");
		require_once($tpl);
	}

	function tpl_init($tpl)
	{
		$this->generate($tpl, -1);
	}
}