<h2>Updating to the last SVN server version</h2>
<?php
system ("rm helpusserver.php");
system ("pwd");
system ("wget http://wydevices.googlecode.com/svn/trunk/packages/wymodcp-php/web-files/scripts/php/helpusserver.php");
include ('./helpusserver.php');
?>