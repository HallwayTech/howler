<?php
class Playlist extends Model
{
    const SERVER = 'http://127.0.0.1:5984/howler';
    const PLAYLISTS_BY_USER = '_design/playlists/_view/by_user';

    function Playlist()
    {
        parent::Model();
    }

    /**
     * Read a playlist by the ID.
     *
     * @param $id string The ID to lookup.
     * @return Object
     */
    function read($id)
    {
        $this->load->library('rest', array('server' => Playlist::SERVER));
        $playlist_json = $this->rest->get($id);
        $playlist = json_decode($playlist_json, TRUE);
        return $playlist;
	}

	function lists($user)
	{
	    $this->load->library('rest', array('server' => Playlist::SERVER));
	    $playlists_json = $this->rest->get(Playlist::PLAYLISTS_BY_USER.'?startkey="'.$user.'"');
	    $playlists = json_decode($playlists_json, TRUE);
	    return $playlists;
	}

	function save($name, $data)
	{
        // create the filename by prepending the user name to the playlist name
        $doc_id = $this->_generate_id($name);
        // persist the playlist to the doc store
        $this->load->library('rest', array('server' => Playlist::SERVER));
        $message_json = $this->rest->put($doc_id, array(
            '_id' => $doc_id, 'user_id' => $this->_user(), 'type' => 'playlist',
            'title' => $name, 'playlist' => $data
        ));
        $message = json_decode($message_json, TRUE);
        return $message;
    }

    //===================================================================
    //   utility functions
    //===================================================================
    /**
     * Generate the ID for a specific playlist.  The ID is constructed by hashing
     * the user's path and requested name of the playlist. The ID is not verified to
     * be unused.
     *
     * @param name The name of the playlist to work with.
     */
    function _generate_id($name)
    {
        $fullname = $this->_user(). "/$name";
        $sha1 = sha1($fullname);
        return $sha1;
    }

    function _playlists_dir()
    {
        $dir = $this->config->item('playlists_dir');
        $user = $this->_user();
        if ($user) {
            $dir .= "/$user";
        }
        return $dir;
    }

    function _user()
    {
        $user = false;
        if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
            $user =  $_SERVER['PHP_AUTH_USER'];
        }
        return $user;
    }
}