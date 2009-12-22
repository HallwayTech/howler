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
    /**
     * Default constructor.
     *
     * @return void
     */
    function Collection()
    {
        parent::Model();
    }

    function read($id)
    {
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
        $collection = $this->rest->get($id, null, 'json');
        return $collection;
    }

    /**
     * Read a collection and return the names of the content entries.
     *
     * @param string $id The ID of the collection to read.
     *
     * @return array Directories and files associated to a collection.
     */
    function byParent($id)
    {
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));

        // get the title
        $title_doc = $this->rest->get($id, null, 'json');
        $title = $title_doc->label;

        // get the listing information
        $prefixes = $this->rest->get(
            "_design/collections/_view/by_parent?startkey=[\"$id\"]&endkey=[\"$id\",\"Z\"]",
            null, 'json'
        );

        // build the lists of files and dirs
        $dirs = array();
        $files = array();

        foreach($prefixes->rows as $row) {
            $info = array('id' => $row->id);
            $key = $row->key;
            if ($key[1] == 'd') {
                $info['label'] = $key[2];
                $dirs[] = $info;
            } elseif ($key[1] == 'f') {
                $value = $row->value;
                $artist = $value->artist;
                $title = $value->title;
                if ($artist && $title) {
                    $info['label'] = "$artist - $title";
                } else {
                    $info['label'] = $value->file;
                }
                $files[] = $info;
            }
        }

        // construct the output
        $output = array(
            'label' => $title, 'dirs' => $dirs, 'files' => $files
        );
        if (!empty($title_doc->parent)) {
            $output['parent'] = $title_doc->parent; 
        }
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
    function startsWith($query)
    {
        // build the lists of files and dirs
        $dirs = array();
        $files = array();

        if (!empty($query)) {
            $search = strtolower($query);
            $startkey = $search;
            $endkey = $search;
            if ($query == '0-9') {
                $startkey = '0';
                $endkey = '9';
            }

            $this->load->library('rest', array(
                'server' => $this->config->item('couchdb_server'))
            );
            $collections = $this->rest->get(
                "_design/collections/_view/by_prefix?startkey=[\"$startkey\",\"d\"]&endkey=[\"$endkey\",\"f\"]",
                null, 'json'
            );

            foreach($collections->rows as $collection) {
                $key = $collection->key;
                if ($key[1] == 'd') {
                    $dirs[] = array('id' => $collection->id, 'label' => $key[2]);
                } else {
                    $value = $collection->value;
                    $files[] = array(
                        'id' => $collection->id, 'title' => $value->title,
                        'artist' => $value->artist, 'album' => $value->album
                    );
                }
            }
        }
        $data['label'] = $query;
        $data['dirs'] = $dirs;
        $data['files'] = $files;
        return $data;
    }
}

/* End of file collection.php */
/* Location: ./system/application/models/collection.php */