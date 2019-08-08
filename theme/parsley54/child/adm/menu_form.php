<?php
$sub_menu = "100290";
include_once('./_common.php');

/*
project PR
메뉴에서 page를 추가하기 위해서 child로 생성됨
*/

if ($is_admin != 'super')
    alert_close('최고관리자만 접근 가능합니다.');

$g5['title'] = '메뉴 추가';

include_once(G5_PATH.'/head.sub.php');

$code = isset($code) ? preg_replace('/[^0-9a-zA-Z]/', '', strip_tags($code)) : '';

// 코드
if($new == 'new' || !$code) {
    $code = base_convert(substr($code,0, 2), 36, 10);
    $code += 36;
    $code = base_convert($code, 10, 36);
}
?>
<div id="menu_frm" class="new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <form name="fmenuform" id="fmenuform" class="new_win_con">

    <div class="new_win_desc">
        <label for="me_type">대상선택</label>
        <select name="me_type" id="me_type">
            <option value="">직접입력</option>
            <option value="group">게시판그룹</option>
            <option value="board">게시판</option>
            <option value="page">페이지</option>
            <option value="content">내용관리</option>
        </select>
    </div>

    <div id="menu_result"></div>

    <div id="menu_page" style="display:none">
      <div id="menu_result">
      <div class="tbl_head01 tbl_wrap">
          <table>
          <thead>
          <tr>
              <th scope="col">제목</th>
                      <th scope="col">선택</th>
          </tr>
          </thead>
          <tbody>



                  <?php
                  // 폴더명 지정
                  $dir = PR_PAGE_PATH;
                  // 핸들 획득
                  $handle  = opendir($dir);
                  $files = array();
                  // 디렉터리에 포함된 파일을 저장한다.
                  while (false !== ($filename = readdir($handle))) {
                      if($filename == "." || $filename == ".."){
                          continue;
                      }
                      // 파일인 경우만 목록에 추가한다.
                      if(is_file($dir . "/" . $filename)){
                          $files[] = $filename;
                      }
                  }
                  // 핸들 해제
                  closedir($handle);
                  // 정렬, 역순으로 정렬하려면 rsort 사용
                  sort($files);
                  // 파일명을 출력한다.
                  foreach ($files as $f) {
                      $page_name = explode('.',$f);
                  ?>
                  <tr>
                      <td><?php echo $f ?></td>
                              <td class="td_mngsmall">
                                <input type="hidden" name="link[]" value="<?php echo G5_URL ?>/?page=<?php echo $page_name[0]?>">
                          <input type="hidden" name="subject[]" value="<?php echo $page_name[0]?>">
                          <button type="button" class="add_select btn btn_03"><span class="sound_only"><?php echo $f ?> </span>선택</button>
                      </td>
                  </tr>
                  <?php
                  }
                  ?>



          </tbody>
          </table>
      </div>

      <div class="local_desc01 menu_exists_tip" style="display:none">
          <p>* <strong>빨간색</strong>의 제목은 이미 메뉴에 연결되어 경우 표시됩니다.</p>
      </div>

      <div class="btn_win02 btn_win">
          <button type="button" class="btn_02 btn" onclick="window.close();">창닫기</button>
      </div>

      </div>
    </div>

    </form>

</div>

<script>
$(function() {
    $("#menu_result").load(
        "./menu_form_search.php"
    );

    function link_checks_all_chage(){

        var $links = $(opener.document).find("#menulist input[name='me_link[]']"),
            $o_link = $(".td_mngsmall input[name='link[]']"),
            hrefs = [],
            menu_exist = false;

        if( $links.length ){
            $links.each(function( index ) {
                hrefs.push( $(this).val() );
            });

            $o_link.each(function( index ) {
                if( $.inArray( $(this).val(), hrefs ) != -1 ){
                    $(this).closest("tr").find("td:eq( 0 )").addClass("exist_menu_link");
                    menu_exist = true;
                }
            });
        }

        if( menu_exist ){
            $(".menu_exists_tip").show();
        } else {
            $(".menu_exists_tip").hide();
        }
    }

    function menu_result_change( type ){

        var dfd = new $.Deferred();

        $("#menu_result").empty().load(
            "./menu_form_search.php",
            { type : type },
            function(){
                dfd.resolve('Finished');
            }
        );

        return dfd.promise();
    }

    $("#me_type").on("change", function() {
        var type = $(this).val();
        //project RD
        //페이지일경우 추가됨
        if (type == 'page') {
          $("#menu_result").html('');
          $("#menu_page").show();
          return false;
        }
        //페이지일경우 추가됨 ==>
        var promise = menu_result_change( type );

        promise.done(function(message) {
            link_checks_all_chage(type);
        });

    });

    $(document).on("click", "#add_manual", function() {
        var me_name = $.trim($("#me_name").val());
        var me_link = $.trim($("#me_link").val());

        add_menu_list(me_name, me_link, "<?php echo $code; ?>");
    });

    $(document).on("click", ".add_select", function() {
        var me_name = $.trim($(this).siblings("input[name='subject[]']").val());
        var me_link = $.trim($(this).siblings("input[name='link[]']").val());

        add_menu_list(me_name, me_link, "<?php echo $code; ?>");
    });
});

