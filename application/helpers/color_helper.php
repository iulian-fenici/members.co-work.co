<?php

function getEventColor($ind = 0){
    $colorsArr = array('6db54d','4d74b5');
    return $colorsArr[$ind];
}

function randomColors() {
    $spread = 25;
    for ($row = 0; $row < 100; ++$row){
        for ($c = 0; $c < 3; ++$c){
            $color[$c] = rand(0 + $spread, 255 - $spread);
        }
        echo "'".rgb2html($color[0],$color[1],$color[2])."',";
//        echo "<div style='float:left; background-color:rgb($color[0],$color[1],$color[2]);'>&nbsp;Base Color&nbsp;</div><br/>";
//        for ($i = 0; $i < 92; ++$i){
//            $r = rand($color[0] - $spread, $color[0] + $spread);
//            $g = rand($color[1] - $spread, $color[1] + $spread);
//            $b = rand($color[2] - $spread, $color[2] + $spread);
//            echo "<div style='background-color:rgb($r,$g,$b); width:10px; height:10px; float:left;'></div>";
//        }
//        echo "<br/>";
    }
}

function rgb2html($r, $g=-1, $b=-1)
{
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return $color;
}
?>
