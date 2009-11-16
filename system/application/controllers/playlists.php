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
		$data = array();

		$playlists = array();
		$dir = scandir($this->playlists_dir());
		if ($dir) {
			foreach ($dir as $file) {
				if ($file != '.' && $file != '..') {
					$playlists[] = $file;
				}
			}

			$data['playlists'] = $playlists;
		} else {
			$data = 'Unable to open playlists folder for user.';
		}
		$output = $this->load->view('playlists', $data);
		echo $output;
	}

	/**
	 * Get a specific playlist from a user's space.
	 *
	 * @param name The name of the playlist to retrieve.
	 */
	function read($name)
	{
		$output = '';

		$filename = $this->build_filename($name);
		$data = '[]';
		if (is_readable($filename)) {
			$file = fopen($filename, 'r');
			$data = fread($file, filesize($filename));
			fclose($file);
			$output = $this->load->view('playlists/html', array('title' => $name, 'playlist' => json_decode($data, TRUE)));
		} else {
			$output = "File is not readable: ${filename}";
		}
		echo $output;
	}

	/**
	 * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
	 * it is updated.
	 *
	 * @param name The name of the playlist to save.
	 */
	function save($name)
	{
		$message = '';
		/* TODO process playlist using dom
		 * $doc = new DOMDocument();
		 * $doc->loadHTML("<html><body>Test<br></body></html>");
		 * echo $doc->saveHTML();
		 * extract id, artist, title, album
		 * persist to database.
		 */
		$playlist = $_POST['playlist'];
		// create the filename by prepending the user name to the playlist name

		if (!empty($playlist) && is_writeable($this->playlists_dir())) {
			$filename = $this->build_filename($name);
			// persist the playlist to file
			$file = fopen($filename, 'w');
			fwrite($file, stripslashes($playlist));
			fclose($file);
		} else {
			$message = 'Unable to write playlist.';
		}
		echo $message;
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
	function build_filename($name)
	{
		$filename = $this->playlists_dir(). "/${name}";
		return $filename;
	}

	function playlists_dir()
	{
		$dir = $this->config->item('playlists_dir');
		$user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : false;
		if ($user) {
			$dir .= "/$user";
		}
		return $dir;
	}
}