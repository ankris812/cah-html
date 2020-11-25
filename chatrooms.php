<?php 
    
    session_start();

    require_once("dbcon.php");

    if (checkVar($_SESSION['userid'])): 
 
        $getRooms = "SELECT *
        			 FROM chat_rooms";
        $roomResults = $mysqli->query($getRooms);		  


include('templates/header.php');
?>



    <div id="page-wrap">
        
    	<div id="section">
    	
            <div id="rooms">
            	<h3>Rooms</h3>
                <ul>
                    <?php 
                        while($rooms = $roomResults->fetch_array()):
                            $room = $rooms['name'];
                            $query = $mysqli->query("SELECT * FROM `chat_users_rooms` WHERE `room` = '$room' ") or die("Cannot find data". $mysqli->error);
                            $numOfUsers = $query->num_rows;
                    ?>
                    <li>
                        <a href="room/?name=<?php echo $rooms['name']?>"><?php echo $rooms['name'] . "<span>Users chatting: <strong>" . $numOfUsers . "</strong></span>" ?></a>
                    </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>
        
    </div>

</body>

</html>

<?php 

    else: 
	   header('Location: http://css-tricks.com/examples/Chat2/');
	   
	endif;
	
?>
