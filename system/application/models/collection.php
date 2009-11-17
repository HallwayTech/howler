<?php
class Collection extends Model
{
	function Collection() {
		parent::Model();
	}

	function read($id) {
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
			}
			elseif (preg_match('/\.mp3$/', $f)) {
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

	function findByStartsWith($query) {
	    // build the lists of files and dirs
	    $data['dirs'] = array();
	    $data['files'] = array();

	    if (!empty($query)) {
	        $search = strtolower($query);

	        // get a list of dirs and show them
	        $path = $this->config->item('music_dir');
	        $dir_list = scandir($path);
	        foreach ($dir_list as $f) {
	            if ($this->_matches($search, $f)) {
	                if (is_dir("$path/$f")) {
	                    $data['dirs'][] = array('id' => $f, 'label' => $f);
	                }
	                else {
	                	$data['files'][] = array('id' => $f, 'title' => $f, 'artist' => '');
	                }
	            }
	        }
	    }
	    $data['label'] = $query;
	    return $data;
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
	    } elseif ($search == '0-9' && is_numeric(substr($f, 0, 1))) {
	        $retval = true;
	    } elseif (stripos($f, $search) === 0) {
	        $retval = true;
	    }
	    return $retval;
	}
}

/* End of file collection.php */
/* Location: ./system/application/models/collection.php */