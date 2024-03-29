<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$latest_skin_url.'/style.css">', 0);
?>

<div class="lt_basic">
	<h2><strong><a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_title"><?php echo $bo_subject ?></strong></a></h2>
	<ul>
	<?php
    $count = count($list);
    for ($i=0; $i<$count; $i++) {
    ?>
        <li>
					<div>
        	<span class="lt_writer"><?php echo get_member_profile_img($list[$i]['mb_id']); ?></span>
            <?php
			if ($list[$i]['icon_secret']) echo "<i class=\"fa fa-lock\" aria-hidden=\"true\"></i> ";

			echo "<a href=\"".$list[$i]['href']."\" class=\"lt_tit\">";
			if ($list[$i]['is_notice'])
                echo "<strong>".$list[$i]['subject']."</strong>";
			else
				echo $list[$i]['subject'];
			if ($list[$i]['icon_new']) echo " <span class=\"new_icon\">N</span>";
			if ($list[$i]['icon_file']) echo " <i class=\"fa fa-download\" aria-hidden=\"true\"></i>";
			if ($list[$i]['icon_link']) echo " <i class=\"fa fa-link\" aria-hidden=\"true\"></i>";
			if ($list[$i]['icon_hot']) echo " <i class=\"fa fa-heart\" aria-hidden=\"true\"></i>";

			echo "</a>";
            ?>
					</div>
					<div>
						<?php //var_dump($list[$i])?>
						<span>
							<?php //echo $list[$i]['datetime']?>
							<?php
							if ($list[$i]['ca_name']) {
								echo '<span class="la_caname"><strong>'.$list[$i]['ca_name'].'</strong></span>';
							}
							?>
							<span class="la_caname">Hit:<strong class="scrap"><?php echo $list[$i]['wr_hit']?></strong></span>
							<span class="la_caname">Co:<strong><?php echo $list[$i]['wr_comment']?></strong> </span></span>
						<span class="lt_date"><i class="far fa-clock"></i> <?php echo $list[$i]['datetime2'] ?></span>
					</div>
		</li>
    <?php
    }
    if($i ==0)
        echo '<li class="empty_li">게시물이 없습니다.</li>'.PHP_EOL;
    ?>
	</ul>
	<a href="<?php echo get_pretty_url($bo_table); ?>" class="lt_more"><i class="fas fa-ellipsis-v"></i><span class="sound_only"><?php echo $bo_subject ?> 더보기</span></a>
</div>
