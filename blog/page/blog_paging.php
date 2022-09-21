<?php
// page given in URL parameter, default page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1; 

$cat="";
$catString="";

if(filter_input(INPUT_GET,"cat")){
    $cat=filter_input(INPUT_GET,"cat");
    $catHome="?cat=$cat";
    $catPages="&cat=$cat";
}

// set number of records per page
$records_per_page = 5;
  
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

echo "<ul class=\"pagination justify-content-center\">";
  
// button for first page
if($page>1){
    echo "<li class=\"page-item\"><a class=\"page-link\" href='blog.php' title='Go to the first page.'>";
        echo "First Page";
    echo "</a></li>";
}

// count all products in the database to calculate total pages
$total_pages = ceil($total_rows / $records_per_page);
  
// range of links to show
$range = 2;
  
// display links to 'range of pages' around 'current page'
$initial_num = $page - $range;
$condition_limit_num = ($page + $range)  + 1;
  
for ($x=$initial_num; $x<$condition_limit_num; $x++) {
  
    // be sure '$x is greater than 0' AND 'less than or equal to the $total_pages'
    if (($x > 0) && ($x <= $total_pages)) {
  
        // current page
        if ($x == $page) {
            echo "<li class='active page-item'><a class=\"page-link\" href=\"blog.php$catHome\">$x </a></li>";
        }
  
        // not current page
        else {
            echo "<li class=\"page-item\"><a class=\"page-link\" href='?page=".$x.$catPages."'>$x</a></li>";
        }
    }
}
  
// button for last page
if($page<$total_pages){
    echo "<l class=\"page-item\"><a class=\"page-link\" href='?page={$total_pages}$catPages' title='Last page is {$total_pages}.'>";
        echo "Last Page";
    echo "</a></li>";
}
  
echo "</ul>";
?>