<?
	session_start();
	header("Content-type:text/html;charset=utf-8");
	include "../../modules/dbcon.php";
	include "../../modules/config.php";
	include "../../modules/func.php";
	include "../../modules/func_q.php";
	include "../../modules/check.php";


	if($_t=="") $_t	= "members"; 				//테이블 이름
	$foldername = "../../$upload_dir/$_t/";

	if($page == '') $page = 1; 					//페이지 번호가 없으면 1
	$list_num = 30; 							//한 페이지에 보여줄 목록 갯수
	$page_num = 10; 							//한 화면에 보여줄 페이지 링크(묶음) 갯수
	$offset = $list_num*($page-1); 				//한 페이지의 시작 글 번호(listnum 수만큼 나누었을 때 시작하는 글의 번호)

	$rot_num = 0;

	//페이지 링크주소 매개변수를 여러개 넘길때는 끝에 '&' 추가
	$link_url = "?_t=$_t&search=$search&search_text=$search_text&popup=$popup&menu_b=$menu_b&menu_m=$menu_m&";


	if ($search_text != "") {
		$search_text   = trim(stripslashes(addslashes($search_text)));
		$qry_where .= " AND (userid like '%$search_text%')";
	}
	if($user_id) $qry_where .= " AND userid='".$user_id."'";

	$query  ="SELECT count(idx) as cnt FROM ".$initial."_".$_t."_log WHERE idx IS NOT NULL ";
	$query .= $qry_where;
	$result = mysql_query($query, $dbconn) or die (mysql_error());
	if($array=mysql_fetch_array($result)) {
		$total_no		= $array[cnt];
	}

	$total_page = ceil($total_no/$list_num);
	$cur_num = $total_no - $list_num*($page-1);

	$query  = "SELECT * FROM ".$initial."_".$_t."_log WHERE idx IS NOT NULL ";
	$query .= $qry_where;
	$query .= " ORDER BY in_date DESC LIMIT $offset, $list_num";
	$result = mysql_query($query, $dbconn) or die (mysql_error());


	include "../inc/header.php";
	//스크립트
	include "../js/goto_page.js.php";
?>
<?
	//상태 변경 셀렉트 메뉴
	include "../js/select_menu.js.php";
?>
<script language="javascript">
<!--
//-->
</script>

<form name="form" id="form">
<input type="hidden" name="idx" id="idx">
<input type="hidden" name="gubun" id="gbn">
<input type="hidden" name="_t" value="<?=$_t?>">
<input type="hidden" name="popup" id="pop" value="<?=$popup?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="search" value="<?=$search?>">
<input type="hidden" name="search_text" value="<?=$search_text?>">
<input type="hidden" name="menu_b" value="<?=$menu_b?>">
<input type="hidden" name="menu_m" value="<?=$menu_m?>">
<input type="hidden" name="menu_t" value="<?=$menu_t?>">
</form>


  <!-- Content Wrapper. Contains page content -->
  <div class="<?=$popup=="1"?"popup-":""?>content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        회원접속정보
        <small>회원로그인 정보내역입니다.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> 회원관리</a></li>
        <li class="active">회원접속정보</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->


      <div class="row">
        <div class="col-xs-12">
          <div class="box">
			<?if($popup!="1") {?>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li><a href="list.php?menu_b=<?=$menu_b?>&menu_m=<?=$menu_m?>">회원관리</a></li>
					<li class="active"><a href="log_list.php?menu_b=<?=$menu_b?>&menu_m=<?=$menu_m?>">회원접속내역</a></li>
				</ul>
			</div>
			<?}?>

			<form method="post" action="<?=$PHP_SELF?>" name="schform">
			<input type="hidden" name="_t" value="<?=$_t?>">
			<input type="hidden" name="menu_b" value="<?=$menu_b?>">
			<input type="hidden" name="menu_m" value="<?=$menu_m?>">
			<input type="hidden" name="menu_t" value="<?=$menu_t?>">
            <div class="box-header">
              <h3 class="box-title">Total : <?=$total_no?>, <?=$page?> / <?=$total_page?> page</h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="search_text" class="form-control pull-right" placeholder="Search" value="<?=$search_text?>">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
			</form>

            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
				<?=fn_page($total_page, $page_num, $page, $link_url)?>
              </ul>
            </div>

            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
			<form name="listform" id="listform" method="post">
			<input type="hidden" name="gubun" id="gubun">
			<input type="hidden" name="_t" value="<?=$_t?>">
			<input type="hidden" name="page" value="<?=$page?>">
			<input type="hidden" name="search" value="<?=$search?>">
			<input type="hidden" name="search_text" value="<?=$search_text?>">
			<input type="hidden" name="menu_b" value="<?=$menu_b?>">
			<input type="hidden" name="menu_m" value="<?=$menu_m?>">
			<input type="hidden" name="menu_t" value="<?=$menu_t?>">
              <table class="table table-hover" id="_table_">
                <tr>
                  <th nowrap width="20">No</th>
                  <th nowrap width="100">아이디</th>
                  <!-- <th nowrap width="200">디바이스 정보</th> -->
                  <th nowrap>브라우저 정보</th>
                  <th nowrap width="100">IP정보</th>
                  <th nowrap width="100">로그인</th>
                  <th nowrap width="100">로그아웃</th>
                </tr>
				<?
				if($total_no == 0) {
				?>
                <tr>
                  <td align="center" colspan="14">등록된 정보가 없습니다.</td>
                </tr>
				<?
				} else {
					while ($array = mysql_fetch_assoc($result)) {
						$rot_num += 1;

						foreach ($array as $tmpKey => $tmpValue) {
							$$tmpKey = $tmpValue;
						}// end foreach

						//제목자르기
						//$subject = cut_string($subject,42);
						//$subject = getNewicon2($subject,$regdate,$link);
				?>
                <tr>
                  <td nowrap data-title="번호"><?=$idx?></td>
                  <td nowrap data-title="아이디" class="f11">
				  <?=$userid?>
				  </td>
                  <!-- <td nowrap data-title="디바이스 정보" class="f11">
				  <b><?=$device?></b>
				  </td> -->
                  <td nowrap data-title="브라우저 정보" class="f11">
				  <?=$agent?>
				  </td>
                  <td nowrap data-title="IP 정보" class="f11"><?=$remote_ip?></td>
                  <td nowrap data-title="로그인" class="f11"><?=$in_date?></td>
                  <td nowrap data-title="로그아웃" class="f11"><?=$out_date?></td>
                </tr>
				<?
						$cur_num --;
					}
				}
				?>
              </table>
			 </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
				<?=fn_page($total_page, $page_num, $page, $link_url)?>
              </ul>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>



    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?
include "../inc/footer.php";
mysql_close($dbconn);
?>