<?php

namespace ajax;

use Manager\DatabaseManager;

if(count($_POST) > 0)
{
    $isEmptyField = FALSE;

    foreach($_POST as $k => $v):

        $cleanedValue = trim($v);

        if(!empty($cleanedValue))
        {
            $a [$k] = $cleanedValue;

        }else{
            $isEmptyField = TRUE;
            BREAK;
        }

    endforeach;

    if(!$isEmptyField)
    {
        require_once '../Manager/DatabaseManager.php';

        $dbm = new DatabaseManager;

        $outputDBM = $dbm->updateUserPassword($_POST);

        if($outputDBM)
        {
            $sucessMsg = 'Password updated!';

        }else $errorMsg = $outputDBM;

    }else $errorMsg = 'All inputs are mandatories';

    echo (isset($errorMsg)) ? 'e<font color="red">'.$errorMsg.'</font>' : '<font color="green">'.$sucessMsg.'</font>';
}