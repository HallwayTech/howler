<?php
$q = $_REQUEST['q'];
if (!empty($q)) {
	require "./$q.php";
	index();
} else {
	require "index.html";
}
?>
