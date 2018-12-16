<?php

for($i = 0; $i < 67; $i++){

    $zero = "";
    if($i < 10) { $zero = "0"; }

    echo "&lt;item 
                android:duration=\"100\"
                android:drawable=\"@drawable/scanner_$zero$i\"/&gt;<br/>";

}



?>