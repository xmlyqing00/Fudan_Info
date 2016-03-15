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
        <title>编辑一则活动</title>
    </head>

    <body ontouchstart>
    <div class="page_header">
        <h1 class="page_title">编辑一则活动</h1>
        <p class="page_desc">维护用户数据，用户名以fdu为前缀必填</p>
        <p class="page_desc">维护用户数据，用户名以fdu为前缀必填</p>
    </div>
    <div class="page_body">
<?php

if (isset($_GET['event_id']) && $_GET['event_id'] != '') {

    $mysql = mysql_connect("localhost", "root", "lyq");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("select * from event_info where event_id='%s';",
        mysql_real_escape_string($_GET['event_id']));
    $res = mysql_query($query, $mysql);
    $row = mysql_fetch_assoc($res);
    if ($row['username'] == $username) {
        ?>
        <form name="edit_event" method="post" onsubmit="return reedit_event(this.submited);" action="">

            <input style="display: none" name="event_id" value="<?php echo $_GET['event_id'];?>" />

            <div class="weui_cells_title">标题</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="title" placeholder="请输入活动标题" name="title" rows="3"
                              onkeyup="count('title', title_cnt, 70);" required="required"><?php echo $row['title'];?></textarea>
                        <div class="weui_textarea_counter">
                            <span id="title_cnt"><?php echo strlen($row['title']);?></span>/70
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_cells_title">地点</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="location" placeholder="请输入活动地点" name="location" rows="2"
                              onkeyup="count('location', location_cnt, 40);" required="required"><?php echo $row['location'];?></textarea>
                        <div class="weui_textarea_counter">
                            <span id="location_cnt"><?php echo strlen($row['location']);?></span>/40
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_hd">
                        <label class="weui_label">时间</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" name="date" type="datetime-local" value="<?php
                            $pos = strpos($row['date'], " ");
                            $date = substr($row['date'], 0, $pos) . "T" . substr($row['date'], $pos+1, strlen($row['date'])-$pos-4);
                            echo $date;
                        ?>"/>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_select weui_select_after">
                    <div class="weui_cell_hd">
                        <label class="weui_label">类别</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="category">
                            <?php
                                $options =
                                    '<option value="culture">人文</option>' .
                                    '<option value="science">科学</option>' .
                                    '<option value="art">艺术</option>' .
                                    '<option value="entertainment">娱乐</option>' .
                                    '<option value="others">其它</option>';

                                $pos = strpos($options, $row['category']);
                                $part1 = substr($options, 0, $pos-7);
                                $part2 = substr($options, $pos-7, strlen($options) - $pos + 7);
                                $options = $part1 . 'selected ' . $part2;
                                echo $options;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">是否有详细描述</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" name="notification" type="checkbox" onclick="show_details()" <?php
                            if ($row['notification'] == 1) {
                                echo 'checked="checked"';
                            }
                        ?>">
                    </div>
                </div>
            </div>
            <div id="details_form" style="display:<?php
                if ($row['notification'] == 1) {
                    echo 'display';
                } else {
                    echo 'none';
                }
            ?>">
                <div class="weui_cells_title">详细描述</div>
                <div class="weui_cells weui_cells_form">
                    <div class="weui_cell">
                        <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="details_text" placeholder="请输入取票信息或主讲人介绍或活动介绍等，主办方不必填写，将自动补上，如果勾选“有详细描述”，则此栏不可为空"
                                  name="details" rows="7" onkeyup="count('details_text', details_cnt, 300);"><?php echo $row['details'];?></textarea>
                            <div class="weui_textarea_counter"><span id="details_cnt"><?php echo strlen($row['details']);?></span>/300</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell weui_cell_switch">
                    <div class="weui_cell_hd weui_cell_primary">是否在活动当周的周一公开发布</div>
                    <div class="weui_cell_ft">
                        <input class="weui_switch" name="publish" type="checkbox" <?php
                            if ($row['publish'] == 1) {
                                echo 'checked="checked"';
                            }
                        ?>">
                    </div>
                </div>
            </div>
            <div class="weui_btn_area">
                <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" onclick="this.form.submited=this.name" value="保存" />
            </div>
            <div class="weui_btn_area">
                <input id="weui_btn_plain_warn" class="weui_btn weui_btn_plain_primary" name="delete" type="submit" onclick="this.form.submited=this.name" value="删除" />
            </div>
        </form>
        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="javascript:history.back();">返回</a>
        </div>
        <div id="error_message"></div>
        <div id="confirm_message"></div>

        <?php
    } else {
        ?>
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd">
                <strong class="weui_dialog_title">访问违规</strong>
            </div>
            <div class="weui_dialog_bd">
                只能编辑自己的文章!
            </div>
            <div class="weui_dialog_ft">
                <a href="index.php" weui_btn_dialog primary">确定</a>
            </div>
        </div>
        <?php
    }

} else {
    ?>
    <form name="edit_event" method="post" onsubmit="return check_event();" action="save_event.php">
        <div class="weui_cells_title">标题</div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="title" placeholder="请输入活动标题"
                              name="title" rows="3" onkeyup="count('title', title_cnt, 70);" required="required"></textarea>
                    <div class="weui_textarea_counter"><span id="title_cnt">0</span>/70</div>
                </div>
            </div>
        </div>
        <div class="weui_cells_title">地点</div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_bd weui_cell_primary">
                    <textarea class="weui_textarea" id="location" placeholder="请输入活动地点"
                              name="location" rows="2" onkeyup="count('location', location_cnt, 40);" required="required"></textarea>
                    <div class="weui_textarea_counter"><span id="location_cnt">0</span>/40</div>
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell">
                <div class="weui_cell_hd">
                    <label class="weui_label">时间</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <input class="weui_input" name="date" type="datetime-local" />
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell weui_cell_select weui_select_after">
                <div class="weui_cell_hd">
                    <label class="weui_label">类别</label>
                </div>
                <div class="weui_cell_bd weui_cell_primary">
                    <select class="weui_select" name="category">
                        <option selected value=""></option>
                        <option value="culture">人文</option>
                        <option value="science">科学</option>
                        <option value="art">艺术</option>
                        <option value="entertainment">娱乐</option>
                        <option value="others">其它</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell weui_cell_switch">
                <div class="weui_cell_hd weui_cell_primary">是否有详细描述</div>
                <div class="weui_cell_ft">
                    <input class="weui_switch" name="notification" type="checkbox" onclick="show_details(details_form)">
                </div>
            </div>
        </div>
        <div id="details_form" style="display: none">
            <div class="weui_cells_title">详细描述</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <textarea class="weui_textarea" id="details_text" placeholder="请输入取票信息或主讲人介绍或活动介绍等，主办方不必填写，将自动补上，如果勾选“有详细描述”，则此栏不可为空"
                                  name="details" rows="7" onkeyup="count('details_text', details_cnt, 300);"></textarea>
                        <div class="weui_textarea_counter"><span id="details_cnt">0</span>/300</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="weui_cells weui_cells_form">
            <div class="weui_cell weui_cell_switch">
                <div class="weui_cell_hd weui_cell_primary">是否在活动当周的周一公开发布</div>
                <div class="weui_cell_ft">
                    <input class="weui_switch" name="publish" type="checkbox" />
                </div>
            </div>
        </div>
        <div class="weui_btn_area">
            <input class="weui_btn weui_btn_plain_primary" name="save" type="submit" value="保存" />
        </div>
    </form>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_plain_default" href="javascript:history.back();">返回</a>
    </div>
    <div id="error_message"></div>
    <?php
}
?>
    </div>
    </body>
    </html>