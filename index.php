<?php

$username = '';
$fullname = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");
    $query = sprintf("select username, fullname from login_serial natural join users where serial='%s';",
                    mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        $username = $row['username'];
        $fullname = $row['fullname'];
        if ($username == 'admin') {
            header('Location: admin.php');
        }
    }
    mysql_close($mysql);
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
    <link rel="stylesheet" type="text/css" href="weui.min.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="functions.js"></script>
    <title>FDUTOPIA</title>
</head>

<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">FDUTOPIA</h1>
    <p class="page_desc">小伙伴们，我们进入夏眠啦</p>
    <p class="page_desc">系统将在暑假结束前开放</p>
    <p class="page_desc">届时欢迎使用非常赞的<span id="info_green">票务系统</span></p>
    <p class="page_desc">祝小伙伴们期末考试顺利！^_^</p>
    <br>
    <p class="page_desc">活动信息将在<span class="text_warn">每周日晚六点</span>被推送</p>
    <p class="page_desc">招新信息只在<span class="text_warn">每学期前四周</span>被推送</p>
    <p class="page_desc">已入驻的主办方数 : <?php
        $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
        mysql_query("set names 'utf8'");
        mysql_select_db("fudan_info");
        $query = "select count(*) as cnt from users;";
        $res = mysql_query($query, $mysql);
        $row = mysql_fetch_assoc($res);
        echo $row['cnt']-2;
        mysql_close($mysql);
        ?></p>
    <p class="page_desc"><?php
        $hour = date('G', time());
        if ($hour >= 0 && $hour < 6) echo '夜深了，' . $fullname . '，早点休息哦～';
        if ($hour >= 6 && $hour < 12) echo '早上好，' . $fullname;
        if ($hour >= 12 && $hour < 18) echo '下午好，' . $fullname;
        if ($hour >= 18 && $hour < 24) echo '晚上好，' . $fullname;
        ?></p>
</div>
<div class="page_body">

<!--    <div id="error_message"></div>-->
<!--    <script type="text/javascript" src="check_html5.js"></script>-->

    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_primary disabled">发布一则活动信息</a>
        <a class="weui_btn weui_btn_plain_primary disabled">发布一则招新信息</a>
        <a class="weui_btn weui_btn_plain_primary" href="registeration_list.php">查看我的票务系统</a>
        <a class="weui_btn weui_btn_plain_primary" href="client_history.php">查看我的历史发布</a>
        <a class="weui_btn weui_btn_plain_primary" href="edit_profile.php">修改我的基本信息</a>
        <a class="weui_btn weui_btn_plain_default" onclick="logout()">退出</a>
    </div>
    <br>
</div>
<!--<canvas id="canvas_effect"></canvas>-->
<!--<script type="text/javascript" src="effects.js"></script>-->
</body>
</html>
