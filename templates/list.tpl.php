<div id="results">
<?php
//print_r($this->var['array']);
foreach ($this->var['array'] as $blogentry) {
	echo "<li><a href=\"?mode=edit&id={$blogentry['id']}\">{$blogentry['title']}</a></li>";
}
?>
</div>
