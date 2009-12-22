<?php
class Playlist extends Model
{
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
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
        if (is_array($id)) {
            $playlists = array('keys' => $id);
            $playlist_json = json_encode($playlists);
            $playlists = $this->rest->post(
                '_all_docs?include_docs=true', $playlist_json, 'json'
            );
            $playlist = $playlists->rows;
        } else {
            $playlist = $this->rest->get($id, null, 'json');
        }
        return $playlist;
	}

	function delete($id)
	{
	    $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
	    $message = $this->rest->delete($id, 'json');
        return $message;
	}

	function lists($user)
	{
	    $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
	    $playlists_json = $this->rest->get("_design/playlists/_view/by_user?startkey=[\"$user\"]&endkey=[\"$user\",\"\u9999\"]");
	    $playlists = json_decode($playlists_json, TRUE);
	    unset($playlists['total_rows']);
	    unset($playlists['offset']);
	    return $playlists;
	}

	function save($user_id, $title, $playlist)
	{
        // create the filename by prepending the user name to the playlist name
        $doc_id = sha1("$user_id/$title");
        $doc = array(
            '_id' => $doc_id, 'user_id' => $user_id, 'type' => 'playlist',
            'public' => true, 'title' => $title, 'playlist' => $playlist
        );
        $doc_json = json_encode($doc);
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
        $message = $this->rest->put($doc_id, $doc_json, 'json');
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
    private function _generate_id($name)
    {
        $fullname = $this->_user(). "/$name";
        $sha1 = sha1($fullname);
        return $sha1;
    }

    private function _playlists_dir()
    {
        $dir = $this->config->item('playlists_dir');
        $user = $this->_user();
        if ($user) {
            $dir .= "/$user";
        }
        return $dir;
    }

    private function _user()
    {
        $user = false;
        if (array_key_exists('PHP_AUTH_USER', $_SERVER)) {
            $user =  $_SERVER['PHP_AUTH_USER'];
        }
        return $user;
    }
}