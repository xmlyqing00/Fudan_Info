<?php

$username = '';

if (isset($_COOKIE['login_serial'])) {
    $mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
    mysql_select_db("fudan_info");
    $query = sprintf("select username from login_serial where serial='%s';",
        mysql_real_escape_string($_COOKIE['login_serial']));
    $res = mysql_query($query, $mysql);
    mysql_close($mysql);
    if (!mysql_num_rows($res)) {
        header('Location: login.html');
    } else {
        $row = mysql_fetch_assoc($res);
        if ($row['username'] != 'admin') {
            header('Location: login.html');
        }
    }
} else {
    header('Location: login.html');
}
?>

<?php

$update_next_week = check_update();
if (date('N', time()) != 7) {
    $week_st = date('y-m-d 00:00:00', strtotime('next week', time()));
    $week_ed = date('y-m-d 00:00:00', strtotime('next week + 14 day', time()));
} else {
    $week_st = date('y-m-d 00:00:00', strtotime('this week', time()));
    $week_ed = date('y-m-d 00:00:00', strtotime('this week + 14 day', time()));
}
$category_name_cn = array('人文', '科学', '艺术', '金融', '体育','娱乐', '其它');
$category_name_en = array('culture', 'science', 'art', 'finance', 'sport', 'entertainment', 'others');
$category_cnt = 7;
$order_id = 1;

$mysql = mysql_connect("localhost", "root", "Xmlyqing2016");
mysql_query("set names 'utf8'");
mysql_select_db("fudan_info");

if ($update_next_week) {
    $query = "delete from published_event;";
    $res = $mysql_query($query);
}

print_header();
$category_id_bias = 0;
for ($i = 0; $i < $category_cnt; $i++) {
    print_article($order_id, $i);
}
mysql_close($mysql);

print_footer();

function check_update() {
    $cur_time_week = date('N', time());
    $cur_time_hour = date('H', time());
    $sunday_week = date('N', strtotime("Sunday"));
    if ($cur_time_week == $sunday_week && 24 - $cur_time_hour <= 4) {
        return true;
    }
    return false;
}

function print_header() {
    $html = '<section><p style="text-align: center;"><span style="font-size: 14px;">复旦乌托邦</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">不再错过，不再遗忘，我们收集与分享</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">每周日晚上见～</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">带有<strong style="text-align: center; white-space: normal; font-size: 14px; line-height: 22.4px;"><span style="font-size: 14px; line-height: 16px; width: 16px; display: inline-block; border-radius: 50%; height: 16px; color: rgb(255, 255, 255); background-color: #0099CC;">i</span></strong>标签的活动</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">在公众号内发送编号可查看取票等详细信息</span></p>';
    $html .= '<br><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br></section>';
    echo $html;
}

function print_title($index, $category_name_cn) {
    $html = '<section><section style="border: 0px; margin-top: 0.8em; margin-bottom: 0.5em; box-sizing: border-box;">' .
            '<section style="display: inline-block; padding-right: 2px; padding-bottom: 2px; padding-left: 2px; box-sizing: border-box; border-bottom-width: 2px; border-bottom-style: solid; border-color: #FF6666; line-height: 1; font-size: 1em; font-family: inherit; text-align: center; text-decoration: inherit; color: rgb(255, 255, 255);">' .
            '<section style="display: inline-block; padding: 0.3em 0.4em; min-width: 1.8em; min-height: 1.6em; border-radius: 80% 100% 90% 20%; line-height: 1; font-size: 1em; font-family: inherit; box-sizing: border-box; word-wrap: break-word !important; background-color: #FF6666;">';
    $html .= sprintf('<section style="box-sizing: border-box;">​%d</section>', $index);
    $html .= '</section><span style="display: inline-block; margin-left: 0.4em; max-width: 100%; color: #FF6666; line-height: 1.4; font-size: 1em; word-wrap: break-word !important; box-sizing: border-box;"><span style="max-width: 100%; font-size: 1em; font-family: inherit; font-weight: bolder; text-decoration: inherit; color: #FF6666; word-wrap: break-word !important; box-sizing: border-box;">';
    $html .= sprintf('<section style="box-sizing: border-box;">%s</section>', $category_name_cn);
    $html .= '</span></span></section><section style="width: 0px; height: 0px; clear: both;"></section></section>';
    echo $html;
}

