<?
	session_start();
	header("Content-type:text/html;charset=utf-8");
	include "../../modules/dbcon.php";
	include "../../modules/config.php";
	include "../../modules/func.php";
	include "../../modules/func_q.php";


	$tbl	= "members"; 							//테이블 이름
	$foldername = "../../$upload_dir/$tbl/";

	if ($UID) {
		$query = "select * from ".$initial."_".$tbl." where user_id='".$UID."'"; // 글 번호를 가지고 조회를 합니다.
		$result = mysql_query($query, $dbconn) or die (mysql_error());
		if($array = mysql_fetch_array($result)) {

			foreach ($array as $tmpKey => $tmpValue) {

				$$tmpKey = $tmpValue;
			}// end foreach

			list($user_hp1,$user_hp2,$user_hp3) = explode("-",$user_hp);
			list($client_tel1,$client_tel2,$client_tel3) = explode("-",$client_tel);
			list($client_tel1,$client_tel2,$client_tel3) = explode("-",$client_tel);

			list($edu_charge_tel1,$edu_charge_tel2,$edu_charge_tel3) = explode("-",$edu_charge_tel);

		}

	} else {
		//등록인 경우
		list($user_hp1,$user_hp2,$user_hp3) = explode("-",$user_hp);
		$remark		= "";


	}

	include "../inc/header.php";
?>

<script type='text/javascript'>
/* 약관동의 - 전체동의 */
function allCheckFunc( obj ) {
		//$("[name=check-agree]").prop("checked", $(obj).prop("checked") );
		$(".check-agree").prop("checked", $(obj).prop("checked") );
}

/* 체크박스 체크시 전체선택 체크 여부 */
function oneCheckFunc( obj )
{
	var allObj = $("[name=check-all]");
	var objName = $(obj).attr("name");

	if( $(obj).prop("checked") )
	{
		checkBoxLength = $("[name="+ objName +"]").length;
		checkedLength = $("[name="+ objName +"]:checked").length;

		if( checkBoxLength == checkedLength ) {
			allObj.prop("checked", true);
		} else {
			allObj.prop("checked", false);
		}
	}
	else
	{
		allObj.prop("checked", false);
	}
}

$(function(){
	$("[name=check-all]").click(function(){
		allCheckFunc( this );
	});
	$(".check-agree").each(function(){
		$(this).click(function(){
			oneCheckFunc( $(this) );
		});
	});
});





