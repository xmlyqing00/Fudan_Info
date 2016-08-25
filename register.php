<html lang="en">
<!-- Welcome! Contact Me: root@lyq.me -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <meta name="keywords" content="FDUTOPIA, FUDAN, INFORMATION, 复旦">
    <meta name="author" content="Liang Yongqing, Liu Xueyue">

    <link rel="stylesheet" type="text/css" href="css/weui.min.css"/>
    <link rel="stylesheet" type="text/css" href="css/style.css"/>

    <script type="text/javascript" src="js/register.js"></script>

    <title>报名/取票 | FDUTOPIA</title>
</head>
<body ontouchstart>
<div class="page_header">
    <h1 class="page_title">报名/取票表</h1>

    <p class="page_desc">填上基本信息就可以报名啦</p>
</div>

<div class="page_body">

    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">学号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" pattern="[0-9]*" name="registration_id_tmp"
                       required="required" placeholder="请输入学号或工号">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">姓名</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" required="required" name="registration_name_tmp"
                       placeholder="请输入姓名">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">专业</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" required="required" name="registration_major_tmp"
                       placeholder="请输入专业">
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">手机号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="number" pattern="[0-9]*" required="required"
                       name="registration_phone_tmp" placeholder="请输入手机号码">
            </div>
        </div>
        <div class="weui_cells_title">留言板（选填）</div>
        <div class="weui_cell">
            <div class="weui_cell_bd weui_cell_primary">
                <textarea class="weui_textarea" placeholder="写给给主办方的悄悄话" name="message_tmp" rows="4"
                          id="message" onkeyup="count('message', message_cnt, 200);"></textarea>

                <div class="weui_textarea_counter"><span id="message_cnt">0</span>/200</div>
            </div>
        </div>
    </div>

    <?php
    $now_time = date('Y-m-d H:i:s', time());

    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_query("set names 'utf8'");
    mysql_select_db("fudan_info");

    $query = sprintf("SELECT * FROM event_info NATURAL JOIN event_registration_date NATURAL JOIN event_registration_common WHERE
        confirm=TRUE AND ((register_date_type='date_st' AND '%s' < event_date) OR (register_date_type='date_ed' AND '%s' < register_date))
        GROUP BY event_id;",
        $now_time, $now_time);
    //echo $query;
    $res = mysql_query($query, $mysql);

    ?>

    <div class="weui_cell weui_cell_select weui_select_after">
        <div class="weui_cell_hd">
            <label class="weui_label">活动</label>
        </div>
        <div class="weui_cell_bd weui_cell_primary">
            <select class="weui_select" name="event_id" id="select_event" onchange="change_event();">
                <?php
                $options = '';
                while ($row = mysql_fetch_assoc($res)) {
                    $options .= '<option value="' . $row['event_id'] . '">' . $row['title'] . '</option>';
                }
                echo $options;
                ?>
            </select>
        </div>
    </div>

    <div id="event_list">

        <?php
        $event_list = mysql_query($query, $mysql);
        $event_cnt = 0;
        while ($event_info = mysql_fetch_assoc($event_list)) {
        //echo $event_info['event_id'];
        $query = sprintf("SELECT * FROM users NATURAL JOIN event_info NATURAL JOIN event_registration_common AS r RIGHT JOIN event_registration_date AS d ON r.event_id=d.event_id WHERE r.event_id='%s';",
            $event_info['event_id']);
        //echo $query;
        $registration_list = mysql_query($query, $mysql);

        ?>
        <div id="<?php echo 'event_id_' . $event_info['event_id'] ?>" <?php
        if ($event_cnt > 0) {
            echo 'style="display: none"';
        }
        $event_cnt++;
        ?>>
            <form name="edit_register" method="post" onsubmit="check_register()" action="save_register.php">

                <input name="registration_id" style="display: none;"/>
                <input name="registration_name" style="display: none;"/>
                <input name="registration_major" style="display: none;"/>
                <input name="registration_phone" style="display: none;"/>
                <textarea name="registration_message" style="display:none;"></textarea>

                <div class="weui_cell weui_cell_select weui_select_after">
                    <div class="weui_cell_hd">
                        <label for="" class="weui_label">场次</label>
                    </div>
                    <div class="weui_cell_bd weui_cell_primary">
                        <select class="weui_select" name="event_serial" id="select_date" onchange="change_date();">
                            <?php
                            while ($registration_info = mysql_fetch_assoc($registration_list)) {
                                $event_date = date('n月j日 H:i', strtotime($registration_info['event_date']));
                                $options .= '<option value="' . $registration_info['registration_serial'] . '">' . $event_date . '</option>';
                            }
                            echo $options;
                            ?>
                        </select>
                    </div>
                </div>

                <?php
                if ($registration_info['speaker'] != '') {
                    ?>
                    <div class="weui_cells_title">【嘉宾】<?php echo $registration_info['speaker']; ?></div>
                    <?php
                }
                if ($registration_info['location'] != '') {
                    ?>
                    <div class="weui_cells_title">【地点】<?php echo $registration_info['location']; ?></div>
                    <?php
                }
                if ($registration_info['hostname'] != '') {
                    ?>
                    <div class="weui_cells_title">【主办方】<?php echo $registration_info['hostname']; ?></div>
                    <?php
                } else {
                    if ($registration_info['username'] != 'fdubot') {
                        ?>
                        <div class="weui_cells_title">【主办方】<?php echo $registration_info['fullname']; ?></div>
                        <?php
                    }
                }
                ?>

                <div id="date_list">
                    <?php
                    $query = sprintf("SELECT * FROM event_registration_date AS d WHERE d.event_id='%s';",
                        $event_info['event_id']);
                    $registration_list = mysql_query($query, $mysql);
                    $date_cnt = 0;
                    while ($registration_info = mysql_fetch_assoc($registration_list)) {
                        ?>
                        <div id="<?php echo 'registration_serial_' . $registration_info['registration_serial'] ?>" <?php
                        if ($date_cnt > 0) {
                            echo 'style="display: none"';
                        }
                        $date_cnt++;
                        ?>>
                            <div class="weui_cells_title">
                                剩余票数<?php echo $registration_info['ticket_count'] - $registration_info['register_count']; ?>
                                张，每人最多可取<?php echo $registration_info['ticket_per_person']; ?>张
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="weui_cell">
                        <div class="weui_cell_hd"><label class="weui_label">票数</label></div>
                        <div class="weui_cell_bd weui_cell_primary">
                            <input class="weui_input" type="number" pattern="[0-9]*"
                                   max="<?php echo $registration_info['ticket_per_person']; ?>" min="0"
                                   value="1" required="required"
                                   name="ticket_num_<?php echo $registration_info['registration_serial']; ?>"
                                   placeholder="请输入要预约的票数">
                        </div>
                    </div>

                    <div class="weui_btn_area">
                        <input name="save" type="submit" value="报名" class="weui_btn weui_btn_plain_primary"/>
                    </div>
                </div>
            </form>
            <?php
            }
            ?>
        </div>

        <div class="weui_btn_area">
            <a class="weui_btn weui_btn_plain_default" href="forgot_ticket.php">找回入场码</a>
        </div>
        <div id="error_message"></div>

        <br>
        <hr>
        <br>

        <p class="page_desc">素心无用，自由分享</p>

        <p class="page_desc">分享复旦内有生命力的讲座活动</p>

        <p class="page_desc">守望复旦人澎湃不息的赤子之心</p>
        <br>

        <p class="page_desc">如果你喜欢我们，欢迎关注公众号FDUTOPIA</p>

        <div class="median_img">
            <img src="images/qrcode_median.jpg" alt="qrcode"/>
        </div>
        <br>
    </div>
    <br>
    <!--<canvas id="canvas_effect"></canvas>-->
    <!--<script type="text/javascript" src="effects.js"></script>-->
</body>
</html>