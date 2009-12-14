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
 * Data access object for collection information.
 * 
 * @category PHP
 * @package  Howler
 * @author   Carl Hall <carl.hall@gmail.com>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.ohloh.net/p/howlerms
 */
class Collection extends Model
{
    const FOLDERS_BY_PREFIX = '_design/folders/_view/by_prefix?startkey="0"&endkey="9"';

    /**
     * Default constructor.
     *
     * @return void
     */
    function Collection()
    {
        parent::Model();
    }

    /**
     * Read a collection and return the names of the content entries.
     *
     * @param string $id The ID of the collection to read.
     *
     * @return array Directories and files associated to a collection.
     */
    function read($id)
    {
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
        $playlists_json = $this->rest->get('_design/folders/_view/by_prefix?startkey="0"&endkey="9"');
        // build the lists of files and dirs
        $dirs = array();
        $files = array();

        // get a list of dirs and show them
        $path = "{$this->config->item('music_dir')}/$id";
        $dir_list = scandir($path);

        foreach ($dir_list as $f) {
            // continue if $f is current directory '.' or parent directory '..'
            if ($f == '.' || $f == '..') {
                continue;
            }

            $entry = "$path/$f";
            if (is_dir($entry)) {
                $dirs[] = array('id' => sha1("$id/$f"), 'label' => $f);
            } elseif (preg_match('/\.mp3$/', $f)) {
                // get any artist, title, album information found
                $tag = @id3_get_tag($entry);

                $info = array();
                $info['id'] = sha1("$id/$f");
                $info['artist'] = ($tag['artist']) ? $tag['artist'] : '';
                $info['title'] = ($tag['title']) ? $tag['title'] : '';
                $info['album'] = ($tag['album']) ? $tag['album'] : '';
                $files[] = $info;
            }
        }

        // construct the output
        $output = array();
        if (sizeof($dirs) > 0) {
            $output['dirs'] = $dirs;
        }
        if (sizeof($files) > 0) {
            $output['files'] = $files;
        }

        $output['label'] = $id;
        $output['back'] = '';
        return $output;
    }

    /**
     * Finds collections by comparing the beginning of a collection name to a
     * query.
     *
     * @param string $query What to look for at the start of a collection's name.
     *
     * @return array An associative array containing 'dirs' and 'files'. If the first
     * found alphanumeric character does not match the requested query, these
     * entries will contain empty arrays.
     */
    function findByStartsWith($query)
    {
        // build the lists of files and dirs
        $data['dirs'] = array();
        $data['files'] = array();

        if (!empty($query)) {
            $search = strtolower($query);

            $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
            $collections_json = $this->rest->get(Collection::FOLDERS_BY_PREFIX.'"'.$query.'"');

            // get a list of dirs and show them
            $path = $this->config->item('music_dir');
            $dir_list = scandir($path);
            foreach ($dir_list as $f) {
                if ($this->_matches($search, $f)) {
                    if (is_dir("$path/$f")) {
                        $data['dirs'][] = array('id' => $f, 'label' => $f);
                    } else {
                        $data['files'][] = array(
                            'id' => $f, 'title' => $f, 'artist' => ''
                        );
                    }
                }
            }
        }
        $data['label'] = $query;
        return $data;
    }

    /**
     * Matches the search term to the beginning of the provided filename.
     *
     * @param string $search What to search for.
     * @param string $f      What to search in.
     *
     * @return true if $search starts with $f.  false otherwise.
     */
    function _matches($search, $f)
    {
        $retval = false;
        if ($search && $f) {
            //	    $f = str_replace('_', '', $f);
            //	    $f = str_replace('(', '', $f);
            //	    $f = trim($f);
            //	    if (!$search) {
            //	        $retval = true;
            //	    } elseif ($search == '0-9' && is_numeric(substr($f, 0, 1))) {
            //	        $retval = true;
            //	    } else
            preg_match("/[a-zA-Z0-9]/", $f, $matches);
            if (!empty($matches)
                && strtoupper($matches[0]) == strtoupper($search)
            ) {
                $retval = true;
            }
        }
        return $retval;
    }
}

/* End of file collection.php */
/* Location: ./system/application/models/collection.php */