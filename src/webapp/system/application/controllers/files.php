<?php
class Files extends Controller
{
    function Files()
    {
        parent::Controller();
    }

    function read($id)
    {
        $this->load->library('rest', array('server' => $this->config->item('couchdb_server')));
        $entry_json = $this->rest->get($id);
        $entry = json_decode($entry_json, true);

        $filename = urldecode($this->config->item('music_dir') . "/{$entry['file']}");
        $filename = stripslashes($filename);

        if (!is_readable($filename)) {
            header("Status: 404 Not Found");
            print "Unable to find the requested file [$filename]";
        } else {
            $basename = basename($filename);
            $filesize = filesize($filename);

            header("Content-Disposition: attachment; filename=".urlencode($filename));
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