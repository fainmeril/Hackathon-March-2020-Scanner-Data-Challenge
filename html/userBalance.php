<!-- Programmer: Cassandra Horne,
	 Program Due: August 6th 2019
--> 
<!DOCTYPE html> 
<?php
    session_start();
    
    require_once 'fploginInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $client_category_id = 5;

    if (isset($_SESSION['$client_category_id'])){
        $client_category_id = $_SESSION['$client_category_id'];
        $username = $_SESSION['$username'];
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
                    <h2>
                        <?php
                            $query = "SELECT w.withdraw_date, w.due_date, w.return_date, w.fine_owed, w.fine_paid, b.title, b.author, b.book_id 
                            FROM book b, withdrawal w, person p 
                            WHERE w.book_id = b.book_id 
                            and w.person_id = p.person_id 
                            AND p.username like '$username' 
                            AND (w.return_date is null OR w.fine_owed !=0 OR w.fine_paid !=0);";
                            $result = $conn->query($query);
                            $totalOwed = 0;
                            $totalPaid = 0;

                            foreach($result as $row){
                                $fine_owed = $row['fine_owed'];
                                $fine_paid = $row['fine_paid'];
                                $book_id   = $row['book_id'];
                                $title     = $row['title'];
                                $author    = $row['author'];
                                $due_date  = $row['due_date'];  
                                $return_date  = $row['return_date'];  

                                $totalOwed = $totalOwed + $fine_owed;
                                $totalPaid = $totalPaid + $fine_paid;
                                 echo "You withdrew book number $book_id, $title by $author and it was due on $due_date."; 
                                if($fine_owed==0 AND $fine_paid==0) {
                                    echo "<br>This book is currently still checked out.<br><br>";   
                                }else{
                                    echo "<br>This book accrewed a fine of $$fine_owed, $$fine_paid of which you repaid when the book on $return_date.<br><br>";       
                                }

                            }
                            $totalLeft = $totalOwed - $totalPaid;
                            echo "Overall you have owed: $$totalOwed.<br>Overall you have paid: $$totalPaid.<br>Overall you still owe: $$totalLeft.";                       
                    
                        ?>
                    </h2>
                </ul>
            </td>
        </table>
    </body>
    <h4 class=footer>You may contact the site builder at horne112@uwindsor.ca</h4>
</html> 
