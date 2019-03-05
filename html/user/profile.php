<?php

ini_set('display_errors', 'On');
session_start();
isset($_SESSION["userId"]) or header("Location: ../index.php") and exit(0);
require_once("../phpThumb_1.7.9/phpThumb.config.php");
include "../mysql.php";
$conn = FALSE;
$userId = $_SESSION["userId"];
$userName = $_SESSION["userName"];
$email = trim($_GET["login"]);
$phone = trim($_POST["txt_phone"]);
$address = trim($_POST["txt_address"]);
$password = $_POST["pwd_new"];
$errmsgUpload = "";
$errmsgUpdate = "";
$errmsgChange = "";
$conn = dbOpen();
if (!$conn) {
    $errmsgUpload .= mysql_error()."<br />";
}
else {
    $rows = retrieveUserByEmail($conn, $email);
    if (is_null($rows)) {
        $errmsgUpload .= mysql_error()."<br />";
    }
    else if (count($rows)!=1) {
        $errmsgUpload .= "Login is invalid!<br />";
    }
    else {
        $row = $rows[0];
        $uploadPath = '../images/';
        $uploadFile = $uploadPath.$row["_id"];
        if (isset($_POST["submit_upload"])) {

            $isUploaded = move_uploaded_file($_FILES["file_upload"]["tmp_name"],
                $uploadFile);
            if (!$isUploaded) {
                switch ($_FILES["file_upload"]["error"]) {
                    case UPLOAD_ERR_INI_SIZE:
                    $errmsgUpload .= "The uploaded file exceeds the maximum file
                    size limit!<br />";
                    break;
                    case UPLOAD_ERR_FORM_SIZE:
                    $errmsgUpload .= "The uploaded file exceeds the maximum file
                    size limit!<br />";
                    break;
                    case UPLOAD_ERR_PARTIAL:
                    $errmsgUpload .= "The uploaded file was only partially
                    uploaded!<br />";
                    break;
                    //TODO ??
                    default:

                    $errmsgUpload .= "Unknown error!<br />";
                }
            }
            else if (strcmp(mime_content_type($uploadFile), "image/png")!=0 &&
                strcmp(mime_content_type($uploadFile), "image/jpeg")!=0 &&
                strcmp(mime_content_type($uploadFile), "image/gif")!=0) {

                $errmsgUpload .= "Image type invalid!<br />";
                copy($uploadPath."profile.png", $uploadFile);

            }
        }
        else if (isset($_POST["submit_update"])) {
            $rows_su = updateUserPhoneAddress($conn, $email, $phone, $address);
            if (is_null($rows_su)) {
                $errmsg .= mysql_error()."<br />";
            }
        }
        else if (isset($_POST["submit_change"])) {
            $rows_sc = updateUserPassword($conn, $email, $password);
            if (is_null($rows_sc)) {
                $errmsg .= mysql_error()."<br />";
            }
        }
}
}
dbClose($conn);
$conn = FALSE;
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>User profile - Vulnerable Voting System</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <style type="text/css">

    body{
        background-image: url("https://res.cloudinary.com/dnodusgbt/image/upload/v1551409243/Iamprofessional.jpg");
        color: black ;
    }
    .float {
        width: 33%;
        float: right;
    }

    .card {
        background-color: gray;
        color: white;
        margin: auto;
    }

    .card h2,h3 {
        color: white;
    }
</style>

<script type='text/javascript'>
    function toggleUpload(isDisplay) {
        var btnUpload = document.getElementById("btn_upload");
        var fileUpload = document.getElementById("file_upload");
        var submitUpload = document.getElementById("submit_upload");
        if (isDisplay) {
            btnUpload.style.display="none";
            fileUpload.style.display="block";
            submitUpload.style.display="block";
        }
        else {
            btnUpload.style.display="block";
            fileUpload.style.display="none";
            submitUpload.style.display="none";
        }
    }
</script>

<script type='text/javascript'>
    function validatePhone(email) {
        var re = /^(?=.{8}$)\d*$/;
        return re.test(String(email).toLowerCase());
    }

    function validateInfo() {
        var txtPhone = document.getElementById("txt_phone").value;
        var txtAddress = document.getElementById("txt_address").value;
        var errmsg = "";
        if (txtPhone=="") {
            errmsg += "Phone is missing!<br />";
        }
        else if (!validatePhone(txtPhone)) {
            errmsg += "Phone incorrect! Need to have 8 digit<br />";
        }
        if (txtAddress=="") {
            errmsg += "Address is missing!<br />";
        }
        document.getElementById("err_update").innerHTML = errmsg;
        return(errmsg=="");
    }

    function validatePassword() {
        var pwdNew = document.getElementById("pwd_new").value;
        var pwdConfirm = document.getElementById("pwd_confirm").value;
        var errmsg = "";
        if (pwdNew=="") {
            errmsg += "New Password is missing!<br />";
        }
        if (pwdConfirm=="") {
            errmsg += "New Pw is missing!<br />";
        }else if (pwdNew != pwdConfirm) {
            errmsg += "New PW not match Confirm PW<br />";
        }
        document.getElementById("err_change").innerHTML = errmsg;
        return(errmsg=="");
    }


