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
        <title>我的历史发布</title>
    </head>

    <body ontouchstart>
    <div class="page_header">
        <h1 class="page_title">我的历史发布</h1>
        <p class="page_desc">维护用户数据，用户名以fdu为前缀必填</p>
        <p class="page_desc">维护用户数据，用户名以fdu为前缀必填</p>
    </div>
    <div class="page_body">
        <article class="weui_article">
            <div class="section_box">
                <div class="section_header">
                    <span class="section_num">1</span>
                    <span class="section_body">活动信息</span>
                </div>
            </div>

            <section>
            <?php
                $mysql = mysql_connect("localhost", "root", "lyq");
                mysql_query("set names 'utf8'");
                mysql_select_db("fudan_info");

                $query = sprintf("select * from event_info where username='%s' order by event_id desc;", $username);
                $res = mysql_query($query, $mysql);
                $event_cnt = 0;
                while ($row = mysql_fetch_assoc($res)) {
                    $event_cnt++;
                    ?>
                    <h3 class="info_title"><?php echo $event_cnt . ". " . $row['title'];?></h3>
                    <div class="info_box">
                        <p class="info_time">最后编辑于<?php
                            echo substr($row['edit_time'], 0, 16);
                            if ($row['publish'] == 1) {
                                echo ' <span class="info_publish">已发布</span>';
                            } else {
                                echo ' <span>未发布</span>';
                            }
                            ?></p>
                        <a id="info_edit" class="weui_btn weui_btn_mini" href="edit_event.php?event_id=<?php echo $row['event_id'];?>">编辑</a>
                    </div>
                    <?php
                }
                mysql_close($mysql);
            ?>
            </section>

            <div class="section_box">
                <div class="section_header">
                    <span class="section_num">2</span>
                    <span class="section_body">招新信息</span>
                </div>
            </div>

            <section>
                <?php
                $mysql = mysql_connect("localhost", "root", "lyq");
                mysql_query("set names 'utf8'");
                mysql_select_db("fudan_info");

                $query = sprintf("select * from recruit_info where username='%s' order by recruit_id desc;", $username);
                $res = mysql_query($query, $mysql);
                $recruit_cnt = 0;
                while ($row = mysql_fetch_assoc($res)) {
                    $recruit_cnt++;
                    ?>
                    <h3 class="info_title"><?php echo $recruit_cnt . ". " . $row['details'];?></h3>
                    <div class="info_box">
                        <p class="info_time">最后编辑于<?php
                            echo substr($row['edit_time'], 0, 16);
                            if ($row['publish'] == 1) {
                                echo ' <span class="info_publish">已发布</span>';
                            } else {
                                echo ' <span>未发布</span>';
                            }
                            ?></p>
                        <a id="info_edit" class="weui_btn weui_btn_mini" href="edit_recruit.php?recruit_id=<?php echo $row['recruit_id'];?>">编辑</a>
                    </div>
                    <?php
                }
                mysql_close($mysql);
                ?>
            </section>
        </article>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="index.php">返回</a>
        </div>
    </div>
    </body>
    </html>