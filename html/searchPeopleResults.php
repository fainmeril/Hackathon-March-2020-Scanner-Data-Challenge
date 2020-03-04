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
        $person_id    = get_post($conn, 'person_id');
        $fname    = get_post($conn, 'fname');
        $lname = get_post($conn, 'lname');
        $client_category_id    = get_post($conn, 'client_category_id');
        $birthdate = get_post($conn, 'birthdate');   
        
            
            $query    = "select b.* from person b  
            WHERE b.fname like '%$fname%' 
             AND b.lname like '%$lname%'";
        
          
            $query    = $query."";     
            
            if (strcmp($client_category_id,"Any")!=0) {
                $query    = $query." AND b.client_category_id like '$client_category_id'";
            }else{
                $query    = $query." AND 1=1";
            }
        
            if ($person_id != 0){
                $query    = $query." AND b.person_id = $person_id";
            }else{
                $query    = $query." AND 1=1";
            }

            if ($birthdate != NULL){
                $query    = $query." AND b.birthdate = $birthdate";
            }else{
                $query    = $query." AND 1=1";
            }

            $query    = $query.";";
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
                                                  
                                $person_id = $rows['person_id'];
                                $fname = $rows['fname'];
                                $lname = $rows['lname'];
                                $client_category_id = $rows['client_category_id'];
                                $birthdate = $rows['birthdate'];
                                $email = $rows['email'];
                                $phoneNumber = $rows['phoneNumber'];
                                $username = $rows['username'];
                                $password = $rows['password'];
                                
                                echo "<tr><td><table>
                                            <tr>Person ID: $person_id<br></tr> 
                                            <tr>First Name: $fname<br></tr> 
                                            <tr>Last Name: $lname<br></tr> 
                                            <tr>Client_category_id: $client_category_id<br></tr> 
                                            <tr>Birthdate: $birthdate<br></tr> 
                                            <tr>Email: $email<br></tr> 
                                            <tr>Phone Number: $phoneNumber<br></tr>
                                            <tr>Username: $username<br></tr>
                                            <tr>Password: $password<br></tr>
                                        </table>
                                    </td>
                                </tr>
                                "; 
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
