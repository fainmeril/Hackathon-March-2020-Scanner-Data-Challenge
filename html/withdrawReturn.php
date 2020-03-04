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
    } else {
        $client_category_id = 5;
    }

      function get_post($conn, $var)
      {
        return $conn->real_escape_string($_POST[$var]);
      }

    if(isset($_POST['book_id']) &&
      isset($_POST['person_id']) &&
      isset($_POST['withdraw']))
    {
        $person_id = get_post($conn, 'person_id');
        $book_id = get_post($conn, 'book_id');
      
        $query = "SELECT c.allwd_days_withdraw
                  FROM person p, client_category c
                  WHERE p.client_category_id = c.client_category_id
                    AND p.person_id = $person_id;";
        $result = $conn->query($query);
        
        foreach($result as $row){
            $allwd_days_withdraw = $row['allwd_days_withdraw'];  
            $withdraw_date  = date('Y-m-d');
        }
        $daysToAdd = $allwd_days_withdraw. ' days';
        $due_date = $withdraw_date;
        date_add($due_date, date_interval_create_from_date_string($daysToAdd));
                        
          $query = "INSERT INTO withdrawal(person_id, book_id, withdraw_date, due_date) VALUES($person_id, $book_id, '$withdraw_date', '$due_date');";
          $result = $conn->query($query);
          if (!$result){ 
              echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
          }else{
              echo "INSERT success.";
          }       
    }


    if(isset($_POST['person_id']) &&
      isset($_POST['book_id']) &&
      isset($_POST['withdraw_date']) &&
      isset($_POST['fine_paid']) &&
      isset($_POST['return']))
    {
        $person_id = get_post($conn, 'person_id');
        $book_id = get_post($conn, 'book_id');
        $withdraw_date = get_post($conn, 'withdraw_date');
        $fine_paid = get_post($conn, 'fine_paid');
        
        $query = "SELECT c.allwd_days_withdraw, w.fine_paid, w.due_date
                  FROM person p, client_category c, withdrawal w 
                  WHERE p.person_id = w.person_id
                    AND p.client_category_id = c.client_category_id
                    AND w.person_id = $person_id 
                    AND w.book_id = $book_id
                    AND w.withdraw_date = '$withdraw_date';";
        $result = $conn->query($query);
        
        foreach($result as $row){
            $allwd_days_withdraw = $row['allwd_days_withdraw'];  
            $fine_paidBefore = $row['fine_paid'];
            $due_date = $row['due_date'];
            $todayDay = date('Y-m-d');
        }
        
        $fine_paid = $fine_paid + $fine_paidBefore;
        
        $diffDays = ceil(abs($todayDay - $due_date) / 86400);
        
        if($diffDays >= 0){
            $fine_owed = 0;
        }else{
            $fine_owed = $diffDays*.10;
        }        
            
          $query = "UPDATE withdrawal 
                    SET return_date = CURRENT_DATE,
                        fine_owed = $fine_owed,
                        fine_paid = $fine_paid
                    WHERE person_id = $person_id 
                    AND book_id = $book_id
                    AND withdraw_date = '$withdraw_date';";
          $result = $conn->query($query);
          if (!$result) {
              echo "UPDATE failed: $query<br>" .$conn->error . "<br><br>";
          }else{
              echo "UPDATE success.";
          }     
    }

    if(isset($_POST['fine_paid']) &&
       isset($_POST['person_id']) &&
      isset($_POST['pay']))
    {
        $fine_paid = get_post($conn, 'fine_paid');
        $person_id = get_post($conn, 'person_id');
        
        $withdraw_date = date('Y-m-d');
        
        $query = "INSERT INTO withdrawal(person_id, book_id, withdraw_date, due_date, return_date, fine_owed, fine_paid) VALUES($person_id, 1, '$withdraw_date', '$withdraw_date', '$withdraw_date', 0.00, $fine_paid);";
          $result = $conn->query($query);
          if (!$result){ 
              echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
          }else{
              echo "INSERT success.";
          }       
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
<form action="withdrawReturn.php" method="post"><pre>  
Withdraw a Book<input type="hidden" name="withdraw" value="yes">
Person ID: <input type="text" name="person_id" required>
Book ID: <input type="text" name="book_id" required>             
<input type="submit" value="Withdraw Book">
</pre></form>
<br>  
<form action="withdrawReturn.php" method="post"><pre>                           
Return a Book<input type="hidden" name="return" value="yes">
Person ID: <input type="text" name="person_id" required>
Book ID: <input type="text" name="book_id" required>
Fine Paid: <input type="text" name="fine_paid" required>
Withdraw Date: <input type="text" name="withdraw_date" required>
<input type="submit" value="Return Book">
</pre></form>
<br>
<form action="withdrawReturn.php" method="post"><pre>                           
Pay a Fine<input type="hidden" name="pay" value="yes">
Person ID: <input type="text" name="person_id" required>
Fine Paid: <input type="text" name="fine_paid" required>
<input type="submit" value="Pay Fine">
</pre></form>
<br>
                    <?php
                      $result->close();
                      $conn->close();
                    ?>
                    </h2>
                </ul>
            </td>
        </table>
    </body>
    <h4 class=footer>You may contact the site builder at horne112@uwindsor.ca</h4>
</html> 
