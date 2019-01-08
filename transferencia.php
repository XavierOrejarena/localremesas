#!/usr/bin/env php
<?php

$a = 606;

do {
    $p = floor($a*0.0001 * 100) / 100;
    $result = $a-$p;
    $a = round($a + 0.01,2);
    echo $result,"\n";
} while ($result != 700);

echo "Debe transferir: ",$a,"\n";

?>