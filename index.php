<?php

$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "lyq");
    mysql_select_db("fudan_info");
    $query = sprintf("select username from login_serial where serial='%s';",
                    mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    mysql_close($mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        $username = $row['username'];
        if ($username == 'admin') {
            header('Location: admin.php');
        }
    }
} else {
    header('Location: login.html');
}
?>

<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="Fudan, Informations">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">
    <link rel="stylesheet" type="text/css" href="../node_modules/weui/dist/style/weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>复旦信息</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">欢迎</h1>
    <p class="page_desc">为复旦活动量身设计</p>
</div>
<div class="page_body">
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_primary" href="edit_event.php">发布一则活动信息</a>
        <a class="weui_btn weui_btn_plain_primary" href="edit_recruit.php">发布一则招新信息</a>
        <a class="weui_btn weui_btn_plain_primary" href="client_history.php">查看我的历史发布</a>
        <a class="weui_btn weui_btn_plain_default" onclick="logout()">退出</a>
    </div>
</div>
</body>
</html>