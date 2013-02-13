<form action=<?php echo $_SERVER['PHP_SELF']; ?> method='post'>
	username
	<input type="text" name= "username" id="username" value="<?php !isset($this->var['username']) ? print '' : print $this->var['username'];?>"><br />
	password
	<input type="password" name="password" id="password" value="<?php !isset($this->var['password']) ? print '' : print $this->var['password'];?>"><br />
	<input type="submit" name="submit" value="submit">
</form>
