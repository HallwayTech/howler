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

    /**
     * Read a collection and return the names of the content entries.
     *
     * @param string $id The ID of the collection to read.
     *
     * @return array Directories and files associated to a collection.
     */
    function byParent($id)
    {
        $this->db->select("p.parent_entry_id, p.label as 'title', e.entry_id, e.label, e.type")
            ->from('entries p')
            ->join('entries e', 'p.entry_id = e.parent_entry_id', 'inner')
            ->where('p.entry_id', $id)
            ->order_by('e.url');
        $query = $this->db->get();
        $result = $query->result();

        $title = null;
        $parent_entry_id = null;

        // build the lists of files and dirs
        $dirs = array();
        $files = array();

        foreach($result as $row) {
            if ($title == null) {
                $title = $row->title;
            }
            if ($parent_entry_id == null && !empty($row->parent_entry_id)) {
                $parent_entry_id = $row->parent_entry_id;
            }
            $info = array('id' => $row->entry_id, 'label' => $row->label);
            if($row->type == 'd') {
                $dirs[] = $info;
            } elseif ($row->type == 'f') {
                $files[] = $info;
            }
        }

        // construct the output
        $output = array(
            'id' => $id, 'label' => $title, 'dirs' => $dirs, 'files' => $files
        );
        if (!empty($parent_entry_id)) {
            $output['parent'] = $parent_entry_id; 
        } else {
            preg_match('/[a-zA-Z0-9]/', $title, $matches);
            if ($matches) {
                $output['search'] = $matches[0];
            }
        }
        return $output;
    }

    function random($count)
    {
        $this->db->select('e.label, e.url, i.*')
            ->from('entries e')
            ->join('id3 i', 'i.entry_id = e.entry_id', 'left')
            ->where("type = 'f'")
            ->order_by("rand()")
            ->limit($count);
        $query = $this->db->get();
        return $query;
    }

    function read($id)
    {
        $this->db->select('e.*, i.artist, i.album, i.title')
            ->from('entries e')
            ->join('id3 i', 'e.entry_id = i.entry_id', 'left')
            ->where('e.entry_id', $id);
        $query = $this->db->get();
        return $query->row();
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
    function startsWith($search)
    {
        $this->db->select('entry_id, label, type')
            ->from('entries')
            ->where('parent_entry_id is null')
            ->where("prefix REGEXP '[$search]'")
            ->order_by('url');
        $query = $this->db->get();

        // build the lists of files and dirs
        $dirs = array();
        $files = array();

        if ($query->num_rows() > 0) {
            foreach($query->result() as $row) {
                if ($row->type == 'd') {
                    $dirs[] = array('id' => $row->entry_id, 'label' => $row->label);
                }
            }
        }
        $data['label'] = strtoupper($search);
        $data['dirs'] = $dirs;
        return $data;
    }
}

/* End of file collection.php */
/* Location: ./system/application/models/collection.php */