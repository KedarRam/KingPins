
<?php
$page_title="Welcome to KingPins FEC - Home Page";
include_once 'headerfooter/header.php';
?>

<div class="container">
<div class="flag">
<h1>Welcome to KingPins FEC</h1>
<p class="lead">KingPins FEC is a family owned entertainment business.<br></p>
<?php if(!isset($_SESSION['username'])): ?>
<p class="lead"> Please <a href="login.php">Login</a></p>
<?php else: ?>
<p class="lead">You are logged in as <b> <?php if(isset($_SESSION['username'])) echo $_SESSION['username'] ?> </b> <a href="logout.php"</a></p>
<?php endif ?>
</div>
</div>

<?php
include_once 'headerfooter/footer.php';
?>

