<?php
/**
 * Howler is a web based media server for streaming MP3 files.
 *
 * PHP version 5
 *
 * @category PHP
 * @package  Howler
 * @author   Carl Hall <carl.hall@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.ohloh.net/p/howlerms
 */

/**
 * Welcome page controller for the Howler application.
 *
 * @category PHP
 * @package  Howler
 * @author   Carl Hall <carl.hall@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.ohloh.net/p/howlerms
 */
class Welcome extends Controller
{
    /**
     * Default constructor.
     * 
     * @return void
     */
    function Welcome()
    {
        parent::Controller();
    }

    /**
     * Default method for the controller. Gathers data for the welcome page then
     * loads the welcome page view.
     * 
     * @return void
     */
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