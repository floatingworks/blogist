<form action=<?php echo $_SERVER['PHP_SELF']; ?> method='post'>
	Title
	<input type="text" name= "title" id="title" value="<?php !isset($this->var['title']) ? print '' : print $this->var['title'];?>"><br />
	Content<br />
	<textarea rows="20" cols="50" name="blogcontent" id="blogcontent"><?php !isset($this->var['blogcontent']) ? print '' : print $this->var['blogcontent'];?></textarea><br />
	last edited: <?php $timestamp = date(DATE_RFC822, $this->var['timeposted']); print $timestamp . "<br />";?>
	<input type="hidden" name="id" value="<?php print $this->var['id'];?>">
	<input type="submit" name="submit" value="submit">
</form>
