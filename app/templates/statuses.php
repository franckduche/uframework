<html>
	<head>
		<title>Statuses</title>
	</head>

<?php foreach ($statuses as $status) : ?>

	<!-- Show the status. -->

	<p>
		<a href="/statuses/<?= $status->getId(); ?>"> <?= $status->getContent(); ?> </a> <br /><br />
		<em> Twitted by <strong> <?= $status->getUsername(); ?> </strong> </em>
	</p>
	
	<!-- Form to delete the status. -->
	
	<form action="/statuses/<?= $status->getId(); ?>" method="POST">
		<input type="hidden" name="_method" value="DELETE">
		<input type="submit" value="Delete">
	</form>
	
	<hr />
	
<?php endforeach; ?>

<br />

<!-- Form to add a status. -->

<form action="/statuses" method="POST">
	<input type="hidden" name="_method" value="POST" />
    <label for="username">Username:</label>
    <input type="text" name="username">
	<br />
    <label for="content">Content:</label>
    <textarea name="content"></textarea>
	<br />
    <input type="submit" value="Tweet!">
</form>

</html>
