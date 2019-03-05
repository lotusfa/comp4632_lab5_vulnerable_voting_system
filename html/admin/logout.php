<?php
if (isset($_POST["submit_login"]) && strcmp($errmsg, "")==0) {
$row = $rows[0];
$_SESSION["userId"] = $row["_id"];
$_SESSION["userName"] = $row["name"];
header("Location: client.php");
}
else {
?>
<html>
<head>
    <meta http-equiv='REFRESH' content='0; url=login.php' />
</head>

<body>
</body>
</html>
<?php } ?>