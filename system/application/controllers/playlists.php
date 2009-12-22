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
	    $user = $this->_current_user();
	    $this->load->model('playlist');
	    $playlists = $this->playlist->lists($user);
		$this->load->view('playlists', $playlists);
	}

	/**
	 * Get a specific playlist from a user's space.
	 *
	 * @param name The name of the playlist to retrieve.
	 */
	function read($id)
	{
        $this->load->model('playlist');
        $playlist = $this->playlist->read($id);
        $playlist_entries = $this->playlist->read($playlist->playlist);
        foreach ($playlist_entries as $entry) {
            $this->load->view('playlist_item', $entry->doc);
        }
	}

	/**
	 * Save a playlist.  If the playlist doesn't exist, it is created.  Otherwise,
	 * it is updated.
	 *
	 * @param name The name of the playlist to save.
	 */
	function save($name)
	{
	    $this->load->helper('request_method');

	    if (is_method_allowed(array(PUT, POST))) {
	       $user_id = $this->_current_user();
	       $playlist_param = $this->input->post('playlist');
	       $playlist = json_decode($playlist_param);
	       $this->load->model('playlist');
            $response = $this->playlist->save($user_id, $name, $playlist);
            if (!empty($response->error)) {
                $response_json = json_encode($response);
                log_message('error', $response_json);
                show_error($response_json);
            }
	    }
	}

	/**
	 * Delete a specific playlist in the logged in user's space.
	 *
	 * @param name The name of the playlist to be deleted.  The name is resolved to
	 *			 the user's space.
	 */
	function delete($id, $rev)
	{
	    $this->load->helper('request_method');

	    if (is_method_allowed(DELETE)) {
	        $this->load->model('playlist');
            $response = $this->playlist->delete("$id?rev=$rev");
            if (!empty($response->error)) {
                $response_json = json_encode($response);
            log_message('error', $response_json);
            show_error($response_json);
            }
        }
	}

	function addEntry($id)
	{
        $this->load->model('collection');
        $entry = $this->collection->read($id);
        $this->load->view('playlist_item', $entry);
	}

	function addParent($parentId)
	{
        $this->load->model('collection');
	    $entries = $this->collection->byParent($parentId);
        foreach ($entries['files'] as $file) {
            $this->addEntry($file['id']);
        }
        foreach ($entries['dirs'] as $dir) {
            $this->addParent($dir['id']);
        }
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