<?php
#!/usr/bin/php
ini_set('memory_limit', '64M');

define('ROOT', '/home/chall/Music');

function walkdir($dir, &$entries) {
    $dir_key = sha1($dir);

    $nodes = @scandir(ROOT."/$dir");
    if ($nodes !== false) {
        foreach ($nodes as $node) {
            if (substr($node, 0, 1) == '.') {
                continue;
            }

            $entry = "$dir/$node";
            while (substr($entry, 0, 1) == '/') {
                $entry = substr($entry, 1);
            }
            $full_entry = ROOT."/$entry";
            $entry_key = sha1($entry);

            if (is_dir($full_entry)) {
                $dir_entry = array(
                    '_id' => $entry_key, 'type' => 'directory', 'label' => $entry
                );
                if (!empty($dir)) {
                    $dir_entry['parent'] = $dir_key; 
                }
                $entries[] = $dir_entry;
                walkdir($entry, $entries);
            } elseif (is_file($full_entry)) {
                $file_entry = array(
                    '_id' => $entry_key, 'type' => 'file', 'file' => $entry
                );
                if (!empty($dir)) {
                    $file_entry['parent'] = $dir_key; 
                }
//                $id3 = id3_get_tag($full_entry);
                $entries[] = $file_entry;
            }
        }
    }

//    print_r($entries);
}

$entries = array();
walkdir('', $entries);
$json = json_encode($entries);
$file = fopen('music.json', 'w');
fwrite($file, $json);
fclose($file);
