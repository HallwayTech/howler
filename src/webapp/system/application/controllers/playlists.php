<?php
class Playlists extends Controller
{
    function Playlists()
    {
        parent::Controller();
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

    /**
     * Delete a specific playlist in the logged in user's space.
     *
     * @param name The name of the playlist to be deleted.  The name is resolved to
     *             the user's space.
     */
    function delete($id)
    {
        $this->load->helper('request_method');

        if (is_method_allowed(DELETE)) {
            $this->load->model('playlist');
            $response = $this->playlist->delete($id);
            if ($response === false) {
                $msg = "Unable to delete playlist [$id]";
                log_message('error', $msg);
                show_error($msg);
            }
        }
    }

    function generate($count)
    {
        $this->load->model('collection');
        $files = $this->collection->random($count);
        foreach ($files->rows as $entry) {
            $this->load->view('playlist_item', $entry->doc);
        }
    }

    /**
     * Get the names of all playlists available to the current logged in user.
     */
    function index()
    {
        $user = $this->_current_user();
        $this->load->model('playlist');
        $playlists = $this->playlist->lists($user);
        if ($playlists) {
            $this->load->view('playlists', array('query' => $playlists));
        } else {
            set_status_header(204);
        }
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
        if ($playlist) {
            foreach ($playlist->result() as $entry) {
                $this->load->view('playlist_item', $entry);
            }
        } else {
            set_status_header(204);
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
            // get the current user
            $user_id = $this->_current_user();

            // get the submitted playlist
            $playlist_param = $this->input->post('playlist');
            $playlist = explode(',', $playlist_param);

            // load the playlist model
            $this->load->model('playlist');

            // save the playlist
            $response = $this->playlist->save($user_id, $name, $playlist);
            if ($response === false) {
                $msg = 'Could not save playlist.';
                log_message('error', $msg);
                show_error($msg);
            }
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