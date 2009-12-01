<?php
class Playlists extends Controller
{
	function Playlists()
	{
		parent::Controller();
	}

	/**
	 * Get the names of all playlists available to the current logged in user.
	 */
	function index()
	{
//		$data = array();
//
//		$playlists = array();
//		$dir = scandir($this->_playlists_dir());
//		if (!empty($dir)) {
//			foreach ($dir as $file) {
//				if ($file != '.' && $file != '..') {
//					$playlists[] = $file;
//				}
//			}
//
//			$data['playlists'] = $playlists;
//		} else {
//			$data = 'Unable to open playlists folder for user.';
//		}

	    $user = $this->_current_user();

	    $playlist_dao = $this->load->model('playlist');
	    $playlists = $playlist_dao->read_list($user);

		$output = $this->load->view('playlists', $playlists);
		echo $output;
	}

	/**
	 * Get a specific playlist from a user's space.
	 *
	 * @param name The name of the playlist to retrieve.
	 */
	function read($name)
	{
        $playlist_dao = $this->load->model('playlist');
        $playlist = $playlist_dao->read($name);
        $output = $this->load->view('playlists/html', array(
            'title' => $playlist['title'],
            'playlist' => json_decode($playlist['playlist'], TRUE)
        ));
	}

	/**
	 * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
	 * it is updated.
	 *
	 * @param name The name of the playlist to save.
	 */
	function save($name)
	{
	    $playlist_dao = $this->load->model('playlist');
        $response = $playlist_dao->save($name);
        if (!array_key_exists($response, 'ok')) {
            echo $response;
        }
	}

	/**
	 * Delete a specific playlist in the logged in user's space.
	 *
	 * @param name The name of the playlist to be deleted.  The name is resolved to
	 *			 the user's space.
	 */
	function delete($name)
	{
		$output = '';
	
		$filename = $this->build_filename($name);
		error_log("Deleting $filename");

		if (is_writeable($filename)) {
			if (unlink($filename)) {
				$response_code = 204;
			} else {
				$response_code = 422;
				$output = "Unable to unlink '$filename': $unlinked";
			}
		} else {
			$response_code = 422;
			$output = "File '$filename' is not writeable.";
		}
	
		error_log("Delete: $response_code - $output");
		echo $output;
	}
	//===================================================================
	//   utility functions
	//===================================================================
	/**
	 * Build the filename to a specific playlist.  The filename is built in relation
	 * to the user's space.  The filename is not verified to exist.
	 *
	 * @param name The name of the playlist to work with.
	 */
	function _build_filename($name)
	{
		$filename = $this->_playlists_dir(). "/${name}";
		return $filename;
	}

	function _playlists_dir()
	{
		$dir = $this->config->item('playlists_dir');
		$user = $this->_current_user();
		if (!empty($user)) {
			$dir .= "/$user";
		}
		return $dir;
	}

	function _current_user()
	{
	    $user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : false;
	    return $user;
	}
}