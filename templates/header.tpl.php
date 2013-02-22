<?php

if ((isset($_SESSION['id']) && !empty($_SESSION['id']))) {
	echo "You are logged in as " . $_SESSION['id'];
	echo " <a href=\"?mode=logout\">log out</a>";
} else {
	echo "Please log in or <a href=\"?mode=register\">register</a>";
}

?>
