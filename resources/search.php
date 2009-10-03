<?php
function search_read($id)
{
    $retval = false;

    // build the lists of files and dirs
    $output['d'] = array();
    $output['f'] = array();

    if (!empty($id)) {
        $search = strtolower($id);

        // get a list of dirs and show them
        $dir_list = scandir(MUSIC_DIR);
        foreach ($dir_list as $f) {
            if (matches($search, $f)) {
                // create directory reference by concatenating path and the current
                // directory listing item
                $abs_dir = MUSIC_DIR."/$f";
                $rel_dir = $f;

                // build the output for a dir
                if (is_dir($abs_dir)) {
                    $output['d'][] = array('d' => $rel_dir, 'l' => $f);
                }
            }
        }
        $retval = array(200, $output);
    }
    return $retval;
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
function matches($search, $f)
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
?>
