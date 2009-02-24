<?php
class Atom 
{
	public static function marshall($title, $data)
	{
        $output = "<feed xmlns='http://www.w3.org/2005/Atom' xmlns:media='http://search.yahoo.com/mrss/'>\n";
        $output .= "\t<title>$title</title>\n";

        foreach ($data as $track) {
            $output .= "\t<entry>\n";
            $output .= "\t\t<title>${track['title']}</title>\n";
            $output .= "\t\t<media:credit role='author'>${track['artist']}</media:credit>\n";
            $output .= "\t\t<media:content url='${track['file']}' type='audio/mp3' />\n";
            $output .= "\t</entry>\n";
        }

        $output .= "</feed>";
		return $output;
	}
}
?>
