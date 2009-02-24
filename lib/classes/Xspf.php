<?php
class Xspf 
{
	public static function marshall($title, $data)
 	{
        $output = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $output .= "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
        $output .= "\t<title>$title</title>\n";
        $output .= "\t<trackList>\n";

        foreach ($data as $track) {
            $output .= "\t\t<track>\n";
            $output .= "\t\t\t<title>${track['title']}</title>\n";
            $output .= "\t\t\t<album>${track['album']}</album>\n";
            $output .= "\t\t\t<creator>${track['artist']}</creator>\n";
            $output .= "\t\t\t<location>${track['file']}</location>\n";
            $output .= "\t\t</track>\n";
        }

        $output .= "\t</trackList>\n";
        $output .= "</playlist>\n";
		return $output;
	}
}
?>
