<!DOCTYPE html>

<!--
[쿠폰 리스트 페이지]
1. 'Coupon_GroupName_List.php' 에서 선택된 그룹명을 전달받는다.

2. 

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
         <thead>
           <tr>
             <th scope="col" class="no">번호</th>
             <th scope="col" class="codenum">코드번호</th>
             <th scope="col" class="date">사용일시</th>
             <th scope="col" class="user">사용유저</th>
           </tr>
         </thead>

         <tbody>


             <?php

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
             $sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; 		

             $sql = "SELECT * FROM $grp_name ".$search_sql." order by auto_num DESC ".$sqlLimit; 

             $dboutput = mysqli_query($connect, $sql);


               while($row = mysqli_fetch_array($dboutput)) {

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
