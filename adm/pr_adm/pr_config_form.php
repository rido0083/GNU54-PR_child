<?php
$sub_menu = "109100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = 'CHILD 기본환경설정';
include_once ('../admin.head.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');
?>

<form name="fconfigform" id="fconfigform" method="post" onsubmit="return fconfigform_submit(this);">
<input type="hidden" name="token" value="" id="token">

<div class="btn_fixed_top btn_confirm">
    <input type="submit" value="확인" class="btn_submit btn" accesskey="s">
</div>

<section id="anc_bo_basic">
    <h2 class="h2_frm">CHILD 기본환경설정</h2>
    <?php echo $pg_anchor ?>
    <?php echo $pr_child_file;
    echo $pr_this_dir;
    ?>

    <div class="local_desc02 local_desc">
      <p>PR_CHILD의 기본사용 옵션을 지정합니다..</p>
    </div>
    <div class="tbl_frm01 tbl_wrap">
      <table>
        <caption>CHILD 기본환경설정</caption>
        <colgroup>
          <col class="grid_4">
          <col>
          <col class="grid_4">
          <col>
        </colgroup>
        <tbody>
          <tr>
            <th scope="row"><label for="cf_delay_sec">글쓰기 간격<strong class="sound_only">필수</strong></label></th>
            <td><input type="text" name="cf_delay_sec" value="30" id="cf_delay_sec" required="" class="required numeric frm_input" size="3"> 초 지난후 가능</td>
            <th scope="row"><label for="cf_link_target">새창 링크</label></th>
            <td>
              <span class="frm_info">글내용중 자동 링크되는 타켓을 지정합니다.</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
</section>

</form>

<script>
function fconfigform_submit(f)
{
    f.action = "./pr_config_form_update.php";
    return true;
}
</script>

<?php
include_once ('../admin.tail.php');
?>
