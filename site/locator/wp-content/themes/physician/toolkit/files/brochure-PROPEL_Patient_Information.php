<?php

header('Content-disposition: attachment; filename=brochure-PROPEL_Patient_Information.pdf');
header('Content-type: application/pdf');
readfile('brochure-PROPEL_Patient_Information.pdf');

?>