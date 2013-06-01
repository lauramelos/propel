<?php

header('Content-disposition: attachment; filename=image-See_it_in_action.jpg');
header('Content-type: application/jpg');
readfile('image-See_it_in_action.jpg');

?>