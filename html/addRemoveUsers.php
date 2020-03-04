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

    if(isset($_POST['fname']) &&
       isset($_POST['lname']) &&
       isset($_POST['username']) &&       
       isset($_POST['password']) &&
       isset($_POST['birthdate']) &&
       isset($_POST['addUser']))
    {
         $fname  = get_post($conn, 'fname');
         $lname  = get_post($conn, 'lname');
         $username  = get_post($conn, 'username');
         $password  = get_post($conn, 'password');
         $client_category_id  = get_post($conn, 'client_category_id');
         $birthdate  = get_post($conn, 'birthdate');
         $email = get_post($conn, 'email');
         $phoneNumber = get_post($conn, 'phoneNumber');

          $query = "INSERT INTO person(fname, lname, username, password, client_category_id, birthdate, email, phoneNumber) VALUES('$fname','$lname','$username','$password', $client_category_id, '$birthdate', '$email', '$phoneNumber');";
          $result = $conn->query($query);
          if (!$result){ 
              echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
          }else{
              echo "INSERT success.";
          }
    }                   

    if(isset($_POST['fname']) &&
      isset($_POST['lname']) &&
      isset($_POST['birthdate']) &&
      isset($_POST['delete3']))
    {
         $fname  = get_post($conn, 'fname');
         $lname  = get_post($conn, 'lname');
         $birthdate  = get_post($conn, 'birthdate');
        
          $query = "DELETE FROM person WHERE fname like '$fname' AND lname like '$lname' and birthdate = '$birthdate';";
          $result = $conn->query($query);
          if (!$result) {
              echo "DELETE failed: $query<br>" .$conn->error . "<br><br>";
          }else{
              echo "DELETE success.";
          }     
    }

if(isset($_POST['username']) &&
      isset($_POST['delete2']))
    {
         $username  = get_post($conn, 'username');

          $query = "DELETE FROM person WHERE username like '$username';";
          $result = $conn->query($query);
          if (!$result) {
              echo "DELETE failed: $query<br>" .$conn->error . "<br><br>"; 
          }else{
              echo "DELETE success.";
          }      
    }

if(isset($_POST['person_id']) &&
      isset($_POST['delete1']))
    {
         $person_id  = get_post($conn, 'person_id');

          $query = "DELETE FROM person WHERE person_id like $person_id;";
          $result = $conn->query($query);
          if (!$result){
              echo "DELETE failed: $query<br>" .$conn->error . "<br><br>";
          }else{
              echo "DELETE success.";
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
<form action="addRemoveUsers.php" method="post"><pre>  
First Name:  <input type="text" name="fname" required>
Last Name:  <input type="text" name="lname" required>
Client_category_id:  <input type="text" name="client_category_id">
Birthdate:  <input type="text" name="birthdate" default="YYYY-MM-DD" required>
Email:  <input type="text" name="email">
Phone Number:  <input type="text" name="phoneNumber">
Username:  <input type="text" name="username" required>
Password:  <input type="text" name="password" required>
<input type="hidden" name="addUser" value="yes">                   
<input type="submit" value="Add User">
                    </pre></form>
                        <br>  
<form action="addRemoveUsers.php" method="post"><pre>                           
Delete People by Person ID <input type="hidden" name="delete1" value="yes">
Person ID:  <input type="text" name="person_id" required> 
<input type="submit" value="Remove User">
</pre></form>
<br>
<form action="addRemoveUsers.php" method="post"><pre>   
Delete People by Username <input type="hidden" name="delete2" value="yes">
Username:  <input type="text" name="username" required>
<input type="submit" value="Remove User">
</pre></form>
 <br>                       
<form action="addRemoveUsers.php" method="post"><pre>  
Delete People by Name and Birthday <input type="hidden" name="delete3" value="yes">
First Name:  <input type="text" name="fname" required>
Last Name:  <input type="text" name="lname" required>
Birthdate:  <input type="text" name="birthdate" required>
<input type="submit" value="Remove User">
</pre></form>
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