function print_article(&$order_id, $category_id) {

    global $category_id_bias, $category_name_cn, $category_name_en, $week_st, $week_ed, $mysql, $update_next_week;
    $query = sprintf("select * from event_info natural join users where publish=1 and category='%s' and date_ed>='%s' and date_st<'%s' order by date_st;",
        $category_name_en[$category_id], $week_st, $week_ed);
    $res = mysql_query($query, $mysql);
    if (!mysql_num_rows($res)) {
        $category_id_bias++;
        return;
    } else {
        $index = $category_id-$category_id_bias+1;
        print_title($index, $category_name_cn[$category_id]);
    }
    $html = sprintf('<ol style="list-style-type: decimal;" class=" list-paddingleft-2" start="%d">', $order_id);
    while ($row = mysql_fetch_assoc($res)) {

        $date_st = date('n月j日 H:i',strtotime($row['date_st']));
        $date_ed = date('n月j日 H:i',strtotime($row['date_ed']));
        $date_st_pos = strpos($date_st, ' ');
        $date_ed_pos = strpos($date_ed, ' ');
        if (substr($date_st, 0, $date_st_pos) == substr($date_ed, 0, $date_ed_pos)) {
            $date_ed = substr($date_ed, $date_ed_pos+1, strlen($date_ed)-$date_ed_pos-1);
        } else {
            $date_st_pos = strpos($date_st, '月');
            $date_ed_pos = strpos($date_ed, '月');
            if (substr($date_st, 0, $date_st_pos) == substr($date_ed, 0, $date_ed_pos)) {
                $date_ed = substr($date_ed, $date_ed_pos+3, strlen($date_ed)-$date_ed_pos-1);
            }
        }

        $html .= '<li>';
        $html .= sprintf('<p style="font-size: 14px;"><strong>%s', $row['title']);
        if ($row['notification'] == 1) {
            $html .= '&nbsp;<span style="text-align: center; padding: 0px;line-height: 16px; margin: 0px;width: 16px; display: inline-block; border-top-left-radius: 50%; border-top-right-radius: 50%; border-bottom-left-radius: 50%; border-bottom-right-radius: 50%;height: 16px;background-color: #0099CC; color: rgb(255, 255, 255);">i</span>';
        }
        $html .= '</strong></p>';
        if (strlen($row['speaker']) > 0) {
            $html .= sprintf('<p style="font-size: 14px;">主讲人：%s</p>', $row['speaker']);
        }
        $html .= sprintf('<p style="font-size: 14px;">%s&nbsp;&nbsp;&nbsp;%s</p>', $date_st . ' - ' . $date_ed, $row['location']);
        $html .= sprintf('<p style="font-size: 14px;">%s&nbsp;', $row['fullname']);
        $html .= '</p></li><br>';

        if ($update_next_week) {
            $details = ($row['details'] == '') ? 'default' : '"' . $row['details'] . '"';
            $query = sprintf('insert into published_event value (%d, "%s", %s);', $order_id, $row['title'], $details);
            echo $query;
            $res = mysql_query($query, $mysql);
        }

        $order_id++;
    }

    $html .= '</ol><br></section>';
    echo $html;
}

function print_footer() {
    $html = '<section><p style="text-align: center;"><span style="color: #00C12B;">* * *</span></p><br>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">FDUTOPIA致力于打造高效的复旦信息分享平台</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">如果你是社长或主办方</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">请联系fdutopia@lyq.me说明负责人身份</span></p>';
    $html .= '<p style="text-align: center;"><span style="font-size: 14px;">获得邀请后即可发布活动</span></p>';
    $html .= '<br></section>';
    echo $html;
}

?>