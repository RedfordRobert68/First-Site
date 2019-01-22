<?php
	require('./includes/config.inc.php');
	$_SESSION['user_id'] = 1;

	require(MYSQL);

	include('./includes/header.html');
?>

<h3>Welcome</h3>
<p class="lead">
	Welcome to the Knowledge is Power, a site dedicated to keeping you up-to-date on the Web security and programming information you need to know. Blah, blah, blah. Yadda, yadda, yadda.
</p>

<?php
	include('./includes/footer.html');
?>
