<?php
    function cutString($phrase) {
        $words = explode(' ', $phrase);
        $resultStr = array();
        
        foreach($words as $word) {
            $clWord = str_replace(',', '', $word);
            if(strlen($clWord) >= 3 && strlen($clWord) <= 6) {
                $resultStr[] = $clWord;
            }
        }
        return implode(' ', $resultStr);
    }
    
    $phrase1 = "Not the Sharpest Tool in the Shed";
    $phrase2 = "A Fool and His Money are Soon Parted";
    $phrase3 = "Don't Count Your Chickens Before They Hatch";
    $phrase4 = "If You Can't Stand the Heat, Get Out of the Kitchen";

    echo 'Input: <br>'; 
    echo $phrase1 . '<br>';
    echo $phrase2 . '<br>';
    echo $phrase3 . '<br>';
    echo $phrase4 . '<br>';
    
    echo '<br>';
    echo 'Output: <br>';
    echo cutString($phrase1) . '<br>';
    echo cutString($phrase2) . '<br>';
    echo cutString($phrase3) . '<br>';
    echo cutString($phrase4) . '<br>';
?>