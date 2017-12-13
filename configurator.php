<?php
/**
 * Created by PhpStorm.
 * User: Joseph
 * Date: 12/12/2017
 * Time: 7:30 PM
 */

$configurationURL = str_replace("AMPERSAND", "&amp;", $_GET['configurationURL']);

?>

<html>
<head>

</head>
<body>
<iframe style="width:100%;height:100%;" src="<?php echo $configurationURL ?>"></iframe>
</body>
</html>
