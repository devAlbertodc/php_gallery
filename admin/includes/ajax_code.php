<?php
require("init.php");

/*
$user = new User();
$user = User::find_by_id($_POST['user_id']);
$user->user_image = $_POST['image_name'];
$user->update();

$user->user_image = "images/".$user->user_image;
echo $user->user_image;
*/

//LESSON 201: THIS PART IS NOT WORKING:
$user = new User();

if(isset($_POST['image_name'])){
    $user->ajax_save_user_image($_POST['image_name'], $_POST['user_id']);
}

//This if works for me:
if(isset($_POST['photo_id'])){
    Photo::display_sidebar_data($_POST['photo_id']);
}
?>