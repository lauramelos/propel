<?php
if($_REQUEST['emsg']=='csvonly')
{
echo '<p class="errormsg">'.__('Please select "csv" file only.').'</p>';
}elseif($_REQUEST['emsg']=='csvonly')
{
echo '<p class="errormsg">'.__('Please check "wp-content/uploads" folder permission. It should be 0777.').'</p>';
}elseif($_REQUEST['emsg']=='id_title')
{
echo '<p class="errormsg">'.__('Please make sure either "ID" or "PRODUCT_NAME" column is in the CSV file, other wise you cannot do update.').'</p>';
}elseif($_REQUEST['emsg']=='wrongfile')
{
echo '<p class="errormsg">'.__('The uploaded file is wrong or zero file size.').'</p>';
}else if($_REQUEST['uemsg']=='csvonly')
{
echo '<p class="errormsg">'.__('Please select "csv" file only.').'</p>';
}elseif($_REQUEST['uemsg']=='csvonly')
{
echo '<p class="errormsg">'.__('Please check "wp-content/uploads" folder permission. It should be 0777.').'</p>';
}elseif($_REQUEST['uemsg']=='id_title')
{
echo '<p class="errormsg">'.__('Please make sure "PRODUCT_NAME" column is in the CSV file, other wise you cannot insert the information.').'</p>';
}elseif($_REQUEST['uemsg']=='wrongfile')
{
echo '<p class="errormsg">'.__('Wrong file or file type you uploading. Please check the file and try again.').'</p>';
}
elseif($_REQUEST['uemsg']=='nodata')
{
echo '<p class="errormsg">'.__('Sorry, no data to export.').'</p>';
}
?>