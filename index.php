<?php

include('include/twig.php');
$twig = init_twig();

echo $twig->render('base.twig');