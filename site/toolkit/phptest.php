<?php

header('Content-disposition: attachment; filename=files/brochure-PROPEL_Patient_Information.pdf');
header('Content-type: application/pdf');
readfile('files/brochure-PROPEL_Patient_Information.pdf');

?>