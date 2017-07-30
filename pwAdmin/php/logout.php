<?php
session_start();
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="1; url=../index.php">
        <script type="text/javascript">
            window.location.href = "../index.php"
        </script>
        <title>Page Redirection</title>
    </head>
    <body>
		<?php
		unset ($_SESSION['un']);
		unset ($_SESSION['pw']);
		unset ($_SESSION['id']);
		unset ($_SESSION['ma']);
		if (isset($_SESSION['t'])){
			unset ($_SESSION['t']);
		}
		if (isset($_SESSION['UD1'])){
			unset ($_SESSION['UD1']);
		}
		if (isset($_SESSION['UD2'])){
			unset ($_SESSION['UD2']);
		}
		if (isset($_SESSION['UD3'])){
			unset ($_SESSION['UD3']);
		}
		?>
        If you are not redirected automatically, follow this <a href='../index.php'>link to here</a>.
    </body>
</html>