<?php

namespace Manager;

use Model\InitConsts as IC;

require_once '../Model/InitConsts.php';     //ENTRY POINT of execution => first class to be called then no need to require again IC

class DatabaseManager implements IC
{
    /**
     * @var
     */
    protected $sqli;

    /**
     * @var
     */
    public $dateOrder;

    /**
     * DatabaseManager constructor.
     */
    public function __construct($p1_date_order)
    {
        $this->sqli = new \mysqli(IC::MYSQLI_HOST, IC::MYSQLI_USER, IC::MYSQLI_PASSWORD, IC::MYSQLI_DBNAME);
        $this->dateOrder = $p1_date_order;
    }

    /**
     * @param $p1_email
     * @param $p2_password
     * @return bool|string
     */
    public function fetchUser($p1_email, $p2_password)
    {
        $encryptedPassword = sha1($p2_password);

        $query = 'SELECT email, password
                  FROM tbl_customers
                  WHERE email = "'.$this->sqli->real_escape_string($p1_email).'"
                  AND password = "'.sha1($this->sqli->real_escape_string(trim($p2_password))).'"';

        $resultFetchMail = $this->sqli->query($query);

        if(is_object($resultFetchMail))
        {
            if ($resultFetchMail->num_rows === 1)
            {
                $row = $resultFetchMail->fetch_array();

                if($encryptedPassword !== IC::HASH_PASSWD && $row['password'] !== IC::HASH_PASSWD)
                {
                    return TRUE;

                }elseif($encryptedPassword === IC::HASH_PASSWD && $row['password'] === IC::HASH_PASSWD) //same initial hash for everyone BUT need to check also if the user typed the same password
                {
                    return 'You must update your password <a href="../admin/changePassword.php?email='.$p1_email.'" target="_blank">here</a> before please!';

                }elseif($encryptedPassword !== IC::HASH_PASSWD && $row['password'] === IC::HASH_PASSWD)
                {
                    return 'You must type the correct initial shared password to update it!';

                }

            }else return 'Your credentials seem invalid!';

        }else return $this->sqli->error;
    }

    /**
     * @param array $a
     * @return bool|string
     */
    public function updateUserPassword(array $a)
    {
        if(strlen($a['password_1']) > 5)
        {
            if($a['password_1'] === $a['password_2'])
            {

                if(sha1($a['password_1']) !== IC::HASH_PASSWD && sha1($a['password_2']) !== IC::HASH_PASSWD)
                {
                    $query = 'UPDATE tbl_customers
                              SET password = "'.sha1($this->sqli->real_escape_string($a['password_1'])).'"
                              WHERE email="'.$this->sqli->real_escape_string($a['email']).'"';

                    $result = $this->sqli->query($query);

                    if($result)
                    {
                        if($this->sqli->affected_rows === 1)
                        {
                            return TRUE;

                        }else return 'We could not find your email in our database!';

                    }else return $this->sqli->error;

                }else return 'You must define a NEW password!';

            }else return 'Passwords must match!';

        }else return 'Your password must be at least of 6 characters!';
    }

    /**
     * @param bool $p1_display_only_available
     * @return \Generator
     */
    public function fetchTampoonInfos($p1_display_only_available = TRUE)
    {
        $result = $this->sqli->query('SELECT * FROM tbl_tampoons '.(($p1_display_only_available) ? 'WHERE quantity > 0' : ''));

        if(is_object($result))
        {
            if ($result->num_rows > 0)
            {
                while ($rows = $result->fetch_array()) yield $rows;

            }else yield 'No tampoon in database!';

        }else yield $this->sqli->error;
    }

    /**
     * @param array $p1_datas_post
     * @param $p2_filesSaved
     * @return bool|string
     */
    public function saveOrder(array $p1_datas_post, $p2_filesSaved)
    {
        $queryOne = 'INSERT INTO tbl_orders
                    SET id_customer = (SELECT id FROM tbl_customers WHERE tbl_customers.email ="'.$this->sqli->real_escape_string($p1_datas_post['clientEmail']).'"),
                    date_order = "'.$this->dateOrder.'",
                    total = "'.$this->sqli->real_escape_string($p1_datas_post['total']).'",
                    status = '.$p2_filesSaved;

        $resultOne = $this->sqli->query($queryOne);

        if($resultOne)
        {
            $insertIdQueryOne = $this->sqli->insert_id;

            $onlyTampoonInfos = [];

            foreach($p1_datas_post as $k => $v):

                if(!empty($v) && !in_array($k, ['password', 'clientEmail', 'quantityTampoon', 'total', ]))
                {
                    $tampoonRef = strtr($k, '_', ' ');
                    $onlyTampoonInfos[$tampoonRef] = $v;

                    $queryTwo = 'INSERT INTO tbl_orders_details
                                  SET id_order = '.$insertIdQueryOne.',
                                    id_tampoon = (SELECT id FROM tbl_tampoons WHERE tbl_tampoons.reference = "'.$tampoonRef.'"),
                                    quantity = '.(int)$v;

                    $resultTwo = $this->sqli->query($queryTwo);

                    if(!$resultTwo) return $this->sqli->error;
                }

            endforeach;

            return $this->updateTampoonQuantities($onlyTampoonInfos);

        }else return $this->sqli->error;

    }
    
    /**
     * @param array $p1_tampoon_infos
     * @return bool|string
     */
    public function updateTampoonQuantities(array $p1_tampoon_infos)
    {
        foreach($p1_tampoon_infos as $k => $v):

            $query = 'UPDATE tbl_tampoons AS tp1 INNER JOIN tbl_tampoons AS tp2 ON tp1.reference = tp2.reference AND tp1.reference ="'.$k.'" SET tp1.quantity = (tp2.quantity - '.(int)$v.')';

            $result = $this->sqli->query($query);

            if($result){

                mysqli_free_result($result); //don't remove this line

            }else return $this->sqli->error;

        endforeach;

        return TRUE;
    }
}