$(document).ready(function(){

	$("#join_submit").click(function() {

		<?if($idx == "") {?>
		if($.trim($('#user_name').val()) == ''){
			alert("Name is a required field.");
			$('#user_name').focus();
			return false;
		}
		if($.trim($('#user_id').val()) == ''){
			alert("ID is a required field.");
			$('#user_id').focus();
			return false;
		}
		if($.trim($('#user_pwd').val()) == ''){
			alert("Password is a required field.");
			$('#user_pwd').focus();
			return false;
		}
		if($.trim($('#user_pwd2').val()) == ''){
			alert("Please check your password again.");
			$('#userpwd2').focus();
			return false;
		}
		if($.trim($('#user_pwd').val()) != $.trim($('#user_pwd2').val())){
			alert("Password is not the same.");
			$('#user_pwd2').focus();
			return false;
		}
		<?}?>
		if($(':radio[name="user_sex"]').is(":checked") == false) {
			alert("Please select gender.");
			$('#man').focus();
			return false;
		}
		if($.trim($('#datepicker').val()) == ''){
			alert("Please enter the date of birth.");
			$('#datepicker').focus();
			return false;
		}
		if($.trim($('#user_hp').val()) == ''){
			alert("Orderer's phone number is required.");
			$('#user_hp').focus();
			return false;
		}

		<?if($grownup == "0") {?>
		if($.trim($('#parent_name').val()) == ''){
			alert("parent name is required field.");
			$('#parent_name').focus();
			return false;
		}
		if($.trim($('#parent_tel').val()) == ''){
			alert("parent contact is required field.");
			$('#parent_tel').focus();
			return false;
		}
		<?}?>
		if($.trim($('#user_email').val()) == ''){
			alert("e-mail adress is required field.");
			$('#user_email').focus();
			return false;
		}
		if($.trim($('#addr3').val()) == ''){
			alert("Address is required field.");
			$('#addr3').focus();
			return false;
		}
		if($.trim($('#addr2').val()) == ''){
			alert("City is required field.");
			$('#addr2').focus();
			return false;
		}
		if($.trim($('#addr1').val()) == ''){
			alert("State/Province/County is required field.");
			$('#addr1').focus();
			return false;
		}
		if($.trim($('#zipcode').val()) == ''){
			alert("ZIP/PostalCode is required field.");
			$('#zipcode').focus();
			return false;
		}
		if($.trim($('#idchk').val()) == '' || $.trim($('#idchk').val()) == 'no'){
			alert("ID is not checked.");
			$('#user_id').focus();
			return false;
		}

		var ag1 = $("#ind-agree-1").is(":checked");
		var ag2 = $("#ind-agree-2").is(":checked");
		var ag3 = $("#ind-agree-3").is(":checked");
		//var ag4 = $("#ind-agree-4").is(":checked");		//일정알림 수락(선택사항)
		if(!(ag1==true && ag2==true && ag3==true)) {
			alert("You can join the membership only if you agree.");
			$("#agreeAll").focus();
			return;
		}

		$("#fm").attr("target", "_self");
		$("#fm").attr("method", "post");
		$("#fm").attr("action", "join_ok.php");
		$("#fm").submit();
	});

	var checkAjaxSetTimeout;
    $('#user_id').keyup(function(){
        clearTimeout(checkAjaxSetTimeout);
        checkAjaxSetTimeout = setTimeout(function() {

			if ($('#user_id').val().length >= 4) {
				var id = $('#user_id').val();

				// ajax 실행
				$.ajax({
					type : 'POST',
					url : 'join_ok.php',
					data : {'user_id':id,'gubun':'idcheck'},
					success : function(data) {
						if (data == "ok") {
							$("#idcheck").html("<span style='color:#0066cc;'>사용 가능한 아이디 입니다.</span>");
							$("#idchk").val(data);
						} else {
							$("#idcheck").html("<span style='color:#FF3300;'>사용 중인 아이디 입니다.</span>");
							$("#idchk").val(data);
						}
					}
				}); // end ajax
			} else {
				$("#idcheck").html("<span style='color:#339900;'>6자이상을 입력하세요</span>");
			}

		},500); //end setTimeout

    }); // end keyup
});

</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
	$( function() {
		$( "#datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true
		});
	} );
