<?php
/*
[로그인 성공 확인 페이지]

1. 세션을 시작한다.
  - 세션 사용 이유 : Admin 계정만 접근 가능하게 만들 수 있는 페이지를 만들기 위해

2. 'Login.html' 페이지로부터 데이터(아이디와 패스워드)를 전송받는다.
  - 이때, 전송받은 방식은 POST. 데이터 이름은 "userid"와 "password"이다.

3. 전송받은 ID값이 MySQL에 존재하는 지 확인한다.
  a) ID 값이 존재할 때
    - 전송받은 비밀번호(ex. "password")가 맞는지 비교한다.
    - ID, PW가 모두 일치하다면 세션 값에 사용자가 로그인한 아이디 값(ex. admin 또는 user1 또는 user2 또는 user3)을 저장한다.

      a-1) admin으로 로그인했다면
        - 세션에 admin을 저장한다.
        - ' CouponMake.php ' 페이지로 이동한다.

      a-2) user로 로그인했다면
        - 세션에 user를 저장한다.
        - ' CouponUser.php ' 페이지로 이동한다

  b) ID 값이 존재하지 않을 때
    - 에러 발생


*/


session_start();

// '1번' 내용과 같이 Login.html로부터 데이터를 전송받는다.
$user_id = $_POST["userid"];
$user_pw = $_POST["password"];

$connect = mysqli_connect("localhost", "naming", "3theowkd!", "Comento") or die("cannot connect");

$check  = "SELECT * FROM UserInfo WHERE userid='$user_id'";

$result = $connect->query($check);

if($result->num_rows==1) {
  $data=$result->fetch_array(MYSQLI_ASSOC);

  if ($data['userpw']==$user_pw) {

    // Admin 계정으로 접속했을 경우
    if($user_id == "admin") {

      $_SESSION['id'] = $user_id;

      echo "
      <script>
       window.alert('(Admin)로그인 되셨습니다.');
       location.href='CouponMain.php';
      </script>
      ";
      exit;

    // User 계정으로 접속했을 경우
    } else {

      $_SESSION['id'] = "user";

      echo "
      <script>
       window.alert('(User)로그인 되셨습니다.');
       location.href='CouponUser.php';
      </script>
      ";
      exit;

    }


  // 패스워드가 틀렸을 경우
  } else {
    echo "
    <script>
     window.alert('PW가 틀렸습니다.');
     location.href='Login.html';
    </script>
    ";

  }

// ID가 틀렸을 경우
} else {

  echo "
  <script>
   window.alert('ID가 존재하지 않습니다.');
   location.href='Login.html';
  </script>
  ";

}

mysqli_close($connect);
?>
