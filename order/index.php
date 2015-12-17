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
    <?php
    echo '<script>'.PHP_EOL.'var tampoonFirstRate = '.IC::TAMPOON_FIRST_RATE.';'.PHP_EOL.'var tampoonSecondRate = '.IC::TAMPOON_SECOND_RATE.';'.PHP_EOL.'var minimumQuantityOrder = '.IC::MINIMUM_Q_ORDER.';'.PHP_EOL.'var currency = "'.IC::CURRENCY[0].'";'.PHP_EOL.'</script>';
    ?>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
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
    <select id="fillAction" onchange="if(this.value === 'all'){ fillAllWith1Q(); }else if(this.value == '1'){ fill50ValWithXQ(parseInt(this.value)); }else{ fillXQuantitiesWithXItems(1, 100); }">
        <option value="all">Fill all with 1</option>
        <option value="1">Fill 50 with 1</option>
        <option value="2">Fill 100 with 1</option>
    </select>
    </form>
         </td>
         <td> | <input type="text" id="num_items" placeholder="Differents Items" style="width: 100px;"> with <input type="text" id="quantity" placeholder="Quantity" style="width: 50px;">
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

        echo '<div class="container_icon" id="container_'.$rows['reference'].'"><table><tr><td><img class="icon" src="'.$icon.'" /></td></tr>';
        echo '<tr><td>'.$rows['reference'].'</td></tr>';
        echo '<tr><td>';
        echo '<input placeholder="'.$rows['quantity'].'" type="number" min="0" max="'.$rows['quantity'].'" id="'.$rows['reference'].'" name="'.$rows['reference'].'" onclick="makeSum();" ';
         echo 'onchange="if(this.value == 0) document.getElementById(\'container_'.$rows['reference'].'\').style.cssText=\'border: none;\';" onfocus="if(document.getElementById(\'checkvalues\').style.visibility === \'visible\') document.getElementById(\'checkvalues\').style.visibility = \'hidden\';"/>';
        echo '&nbsp;dispo</td></tr>';
        echo '</table></div>';

    endforeach;
?>
        </form>
    </div>
</body>
</html>