<?php
class Welcome extends Controller
{
	function Welcome()
	{
		parent::Controller();	
	}

	function index()
	{
		// build alpha numeric navigation
        $alpha_nav = array();
        $alpha_nav[] = array('#', '0-9');
        for ($i = ord('a'), $size = ord('z'); $i <= $size; $i++) {
            $alpha_nav[] = chr($i);
        }
        $data['alpha_nav'] = $alpha_nav;

        $data['random'] = $this->config->item('default_random');

		$repeats = array(
			'NONE' => 'Repeat None',
			'SONG' => 'Repeat Song',
			'LIST' => 'Repeat List'
		);
		$data['repeats'] = $repeats;
        $data['repeat'] = $this->config->item('default_repeat');

		$this->load->view('welcome_message', $data);
	}
}


/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */