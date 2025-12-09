<?php
session_start();
session_unset();
session_destroy();
header("Location: welcome.php?message=logout");
exit();
?>
