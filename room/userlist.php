<?php
/* 
Author: Kenrick Beckett
Author URL: http://kenrickbeckett.com
Name: Chat Engine 2.0
*/
require_once("../dbcon.php");

//Start Array
$data = array();
// Get data to work with
		$current = cleanInput($_GET['current']);
		$room = cleanInput($_GET['room']);
		$username = cleanInput($_GET['username']);
		$now = time();
// INSERT your data (if is not already there)
       	$findUser = "SELECT * FROM `chat_users_rooms` WHERE `username` = '$username' AND `room` ='$room' ";
		
		if(!hasData($findUser))
				{
					$insertUser = "INSERT INTO `chat_users_rooms` (`id`, `username`, `room`, `mod_time`) VALUES ( NULL , '$username', '$room', '$now')";
					$mysqli->query($insertUser) or die($mysqli->error);
				}		
		 	$findUser2 = "SELECT * FROM `chat_users` WHERE `username` = '$username'";
			if(!hasData($findUser2))
				{
					$insertUser2 = "INSERT INTO `chat_users` (`id` ,`username` , `status` ,`time_mod`)
					VALUES (NULL , '$username', '1', '$now')";
					$mysqli->query($insertUser2);
					$data['check'] = 'true';
				}			
		$finish = time() + 7;
		$getRoomUsers = $mysqli->query("SELECT * FROM `chat_users_rooms` WHERE `room` = '$room'");
		$check = $getRoomUsers->num_rows;
        	
	    while(true)
		{
			usleep(10000);
			$mysqli->query("UPDATE `chat_users` SET `time_mod` = '$now' WHERE `username` = '$username'");
			$olduser = time() - 5;
			$eraseuser = time() - 30;
			$mysqli->query("DELETE FROM `chat_users_rooms` WHERE `mod_time` <  '$olduser'");
			$mysqli->query("DELETE FROM `chat_users` WHERE `time_mod` <  '$eraseuser'");
			$check = $mysqli->query("SELECT * FROM `chat_users_rooms` WHERE `room` = '$room' ")->num_rows;
			$now = time();
			if($now <= $finish)
			{
				$mysqli->query("UPDATE `chat_users_rooms` SET `mod_time` = '$now' WHERE `username` = '$username' AND `room` ='$room'  LIMIT 1") ;
				if($check != $current){
				 break;
				}
			}
			else
			{
				 break;	
		    }
        }		 		
// Get People in chat
		if($getRoomUsers->num_rows != $current)
		{
			$data['numOfUsers'] = $getRoomUsers->num_rows;
			// Get the user list (Finally!!!)
			$data['userlist'] = array();
			while($user = $getRoomUsers->fetch_array())
			{
				$data['userlist'][] = $user['username'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		else
		{
			$data['numOfUsers'] = $current;	
			while($user = $getRoomUsers->fetch_array())
			{
				$data['userlist'][] = $user['username'];
			}
			$data['userlist'] = array_reverse($data['userlist']);
		}
		echo json_encode($data);

?>
