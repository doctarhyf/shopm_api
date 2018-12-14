<?php

include("phpqrcode/qrlib.php");


QRcode::png("ip_192.168.1.2", "QRTEST.jpg", QR_ECLEVEL_L, 10);

?>