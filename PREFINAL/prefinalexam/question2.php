<?php
    function compareNumbers($num1, $num2) {
        if(($num1 - $num2) % 10 == 0) {
            return "true";
        }
        return "false";
    }
    
    $tc1_num1 = 21;
    $tc1_num2 = 51;
    
    $tc2_num1 = 12;
    $tc2_num2 = 73;
    
    $tc3_num1 = 54;
    $tc3_num2 = 94;
    
    $tc4_num1 = 11;
    $tc4_num2 = 82;
    
    echo 'Input: <br>';
    echo $tc1_num1 . ' ' . $tc1_num2 . '<br>';
    echo $tc2_num1 . ' ' . $tc2_num2 . '<br>';
    echo $tc3_num1 . ' ' . $tc3_num2 . '<br>';
    echo $tc4_num1 . ' ' . $tc4_num2 . '<br>';
    
    echo '<br>';
    echo 'Output: <br>';
    echo compareNumbers($tc1_num1, $tc1_num2) . '<br>';
    echo compareNumbers($tc2_num1, $tc2_num2) . '<br>';
    echo compareNumbers($tc3_num1, $tc3_num2) . '<br>';
    echo compareNumbers($tc4_num1, $tc4_num2) . '<br>';
?>
