<!-- Programmer: Cassandra Horne,
	 Program Due: August 6th 2019
--> 
<!DOCTYPE html> 
<?php
    session_start();
    
    require_once 'fploginInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    function get_post($conn, $var)
    {
        return $conn->real_escape_string($_POST[$var]);
    }
            $query    = "select b.*, w.withdraw_date, w.due_date, w.return_date, p.fname, p.lname 
            from book b, person p, withdrawal w 
            WHERE w.person_id = p.person_id 
            AND w.book_id = b.book_id 
            AND w.return_date is null AND w.due_date < CURRENT_DATE;";

            $result   = $conn->query($query);

            if (!$result){		
                echo "SEARCH failed: $query<br>".$conn->error."<br><br>";
            } else {
                echo "located records successfully";
            }

    $client_category_id = 5;

    if (isset($_SESSION['$client_category_id'])){
        $client_category_id = $_SESSION['$client_category_id'];
    } else {
        $client_category_id = 5;
    }
?>
<html>
    <head>
        <link href="http://horne112.myweb.cs.uwindsor.ca/60334/assignments/FinalProject/css/finalProjectSiteCSS.css" rel="stylesheet" type="text/css">  
    </head>
    <h3 align=right><a href="loginout.php"><b>Sign in/Sign out</b></a></h3>
   
    <h1 class=header align=center>
        <IMG id="libraryIcon" alt="Library Icon" src="http://horne112.myweb.cs.uwindsor.ca/60334/assignments/FinalProject/pictures/logo.jpg" BORDER="2%" align="left">
          <a href=index.php>  Rivendell Library </a>
    </h1>
        
    <body>
        <table>
            <td class=menu>
               <ul class=column>
                 <li><a href="index.php"><b>Home</a> </li>
                 <li><a href="searchBooks.php">Search Books</b></a> </li>
                   
                <?php
                    if(($client_category_id == 1)||($client_category_id == 2)||($client_category_id == 3)){
                        echo '<li><a id="LI6" href="libraryStatistics.php"><b>Library Statistics</b></a> </li><li><a id="ST1" href="userBalance.php"><b>Your balance</b></a> </li>';
                    }
                       
                    if($client_category_id == 1){    
                        echo '<li><a id="LI1" href="searchPeople.php"><b>Search Users</a> </li>
                         <li><a id="LI2" href="searchResultsOverdue.php">Currently Overdue Books</a> </li>  
                         <li><a id="LI3" href="addRemoveBooks.php">Add/Remove Books</a> </li>
                         <li><a id="LI4" href="addRemoveUsers.php">Add/Remove Users</a> </li>
                         <li><a id="LI5" href="withdrawReturn.php">Withdraw/Return Books</a></b></li>';   
                    }
                ?> 
              </ul>
            </td>
            <td class=content>
                <ul class=clearfix>
                    <h4>
                        <table>
                        <?php 
                            foreach($result as $rows){
                                $imgString = "http://horne112.myweb.cs.uwindsor.ca/60334/assignments/FinalProject/pictures/".$rows['img_filename'];
                                $authorString = $rows['author'];
                                $titleString = $rows['title'];
                                $categoryString = $rows['category'];
                                $yearString = $rows['year'];
                                $isbnString = $rows['isbn'];
                                $book_idString = $rows['book_id'];
                                $shelf_locString = $rows['shelf_loc'];
                        
                                echo "<tr><td><img src=$imgString alt='book cover'></td>
                                    <td><table>
                                            <tr>Author: $authorString<br></tr> 
                                            <tr>Title: $titleString<br></tr> 
                                            <tr>Category: $categoryString<br></tr> 
                                            <tr>Year: $yearString<br></tr> 
                                            <tr>ISBN: $isbnString<br></tr> 
                                            <tr>Book ID: $book_idString<br></tr> 
                                            <tr>Shelf Location: $shelf_locString<br></tr>
                                        </table>
                                    </td>
                                "; 
                                
                                   $fnameString = $rows['fname'];
                                    $lnameString = $rows['lname'];
                                    $withdraw_dateString = $rows['withdraw_date'];
                                    $due_dateString = $rows['due_date'];
                                    echo "<td>This book was withdrawn by $fnameString $lnameString on $withdraw_dateString and is due by $due_dateString.</td>";
                                
                                
                                echo "</tr>";
                            }
                        ?>
                        </table>
                    </h4>
                </ul>
            </td>
        </table>
    </body>
    <h4 class=footer>You may contact the site builder at horne112@uwindsor.ca</h4>
</html> 
