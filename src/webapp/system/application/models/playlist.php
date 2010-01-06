<?php
class Playlist extends Model
{
    function Playlist()
    {
        parent::Model();
    }

    function delete($id)
    {
        $where = array('playlist_id' => $id);
        $this->db->trans_start();
        $this->db->delete('playlist_entries', $where);
        $this->db->delete('playlists', $where);
        $this->db->trans_complete();
        $response = $this->db->trans_status();
        return $response;
    }

    function lists($user)
    {
        $query = $this->db->get_where('playlists', array('user_id' => $user));
        $result = false;
        if ($query->num_rows > 0) {
           $result = $query;
        }
        return $result;
    }

    /**
     * Read a playlist by the ID.
     *
     * @param $id string The ID to lookup.
     * @return Object
     */
    function read($id)
    {
        $this->db->select('p.name, e.parent_entry_id, e.label, i.*')
            ->from('playlists p')
            ->join('playlist_entries pe', 'p.playlist_id = pe.playlist_id', 'inner')
            ->join('entries e', 'pe.entry_id = e.entry_id', 'inner')
            ->join('id3 i', 'e.entry_id = i.entry_id', 'left')
            ->where('p.playlist_id', $id);
        $query = $this->db->get();

        if ($query->num_rows > 0) {
            return $query;
        } else {
            return false;
        }
	}

	function save($user_id, $title, $entries)
	{
	    $response = false;

	    if (!empty($user_id) && !empty($title) && is_array($entries)) {
    	    // start a transaction so we don't save a playlist without entries
    	    $this->db->trans_start();

            // create the filename by prepending the user name to the playlist name
            $playlist_id = sha1("$user_id/$title");

            // build the playlist document and insert it
            $playlist = array(
                'playlist_id' => $playlist_id, 'user_id' => $user_id,
                'name' => $title
            );
            $this->db->insert('playlists', $playlist);

            $insert = "insert into playlist_entries (entry_id, playlist_id) values ('";
            $glue = "', '$playlist_id'), ('";
            $values = implode($glue, $entries);

            // be sure to tack on the playlist_id at the end as implode only puts the
            // glue between the entries.
            $sql = $insert.$values."', '$playlist_id')";

            // save to the database
            $this->db->query($sql);

            $this->db->trans_complete();
            $response = $this->db->trans_status();
	    }
        return $response;
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