</script>


</head>

<body onload='javascript: toggleUpload(false);'>
    <div class="container">
        <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
            <table border='0' width='100%' class="card">
                <tr>

                    <td colspan='3'>
                        <table>
                            <tr>
                                <td>
                                    <img src="https://res.cloudinary.com/dnodusgbt/image/upload/v1551410013/pro_1351909.png" width="100px" />
                                </td>
                                <td>
                                    <h2>Vulnerable Voting System</h2>
                                    <h3>User profile</h3>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <!-- userinfo -->
                    <td align='RIGHT' valign='BOTTOM'>
                        <?=$row['name'];?><br />
                    </td>
                </tr>
                <!-- navigation -->
                <tr bgcolor='#8AC007' align='CENTER'>
                    <td width='25%'>Profile</td>
                    <td width='25%'><a href='voting.php?login=<?=$email;?>'>Voting</a></td>
                    <td width='25%'><a href='result.php?login=<?=$email;?>'>Result</a></td>
                    <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
                </tr>
            </table>
        </form>

        <div class="row" >
            <div class="card col-sm" >
                <h3>Account Information:</h3>
                Name: <?=$row['name'];?><br />

                Email: <?=$row['email'];?><br />

                Phone: <?=$row['phone'];?><br />

                Address: <?=$row['address'];?><br />
            </div>
            <div class="card col-sm" >
                <div class='float card' style="width: 100%;">

                    <font color='#FF0000'>
                        <span id='err_upload'><?=$errmsgUpload;?></span>
                    </font>
                    
                    <div>
                        <?php
                        if (file_exists($uploadFile)) {
                            ?>
                            <img src='<?=htmlspecialchars(phpThumbURL("src=".$uploadFile));?>' alt='profile
                            pic' height='150' /><br />
                            <?php
                        }
                        else {
                            ?>
                            <img src='<?=htmlspecialchars(phpThumbURL("src=../images/profile.png"));?>'
                            alt='profile pic' height='150' /><br />
                            <?php
                        }
                        ?>
                    </div>

                    <br />

                    <form id='form_upload' name='form_upload' enctype='multipart/form-data' method='POST' action='profile.php?login=<?=$email;?>'>

                        <input type='BUTTON' id='btn_upload' name='btn_upload' value='Upload profile pic' onclick='javascript: toggleUpload(true);'>

                        <input type='HIDDEN' id='MAX_FILE_SIZE' name='MAX_FILE_SIZE' value='1000000' />
                        <input type='FILE' id='file_upload' name='file_upload'>

                        <input type='SUBMIT' id='submit_upload' name='submit_upload' value='Upload' onclick='javascript: toggleUpload(false);'>

                    </form>

                </div>
            </div>
        </div>



        <br>
        <div class="row" >

            <div class="card col-sm" >

                <h3>Update Information:</h3>

                <font color='#FF0000'>

                    <span id='err_update'></span>

                </font>




                <form id='form_update' name='form_update' enctype='multipart/form-data' method='POST' action='profile.php?login=<?=$email;?>'>

                    Phone:   <input type='TEXT' id='txt_phone' name='txt_phone' value='' size='30' /><br />

                    Address: <input type='TEXT' id='txt_address' name='txt_address' value='' size='30' /><br />

                    <input type='SUBMIT' id='submit_update' name='submit_update' value='Update' onclick='javascript: return validateInfo();' /><br />

                </form>

            </div>

            <div class="card col-sm" >
                <div class="card" style="float: right;">

                    <h3>Change Password:</h3>

                    <font color='#FF0000'>

                        <span id='err_change'></span>

                    </font>

                    <form id='form_change' name='form_change' method='POST' action='profile.php?login=<?=$email;?>'>

                        New Password: <input type='PASSWORD' id='pwd_new' name='pwd_new' value='' size='16' /><br />

                        Confirm Passowrd: <input type='PASSWORD' id='pwd_confirm' name='pwd_confirm' value='' size='16' /><br />

                        <input type='SUBMIT' id='submit_change' name='submit_change' value='Change' onclick='javascript: return validatePassword();' /><br />

                    </form>

                </div>
            </div>
        </div>
    </div>
</body>
</html>