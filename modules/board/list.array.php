<?
		$rot_num += 1;

		$idx			= stripslashes($array[idx]);
		$view_flag		= stripslashes($array[view_flag]);
		$category		= stripslashes($array[category]);
		$userid			= stripslashes($array[userid]);
		$username		= db2html($array[username]);
		$email			= stripslashes($array[email]);
		$homep			= stripslashes($array[homep]);
		$tel			= stripslashes($array[tel]);
		$title			= db2html($array[title]);
		$body			= db2html($array[body]);
		$regdate		= stripslashes($array[regdate]);
		$counts			= stripslashes($array[counts]);
		$thread			= stripslashes($array[thread]);
		$depth			= stripslashes($array[depth]);
		$pos			= stripslashes($array[pos]);
		$passwd			= stripslashes($array[passwd]);
		$user_file		= stripslashes($array[user_file]);
		$img_file		= stripslashes($array[img_file]);
		$user_ip		= stripslashes($array[user_ip]);
		$delflag		= stripslashes($array[delflag]);
		$is_secret		= stripslashes($array[is_secret]);
		$notice_yn		= stripslashes($array[notice_yn]);
		$site			= stripslashes($array[site]);
		$comment		= stripslashes($array[comment]);
		$counts_like	= stripslashes($array[counts_like]);
		$counts_bad		= stripslashes($array[counts_bad]);
		$tag			= stripslashes($array[tag]);
		$mailflag	 	= stripslashes($array[mailflag]);
		$smsflag	 	= stripslashes($array[smsflag]);
		$adminid	 	= stripslashes($array[adminid]);


		if($cfg_bbs_kind=="board") {
			// ---------  응답의 인덴트 -----------------
			if($depth > 0) {
				for($j = 2 ;$j <= $depth;$j++) {
				  $depth_str .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				}
				$depth_str .= "&nbsp;&nbsp;&nbsp;<i class=\"fa  fa-reply transform\"></i> ";
			}
		}

		$subject = cut_string($title, 50, "..");
		//$subject = WShortString($title, 60);

		list($_ymd,$_his) = explode(" ",$regdate);
		list($_year,$_month,$_day) = explode("-",$_ymd);
		list($_hour,$_min,$_sec) = explode(":",$_his);

		$_timestemp = mktime($_hour, $_min, $_sec, $_month, $_day, $_year);
		$_newdate = date("YmdHis",strtotime("+2 day", $_timestemp));
		if($_newdate > date("YmdHis")) {
			$new_img = "<span class=\"pull-right-container\"><small class=\"label  label-danger\">new</small></span>";
		} else {
			$new_img = "";
		}

		if(substr($regdate,0,10) == date("Y-m-d")) $regdate = substr($regdate,11,5);
		else $regdate = substr($regdate,0,10);

		//댓글수
		$sql= "select count(idx) as cnt from ".$initial."_bbs_boardcomment where idx is not null and fid = '$idx' and tbl ='$_t'";
		$rs = mysql_query($sql,$dbconn) or die (mysql_error());
		if($arr = mysql_fetch_array($rs)) {
			$cnt	= $arr[cnt];
		}

		//이미지정보
		$sql= "select * from ".$initial."_data_files where seq is not null and fid = '$idx' and tbl ='$_t'";
		$sql .= " and data_type='img'";
		$sql .= " and num='1'";
		$rs = mysql_query($sql,$dbconn) or die (mysql_error());
		if($arr = mysql_fetch_array($rs)) {
			$img_real_filename	= $arr[real_filename];
			$img_file_size      = stripslashes($arr[file_size]);
			$img_file_type      = stripslashes($arr[file_type]);
			$img_file_ext       = stripslashes($arr[file_ext]);
		}

		//본문내용중 이미지 추출
		$body = str_replace("/$upload_dir", $image_site_url."/$upload_dir", $body);
		$_img = getImageContent($body);
		$_img_count = count($_img);
		for ($i=0; $i<$_img_count; $i++) {
			//echo $_img[$i][0]."<br>"; // 파일명
			//echo $_img[$i][1]."<P>"; // 파일명을 뺀 URL 명

			$_img_ = $_img[$i][1].$_img[$i][0];
		}


		$contents	= strip_tags($body);
		$contents_length = strlen($contents);
		//$contents	= cut_string($contents,250, "..");
		$contents	= convertHashtags($contents);
?>