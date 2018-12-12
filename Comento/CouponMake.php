<?php

/*
[쿠폰코드 발행 페이지(2)]
0. 세션을 실행한다.
  - admin으로 로그인이 안되어있을 시 해당 페이지 내용을 볼 수 없다.
  - admin으로 로그인 되었을 시 해당 페이지 내용을 볼 수 있다.

1. (CouponMain.php) Admin으로부터 3자리 값(ex.prefix)을 받아온다     ==> [완료]
  - 받아온 값을 $getvalue라고 정한다.
    a) 테이블명을 생성한다.(ex. "Group_".$getvalue)
    b) 데이터베이스(테이블명 : GroupName)에 "Group_".$getvalue를 저장한다.

2. 12자리(숫자+영문 조합) 쿠폰 코드 넘버를 생성한다
  - 함수 coupon_generator()에서 생성             ==> [완료]
    a) 1번째 쿠폰 코드 발생
      a-1) 12자리 쿠폰 코드 번호를 생성한다.
      a-2) 발생한 쿠폰 코드 번호를 Array에 저장한다
      a-3) Array가 1만개가 될때까지 생성시킨다.      ==> 문장 그대로 코드 작성하지 못함. 함수 생성 시 단순 반복문으로 1만개 반복
                                              ==> (예외처리) array에 저장 중 데이터 중복 생겼을 때에 예외처리가 필요하다!!!

    b) 2번째 쿠폰 코드 발생
      b-1) 12자리 쿠폰 코드 번호를 생성한다.
      b-2) 발생한 쿠폰 코드 번호를 Array에 저장한다
      b-3) Array가 1만개가 될때까지 생성시킨다.

      ...(위와 같은 방식으로 반복)

    j) 10번째 쿠폰 코드 발생


****** (고려사항) 추후 다중 배열로 coupon_generator() 함수 내에 10만개가 한번에 생성되는 코드 작성 고려 --> why? 현재 속도가 너무 느리다 ******


3. 데이터베이스에 저장한다                          ==> [완료]
  a) 다중배열로 저장된 쿠폰 코드 번호를 출력한다(ex. array[0~9][0~9999])
  b) admin으로부터 입력받은 값(prefix) + array[0~9]값을 4자리의 쿠폰 코드 번호로 변환한다.
      - admin으로 입력받은 값은 $getvalue로 지정한다.
  c) 12자리 쿠폰 코드 번호를 "****-****-****" 와 같은 형식으로 변환한다.
  d) 위의 순서 b,c를 결합해 16자리 쿠포 코드 번호를 생성한다. (ex. ****-****-****-****)
  e) 데이터베이스에 저장한다.


****** (고려사항)저장까지 완료되는데 1분 30초정도 소요. 너무 느리다. ******


4. 저장이 완료되면 ' Coupon_GroupName_List.php ' 화면으로 이동한다.      ==> [완료]



*/

$connect = mysqli_connect("localhost", "naming", "3theowkd!", "Comento") or die("cannot connect");


// '1번' 사용자(Admin)으로부터 값을 받아오는 부분
$getvalue = $_GET["prefix"];

// '1-a)'번에 데이터베이스 테이블 생성
$tablename = "Group_".$getvalue;
$cr_table = "CREATE TABLE ".$tablename."(auto_num int auto_increment, code_num varchar(40), used_date datetime, who_used varchar(20), primary key(auto_num));";
$cr_result = mysqli_query($connect, $cr_table);
if($cr_result == true) {

  // '1-b)' 번에 GroupName 테이블에 데이터 추가
  $group_name = "INSERT INTO GroupName(group_name) VALUES('$tablename')";
  $insert = mysqli_query($connect, $group_name);

} else if($cr_result == false) {

  echo mysqli_error($connect);

}


// '2번' 2-a) ~ 2-j)번까지 1만개의 쿠폰 코드를 10번 반복 생성
$db_input = array();
for($j=0; $j<10; $j++) {
  $db_input[$j] = coupon_generator();
}


// '3번' 데이터베이스에 저장하는 부분
// '3-a)' 에서 각 배열안에 존재하는 값들을 출력하는 과정
for($z=0; $z<count($db_input); $z++) {

  for($i=0; $i<10000; $i++) {

    // 3-b) ~ 3-d)번 과정
    // 첫번째 코드넘버 : admin으로부터 입력받은 값($getvalue)과 0 ~ 9까지의 값
    // 두번재 코드넘버 : coupon_generator()로부터 받아온 코드 번호의 0~3까지의 4자리수
    // 세번재 코드넘버 : coupon_generator()로부터 받아온 코드 번호의 4~7까지의 4자리수
    // 네번재 코드넘버 : coupon_generator()로부터 받아온 코드 번호의 8~11까지의 4자리수
    $chg_code = $getvalue."".$z."-".substr($db_input[$z][$i],0,4)."-".substr($db_input[$z][$i],4,4)."-".substr($db_input[$z][$i],8,4);


    // 3-e)과 같이 데이터베이스에 저장한다.
    $sql = "INSERT INTO $tablename(code_num) VALUES('$chg_code')";
    $result = mysqli_query($connect, $sql);
    if($result === false) {
      echo mysqli_error($connect);
    }

  }

  if($z==9) {
    echo "
    <script>
     window.alert('쿠폰 생성이 완료되었습니다.');
     location.href='Coupon_GroupName_List.php';
    </script>
    ";
  }

}



// '2번'에  '2-a)'에서 12자리의 쿠폰 코드 번호를 생성하는 함수
function coupon_generator($len = 12) {

  $arr_codenum = array();

  for($countnum=0; $countnum<10000; $countnum++) {

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";

    srand((double)microtime()*1000000);
    $i = 0;
    $str = "";

    while ($i < $len) {
        $num = rand() % strlen($chars);
        $tmp = substr($chars, $num, 1);
        $str .= $tmp;
        $i++;
    }
    $str = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\1-\2-\3-\4", $str);
    $arr_codenum[$countnum] = $str;

  }

  return $arr_codenum;
}

mysqli_close($connect);

?>
