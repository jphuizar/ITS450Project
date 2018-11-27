<?php
require ('./includes/config.inc.php');
$page_title = 'Shop Art - Contact us';
include ('./includes/header.html');
require (MYSQL);
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(trim($_POST['name'])=='')
        die('<font color="#d2691e">Please enter a name.</font>');
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
        die('<font color="#d2691e">Please enter a valid email.</font>');
    if(trim($_POST['title'])=='')
        die('<font color="#d2691e">Please enter a title.</font>');
    if(trim($_POST['message'])=='')
        die('<font color="#d2691e">Please enter a message.</font>');
    $name=$_POST['name'];
    $title=$_POST['title'];
    $message=$_POST['message'];
    
    $mysqli = $dbc;
    if ($mysqli->connect_errno) {
        die("Connect failed:  $mysqli->connect_error\n");
    }
    $hash=sha1($name.$_POST['email'].$title.$message);
    $stmt=$mysqli->prepare('INSERT INTO `messages` (`name`, `email`, `title`, `message`,`msghash`, `msgid`) VALUES (?,?,?,?,?, NULL)');
    $stmt->bind_param('sssss',$name,$_POST['email'],$title,$message,$hash);
    $stmt->execute();
    if($stmt->affected_rows<=0){
        die('<font color="#d2691e">Message Exists.</font>');
    }
    $stmt->close();
    $message=htmlspecialchars($message);
    require_once(BASE_URI.'email.config.php');
    $email=new stdClass();
    $email->{'personalizations'}=[(object)['to'=>[(object)['email'=> $_POST['email'] ]],
    'subject'=>"Shop Art Customer Service"]];
    $email->{'from'}=(object)['email'=>EMAIL_SENDER_ADDRESS,'name'=>EMAIL_SENDER_NAME];
    $email->{'content'}=[(object)['type'=>'text/html','value'=>str_replace("\n",'<br>',"Hello $name,
    
    Thank you for contacting Shop Art. Your feedback is valuable to us. A Customer Service Representative will be in touch with you shortly.
    
    This is a copy of your message:
    Title: $title
    Message: $message")]];
    //var_dump($email);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'https://api.sendgrid.com/v3/mail/send');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($email,JSON_PRETTY_PRINT));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '.EMAIL_API_KEY,
        'Content-Type: application/json'
        ));
    curl_exec($ch);
    print('<font color="#d2691e">Your response has been recorded. Thank You.</font>');
}
include ('./views/contact.html');
// Include the footer file:
include ('./includes/footer.html');
