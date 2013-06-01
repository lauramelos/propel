<?php

header('Content-disposition: attachment; filename=brochure-PROPEL_Physician_Information.pdf');
header('Content-type: application/pdf');
readfile('brochure-PROPEL_Physician_Information.pdf');

?>