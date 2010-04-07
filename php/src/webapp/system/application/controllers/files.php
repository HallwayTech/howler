<?php
class Files extends Controller
{
    function Files()
    {
        parent::Controller();
    }

    function read($id)
    {
        $query = $this->db->query("select url from entries where entry_id = '$id'");

        if ($query->num_rows == 0) {
            header("Status: 204 No Content");
            return;
        }

        $entry = $query->row();
        $filename = urldecode($this->config->item('music_dir') . "/{$entry->url}");
        $filename = stripslashes($filename);

        if (!is_readable($filename)) {
            header("Status: 404 Not Found");
            print "Unable to find the requested file [$filename]";
        } else {
            $basename = basename($filename);
            $filesize = filesize($filename);

            header("Content-Disposition: attachment; filename=\"{$entry->url}\"");
            header("Content-Type: audio/mpeg");
            header("Content-Length: $filesize");
            header("Content-Transfer-Encoding: binary");
            header("Pragma: no-cache");
            header("Expires: 0");

            $file = @fopen($filename,"rb");
            if ($file) {
                while (!feof($file)) {
                    print fread($file, 1024 * 8);
                    flush();
                    if (connection_status() != 0) {
                        break;
                    }
                }
                @fclose($file);
            }
        }
    }
}