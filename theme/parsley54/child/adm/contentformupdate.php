<?php
$sub_menu = '300600';
// include_once(G5_ADMIN_PATH.'/_common.php');
// include_once(G5_ADMIN_PATH.'/admin.lib.php');

if ($w == "u" || $w == "d")
    check_demo();

// if ($w == 'd')
//     auth_check($auth[$sub_menu], "d");
// else
//     auth_check($auth[$sub_menu], "w");

//check_admin_token();

if ($w == "" || $w == "u")
{
    if(preg_match("/[^a-z0-9_]/i", $co_id)) alert("ID 는 영문자, 숫자, _ 만 가능합니다.");

    $sql = " select * from {$g5['content_table']} where co_id = '$co_id' ";
    $co_row = sql_fetch($sql);
}

$co_id = preg_replace('/[^a-z0-9_]/i', '', $co_id);
$co_subject = strip_tags($co_subject);
$co_include_head = preg_replace(array("#[\\\]+$#", "#(<\?php|<\?)#i"), "", substr($co_include_head, 0, 255));
$co_include_tail = preg_replace(array("#[\\\]+$#", "#(<\?php|<\?)#i"), "", substr($co_include_tail, 0, 255));

// 관리자가 자동등록방지를 사용해야 할 경우
if (($co_row['co_include_head'] !== $co_include_head || $co_row['co_include_tail'] !== $co_include_tail) && function_exists('get_admin_captcha_by') && get_admin_captcha_by()){
    include_once(G5_CAPTCHA_PATH.'/captcha.lib.php');

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

@mkdir(G5_DATA_PATH."/content", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH."/content", G5_DIR_PERMISSION);

if ($co_himg_del)  @unlink(G5_DATA_PATH."/content/{$co_id}_h");
if ($co_timg_del)  @unlink(G5_DATA_PATH."/content/{$co_id}_t");

$error_msg = '';

if( $co_include_head ){

    $file_ext = pathinfo($co_include_head, PATHINFO_EXTENSION);

    if( ! $file_ext || ! in_array($file_ext, array('php', 'htm', 'html')) || ! preg_match('/^.*\.(php|htm|html)$/i', $co_include_head) ) {
        alert('상단 파일 경로의 확장자는 php, htm, html 만 허용합니다.');
    }
}

if( $co_include_tail ){

    $file_ext = pathinfo($co_include_tail, PATHINFO_EXTENSION);

    if( ! $file_ext || ! in_array($file_ext, array('php', 'htm', 'html')) || ! preg_match('/^.*\.(php|htm|html)$/i', $co_include_tail) ) {
        alert('하단 파일 경로의 확장자는 php, htm, html 만 허용합니다.');
    }
}

if( $co_include_head && ! is_include_path_check($co_include_head, 1) ){
    $co_include_head = '';
    $error_msg = '/data/file/ 또는 /data/editor/ 포함된 문자를 상단 파일 경로에 포함시킬수 없습니다.';
}

if( $co_include_tail && ! is_include_path_check($co_include_tail, 1) ){
    $co_include_tail = '';
    $error_msg = '/data/file/ 또는 /data/editor/ 포함된 문자를 하단 파일 경로에 포함시킬수 없습니다.';
}

$co_seo_title = exist_seo_title_recursive('content', generate_seo_title($co_subject), $g5['content_table'], $co_id);

$sql_common = " co_include_head     = '$co_include_head',
                co_include_tail     = '$co_include_tail',
                co_html             = '$co_html',
                co_tag_filter_use   = '$co_tag_filter_use',
                co_subject          = '$co_subject',
                co_content          = '$co_content',
                co_mobile_content   = '$co_mobile_content',
                co_seo_title        = '$co_seo_title',
                co_skin             = '$co_skin',
                co_mobile_skin      = '$co_mobile_skin' ";

// pr-child
  // pr_co_filename
  // pr_access_mblevel
  //
$sql_prcommon['co_filename'] = $_POST['pr_co_filename'];
$sql_prcommon['access_mblv'] = $_POST['pr_access_mblv'];
$sql_prcommon['co_point'] = $_POST['pr_co_point'];
$sql_prcommon['co_view'] = $_POST['pr_co_view'];

$sql_prcommon = pr_json_encode($sql_prcommon);
// pr-child

if ($w == "")
{
    $row = $co_row;
    if ($row['co_id'])
        alert("이미 같은 ID로 등록된 내용이 있습니다.");

    $sql = " insert {$g5['content_table']}
                set co_id = '$co_id',
                    $sql_common ";
    sql_query($sql);

    // pr-child
    $sql_pr = "
      insert ".PR_CONTENT_EXP." set
      co_id = '$co_id'
      , co_key = 'config'
      , co_value = '{$sql_prcommon}'
    ";
    sql_query($sql_pr);
    // pr-child
}
else if ($w == "u")
{
    $sql = " update {$g5['content_table']}
                set $sql_common
              where co_id = '$co_id' ";
    sql_query($sql);

    // pr-child
    $sql_prsh = " select co_id from ".PR_CONTENT_EXP." where co_id = '{$co_id}' ";
    $row_prsh = sql_fetch($sql_prsh);
    if ($row_prsh) {
      $sql_pr = "
        update ".PR_CONTENT_EXP." set
        co_value = '{$sql_prcommon}' where co_id = '{$co_id}'
      ";
    } else {
      $sql_pr = "
        insert ".PR_CONTENT_EXP." set
        co_id = '$co_id'
        , co_key = 'config'
        , co_value = '{$sql_prcommon}'
      ";
    }
    sql_query($sql_pr);
    // pr-child
}
else if ($w == "d")
{
    @unlink(G5_DATA_PATH."/content/{$co_id}_h");
    @unlink(G5_DATA_PATH."/content/{$co_id}_t");

    $sql = " delete from {$g5['content_table']} where co_id = '$co_id' ";
    sql_query($sql);

    // pr-child
    $sql_pr = "
      delete from ".PR_CONTENT_EXP."
      where co_id = '{$co_id}'
    ";
    sql_query($sql_pr);
    // pr-child
}

if(function_exists('get_admin_captcha_by'))
    get_admin_captcha_by('remove');

g5_delete_cache_by_prefix('content-'.$co_id.'-');

if ($w == "" || $w == "u")
{
    if ($_FILES['co_himg']['name'])
    {
        $dest_path = G5_DATA_PATH."/content/".$co_id."_h";
        @move_uploaded_file($_FILES['co_himg']['tmp_name'], $dest_path);
        @chmod($dest_path, G5_FILE_PERMISSION);
    }
    if ($_FILES['co_timg']['name'])
    {
        $dest_path = G5_DATA_PATH."/content/".$co_id."_t";
        @move_uploaded_file($_FILES['co_timg']['tmp_name'], $dest_path);
        @chmod($dest_path, G5_FILE_PERMISSION);
    }

    if( $error_msg ){
        alert($error_msg, "./contentform.php?w=u&amp;co_id=$co_id");
    } else {
        goto_url("./contentform.php?w=u&amp;co_id=$co_id");
    }
}
else
{
    goto_url("./contentlist.php");
}
?>
