#!/usr/bin/env php
<?php

$img_url = 'https://scontent.fccs3-1.fna.fbcdn.net/v/t1.0-9/41925206_336017553633002_2357376427717820416_n.jpg?_nc_cat=0&oh=a7ef737dc45e280c6cb47ff5605e884d&oe=5C2C1D5E';
$file_name = dirname(__FILE__)."/".rand(0,99999).".jpeg";

copy($img_url, $file_name);
?>