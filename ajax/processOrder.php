<?php

namespace ajax;

use Model\InitConsts;

if(count($_POST) > 0)
{

    $email      = trim($_POST['clientEmail']);
    $password   = trim($_POST['password']);
    $dateOrder  = date('Y-m-d h:i:s');

    if(!empty($email) && !empty($password))
    {
        include_once '../Manager/DatabaseManager.php';

        $dbm = new \Manager\DatabaseManager($dateOrder);
        $correctUser = $dbm->fetchUser($email, $password);

        if($correctUser)
        {
            include_once '../Manager/FileManager.php';

            $om = new \Manager\FileManager($email, $dateOrder);
            $outputCSV = $om->formatAndWriteCSV($_POST);

            if(is_string($outputCSV)) $errorMsg = $outputCSV.'<br>';

            $outputPDF = $om->formatAndWritePDF($_POST);

            if(is_string($outputPDF)) $errorMsg .= $outputPDF.'<br>';

            $savedOrder = $dbm->saveOrder($_POST, ($outputPDF && $outputCSV));

            if(is_string($savedOrder)) $errorMsg .= $savedOrder.'<br>';

            if(InitConsts::SEND_MAIL_ENABLED)
            {
                include_once '../Manager/MailManager.php';

                $mm = new \Manager\MailManager($email, $om->ref, [$om->csvPath, $om->pdfPath, ], $om->date);
                $output = $mm->send();

                if($output)
                {
                    $successMessage = '<font color="green">Mails sent</font>';

                }else $errorMsg .= $output;

            }else $errorMsg .= 'OK but mails are not enabled (enable them in const)';

        }else $errorMsg = $correctUser;


    }else $errorMsg = 'All inputs are mandatories!';

    echo (isset($errorMsg)) ? 'e<font color="red">'.$errorMsg.'</font>' : $successMessage;
}