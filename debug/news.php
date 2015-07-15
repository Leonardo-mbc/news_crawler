<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
    include "../mysql/connect.php";
        
    if($_GET['id']) $ID = $_GET['id'];
        else die("id = ?");
        
    $result = $db->prepare("SELECT name, url FROM news WHERE topic_id = ?");
    $result->bind_param('s', $ID);
    $result->execute();
    $result->bind_result($name, $url);
    
    while($result->fetch())
    {
        echo "<a href='$url'>$name</a><br/>";
    }
    
    $result->close();
?>