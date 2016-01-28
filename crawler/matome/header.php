<?php
    $loc = "/home/leonardo/services/news/";
    include $loc."crawler/function.php";
    include $loc."mysql/connect.php";
    $db->select_db("matome_datasets");

    function fArray_merge($aOld, $aNew) {
        foreach($aNew as $sKey=>$mValue) {
            if (isset($aOld[$sKey]) && is_array($mValue) && is_array($aOld[$sKey])) {
                $aOld[$sKey] = fArray_merge($aOld[$sKey], $mValue);
            } else {
                $aOld[$sKey] = $mValue;
            }
        }

        return($aOld);
    }
?>