</script>

		<!-- container -->
		<div id="container">

			<section>
				<div class="cont_sign">
					<h2>Sign Up</h2>
					<form method="post" name="fm" id="fm" enctype="multipart/form-data">
					<input type="hidden" name="page" value='<?=$page?>'>
					<input type="hidden" name="idx" value='<?=$idx?>'>
					<input type="hidden" name="gubun" value='<?=$gubun?>'>
					<input type="hidden" name="popup" value="<?=$popup?>">
					<input type="hidden" name="grownup" value="<?=$grownup?>">
					<input type="hidden" name="idchk" id="idchk" value="">
						<article>
							<div class="infoBox">


								<ul class="inList">
									<li>
										<label for="name">Name</label>
										<ul>
											<li><input type="text" class="itx int1" name="user_name" id="user_name" value=""></li>
										</ul>
									</li>
									<li>
										<label for="id">ID</label>
										<ul>
											<li>
											<?if($UID == "") {?>
											<input type="text" class="itx int1" name="user_id" id="user_id" value="">
											<span id="idcheck"></span>
											<?} else {?>
											<?=$user_id?>
											<?}?>
											</li>
										</ul>
										<!-- <input type="button" class="ibx1" value="ID duplication check"> -->
									</li>
									<li>
										<label for="pw">Password</label>
										<ul>
											<li><input type="password" class="itx int1" name="user_pwd" id="user_pwd" value=""></li>
										</ul>
									</li>
									<?if($UID == "") {?>
									<li>
										<label for="c-pw">Confirm Password</label>
										<ul>
											<li><input type="password" class="itx int1" name="user_pwd2" id="user_pwd2" value=""></li>
										</ul>
									</li>
									<?}?>
									<li>
										<label>Sex / Date of birth</label>
										<ul class="d-type">
											<li><input type="radio" name="user_sex" id="man" value="M" <?=$user_sex=="M"?"checked":""?>> <label for="man">Man</label></li>
											<li><input type="radio" name="user_sex" id="woman" value="F" <?=$user_sex=="F"?"checked":""?>> <label for="woman">Woman</label></li>
											<li class="dob first">
											<input type="text" class="itx int1" name="user_birth" id="datepicker" value="">
											<!--
												<select class="selec1 int2" name="user_birth">
													<option value="">Year</option>
													<?for($i=date("Y");$i > 1900 ;$i--) {?>
													<option value="<?=$i?>"><?=$i?></option>
													<?}?>
												</select> -->
											</li><!--
											<li class="dob">
												<select class="selec1 int2">
													<option>Month</option>
												</select>
											</li>
											<li class="dob">
												<select class="selec1 int2">
													<option>Day</option>
												</select>
											</li> -->
										</ul>
									</li>
									<li>
										<label for="user_hp">Contact</label>
										<ul>
											<li><input type="text" class="itx int3" name="user_hp" id="user_hp" value=""></li>
										</ul>
										<!-- <ul class="d-type">
											<li>
												<select class="selec1 int2">
													<option>Select</option>
												</select>
											</li>
											<li class="dash">-</li>
											<li>
												<input type="text" class="itx int2">
											</li>
											<li class="dash">-</li>
											<li>
												<input type="text" class="itx int2">
											</li>
										</ul> -->
									</li>
								</ul>
							</div>
						</article>
						<?if($grownup=="0") {?>
						<article>
							<div class="infoBox">
								<h3>· Parental Information</h3>
								<ul class="inList">
									<li>
										<label for="p-name">Parent's name</label>
										<ul>
											<li><input type="text" class="itx int1" name="parent_name" id="parent_name"></li>
										</ul>
									</li>
									<li>
										<label for="p-contact">Contact</label>
										<ul>
											<li><input type="text" class="itx int3" name="parent_tel" id="parent_tel" value=""></li>
										</ul>
										<!-- <ul class="d-type">
											<li>
												<select class="selec1 int2">
													<option>Select</option>
												</select>
											</li>
											<li class="dash">-</li>
											<li>
												<input type="text" class="itx int2">
											</li>
											<li class="dash">-</li>
											<li>
												<input type="text" class="itx int2">
											</li>
										</ul> -->
									</li>
								</ul>
							</div>
						</article>
						<?}?>
						<article>
							<div class="infoBox">
								<h3>· Additional Information</h3>
								<ul class="inList">
									<li>
										<label for="user_email">e-mail</label>
										<ul>
											<li><input type="text" class="itx int3" name="user_email" id="user_email"></li>
										</ul>
									</li>
									<li>
										<label for="home-addr">Home address</label>
										<ul class="d-type-addr">
											<li>
												<input type="text" class="itx int4" name="addr3" id="addr3" value="" placeholder="Address">
												<!-- <input type="button" class="ibx2" name="home-addr" id="home-addr" value="Search address"> -->
											</li>
											<li>
												<input type="text" class="itx int4" name="addr2" id="addr2" value="" placeholder="City">
											</li>
											<li>
												<input type="text" class="itx int4" name="addr1" id="addr1" value="" placeholder="State/Province/County">
											</li>
											<li>
												<input type="text" class="itx int2" name="zipcode" id="zipcode" value="" placeholder="ZIP/Postal Code">
											</li>
										</ul>
									</li>
								</ul>

							</div>
						</article>

						<div class="agreeBox">
							<h4>
								<label class="checkbox2 check-left" for="all-ind-agree">
									<span>Accept all terms and conditions</span>

									<input type="checkbox" id="all-ind-agree" name="check-all">
									<i class="chk"></i>
								</label>
								<a href="#" class="viewTerms">View Terms and Conditions ></a>
							</h4>
							<ul>
								<li>
									<label class="checkbox2 check-left" for="ind-agree-1">
										<span>Terms of Use agreement</span>
										<input type="checkbox" id="ind-agree-1" name="check_agree1" class="check-agree" value="Y">
										<i class="chk"></i>
									</label>

								</li>
								<li>
									<label class="checkbox2 check-left" for="ind-agree-2">
										<span>Privacy Policy View Agreement</span>
										<input type="checkbox" id="ind-agree-2" name="check_agree2" class="check-agree" value="Y">
										<i class="chk"></i>
									</label>
								</li>
								<li>
									<label class="checkbox2 check-left" for="ind-agree-3">
										<span>Personal information Third-party provision and referral agreement</span>
										<input type="checkbox" id="ind-agree-3" name="check_agree3" class="check-agree" value="Y">
										<i class="chk"></i>
									</label>
								</li>
								<li>
									<label class="checkbox2 check-left" for="ind-agree-4">
										<span>Accept event notifications (optional)</span>
										<input type="checkbox" id="ind-agree-4" name="check_agree4" class="check-agree" value="Y">
										<i class="chk"></i>
									</label>
								</li>
							</ul>
						</div>

						<div class="btn-area">
							<a href="javascript:;" id="join_submit" class="btn-c btn-complete">Completed membership</a>
						</div>
					</form>
				</div>
			</section>
		</div>

