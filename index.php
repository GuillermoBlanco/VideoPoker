<?php
    session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
            echo '<div>
            <form action="jugar.php" method="get" enctype="text/plain" >
            <p>Tu nombre:<input style="" type="text" name="nombre"></p>
            <p>Tu dinero:<input style="" type="number" min=5 max=10 name="dinero"></p>
            <input type="submit" value="enviar">
            </form>
            </div>';
        ?>
    </body>
</html>
