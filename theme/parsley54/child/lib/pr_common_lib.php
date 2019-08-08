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
$cfsql = " select exp_value from ".PR_CONFIG_EXP." where exp_type = 'pr_config' ";
$cfrow = sql_fetch($cfsql);
$pr['config'] = pr_json_decode($cfrow['exp_value']);

/*
$pr['theme_bell_ch'] = 'true';                              //알림기능을 사용여부
$pr['theme_at_ch'] = 'true';                                //@.js를 이용한 멘션기능을 사용여부
$pr['theme_frend_ch'] = 'true';                             //친구기능 사용여부
*/

//현재 디렉토리와 파일명을 반환 합니다.
$pr_php_self = explode("/", $_SERVER['PHP_SELF']);
$pr_file_cnt = count($pr_php_self) - 1;
$pr_dir_cnt = count($pr_php_self) - 2;
$pr_this_page = $pr_php_self[$pr_file_cnt];
$pr_this_dir = $pr_php_self[$pr_dir_cnt];

$pr_path = '/';

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

//알림문구를 배열로 정리
$pr['bell_message'] = array(
		'at_comment' => '[bo_title]에 댓글[bell_subject]에 회원님을 언급하셨습니다.'  ,
		'at_write' => '[bo_title]에 글[bell_subject]에 회원님을 언급하셨습니다.'  ,
		'memo' => '[bell_subject]의 쪽지가 도착했습니다.'  ,
		'comment' => '[bo_title]에 [bell_subject]댓글을 남기셨습니다.'  ,
		'good' => '[bo_title]의 [bell_subject]을 추천하셨습니다.'  ,
		'bed' => '[bo_title]의 [bell_subject]을 비추천하셨습니다.'  ,
		'friend' => '[friend][bo_title]에 글[bell_subject]를 작성하셨습니다.'
);

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


//해당일수 이상의 알림은 자동삭제
function pr_bell_del ($belldel_day) {
	global $pr;
	if ($del_day != 0) { // 해당일이 0이면 자동삭제 기능을 사용하지 않음
		$del_time =  date("Y-m-d", strtotime("-{$del_day}day")).' 00:00:00';
		$sql = "
			delete from {$pr['pr_table_exp']} where msg_wdate < '{$del_time}' and msg_check != 'd'
		";
		@sql_query($sql);
	}
} // srd_pushmsg_del

//json 관련 버전관련이슈가 생길지 몰라 생성
function pr_json_encode($in_array) {
	return json_encode ($in_array);
}
function pr_json_decode($in_array) {
	$in_array = json_decode ($in_array);
	//배열을 오브젝트로 변환
	$in_array = (array) $in_array;
	return $in_array;
}

//알림을 입력
function rd_bell_insert ($from_member , $to_member , $subj , $msg_link , $bell_type ) {
	global $opr;
	//G5_TIME_YMDHIS;
	$sql_insert = "
		insert into {$pr['pr_table_exp']}  set
	";
	//@sql_query($sql_insert);
}

//알림 읽음 처리
function pr_bell_update ($bell_id) {
}


/*
# 그누보드에서 사용되는 함수지만 추가적인 기능이 필요하거나 변경이 필요한 경우 복사됨
*/
//그누보드에서 복사된 함수
function pr_member_profile_img($mb_id=''){		// 이미지 url만을 치환받기위해 복사됨
    global $member;
    static $no_profile_cache = '';
    static $member_cache = array();
    $src = '';
    if( $mb_id ){
        if( isset($member_cache[$mb_id]) ){
            $src = $member_cache[$mb_id];
        } else {
            $member_img = G5_DATA_PATH.'/member_image/'.substr($mb_id,0,2).'/'.$mb_id.'.gif';
            if (is_file($member_img)) {
                $member_cache[$mb_id] = $src = str_replace(G5_DATA_PATH, G5_DATA_URL, $member_img);
            }
        }
    }
    if( !$src ){
        if( !empty($no_profile_cache) ){
            $src = $no_profile_cache;
        } else {
            // 프로필 이미지가 없을때 기본 이미지
            $no_profile_img = (defined('G5_THEME_NO_PROFILE_IMG') && G5_THEME_NO_PROFILE_IMG) ? G5_THEME_NO_PROFILE_IMG : G5_NO_PROFILE_IMG;
            $tmp = array();
            preg_match( '/src="([^"]*)"/i', $foo, $tmp );
            $no_profile_cache = $src = isset($tmp[1]) ? $tmp[1] : G5_IMG_URL.'/no_profile.gif';
        }
    }
    return $src;
}
//그누보드에서 복사된 함수 end

$pr_child_file = PR_THEME_CHILD.$pr_path.$pr_this_page;
?>
