<!DOCTYPE html>

<!--
[쿠폰 코드 발행 페이지(1)]
1. 세션을 시작한다

2. 웹페이지 화면이 보인다
  a) 세션 값이 admin일 경우
    - 쿠폰 코드 발행 페이지를 보여준다
  b) 세션 값이 admin이 아닐 경우
    - 쿠폰 코드 발행 페이지를 보여주지 않는다
    - admin으로 로그인해야 한다고 알려준다

3. 쿠폰발행 버튼을 선택할 경우
  a) 입력 받은 3자리 값을 Get 방식으로 'CouponMake.php'에 전달한다

  ****** (고려사항) 쿠폰번호 3자리 값을 데이터베이스(테이블명 : GroupName)에서 중복체크 필요 ******

-->

<html>

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



    <title>Comento</title>


    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  </head>


<?php

session_start();

// '2-a)'번에 세션 값이 admin과 같을 경우
if($_SESSION['id'] == "admin") {

    echo "Admin으로 로그인";

?>

<!------ Include the above in your HEAD tag ---------->

<div class="container">

<div class="row" style="margin-top:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		<form role="form" onsubmit="checkSubmit" action="./CouponMake.php" method="get" >
			<fieldset>
				<h2>Admin 쿠폰 발행 페이지</h2>
				<hr class="colorgraph">
				<div class="form-group">
                    <input type="prefix" name="prefix" id="prefix" class="form-control input-lg" maxlength="3" placeholder="쿠폰발행 3자리 입력하세요" required>
				</div>
				<hr class="colorgraph">
				<div class="row">
					<div class="form-group">
            <!-- '3-a)'번과 같이 쿠폰발행을 선택할 경우 -->
                    <input type="submit" class="btn btn-lg btn-success btn-block" value="쿠폰발행">
					</div>
				</div>
			</fieldset>
		</form>
    <div class="form-group">
						<a href="Coupon_GroupName_List.php" class="btn btn-lg btn-primary btn-block">쿠폰리스트</a>
		</div>
	</div>
</div>

</div>



<?php

// '2-b)'번에 세션 값이 admin이 아닐 경우
} else {

  echo "Admin 계정만 접속할 수 있습니다.";

}


 ?>
