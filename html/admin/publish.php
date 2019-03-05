<?php
include "../mysql.php";
session_start();
$userId = $_SESSION["userId"];
$userName = $_SESSION["userName"];
$conn = FALSE;
$topic = trim($_POST["txt_topic"]);
$option_a = trim($_POST["txt_option_a"]);
$option_b = trim($_POST["txt_option_b"]);
$option_c = trim($_POST["txt_option_c"]);
$option_d = trim($_POST["txt_option_d"]);
$errmsg = "";
if (isset($_POST["submit_publish"])) {
    $conn = dbOpen();
    if (!$conn) {
        $errmsg .= mysql_error()."<br />";
    }
    $rows = retrieveVoteByTopic($conn, $topic);
    if (is_null($rows)) {
        $errmsg .= mysql_error()."<br />";
    }
    else if (count($rows)!=0) {
        $errmsg .= "Vote already exist!<br />";
    }
    else {
        $res = createVote($conn, $topic, $option_a, $option_b, $option_c, $option_d);
        if (!$res) {
            $errmsg .= mysql_error()."<br />";
        }
    }
    dbClose($conn);
    $conn = FALSE;
}
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Vote Publish - Vulnerable Voting System</title>

    <script type='text/javascript'>

        function validateVote() {
            
            var txt_topic = document.getElementById("txt_topic").value;
            var txt_option_a = document.getElementById("txt_option_a").value;
            var txt_option_b = document.getElementById("txt_option_b").value;
            var txt_option_c = document.getElementById("txt_option_c").value;
            var txt_option_d = document.getElementById("txt_option_d").value;

            var errmsg = "";
            if (txt_topic=="") {
            errmsg += "Topic is missing!<br />";
            }

            if (txt_option_a=="") {
            errmsg += "Option A is missing!<br />";
            }
            if (txt_option_b=="") {
            errmsg += "Option B is missing!<br />";
            }
            if (txt_option_c=="") {
            errmsg += "Option C is missing!<br />";
            }
            if (txt_option_d=="") {
            errmsg += "Option D is missing!<br />";
            }
            document.getElementById("err_publish").innerHTML = errmsg;
            return(errmsg=="");
        }
    </script>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='2'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>Management Portal</h3>
                </td>
                <!-- Account Info -->
                <td align='RIGHT' valign='BOTTOM'>
                    Admin: <?=$userName;?> <br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#ECEDEF' align='CENTER'>
                <td width='25%'><a href='client.php'>Client Profile</a></td>
                <td width='25%'>Vote Publish</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Publish New Vote:</h3>
    <font color='#FF0000'>
        <span id='err_publish'><?=$errmsg;?></span>
    </font>
    <form id='form_publish_vote' name='form_publish_vote' method='POST' action='publish.php'>
        Topic: <input type='TEXT' id='txt_topic' name='txt_topic' value='' size='50' /><br />
        Option A: <input type='TEXT' id='txt_option_a' name='txt_option_a' value='' size='25' /><br />
        Option B: <input type='TEXT' id='txt_option_b' name='txt_option_b' value='' size='25' /><br />
        Option C: <input type='TEXT' id='txt_option_c' name='txt_option_c' value='' size='25' /><br />
        Option D: <input type='TEXT' id='txt_option_d' name='txt_option_d' value='' size='25' /><br />
        <input type='SUBMIT' id='submit_publish' name='submit_publish' value='Publish Vote' onclick='javascript: return validateVote();' />
    </form>
</body>
</html>