<script>
$(document).ready(function(){
	var $termsWrap = $(".termsWrap");
	var $btnTermsView = $(".viewTerms");
	var $termsClose = $(".termsClose");
	var $btnTermsClose = $(".btnTermsClose");

	$btnTermsView.on("click",function(){
		$termsWrap.show();
		$("body,#wrap").addClass("overflow_hidden");
	});
	$termsClose.on("click",function(){
		$termsWrap.hide();
		$("body,#wrap").removeClass("overflow_hidden");
	});
	$btnTermsClose.on("click",function(){
		$termsWrap.hide();
		$("body,#wrap").removeClass("overflow_hidden");
	});
});
</script>


<style>
.termsWrap { position:absolute; left:0; top:0; width:100%; height:100%; z-index:99999; display:none; }
.termsClose { position:absolute; left:0; top:0; width:100%; height:100%; z-index:1; background:rgba(0,0,0,0.3);  }
.termsInside { position:relative; width:80%; margin:0 auto; top:50%; margin-top:-300px; height:600px;  z-index:2; background:#fff; overflow-y:scroll;
	padding:35px 25px;
}
a.btnTermsClose { font-size:20px; position:absolute; right:20px; top:20px; color:#999; font-family:arial; z-index:2; }
.overflow_hidden { overflow:hidden; }
</style>
			<!-- layer -->
			<div class="termsWrap">
				<div class="termsClose"></div>
				<div class="termsInside content">
					<h2>Terms of use</h2>
					<a href="#n" class="btnTermsClose">X</a>

					<div class="policyBox">
						<p>In accordance with the Personal Information Protection Act, &lt;Ifave.co.kr '(' ifave.co.kr 'or' FAVE ') shall protect the personal information of the user and protect the rights and interests of the user, We have the following processing policy.<br/><br/>

						The Company shall notify the Company through the website announcement (or individual announcement) when revising the personal information processing policy.<br/><br/>

						This policy will be effective from October 15, 2018.</p>

						<h3>1. Purpose of processing personal information &lt;Company's Strong Friend&gt; ('ifave.co.kr' or 'FAVE') handles personal information for the following purposes. The processed personal information will not be used for any purpose other than the following purposes.</h3>
						<ol>
							<li>
								<h4>a. Homepage membership and management</h4>
								<p>Confirmation of membership, identification and certification of member by providing membership service, maintenance and management of membership, confirmation of identity by execution of limited identification system, prevention of illegal use of service, confirmation of legal representative agreement when collecting personal information of children under 14 years old, We handle personal information for the purpose of various notices and notices, grievance handling, and record keeping for dispute settlement.</p>
							</li>
							<li>
								<h4>b. Civil affairs office processing</h4>
								<p>We process personal information for the purpose of confirming the identity of a complainant, confirming a complaint, contacting and notifying for a fact investigation, and notifying the result of processing.</p>
							</li>
							<li>
								<h4>c. Providing goods or services</h4>
								<p>We handle personal information for the purpose of delivering goods, providing services, sending out invoices, providing content, providing personalized services, self-certification, age verification, bill settlement, collection of debts.</p>
							</li>
							<li>
								<h4>d. Marketing and advertising</h4>
								<p>Provide new service (product) development and customized service, provide event and advertisement information and participation opportunity, provide service according to demographic characteristics, advertisement, check validity of service, access frequency or statistics on member's service usage We process personal information for the purpose of.</p>
							</li>
							<li>
								<h4>e. Personal image information</h4>
								<p>We process personal information for prevention and investigation of crime, facility safety and fire prevention, traffic control, collection, analysis and provision of traffic information.</p>
							</li>
						</ol>

						<h3>2. Personal Information File Status</h3>
						<ol>
							<li>
								<h4>1. Personal information Filename: Strong friend</h4>
								<ul>
									<li>
										- Personal information items: email, mobile phone number, home address, home phone number, password question and answer, password, login ID, gender, date of birth, name, company phone number, title, department, company name, The information of the user, the date of birth, the date of birth, the date of birth, the date of birth, the date of birth, the date of birth, the date of birth, Address, legal representative mobile phone number
									</li>
									<li>
										- Collection method: collection through homepage, sweepstakes, delivery request, generation information collection tool
									</li>
									<li>
										- Possible basis: Strong friends
									</li>
									<li>
										- Retention period: 10 years
									</li>
									<li>
										- Related Laws: Records on the collection, processing and use of credit information: 3 years, records of consumer complaints or disputes: 3 years, records of payments and supplies: 5 years, records of contracts or withdrawals: 5 years, record of display / advertisement: 6 months
									</li>
								</ul>
							</li>
						</ol>

						<h3>3. Processing and Retention Period of Personal Information</h3>
						<ol>
							<li>① "FAVE" means the processing of personal information within the period of holding or using personal information in compliance with laws and regulations, I will.</li>
							<li>② Each personal information processing and retention period is as follows.<br/><br/>
								<ol class="inside_ol">
									<li>
										<h4>1. &lt;Homepage membership and management&gt;</h4>
										<p>Personal information related to &lt;homepage membership and management&gt; will be held and used for the above purposes from the date of consent for collection and use until &lt;10 years&gt;
										</p><br/>
										<ul>
											<li>
												- Possible basis: Strong friends
											</li>
											<li>
												- Related laws:
												<ol>
													<li>1) Records on collection, processing and use of credit information: 3 years</li>
													<li>2) Records of consumer complaints or disputes: 3 years</li>
													<li>3) Record of payment and goods supply: 5 years</li>
													<li>4) Record of contract or withdrawal of subscription: 5 years</li>
													<li>5) Record of indication / advertisement: 6 months</li>
												</ol>
											</li>
											<li>
												- Reason for exception:
											</li>
										</ul>
									</li>
								</ol>
							</li>
						</ol>

						<h3>4. Matters concerning third party provision of personal information</h3>
						<ol>
							<li>① &lt;Strong Friends&gt; ('ifave.co.kr' or 'FAVE') is a consent of the information entity, We will only provide personal information to third parties if they fall under Articles 17 and 18 of the Personal Information Protection Act, including special provisions of law.</li>
							<li>② &lt;Company's Healthy Friends&gt; ('ifave.co.kr') provides personal information to third parties as follows.<br/><br/>
								<ol class="inside_ol">
									<li>
										<h4>1. &lt;Company's Strong Friend&gt;</h4>
										<ul>
											<li>
												- Personal information: Strong friends
											</li>
											<li>
												- Personal information of the recipient Purpose: E-mail, mobile phone number, home address, home phone number, password Question and answer, password, login ID, sex, date of birth, name, company telephone number, title, department, , Birthday, anniversary, marital status, hobby, physical information, educational background, religion, social security number, credit card information, bank account information, service use log, access log, cookie, access IP information, payment history, legal representative name, , Legal representative's home address, legal representative mobile phone number
											</li>
											<li>
												- Period of use: 10 years
											</li>
										</ul>
									</li>
								</ol>
							</li>
						</ol>

						<h3>5. Commitment to personal information processing</h3>
						<ol>
							<li>① &lt;Strong Friends of Company&gt; ('FAVE') consigns the following personal information processing tasks for smooth personal information business processing.<br/><br/>
								<ol class="inside_ol">
									<li>
										<h4>1. &lt;Strong friend&gt;</h4>
										<ul>
											<li>
												- Trustee (trustee): Strong friend
											</li>
											<li>
												- Contents of the entrusted business: handling of complaints such as purchase and payment, shipping or billing, identity verification (financial transaction, financial service), charge collection, identity verification by using membership service, complaint handling, Provide new service (product) development and customized service, provide event and advertisement information and participate, operate video information processing device
											</li>
											<li>
												- Charging period: 10 years
											</li>
										</ul>

									</li>
								</ol>
							</li>
							<li>
								② &lt;Strong Friends of Company&gt;('ifave.co.kr' or 'FAVE') is prohibited from processing personal information except for the purpose of conducting the consignment business pursuant to the Article 25 of the Personal Information Protection Act, technical and administrative protection measures, restricting restrictions, The management, supervision, and compensation for damages are specified in documents such as contracts, and we supervise whether the trustee handles personal information safely.
							</li>
							<li>
								③ If the contents of the consignment service or the consignee change, we will disclose it through this personal information processing policy without delay.
							</li>
						</ol>

						<h3>6. Rights and duties of information and legal representatives and how they are exercised The user may exercise the following rights as a subject of personal information</h3>
						<ol>
							<li>① The information subject can exercise the rights such as viewing, correcting, deleting and stopping the processing of personal information at any time for Strong friends of corporations.</li>
							<li>② The exercise of rights pursuant to Paragraph 1 above may be made in writing, e-mail or fax (FAX) in accordance with Article 41 (1) of the Enforcement Decree of the Personal Information Protection Act for Strong friends of corporations, and Strong friends of corporations shall take measures without delay.</li>
							<li>③ The exercise of rights under Paragraph (1) may be conducted through the legal representative or authorized representative of the information entity. In this case, a power of attorney must be submitted in accordance with Article 11 of the Enforcement Regulations of the Personal Data Protection Act.</li>
							<li>④ The right of the information subject may be restricted according to Article 35, Clause 5, Article 37, Clause 2 of Personal Information Protection Act.</li>
							<li>⑤ The request for correction and deletion of personal information can not be requested to be deleted if the other personal information is stated in the other statute.</li>
							<li>⑥ A Strong friend of a corporation confirms whether the person who requests the reading, correction or deletion according to the right of the information subject, requests to stop the process, or requests to stop the process is his or her legal representative.
							</li>
						</ol>


						<h3>7. Creating an item of personal information to process</h3>
						<ol>
							<li>① &lt;Strong Friends of Company&gt; ('ifave.co.kr' or 'FAVE') processes the following personal information items.
								<h4>1 &lt;Homepage membership and management&gt;</h4>
								<ul>
									<li>
										- Required items: Email, Mobile phone number, Home address, Home phone number, Password question and answer, Password,	Login ID, Sex, Date of birth, Name, Company phone number, Position, Department, Company name, Occupation, Anniversary, Marriage , Hobbies, physical information, education, religion, social security number, credit card information, bank account information, service usage log, access log, cookie, access IP information, payment history, legal representative name, legal representative home telephone number, , Legal representative mobile phone number
									</li>
									<li>
										- Choices: email, mobile phone number, home address, home phone number, password Q & A, password, login ID, gender, date of birth, name, phone number, title, department, company name, occupation, , Hobbies, physical information, education, religion, social security number, credit card information, bank account information, service usage log, access log, cookie, access IP information, payment history, legal representative name, legal representative home telephone number, , Legal representative mobile phone number
									</li>
								<ul>
							</li>
						</ol>


						<h3>7. Creating an item of personal information to process</h3>
						<ol>
							<li>① &lt;Strong Friends of Company&gt; ('ifave.co.kr' or 'FAVE') processes the following personal information items.
								<h4>1 &lt;Homepage membership and management&gt;</h4>
								<ul>
									<li>
										- Required items: Email, Mobile phone number, Home address, Home phone number, Password question and answer, Password,	Login ID, Sex, Date of birth, Name, Company phone number, Position, Department, Company name, Occupation, Anniversary, Marriage , Hobbies, physical information, education, religion, social security number, credit card information, bank account information, service usage log, access log, cookie, access IP information, payment history, legal representative name, legal representative home telephone number, , Legal representative mobile phone number
									</li>
									<li>
										- Choices: email, mobile phone number, home address, home phone number, password Q & A, password, login ID, gender, date of birth, name, phone number, title, department, company name, occupation, , Hobbies, physical information, education, religion, social security number, credit card information, bank account information, service usage log, access log, cookie, access IP information, payment history, legal representative name, legal representative home telephone number, , Legal representative mobile phone number
									</li>
								<ul>
							</li>
						</ol>


						<h3>8. Destruction of Personal Information In principle, &lt;FAVE&gt; will, in principle, destroy personal information without delay if the purpose of processing personal information is achieved. Procedures, deadlines and destruction methods are as follows.</h3>
						<ol>
							<li>① &lt;Strong Friends of Company&gt; ('ifave.co.kr' or 'FAVE') processes the following personal information items.
								<ul>
									<li>
										-Destruction procedure
										<p>The information entered by the user is transferred to a separate DB after completion of the purpose (separate documents in the case of paper) and is stored or temporarily destroyed after a certain period of time according to internal policies and other related laws. At this time, the personal information transferred to the DB is not used for other purposes unless it is under the law.</p>
									</li>
									<li>
										- Destruction period
										<p>
										In the case where the personal information of the user has elapsed, within 5 days from the end of the period of holding the personal information, if the personal information such as accomplishing the purpose of processing personal information, abolishing the service, We will destroy the personal information within 5 days from the day when it is recognized that the processing of the personal information is unnecessary.
										</p>
									</li>
									<li>
										- Destruction method
										<p>Information in the form of electronic files is a technical method that can not reproduce records.</p>
										<p>Personal information printed on paper is crushed by crusher or destroyed by incineration.</p>
									</li>
								<ul>
							</li>
						</ol>


						<h3>9. Matters on installation, operation and rejection of automatic collection of personal information</h3>
						<ol>
							<li>① &lt;Strong Friends of Company&gt; ('ifave.co.kr' or 'FAVE') processes the following personal information items.
								<ul>
									<li>
										① Strong friends use 'cookies' which store their usage information and provide them from time to time to provide personalized services. ② Cookies are a small amount of information sent by the server (http) used to run the website to the user's computer browser and may be stored on the hard disk of the user's PC. end. Purpose of Cookie: It is used to provide optimized information to users by identifying the types of visits and usage of each service and websites visited by users, popular searches, and security access. I. Installing cookies • Operate and deny: You can refuse to store cookies via the Options setting on the Tools> Internet Options> Privacy menu at the top of your web browser. All. If you refuse to store cookies, you may have difficulty using customized services.
									</li>
								<ul>
							</li>
						</ol>

						<h3>10. Wrote personal information protection officer</h3>
						<ol>
							<li>① A Strong friend ('ifave.co.kr' or 'FAVE') is responsible for the handling of personal information, and in order to deal with complaints of information subjects related to the processing of personal information, We are designating the person in charge of privacy protection.
								<ul>
									<li>
										▶ Personal information protection officer<br/>
										Name: Hwang Se Don<br/>
										Position: CEO<br/>
										Contact: 051-761-5166, kikli1@naver.com, 051-761-5166<br/>
										※ It leads to personal information protection department.
									</li>
									<li>
										▶ Personal information protection department<br/>
										Department: Management Planning<br/>
										Contact person: Kang Na Hui<br/>
										Contact: 051-761-5166, kikli1@naver.com, 051-761-5166
									</li>
								<ul>
							</li>
							<li>
								② The information subject shall be responsible for all personal information protection inquiries, complaints, damages remedies, etc. related to the personal information protection officer You can contact the responsible department. A Strong friend ('ifave.co.kr' or 'FAVE') will answer and handle inquiries of the information subject without delay.
							</li>
						</ol>


						<h3>11. Change of personal information processing policy</h3>
						<ol>
							<li>① This personal information processing policy will be applied from the effective date. If there are additions, deletions and corrections of the changes according to laws and policies, we will notify them through announcements 7 days before the implementation of the changes.
							</li>
						</ol>

						<h3>12. Measures to Ensure the Safety of Personal Information FAVE , a <company's Strong friends>, performs the technical, administrative and physical measures necessary for securing safety in accordance with Article 29 of the Personal Information Protection Act as follows.</h3>
						<ol>
							<li>1. Regular self-audit conducted
								<p>We conduct our own audits regularly (quarterly) to ensure the stability of handling personal information.</p>
							</li>
							<li>2. Minimization and training of personal information handling staff
								<p>Employees who deal with personal information are designated and limited to the person in charge, and measures are taken to minimize personal information.</p>
							</li>
							<li>3. Establishment and enforcement of internal management plan
								<p>We have established and implemented an internal management plan for the safe handling of personal information.</p>
							</li>
							<li>4. Technical measures against hacking
								<p>
									In order to prevent leakage and damage of personal information caused by hacking or computer virus, 'FAVE' is a security program that periodically updates and checks the system, installs the system in an area controlled from outside, / They are physically monitored and blocked.
								</p>
							</li>
							<li>5. Encryption of personal information
								<p>
									The personal information of the user is encrypted and stored and managed so that only the user can know it, and the important data is using the separate security function such as encrypting the file and transmission data or using the file lock function.
								</p>
							</li>
							<li>6. Preventing the keeping of connection logs and forgery
								<p>
									We maintain and manage the records of access to the personal information processing system for at least six months and use the security function to prevent forgery, theft and loss of access records.
								</p>
							</li>
							<li>7. Restrict access to personal information
								<p>
									We take necessary measures to control access to personal information through granting, modifying, and deleting access rights to the database system that processes personal information. We also control unauthorized access from outside by using an intrusion prevention system.
								</p>
							</li>
							<li>8. Using locks for document security
								<p>
									We keep documents with personal information and auxiliary storage media in safe place with lock.
								</p>
							</li>
							<li>9. Access control to unauthorized persons
								<p>
									We have set up a separate physical storage area for personal information and set up access control procedures.
								</p>
							</li>
						</ol>

					</div>
				</div>
			</div><!-- //layer -->



<?
include("../inc/footer.php");
mysql_close($dbconn);
?>
