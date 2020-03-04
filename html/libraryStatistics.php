<!-- Programmer: Cassandra Horne,
	 Program Due: August 6th 2019
--> 
<!DOCTYPE html> 
<?php
    session_start();
    
    require_once 'fploginInfo.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);

    $username = _SESSION['$username'];

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
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart1);
            google.charts.setOnLoadCallback(drawChart2);
            google.charts.setOnLoadCallback(drawChart3);
            function drawChart1() {
                var data = google.visualization.arrayToDataTable([
                  ['Category', 'Number of Books']
                  <?php
                    $query = "SELECT b.category, count(*) as cnt FROM book b GROUP BY b.category;";
                    $result = $conn->query($query);

                    while($row = mysqli_fetch_array($result)){
                        printf(",\n['%s', %s]",$row[0],$row[1]);
                    }
                    ?>    
                ]);
                var options = {
                    title: 'Books Read by Category'            
                };
                var chart = new  google.visualization.PieChart(document.getElementById('piechart'));
                chart.draw(data, options);
            }

            function drawChart2() {
                var data = google.visualization.arrayToDataTable([
                  ['Date', 'Number of Books']
                  <?php
                    $query = "SELECT w.due_date, count(*) as cnt FROM withdrawal w GROUP BY w.due_date;";
                    $result = $conn->query($query);
                    while($row = mysqli_fetch_array($result)){
                         printf(",\n['%s', %s]",$row[0],$row[1]);
                    }
                    ?>    
                ]);
                var options = {
                    title: 'Books Taken Out by Date'
                };
                var chart = new   google.visualization.BarChart(document.getElementById('barchart'));
                chart.draw(data, options);
            }
            function drawChart3() {
                var data = google.visualization.arrayToDataTable([
                  ['Date', 'Number of Books']
                  <?php
                    $query = "SELECT EXTRACT( YEAR_MONTH FROM w.due_date), count(*) as cnt FROM withdrawal w GROUP BY w.due_date;";
                    $result = $conn->query($query);
                    while($row = mysqli_fetch_array($result)){
                         printf(",\n['%s', %s]",$row[0],$row[1]);
                    }
                    ?>    
                ]);
                var options = {
                    title: 'Books Taken Out by Month/Year'
                };
                var chart = new   google.visualization.BarChart(document.getElementById('barchart2'));
                chart.draw(data, options);
            } 
    </script>
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
                        <div id="piechart" style="width: 150%; height: 400px;"></div>
                        <br><br><br>
                        <div id="barchart" style="width: 150%; height: 400px;"></div>
                        <br><br><br>
                        <div id="barchart2" style="width: 150%; height: 400px;"></div>
                    </h2>
                </ul>
            </td>
        </table>
    </body>
    <h4 class=footer>You may contact the site builder at horne112@uwindsor.ca</h4>
</html> 
