<?php

namespace Order;
use Model\InitConsts as IC;
require_once '../Model/InitConsts.php';

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Tampoon</title>
    <meta name="description" content="Tampoon" />
    <style>
        *{ margin: 0; padding: 0;}

        html, body{
            width: 100%;
            height: 100%;
        }

        body{ font-family: arial, sans-serif; font-size: 12px; }

        .icon{ width: 50px; height: 50px; text-align: center;}

#infos{ font-size: 18px; display: none; padding: 5px; color: white; width: 150px; height: 80px; /*position: fixed;*/ background-color: black; opacity: 0.8; border-radius: 5px; /*left: 80%; top: 10px;*/ text-align: center; }

#checkvalues{ position: fixed; left: 20%; font-weight: bold; font-size: 18px; visibility: hidden; padding: 15px; color: white; width: 600px; height: 95%; background-color: black; opacity: 0.9; border-radius: 5px; top: 10px; text-align: center; }

#return_from_checkvalues{ overflow: auto; height: 90%; }

#btnClose{ width: 26px; height: 26px; border-radius: 13px; background-color: white; cursor: pointer; color: black; }

#top{ text-align: center; width: 700px; margin-left: auto; margin-right: auto; }

a{ text-decoration: none; color: cornflowerblue; font-family: arial, sans-serif;}

p{ margin-top: 10px; margin-bottom: 10px; }

.container_icon{  display: inline-block; margin: 15px; }

.bigInput{ width: 300px; height: 35px; font-weight: bold; font-size: 25px; border-radius: 7px; }

</style>
    <?php
    echo '<script>'.PHP_EOL.'var tampoonFirstRate = '.IC::TAMPOON_FIRST_RATE.';'.PHP_EOL.'var tampoonSecondRate = '.IC::TAMPOON_SECOND_RATE.';'.PHP_EOL.'var minimumQuantityOrder = '.IC::MINIMUM_Q_ORDER.';'.PHP_EOL.'var currency = "'.IC::CURRENCY[0].'";'.PHP_EOL.'</script>';
    ?>
<script type="text/javascript" src="../js/script.js"></script>
</head>
<body>
<div id="checkvalues">
    <div onclick="document.getElementById('checkvalues').style.visibility = 'hidden';" id="btnClose"><div style="padding-top: 4px;"><b>X</b></div></div>
    <h1>Confirmation</h1>
    <hr/>
    <p id="return_from_checkvalues"></p>
</div>
<div id="top">
 <table>
     <tr>
         <td>
            <img src="../img/logo-tp.png" style="border: none; width: 200px; margin-right: 10px;" />
         </td>
         <td>
    <form style="float: left">
    <select id="fillAction" onchange="if(this.value === 'all'){ fillAllWith1Q(); }else{ fill50ValWithXQ(parseInt(this.value)); }">
        <option value="all">Fill all with 1</option>
        <option value="1">Fill 50 with 1</option>
        <option value="2">Fill 50 with 2</option>
    </select>
    </form>
         </td>
         <td> | <input type="text" id="num_items" placeholder="Items" style="width: 50px;"> with <input type="text" id="quantity" placeholder="Quantity" style="width: 50px;">
             </td>
         <td rowspan="2">
            <a href="#" onclick="fillXQuantitiesWithXItems(document.getElementById('quantity').value, document.getElementById('num_items').value);">Fill Your Values</a>
     </td>
         <td>
             <div id="infos"><p id="return_from_makeSum" style="margin-bottom: 0;"></p></div>
         </td>
     </tr>
     </table>
</div>
<div id="main">
    <form method="post" name="the_form">
<?php
require_once '../Manager/DatabaseManager.php';

$dbm = new \Manager\DatabaseManager;

$outputDBM = $dbm->fetchTampoonInfos();

    foreach($outputDBM as $rows):

        $icon = '../icon'.IC::DS.$rows['reference'].'.jpg';

        echo '<div class="container_icon"><table><tr><td><img class="icon" src="'.$icon.'" /></td></tr>';
        echo '<tr><td>'.$rows['reference'].'</td></tr>';
        echo '<tr><td>';
        echo '<input placeholder="'.(($rows['quantity'] > 0) ? $rows['quantity'] : 0).' dispo" type="text" style="width: 50px; margin-left: 5px;" id="'.$rows['reference'].'" name="'.$rows['reference'].'" onkeyup="makeSum();"';
        echo ' onfocus="if(document.getElementById(\'checkvalues\').style.visibility === \'visible\') document.getElementById(\'checkvalues\').style.visibility = \'hidden\';"/>';
        echo '</td></tr>';
        echo '</table></div>';

    endforeach;
?>
        </form>
    </div>
</body>
</html>