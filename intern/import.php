<?php

system("mysql -uweb1 -pstudb2002 -hlocalhost usr_web1_1 < /srv/www/htdocs/web1/html/stu.sql", $fp); 
if ($fp==0) echo "Daten importiert"; else echo "Es ist ein Fehler aufgetreten";
?>