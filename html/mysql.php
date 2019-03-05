<?php
$DB_HOST = "10.10.4.99";
$DB_PORT = "3306";
$DB_USER = "user4632";
$DB_PASS = "pass4632";
$DB_NAME = "labdb";

function dbOpen() {
    global $DB_HOST, $DB_PORT, $DB_USER, $DB_PASS, $DB_NAME;

    $conn = mysql_connect($DB_HOST.":".$DB_PORT, $DB_USER, $DB_PASS);
    if ($conn) {
        mysql_select_db($DB_NAME, $conn);
    }

    return $conn;
}

function dbClose($conn) {
    if (isset($conn) && $conn!=FALSE) {
        mysql_close($conn);
    }
}

function retrieveUserByEmailPassword($conn, $email, $password) {
    $rows = NULL;
    $sql = "";
    $sql .= "SELECT * ";
    $sql .= "FROM User ";
    $sql .= "WHERE email='".$email."' AND password='".$password."' ";
    $res = mysql_query($sql, $conn);
    if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysql_free_result($res);
    }
    return $rows;
}

function retrieveUserByEmail($conn, $email) {
    return retrieveUserByEmailHkid($conn, $email, "aaaaaaaaxxxxxxx");
}

function retrieveUserByEmailHkid($conn, $email, $hkid) {
    $rows = NULL;
    $sql = "";
    $sql .= "SELECT * ";
    $sql .= "FROM User ";
    $sql .= "WHERE email='".$email."' OR hkid='".$hkid."' ";
    $res = mysql_query($sql, $conn);
    if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysql_free_result($res);
    }
    return $rows;
}

function createUser($conn, $email, $password, $name, $hkid, $phone, $address,
    $status) {
    $sql = "";
    $sql .= "INSERT INTO User (email, password, name, hkid, phone, address, status)
    ";
    $sql .= "VALUES ('".$email."', '".$password."', '".$name."', '".$hkid."',
    '".$phone."', '".$address."', '".$status."') ";
    $res = mysql_query($sql, $conn);
    return $res;
}

function updateUserPhoneAddress($conn, $email, $phone, $address) {
    $sql = "";
    $sql .= "UPDATE User SET `phone` = '".$phone."',`address` = '".$address."' WHERE email='".$email."' ";
    $res = mysql_query($sql, $conn);
    return $res;
}

function updateUserPassword($conn, $email, $password) {
    $sql = "";
    $sql .= "UPDATE User SET `password` = '".$password."' WHERE email='".$email."' ";
    $res = mysql_query($sql, $conn);
    return $res;
}

function retrieveVoteByTopic($conn, $topic) {
    $rows = NULL;
    $sql = "";
    $sql .= "SELECT * ";
    $sql .= "FROM Vote ";
    $sql .= "WHERE topic='".$topic."' ";
    $res = mysql_query($sql, $conn);
    if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysql_free_result($res);
    }
    return $rows;
}

function createVote($conn, $topic, $option_a, $option_b, $option_c, $option_d) {
    $sql = "";
    $sql .= "INSERT INTO Vote (topic, option_a, option_b, option_c, option_d)
    ";
    $sql .= "VALUES ('".$topic."', '".$option_a."', '".$option_b."', '".$option_c."',
    '".$option_d."') ";
    $res = mysql_query($sql, $conn);
    return $res;
}

function retrieveVoteByTopicUseridVoteid($conn, $topic, $userId) {
    $rows = NULL;
    $sql = "";
    $sql .= "SELECT * ";
    $sql .= "FROM Vote ";
    $sql .= "WHERE Vote.topic LIKE '%".$topic."%' AND _id NOT IN ( ";
    $sql .= " SELECT vote_id ";
    $sql .= " FROM User_Vote ";
    $sql .= " WHERE user_id=".$userId." ";
    $sql .= ") ";
    $res = mysql_query($sql, $conn);
    if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysql_free_result($res);
    }
    return $rows;
}

function retrieveUserVoteByEmail($conn, $email) {
    $rows = NULL;
    $sql = "";
    $sql .= "SELECT User_Vote.*, Vote.topic, Vote.option_a, Vote.option_b,
    Vote.option_c, Vote.option_d ";
    $sql .= "FROM User_Vote, User, Vote ";
    $sql .= "WHERE User_Vote.user_id=User._id AND User_Vote.vote_id=Vote._id AND
    User.email='".$email."' ";
    $res = mysql_query($sql, $conn);
    if ($res) {
        $rows = array();
        while ($row = mysql_fetch_assoc($res)) {
            $rows[] = $row;
        }
        mysql_free_result($res);
    }
    return $rows;
}

function createUserVote($conn, $userId, $voteId, $choice) {
    $sql = "";
    $sql .= "INSERT INTO User_Vote (user_id, vote_id, choice)
    ";
    $sql .= "VALUES ('".$userId."', '".$voteId."', '".$choice."') ";
    $res = mysql_query($sql, $conn);
    return $res;
}

?>