<!DOCTYPE html>

<!--
[쿠폰 리스트 페이지]
0. 세션을 실행한다.
  - admin으로 로그인이 안되어있을 시 해당 페이지 내용을 볼 수 없다.
  - admin으로 로그인 되었을 시 해당 페이지 내용을 볼 수 있다.

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

 ?>


 <div class="container">
   <div class="row">

     &emsp;&emsp;

     <article class="boardArticle">
       <br><br><br>
       <h3>그룹명 페이징</h3>
       <br>
       <table>
         <thead>
           <tr>
             <th scope="col" class="no">번호</th>
             <th scope="col" class="codenum">그룹명</th>
           </tr>
         </thead>

         <tbody>
             <?php

             // $dbpaging은 테이블(board_db)에 존재하는 레코드 행(row) 갯수를 알아오는 명령어
             $sql = "SELECT COUNT(*) as cnt FROM GroupName";
             $dbpaging = mysqli_query($connect, $sql);
             $rowpaging = mysqli_fetch_array($dbpaging);
             $allPost = $rowpaging['cnt'];
             $onePage = 5;
             $allPage = ceil($allPost / $onePage); // 전체 페이지의 수



             $oneSection = 10;		                // 한번에 보여줄 총 페이지 갯수(ex. [1][2][3]...[10])
             $currentSection = ceil($page / $oneSection); //현재 섹션
             $allSection = ceil($allPage / $oneSection);  //전체 섹션의 수

             $firstPage = ($currentSection * $oneSection) - ($oneSection - 1); //현재 섹션의 처음 페이지

             if($currentSection == $allSection) {
               $lastPage = $allPage;
             } else {
               $lastPage = $currentSection * $oneSection;
             }

             $prevPage = (($currentSection - 1) * $oneSection);                       //이전 페이지, 11~20일 때 이전을 누르면 10 페이지로 이동. 웹페이지에서는 '...'을 가리킨다.
             $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1);   //다음 페이지, 11~20일 때 다음을 누르면 21 페이지로 이동.





             $paging = '<ul class="pagination justify-content-center">'; // 페이징을 저장할 변수

             //첫 페이지가 아니라면 처음 버튼을 생성
             if($page != 1) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_GroupName_List.php?page=1">&laquo</a></li>';
             }
             //첫 섹션이 아니라면 이전 버튼을 생성
             if($currentSection != 1) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_GroupName_List.php?page=' . $prevPage . '">...</a></li>';
             }

             for($i = $firstPage; $i <= $lastPage; $i++) {
               if($i == $page) {
                 $paging .= '<li class="page-item"><a class="page-link">' . $i . '</a></li>';
               } else {
                 $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_GroupName_List.php?page=' . $i . '">' . $i . '</a></li>';
               }
             }

             //마지막 섹션이 아니라면 다음 버튼을 생성
             if($currentSection != $allSection) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_GroupName_List.php?page=' . $nextPage . '">...</a></li>';
             }

             //마지막 페이지가 아니라면 끝 버튼을 생성
             if($page != $allPage) {
               $paging .= '<li class="page-item"><a class="page-link" href="./Coupon_GroupName_List.php?page=' . $allPage . '">&raquo</a></li>';
             }
             $paging .= '</ul>';
             // 페이징 끝

             $currentLimit = ($onePage * $page) - $onePage; 					 		//몇 번째의 글부터 가져오는지
             $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage;

             $sql = "SELECT * FROM GroupName order by auto_num DESC LIMIT $currentLimit, $onePage ";      //원하는 개수만큼 가져온다. (0번째부터 20번째까지)

             $dboutput = mysqli_query($connect, $sql);

               while($row = mysqli_fetch_array($dboutput)) {

             ?>

           <tr>

             <!-- 코드 그룹명 선택 시 화면 이동 -->
             <td name="auto_num" class="no"><?php echo $row['auto_num']?></td>
             <td name="title" class="codenum"><a href="./Coupon_List.php?Name=<?php echo $row['group_name']?>"><?php echo $row['group_name']?></td> </a>

           </tr>

             <?php
               }
             ?>

         </tbody>
       </table>

       <div class="paging">
         &emsp;
         <?php echo $paging ?>
       </div>

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
