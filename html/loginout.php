
<!-- Programmer: Cassandra Horne,
	 Program Due: August 6th 2019
--> 
<!DOCTYPE html> 
<html>
<?php
    session_start();
    
    require_once 'fploginInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    function get_post($conn, $var)
      {
        return $conn->real_escape_string($_POST[$var]);
      }
    
      if (isset($_POST['username'])   &&
          isset($_POST['password']))
      {
            $username = get_post($conn, 'username');
            $password = get_post($conn, 'password');
            $query    = "SELECT * FROM person WHERE username like '$username' and password like '$password';";
            $result   = $conn->query($query);
            $rows = $result->num_rows;
            if ($rows==0){		
                echo "<h3>Either wrong username or password entered.<br></h3>";
            } 
          
            if ($rows==1){        
                foreach($result as $row){
                    $client_category_id = $row['client_category_id'];
                    $_SESSION['$client_category_id'] = $client_category_id;
                    $_SESSION['$username'] = $username;

                    if($client_category_id == 1){
                        echo "<h3>Welcome librarian: ".$row['fname']." ".$row['lname'].".<br></h3>";
                    }

                    if($client_category_id == 2){
                        echo "<h3>Welcome teacher: ".$row['fname']." ".$row['lname'].".<br></h3>";
                    }

                    if($client_category_id == 3){
                        echo "<h3>Welcome student: ".$row['fname']." ".$row['lname'].".<br></h3>";
                    }
                }
            }
            
            if($rows>1){
                echo "<h3>Error: Multiple users match this username.<br></h3>";
            } 
       }

    if (isset($_POST['logout'])){
        $_SESSION = array();       
    }       
 
    $client_category_id = 5;

    if (isset($_SESSION['$client_category_id'])){
        $client_category_id = $_SESSION['$client_category_id'];
    } else {
        $client_category_id = 5;
    }
?>

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
                            if (($client_category_id != 1)&&($client_category_id != 2)&&($client_category_id != 3)){
                        ?>
                                <form action="loginout.php" method="post"><pre>
Username: <input type="text" name="username">
Password: <input type="password" name="password">
                                    <input type="submit" value="Log In">
                                </pre></form>
                                <br><br><br>
                        <?php 
                            }else{
                        ?>
                                <form action="loginout.php" method="post"><pre>
<input type="submit" name="logout" value="Log Out" onclick="">
                                </pre></form>
                        <?php
                            }
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
