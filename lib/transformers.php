<?php
function marshall_atom($title, $data)
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

function marshall_xspf($title, $data)
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
?>
