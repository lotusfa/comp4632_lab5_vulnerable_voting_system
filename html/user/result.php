<?php
ini_set('display_errors', 'On');
session_start();
isset($_SESSION["userId"]) or header("Location: ../index.php") and exit(0);
include "../mysql.php";
$conn = FALSE;
$userId = $_SESSION["userId"];
$userName = $_SESSION["userName"];
$email = trim($_GET["login"]);
$errmsg = "";
$conn = dbOpen();
if (!$conn) {
    $errmsg .= mysql_error()."<br />";
}
else {
    $rows = retrieveUserVoteByEmail($conn, $email);
    if (is_null($rows)) {
        $errmsg .= mysql_error()."<br />";
    }
    else if (count($rows)==0) {
        $errmsg .= "No Result!<br />";
    }
}
dbClose($conn);
$conn = FALSE;
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>User Result - Vulnerable Voting System</title>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Result</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    <?=$userName;?><br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=<?=$email;?>'>Profile</a></td>
                <td width='25%'><a href='voting.php?login=<?=$email;?>'>Voting</a></td>
                <td width='25%'>Result</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Voting Record:</h3>
    <font color='#FF0000'>
        <span id='err_vote'><?=$errmsg?></span>
    </font>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Hot Topic</th>
            <th>My Choice</th>
        </tr>
        <?php
        for ($i=0; $i<count($rows); $i++) {
            $row = $rows[$i];

            switch ($row["choice"]) {
                case 1:
                    $choice_text = $row["option_a"];
                    break;
                case 2:
                    $choice_text = $row["option_b"];
                    break;
                case 3:
                    $choice_text = $row["option_c"];
                    break;
                case 4:
                    $choice_text = $row["option_d"];
                    break;
            }
            
            ?>
            <tr bgcolor='#C1E0EB'>
                    <td><?=$row["topic"];?></td>
                    <td><?=$choice_text;?></td>
                </tr>
                <?php
            }
            ?>
    </table>
</body>
</html>