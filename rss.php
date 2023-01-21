<?php
function mkRSS () {
////KONFIGURATION (diese Variablen müssen angepasst werden):
   $dbServer = "localhost";  //database server (meist localhost)
   $dbName = "";         //name of database
   $dbUser = "";           // user name
   $dbPassword=""; // user password
   $tableName="stu_kn_messages";     // Tabellenname der Tab., aus der die Daten entnommen werden
   $lines="20";              // Anzahl anzuzeigender Datensätze
   $filename="rss";       //ohne Extension
   $title="STU KN-Feed";     //Titel des RSS-Feeds
   $description="Die ".$lines." letzten KN-Mitteilungen";     //Was zeigt das RSS-Feed?
   $language="de";           //Sprachkürzel
   $link="http://www.stuniverse.de"; //Link
   $itemTitle="subject"; //Spaltenname aus der DB, wo der Titel des einzelnen Items steht
   $itemText="text";  //Spaltenname aus der DB, wo der Text des einzelnen Items steht

   //das SQL-Statement muss an die eigenen Erfordernisse angepasst werden
   $sql="SELECT * FROM ".$tableName." ORDER BY id DESC LIMIT 0,$lines";

//Bitte beachten, dass in der Zeile 45 noch die Variable $itemLink angepasst werden muss.
////ENDE KONFIGURATION

//DOCUMENT_ROOT wird automatisch ermittelt
   $siteRoot= substr_replace ($_SERVER[DOCUMENT_ROOT].$_SERVER["PHP_SELF"],
              "",strrpos ($_SERVER[DOCUMENT_ROOT].$_SERVER["PHP_SELF"], "/")+1);

//Erzeugen des RSS-Inhaltes:
   $rssHeader="<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>
              <rss version=\"0.91\">
              <channel>
              <title>".$title."</title>
              <description>".$description."</description>
              <language>".$language."</language>
              <link>$link</link>
              ";
   $rssFooter="</channel>
              </rss>";
   $dbLink = mysql_connect ($dbServer,$dbUser,$dbPassword) or die (mysql_error());
   $setdb = mysql_select_db($dbName,$dbLink) or die (mysql_error());
   $result=mysql_query($sql,$dbLink) or die(mysql_error());
   $content=$rssHeader;
   while($row=mysql_fetch_array($result)){
      $titel=substr ($row[$itemTitle], 0, 150);  //$row anpassen! auch in der nächsten Zeile
      $text=substr ($row[$itemText], 0, 500); //der Text darf höchstens 500 Zeichen lang sein
      $itemLink="...";   //dies ist ein Link, der DIREKT diesen Punkt auf der Homepage öffnet.
                         //Er muss entsprechend deines Scripts dynamisch gebildet werden.
      $content.="<item>
                <title>".strip_tags(stripslashes(htmlentities($titel)))."</title>
                <description>".strip_tags(stripslashes(htmlentities($text)))."</description>
                <link>".$itemLink."</link>
                </item>
                ";
   }
   //file wird geschrieben
   $fh=fopen ($siteRoot."rss/".$filename.".rss", "w+");
   fputs ( $fh, $content);
   fclose($fh);

   //file wird umbenannt
   rename ($siteRoot."rss/".$filename.".rss", $siteRoot."rss/".$filename.".xml");
   return;
}
mkRSS();
?>
