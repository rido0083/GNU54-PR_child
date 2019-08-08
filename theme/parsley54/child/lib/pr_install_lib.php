<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
//기본 테이블이 없다면 생성합니다.
$is_config_db = pr_exist_table (PR_CONFIG_EXP);

if ($is_config_db == 'false') {

	//디비를 생성함
  /*
  하나의 테이블 확장으로 작업하려 했으나 몇개의 확장 테이블을 지정해둠

    `exp_id`            자동생성 번호 입니다.
    `exp_key`           타입을 지정합니다.
    `exp_value`         사용할 데이터로 사용합니다 (json 타입으로 저장됩니다.)
    `wdate`             작성일
    `udate`             수정일
  */
	$is_add_exp = "
		CREATE TABLE IF NOT EXISTS `".PR_CONFIG_EXP."` (
			`exp_id` int(11) NOT NULL auto_increment,
			`exp_key` varchar(255) NOT NULL default '',
			`exp_value` text NOT NULL default '',
      `wdate` datetime NOT NULL default '0000-00-00 00:00:00',
      `udate` datetime NOT NULL default '0000-00-00 00:00:00',
			PRIMARY KEY  (`exp_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8
	";
	//echo $is_add_exp;
	@sql_query($is_add_exp);

	//기본 환경설정을 지정한다. pr_config
	$pr_insert = array();
	$pr_insert['bell_days'] = 180;											//알림을 자동삭제
	$pr_insert['frend_count'] = 50;											//
	$pr_insert['block_member_count'] = 50;							//
	$pr_insert['frend_count'] = 50;											//
	$pr_insert['frend_count'] = 50;											//
	$pr_insert['frend_count'] = 50;											//
	$pr_insert['frend_count'] = 50;											//

	$insert_value = pr_json_encode($pr_insert);
	$query = "
		 insert into ".PR_CONFIG_EXP." set exp_key = 'pr_config' , exp_value='{$insert_value}' , wdate = '".G5_TIME_YMDHIS."'
	";
	@sql_query($query);
}

?>
