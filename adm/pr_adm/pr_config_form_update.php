<?php
include_once('./_common.php');

check_demo();

auth_check($auth[$sub_menu], 'w');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

check_admin_token();


//설정을 저장합니다.
$insert_value = array();

//배열을 치환해준다.
if ($_POST) {
  foreach ($_POST as $key => $value) {
    if ($key != 'token') {
      $key = str_replace('pr_','',$key);
      $insert_value[$key] = $value;
    }
  }
} else {

}

var_dump($insert_value);

$insert_json = pr_json_encode($insert_value);

$sql = "update ".PR_CONFIG_EXP." set exp_value='{$insert_json}' , udate = '".G5_TIME_YMDHIS."' where exp_key = 'pr_config' " ;
$query = sql_query($sql);

goto_url('./pr_config_form.php', false);
?>
