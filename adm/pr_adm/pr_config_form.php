<?php
$sub_menu = "109100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = 'CHILD 기본환경설정';
include_once (G5_ADMIN_PATH.'/admin.head.php');

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
            <th scope="row"><label for="pr_bell_days">알림(일수)<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="number" name="pr_bell_days" value="<?php echo $pr_config['bell_days']?>" id="pr_bell_days" required="" class="required numeric frm_input" size="3">
              일 지난후 삭제
            </td>
            <th scope="row"><label for="pr_bell_scrap">스크렙 알림</label></th>
            <td>
              <label id="pr_bell_scrap"><input type="radio" name="pr_bell_scrap" value="Y" <?php echo ($pr_config['bell_scrap'] == 'Y') ? 'checked' : ''?> required >YES</label>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <label id=""><input type="radio" name="pr_bell_scrap" value="N" <?php echo ($pr_config['bell_scrap'] == 'N') ? 'checked' : ''?> required >NO</label>
              <span class="frm_info">스크랩해둔 게시물의 알림을 받습니다. (속도저하를 가져울 수 있습니다.)</span>
            </td>
          </tr>

          <tr>
            <th scope="row"><label for="pr_bell_bo_title">게시판이름<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="number" name="pr_bell_bo_title" value="<?php echo $pr_config['bell_bo_title']?>" id="pr_bell_bo_title" required="" class="required numeric frm_input" size="3">
              <span class="frm_info">알림 발송시 게시판이름의 최대글자 수</span>
            </td>
            <th scope="row"><label for="pr_bell_subj">제목/댓글<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="number" name="pr_bell_subj" value="<?php echo $pr_config['bell_subj']?>" id="pr_bell_subj" required="" class="required numeric frm_input" size="3">
              <span class="frm_info">알림 발송시 제목 및 댓글내용 최대글자 수</span>
            </td>
          </tr>

          <tr>
            <th scope="row"><label for="pr_block_count">차단회원<strong class="sound_only">필수</strong></label></th>
            <td>
              <input type="number" name="pr_block_count" value="<?php echo $pr_config['block_count']?>" id="pr_block_count" required="" class="required numeric frm_input" size="3">
              <span class="frm_info">많은수를 입력시 속도저하를 가져울 수 있습니다.</span>
            </td>
            <th scope="row"><label for="pr_follow_count">팔로우회원<strong class="sound_only">필수</strong></label></label></th>
            <td>
              <input type="number" name="pr_follow_count" value="<?php echo $pr_config['follow_count']?>" id="pr_follow_count" required="" class="required numeric frm_input" size="3">
              <span class="frm_info">많은수를 입력시 속도저하를 가져울 수 있습니다.</span>
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
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>
