<?php // m3cron ver 1.10
include_once("./_common.php");
$sub_menu = "109200";
//include_once(PR_LIB_PATH.'/m3cron.lib.php');

$g5['title'] = "m3cron 관리자";
include_once(G5_ADMIN_PATH.'/admin.head.php');

// DB 없으면 생성
sql_query( "CREATE TABLE IF NOT EXISTS `{$m3cron['config_table']}` (
	`name` VARCHAR( 50 ) NOT NULL ,
	`descript` VARCHAR ( 255 ) NOT NULL ,
	`type` VARCHAR( 10 ) NOT NULL ,
	`d` TINYINT NOT NULL ,
	`w` TINYINT NOT NULL ,
	`h` TINYINT NOT NULL ,
	`lastrun` DATETIME NOT NULL ,
	`lastruntime` FLOAT NOT NULL ,
	`status` TINYINT NOT NULL ,
	`robot` TINYINT NOT NULL ,
	UNIQUE ( `name` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ");

sql_query( "CREATE TABLE IF NOT EXISTS `{$m3cron['log_table']}` (
	`name` VARCHAR( 50 ) NOT NULL ,
	`datetime` DATETIME NOT NULL ,
	`runtime` FLOAT NOT NULL ,
	`ip` CHAR( 15 ) NOT NULL ,
	`robot` TINYINT NOT NULL ,
	`mb_id` VARCHAR( 50 ) NOT NULL ,
	INDEX (  `name` ,  `datetime` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8 "  );

// 존재하는 파일 목록 가져오기
if (is_dir($m3cron['path'])) {
	// php 파일 include
	$dir = dir($m3cron['path']);
	while ($entry = $dir->read()) {
		if(preg_match('/\.php$/i', $entry)) $m3cron['list'][] = $entry;
	}
}

// 이하는 m3cron 폴더에 파일이 있을 경우에만 실행
if($m3cron['list']) {

	// m3cron_config에 있지만 파일이 없는 놈들은 삭제
	$query = sql_query("select name from `{$m3cron['config_table']}`");
	while($row = sql_fetch_array($query)) {
		if(!in_array($row['name'], $m3cron['list'])) sql_query("delete from `{$m3cron['config_table']}` where name='{$row['name']}' limit 1");
	}

	// m3cron_config에 입력하기. 에러 무시하면 unique가 걸려있으므로 새로운 녀석들만 들어감
	foreach($m3cron['list'] as $name) {
	sql_query("insert into `{$m3cron['config_table']}` set name='{$name}'", false);
	}

	// 목록 보이기 시작
	$query = sql_query("select * from `{$m3cron['config_table']}`");
	$cnt = sql_num_rows($query);
	$temp = sql_fetch("select count(*) as cnt2 from `{$m3cron['config_table']}` where status='1'");
	$cnt2 = $temp['cnt2'];
	$temp = sql_fetch("select count(*) as cnt3 from `{$m3cron['config_table']}` where status!='1'");
	$cnt3 = $temp['cnt3'];
?>
<style>
<!--
.active {background: #eaeaea !important;}
.On {font-weight:bold;}
-->
</style>
<div class="local_ov01 local_ov">
    <span class="btn_ov01"><span class="ov_txt">총프로그램 </span><span class="ov_num"></span></span>
    <span class="btn_ov01"><span class="ov_txt"> 건수  </span><span class="ov_num"><?php echo number_format($cnt)?>개</span></span>
    <span class="btn_ov01"><span class="ov_txt"> 실행 중  </span><span class="ov_num On"> <?php echo number_format($cnt2)?>개</span></span>
    <span class="btn_ov01"><span class="ov_txt"> 비활성화  </span><span class="ov_num"> <?php echo number_format($cnt3)?>개</span></span>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption>m3cron 관리자 목록</caption>
    <thead>
    <tr>
        <th scope="col">파일</th>
        <th scope="col">주기</th>
        <th scope="col">일</th>
        <th scope="col">요일</th>
        <th scope="col">시</th>
        <th scope="col">마지막 실행</th>
        <th scope="col">실행시간</th>
        <th scope="col">로봇</th>
        <th scope="col">상태</th>
        <th scope="col">수정</th>
	</tr>
    </thead>
    <tbody>

<?php	// 루프 돌리기
	while($prog = sql_fetch_array($query)) {
?>
<tr class="<?php echo $prog['status']?" active":""?>">
	<td><span title="<?php echo $prog['descript']?>"><?php echo $prog['name']?></span></td>
	<td><?php echo $prog['type']?></td>
	<td<?php echo $prog['type']=="monthly"?"":" class='pale'"?>><?php echo $prog['d']?></td>
	<td<?php echo $prog['type']=="weekly"?"":" class='pale'"?>><?php echo $day_arr[$prog['w']]?></td>
	<td><?php echo $prog['h']?></td>
	<td><?php echo $prog['lastrun']?></td>
	<td class="align_r"><?php echo sprintf("%.3f", $prog['lastruntime']*1000)?> msec</td>
	<td><?php echo $robot_arr[$prog['robot']]?></td>
	<td><span class="<?php echo $status_arr[$prog['status']]?>"><?php echo $status_arr[$prog['status']]?></span></td>
	<td><a href="./m3cron_edit.php?name=<?php echo urlencode($prog['name'])?>" class="btn btn_03">수정</a>
</td></tr>
<?php 	} ?>
</tbody>
</table>
</div>
<?php
	}
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