function add_menu_list(name, link, code)
{
    var $menulist = $("#menulist", opener.document);
    var ms = new Date().getTime();
    var sub_menu_class;
    <?php if($new == 'new') { ?>
    sub_menu_class = " class=\"td_category\"";
    <?php } else { ?>
    sub_menu_class = " class=\"td_category sub_menu_class\"";
    <?php } ?>

    var list = "<tr class=\"menu_list menu_group_<?php echo $code; ?>\">";
    list += "<td"+sub_menu_class+">";
    list += "<label for=\"me_name_"+ms+"\"  class=\"sound_only\">메뉴<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"hidden\" name=\"code[]\" value=\"<?php echo $code; ?>\">";
    list += "<input type=\"text\" name=\"me_name[]\" value=\""+name+"\" id=\"me_name_"+ms+"\" required class=\"required frm_input full_input\">";
    list += "</td>";
    list += "<td>";
    list += "<label for=\"me_link_"+ms+"\"  class=\"sound_only\">링크<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"me_link[]\" value=\""+link+"\" id=\"me_link_"+ms+"\" required class=\"required frm_input full_input\">";
    list += "</td>";
    list += "<td class=\"td_mng\">";
    list += "<label for=\"me_target_"+ms+"\"  class=\"sound_only\">새창</label>";
    list += "<select name=\"me_target[]\" id=\"me_target_"+ms+"\">";
    list += "<option value=\"self\">사용안함</option>";
    list += "<option value=\"blank\">사용함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td class=\"td_numsmall\">";
    list += "<label for=\"me_order_"+ms+"\"  class=\"sound_only\">순서<strong class=\"sound_only\"> 필수</strong></label>";
    list += "<input type=\"text\" name=\"me_order[]\" value=\"0\" id=\"me_order_"+ms+"\" required class=\"required frm_input\" size=\"5\">";
    list += "</td>";
    list += "<td class=\"td_mngsmall\">";
    list += "<label for=\"me_use_"+ms+"\"  class=\"sound_only\">PC사용</label>";
    list += "<select name=\"me_use[]\" id=\"me_use_"+ms+"\">";
    list += "<option value=\"1\">사용함</option>";
    list += "<option value=\"0\">사용안함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td class=\"td_mngsmall\">";
    list += "<label for=\"me_mobile_use_"+ms+"\"  class=\"sound_only\">모바일사용</label>";
    list += "<select name=\"me_mobile_use[]\" id=\"me_mobile_use_"+ms+"\">";
    list += "<option value=\"1\">사용함</option>";
    list += "<option value=\"0\">사용안함</option>";
    list += "</select>";
    list += "</td>";
    list += "<td class=\"td_mngsmall\">";
    <?php if($new == 'new') { ?>
    list += "<button type=\"button\" class=\"btn_add_submenu btn\">추가</button>";
    <?php } ?>
    list += "<button type=\"button\" class=\"btn_del_menu btn\">삭제</button>";
    list += "</td>";
    list += "</tr>";

    var $menu_last = null;

    if(code)
        $menu_last = $menulist.find("tr.menu_group_"+code+":last");
    else
        $menu_last = $menulist.find("tr.menu_list:last");

	if($menu_last.size() > 0) {
        $menu_last.after(list);
    } else {
        if($menulist.find("#empty_menu_list").size() > 0)
            $menulist.find("#empty_menu_list").remove();

        $menulist.find("table tbody").append(list);
    }

    $menulist.find("tr.menu_list").each(function(index) {
        $(this).removeClass("bg0 bg1")
            .addClass("bg"+(index % 2));
    });

    window.close();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');

//project PR
//마지막은 항상 닫아주세요
exit();
?>
