<?php
class Playlist extends Model
{
    /**
     * Read a playlist by the ID.
     *
     * @param $id string The ID to lookup.
     * @return Object
     */
    function read($id)
    {
//        $user = '// figure this out';
//        $doc_id = sha1("$user/$id");
        $this->load->library('rest', array(
            'server' => 'http://127.0.0.1:5984/'
        ));
        $playlist_json = $this->rest->get("/howler/$id");
        $playlist = json_decode($playlist_json, TRUE);
        return $playlist;
	}

	function read_list($user)
	{
	    $this->load->library('rest', array(
	       'server' => 'http://127.0.0.1:5984/'
	    ));
	    $playlists_rest_json = $this->rest->get(
	       '/howler/_design/playlists/_view/by_user?key="'.$user.'"'
	    );
	    $playlists_rest = json_decode($playlists_rest_json, TRUE);
	    $playlists = $playlists_rest['rows'];
	    return $playlists;
	}

	function save($name)
	{
        $playlist_json = $_POST['playlist'];
        // create the filename by prepending the user name to the playlist name

        $doc_id = $this->_generate_id($name);
        // persist the playlist to the doc store
        $this->load->library('rest', array(
            'server' => 'http://localhost:5984/'
        ));
        $message_json = $this->rest->put("/howler/$doc_id", array(
            '_id' => $doc_id, 'title' => $name, 'playlist' => $playlist_json
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
        $fullname = $this->playlists_dir(). "/$name";
        $sha1 = sha1($fullname);
        return $sha1;
    }

    function _playlists_dir()
    {
        $dir = $this->config->item('playlists_dir');
        $user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : false;
        if ($user) {
            $dir .= "/$user";
        }
        return $dir;
    }
}