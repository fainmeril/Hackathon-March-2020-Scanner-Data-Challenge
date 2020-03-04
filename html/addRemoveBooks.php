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

    if(isset($_POST['category']) &&
      isset($_POST['isbn']) &&
      isset($_POST['addBook']))
    {
         $author  = get_post($conn, 'author');
         $title  = get_post($conn, 'title');
         $category  = get_post($conn, 'category');
         $year  = get_post($conn, 'year');
         $isbn  = get_post($conn, 'isbn');
         $shelf_loc  = get_post($conn, 'shelf_loc');
         $img_filename = get_post($conn, 'img_filenameLoc');

          $query = "INSERT INTO book(author, title, category, year, isbn, img_filename, shelf_loc) VALUES('$author','$title','$category',$year, '$isbn', '$img_filename', '$shelf_loc');";
          $result = $conn->query($query);
          if (!$result){ 
              echo "INSERT failed: $query<br>" . $conn->error . "<br><br>";
          }else{
              echo "INSERT success.";
          }
          
          if($result){
              $name = $_FILES['img_filenameLoc']['http://horne112.myweb.cs.uwindsor.ca/60334/assignments/FinalProject/pictures/'];
              move_uploaded_file($_FILES['img_filenameLoc']['tmp_name'], $name);
          }        
    }


    if(isset($_POST['author']) &&
      isset($_POST['title']) &&
      isset($_POST['delete1']))
    {
         $author  = get_post($conn, 'author');
         $title  = get_post($conn, 'title');

          $query = "DELETE FROM book WHERE author like '$author' AND title like '$title';";
          $result = $conn->query($query);
          if (!$result) {
              echo "DELETE failed: $query<br>" .$conn->error . "<br><br>";
          }else{
              echo "DELETE success.";
          }     
    }

if(isset($_POST['book_id']) &&
      isset($_POST['delete2']))
    {
         $book_id  = get_post($conn, 'book_id ');

          $query = "DELETE FROM book WHERE book_id = $book_id;";
          $result = $conn->query($query);
          if (!$result) {
              echo "DELETE failed: $query<br>" .$conn->error . "<br><br>"; 
          }else{
              echo "DELETE success.";
          }      
    }

if(isset($_POST['isbn']) &&
      isset($_POST['delete3']))
    {
         $isbn  = get_post($conn, 'isbn');

          $query = "DELETE FROM book WHERE isbn like '$isbn';";
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
                        <form action="addRemoveBooks.php" method="post"><pre>  
Author: <input type="text" name="author">
Title: <input type="text" name="title" required>
Category: <input type="text" name="category" required>
Year: <input type="text" name="year">
ISBN: <input type="text" name="isbn" required>
Shelf Location: <input type="text" name="shelf_loc">    
<input type="hidden" name="addBook" value="yes">
Select Image File: <input type='file' name='img_filenameLoc' size='10'>                     
<input type="submit" value="Add Book">
                    </pre></form>
                        <br>  
<form action="addRemoveBooks.php" method="post"><pre>                           
Delete Books by Title and Author<input type="hidden" name="delete1" value="yes">
Author: <input type="text" name="author" required>
Title: <input type="text" name="title"  required>
<input type="submit" value="Remove Book">
</pre></form>
<br>
<form action="addRemoveBooks.php" method="post"><pre>   
Delete Books by Book ID<input type="hidden" name="delete2" value="yes">
Book ID: <input type="text" name="book_id" required>
<input type="submit" value="Remove Book">
</pre></form>
 <br>                       
<form action="addRemoveBooks.php" method="post"><pre>  
Delete Books by ISBN  <input type="hidden" name="delete3" value="yes">
ISBN: <input type="text" name="isbn"  required>
<input type="submit" value="Remove Book">
</pre></form>
 <br><br><br><br>        
                        <?php 
                            $query    = "select b.* from book b";
                            $result = $conn->query($query);
                            if (!$result) echo "SEARCH failed: $query<br>" . $conn->error . "<br><br>";
                            foreach($result as $rows){
                                $imgString = "http://horne112.myweb.cs.uwindsor.ca/60334/assignments/FinalProject/pictures/".$rows['img_filename'];
                                $authorString = $rows['author'];
                                $titleString = $rows['title'];
                                $categoryString = $rows['category'];
                                $yearString = $rows['year'];
                                $isbnString = $rows['isbn'];
                                $book_idString = $rows['book_id'];
                                $shelf_locString = $rows['shelf_loc'];
                        
                                echo "<tr><td></td><td><img src=$imgString alt='book cover'><table>
                                            <tr><br>Author: $authorString<br></tr> 
                                            <tr>Title: $titleString<br></tr> 
                                            <tr>Category: $categoryString<br></tr> 
                                            <tr>Year: $yearString<br></tr> 
                                            <tr>ISBN: $isbnString<br></tr> 
                                            <tr>Book ID: $book_idString<br></tr> 
                                            <tr>Shelf Location: $shelf_locString<br></tr>
                                        </table>
                                    </td>
                                    </tr>
                                "; 
                            }
                        ?>
                        
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
