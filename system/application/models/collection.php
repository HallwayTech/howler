<?php
class Collection extends Model
{
	var $label = null;
	var $collections = null;
	var $entries = null;

	function Collection_entry() {
		parent::Model();
	}

	function read($id) {
		// build the lists of files and dirs
		$output = array();
		$dirs = array();
		$files = array();

		// get a list of dirs and show them
		$path = MUSIC_DIR."/$id";
		$dir_list = scandir($path);

		$this->label = $id;
		foreach ($dir_list as $f) {
			// continue if $f is current directory '.' or parent directory '..'
			if ($f == '.' || $f == '..') {
				continue;
			}

			// determine the file extension
			$path_info = pathinfo($f);
			$dirname = $path_info['dirname']; // /var/www
			$basename = $path_info['basename']; //  index.html
			$filename = $path_info['filename']; //  index

			if (is_dir("$path/$f")) {
				$dirs[] = $f;
			}
			elseif (array_key_exists('extension') && $path_info['extension'] == 'mp3') {
				// get any artist, title, album information found
				list($artist, $title, $album) = id3Info(MUSIC_DIR . "/$id/$f");
				$info = array (
					'f' => utf8_encode($f)
				);
				if (!empty ($artist)) {
					$info['a'] = $artist;
				}
				if (!empty ($title)) {
					$info['t'] = $title;
				}
				if (!empty ($album)) {
					$info['l'] = $album;
				}
				$files[] = $info;
			}
		}
		if (sizeof($dirs) > 0) {
			$output['dirs'] = $dirs;
		}
		if (sizeof($files) > 0) {
			$output['files'] = $files;
		}

		$output['label'] = $id;
	    return $output;
	}

	function findByStartsWith($query) {
	    // build the lists of files and dirs
	    $output['dirs'] = array();
	    $output['files'] = array();

	    if (!empty($query)) {
	        $search = strtolower($query);

	        // get a list of dirs and show them
	        $dir_list = scandir(MUSIC_DIR);
	        foreach ($dir_list as $f) {
	            if ($this->_matches($search, $f)) {
	                // create directory reference by concatenating path and the current
	                // directory listing item
	                $abs_dir = MUSIC_DIR."/$f";
	                $rel_dir = $f;

	                // build the output for a dir
	                if (is_dir($abs_dir)) {
	                    $output['dirs'][] = $f;
	                }
	                else {
	                	$output['files'][] = array('f' => $rel_dir, 'l' => $f);
	                }
	            }
	        }
	    }
	    $output['label'] = $query;
	    return $output;
	}

	/**
	 * Matches the search term to the beginning of the provided filename.  This is done
	 * instead of regex for performance (this is faster).
	 *
	 * @param string $search What to search for.
	 * @param string $f      What to search in.
	 *
	 * @return true if $search starts with $f.  false otherwise.
	 */
	function _matches($search, $f)
	{
	    $f = str_replace('_', '', $f);
	    $f = str_replace('(', '', $f);
	    $f = trim($f);
	    $retval = false;
	    if (!$search) {
	        $retval = true;
	    } elseif ($search == '#' && is_numeric(substr($f, 0, 1))) {
	        $retval = true;
	    } elseif (stripos($f, $search) === 0) {
	        $retval = true;
	    }
	    return $retval;
	}
}

/* End of file collection.php */
/* Location: ./system/application/models/collection.php */