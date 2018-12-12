<!DOCTYPE html>

<!--
[쿠폰 리스트 페이지]
1.

-->

<html>

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">



    <title>Comento</title>

    <!-- Bootstrap core CSS -->
  	<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  	<!-- 게시판 리스트 css디자인 -->
  	<link rel="stylesheet" href="./css/board.css" />


    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>


  </head>


  <body>

<?php

session_start();

// '2-a)'번에 세션 값이 admin과 같을 경우
if($_SESSION['id'] == "admin") {

    echo "Admin으로 로그인";

?>

<?php

$connect = mysqli_connect("localhost", "naming", "3theowkd!", "Comento") or die("cannot connect");

// 페이징 시작
if(isset($_GET['page'])) {
  $page = $_GET['page'];
} else {
  $page = 1;
}

// 검색 시작
if(isset($_GET['searching'])) {
  $searching = $_GET['searching'];
}

// GroupName 검색
if(isset($_GET['Name'])) {
  $grp_name = $_GET['Name'];
}

if(isset($_GET['Name']) && isset($_GET['searching'])) {

  $search_sql = 'where code_num like "%'.$searching.'%"';

} else {
  $search_sql = '';
}

 ?>

 <!-- Page Content -->
 <div class="container">
   <div class="row">

     &emsp;&emsp;

     <article class="boardArticle">
       <br><br><br>
       <h3><?php echo $grp_name;?> 코드번호</h3>
       <br>
       <table>
         <!-- 게시판 하단에 '자유게시판'으로 작성해주는 코딩내용 -->
         <!-- <caption class="readHide">자유게시판</caption> -->
         <thead>
           <tr>
             <th scope="col" class="no">번호</th>
             <th scope="col" class="codenum">코드번호</th>
             <th scope="col" class="date">사용일시</th>
             <th scope="col" class="user">사용유저</th>
           </tr>
         </thead>

         <tbody>

           <!-- 주석 !!! :  DataBase에서 게시판에 작성할 내용 코딩하는 부분 -->
             <?php

             // $dbpaging은 테이블(board_db)에 존재하는 레코드 행(row) 갯수를 알아오는 명령어
             if($search_sql != null) {

               $sql = "SELECT COUNT(*) as cnt FROM $grp_name ".$search_sql;

             } else {

               $sql = "SELECT COUNT(*) as cnt FROM $grp_name ";

             }


             $dbpaging = mysqli_query($connect, $sql);
             $rowpaging = mysqli_fetch_array($dbpaging);
             $allPost = $rowpaging['cnt'];

             $onePage = 100;
             $allPage = ceil($allPost / $onePage); // 전체 페이지의 수



             $oneSection = 10;		// 한번에 보여줄 총 페이지 개수
             $currentSection = ceil($page / $oneSection); //현재 섹션
             $allSection = ceil($allPage / $oneSection);  //전체 섹션의 수

             $firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

             if($currentSection == $allSection) {
               $lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지가 된다.
             } else {
               $lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
             }

             $prevPage = (($currentSection - 1) * $oneSection); //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동.
             $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.



             $paging = '<ul class="pagination justify-content-center">'; // 페이징을 저장할 변수

             //첫 페이지가 아니라면 처음 버튼을 생성
             if($page != 1) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_List.php?Name='.$grp_name.'&page=1">&laquo</a></li>';
             }
             //첫 섹션이 아니라면 이전 버튼을 생성
             if($currentSection != 1) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_List.php?Name='.$grp_name.'&page=' . $prevPage . '">...</a></li>';
             }
             for($i = $firstPage; $i <= $lastPage; $i++) {
               if($i == $page) {
                 $paging .= '<li class="page-item"><a class="page-link">' . $i . '</a></li>';
               } else {
                 $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_List.php?Name='.$grp_name.'&page=' . $i . '">' . $i . '</a></li>';
               }
             }
             //마지막 섹션이 아니라면 다음 버튼을 생성
             if($currentSection != $allSection) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_List.php?Name='.$grp_name.'&page=' . $nextPage . '">...</a></li>';
             }
             //마지막 페이지가 아니라면 끝 버튼을 생성
             if($page != $allPage) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_List.php?Name='.$grp_name.'&page=' . $allPage . '">&raquo</a></li>';
             }
             $paging .= '</ul>';
             // 페이징 끝

             $currentLimit = ($onePage * $page) - $onePage; 					 		//몇 번째의 글부터 가져오는지
             $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; 		//limit sql 구문

             $sql = "SELECT * FROM $grp_name ".$search_sql." order by auto_num DESC ".$sqlLimit; //원하는 개수만큼 가져온다. (0번째부터 20번째까지)

             // $result = $db->query($sql);

             // 아래 코드 내용 mysql syntax 오류 발생
             // $result = $connect->query($sql);
             // $result_set = mysqli_query($connect, $sel);
             //$sel = "SELECT * FROM board_db ORDER BY auto_num desc limit 10";
             $dboutput = mysqli_query($connect, $sql);

               // 변수 dboutput에서 테이블(board_db)에서 받아온 레코드를 mysqli_fetch_array에 넣는다
               // mysqli_fetch_array는 레코드의 한 줄(row)를 기준으로 읽어온다
               // 읽어온 레코드 중 'bd_date'(자료형 datetime)을 '연도 시간'(ex. 2018-06-12 11:01:10)으로 나눠져 있으므로
               // 이것을 날짜와 시간으로 나눠서 변수에 입력시킨다.--> 그게바로 explode 함수

               while($row = mysqli_fetch_array($dboutput)) {
                 // explode로 날짜와 시간을 나눠주고 작성하는 기준으로 '그날' 작성하는 것이라면, 일자는 보이지 않고 시간만 보이도록 설정하는 코딩
                 // Date는 금일 해당 날짜를 뜻한다.
                 $datetime = explode(' ', $row['used_date']);

                 $date = $datetime[0];
                 $time = $datetime[1];
                 if($date == Date('Y-m-d'))
                   $row['used_date'] = $time;
                 else
                   $row['used_date'] = $date;
             ?>

           <tr>

             <td name="auto_num" class="no"><?php echo $row['auto_num']?></td>


             <!-- ***** 주석 ***** -->
             <!-- 처음에 get 메소드 형식으로 보내기 위해서는 form이 따로 존재해야하는 줄 알았다. -->
             <!-- but!!! 꼭 폼 형식으로 보내는 것이 아니라 URL에 DB에 입력할 값을 보여주는 형식이 get 방식인 것. -->
             <!-- 제목(title)을 클릭했을 시 get 방식으로 URL에 게시판 글 번호(auto_num)을 넘겨준다. -->
             <!-- 글 번호(auto_num)을 넘겨주는 이유는 DataBase에서 프라이머리키로 설정해놨기 때문에 겹칠일이 없다. -->

             <td name="title" class="codenum"><?php echo $row['code_num']?></td> </a>
             <td name="writer" class="date"><?php echo $row['used_date']?></td>
             <td name="date" class="user"><?php echo $row['who_used']?></td>
           </tr>
             <?php
               }
             ?>
         </tbody>
       </table>


       </div>

       <!-- 하단에 페이징 부분을 보여준다. -->
       <!-- 변수 $paging으로 선언된 부분을 하단의 숫자로 보여줄 수있도록 한다. paging에 대한 코딩 내용은 이해가 필요하다. (2018.06.12) -->
       <div class="paging">
         &emsp;
         <?php echo $paging ?>
       </div>

       <form name="searching" method="get" action="./Coupon_List.php">
        <table>
            <tr>
                <td><input type="text" name="searching" style="width:300px; height:30px" required></td>
                <td><input type="text" name="Name" value="<?php echo $grp_name;?>" hidden></td>

                <td colspan="3" style="padding-top: 15px">
                    <input type="submit" class="btn btn-success" value="검색">
                </td>

            </tr>
        </table>
      </form>

     </article>

   </div>
 </div>






<?php

// '2-b)'번에 세션 값이 admin이 아닐 경우
} else {

  echo "Admin 계정만 접속할 수 있습니다.";

}
 ?>

</body>


</html>
