<?php
    date_default_timezone_set('Asia/Tokyo');    # 応急処置
    include "../../mysql/connect.php";
    $db->select_db("2ch_datasets");

    if($_GET['thread_id']){
        $thread_id = (int)$_GET['thread_id'];
    } else {
        if($_SERVER["argv"][1]) $thread_id = (int)$_SERVER["argv"][1];
            else die("thread_id = ?");
    }

    $result = $db->prepare("SELECT id, name, mail, body, details FROM responses WHERE thread_id = ? ");
    $result->bind_param('i', $thread_id);
    $result->execute();

    # -> output news
    $result->bind_result($id, $name, $mail, $body, $details);

    $responses = array('responses' => array());
    while($result->fetch()) {
        $responses["responses"][] = array(
            'id' => $id,
            'name' => $name,
            'mail' => $mail,
            'body' => $body,
            'details' => $details
        );
    }

    $result->close();

    $json = json_encode($responses, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    echo $json;
?>
