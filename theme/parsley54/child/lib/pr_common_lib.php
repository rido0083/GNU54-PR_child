<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

##############################################################
#
# 그누보드5.3을 위한 확장 빌드 BUILD rd
#
##############################################################

/**
 * rd_THEME Ver 0.1 testver
 * 제작자 : Rido
 * 제작자 메일 : rido0083@gmai.com
 * GITHUB :
 *
 *
*
*	본테마는 그누보드의 확장성과 기타 rd 프로젝트의 플러그인들을 사용하기 위해 제작되었습니다.
*	일단 기본족인 child의 개념의 워드프레스의 그것과 비슷한 개념을 가지고 있습니다.
*
*	자세한 사항은 theme폴더의 README.txt에 기재하겠습니다.
*
*
*/

//필요한 데이터베이스를 생성한다.
include_once(PR_THEME_CHILD . '/lib/pr_install_lib.php');


//기본 config를 할당
$cfsql = " select exp_value from ".PR_CONFIG_EXP." where exp_key = 'pr_config' ";
$cfrow = sql_fetch($cfsql);
$pr_config = pr_json_decode($cfrow['exp_value']);

switch ($pr_this_dir) {
	case 'adm':	//관리자를 대체합니다.
	case 'pr_adm':
		$pr_path = '/adm/';
		break;
	case 'bbs':	//게시판등 기본 그누보드를 대체합니다.
		$pr_path = '/bbs/';
		break;
	case 'plugin':	//플로그인등을 대체합니다.
		$pr_path = '/plugin/';
		break;
}


//해당 플러그인에 필요한 디비가 있는지를 체크후 없다면 디비생성
function pr_exist_table($table_name) {
	//echo "SHOW TABLES LIKE '{$table_name}'";
  $result = @sql_fetch("SHOW TABLES LIKE '{$table_name}'");
	$return = 'false';
	if ($result) {
		$return = 'true';
	}
	return $return;
}

//해당일이 글쓴날에서 얼마나 지났는지를 리턴
function pr_date_return ($datetime) {
	//그누보드 익명닉네임 이 알려주신 팁
	$_timestamp = array(86400*365, 86400*31, 86400, 3600, 60, 1);
	$_timetitle = array("년 전", "개월 전", "일 전", "시간 전", "분 전", "초 전");

	$d = strtotime($datetime);

	foreach($_timestamp as $key => $value)
	if($d <= time() - $value) return (int)((time() - $d)/$_timestamp[$key]).$_timetitle[$key];
} // emd srd_date_return

//json 관련 버전관련이슈가 생길지 몰라 생성
function pr_json_encode($in_array) {
	return json_encode ($in_array , JSON_UNESCAPED_UNICODE);
}
function pr_json_decode($in_array) {
	$in_array = json_decode ($in_array);
	//배열을 오브젝트로 변환
	$in_array = (array) $in_array;
	return $in_array;
}


//해당 게시물의 정보틀 반환한다.
function pr_get_write($bo_table , $wr_id) {
	global $g5;
	$sql = " select * from ".$g5['write_prefix'] . $bo_table." where wr_id = {$wr_id}";
	$get_write = sql_fetch($sql);
	$get_write['href'] = get_pretty_url($bo_table, $get_write['wr_id'], '');
	return $get_write;
}


//해당 회원의 팔로잉 수
function pr_follow_count () {
	global $member;
}


//해당 회원이 차단한 회원 수
function pr_block_count () {
	global $member;
}


$pr_child_file = PR_THEME_CHILD.$pr_path.$pr_this_page;
$is_file_child = file_exists($pr_child_file);


//head.php를 호출하지 않는 update페이지
//$pr_this_page
$pr_updatepage_ar = array();
$pr_updatepage_ar = array(
	'contentformupdate.php'
	,'.php'
);
if (in_array($pr_this_page , $pr_updatepage_ar)) {
	if ($is_file_child) {
		include_once($pr_child_file);
		//exit;
	}
}
// add_event('prhead','pr_addhead',20,0);
// function pr_addhead() {
// 	global $is_file_child;
// 	if ($is_file_child) {
// 		include_once($pr_child_file);
// 		//exit;
// 	}
// }

//shotcode를 반환한다.
add_event('tail_sub','pr_shotcode',20,0);
function pr_shotcode() {
	//shocode 관련 js를 불러온다. (아직은 테스트중..)
	//function add_javascript($javascript, $order=0)
	// add_javascript(PR_AJAX_JS.'/pr_common.js', 0);
?>
	<script>
	$(document).ready(function(){

	  var html = $("#bo_v_atc").html();
	  if ($("#bo_v_atc").lenth > 0 ) {
	    html = html.split("[shotcode").join("<span class='pr_shotcode' ");
	    html = html.split("/shotcode]").join("</span>");
	    $("#bo_v_atc").html(html);

	     $(".pr_shotcode").each(function(){
	      var code_name = $(this).data("name");
	      var code_array = $(this).data("array");
	      $(this).html(code_name);
	     });
	  }

	  $(".shotcode").each(function(index){
	    var code_name = $(this).data("name");
	    var code_array = $(this).data("array");

	    var request = $.ajax({
	      url: "<?php echo PR_AJAX_URL?>/pr_shotcode.php",
	      method: "POST",
	      data: {
	        pr_shortcode : 'true'
	        , code_name : code_name
	        , code_array : code_array
	      },
	      dataType: "html"
	    });

	    request.done(function( data ) {
	      $('.shotcode').eq(index).html( data );
	    });
	  });
	});

	</script>
<?php
}
?>
