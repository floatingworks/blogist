<div id="results">
<?php
//print_r($this->var['array']);
foreach ($this->var['array'] as $blogentry) {
	$timestamp = date(DATE_RFC822, $blogentry['timeposted']);
	echo "<li><a href=\"?mode=edit&id={$blogentry['id']}\">{$blogentry['title']}</a> posted - {$timestamp}</li>";
}
?>
</div>
