<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Delete News</title>
</head>
<body>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <?php
    require_once "Connection.php";
    session_start();
    $conn = (new Connection())->getPdo();
    try {
        $stmt = $conn->prepare("select id,titulo, texto,categoria,fecha,imagen from noticias");
        $stmt->execute();
        $result = $stmt->fetchAll();
        echo "<table>";
        echo "<tr>";
        echo "<th class='columna'>Titulo</th>";
        echo "<th class='columna'>Texto</th>";
        echo "<th class='columna'>Categoria</th>";
        echo "<th class='columna'>Fecha</th>";
        echo "<th class='columna'>Imagen</th>";
        echo "<th class='columna'>Borrar</th>";
        echo "</tr>";
        foreach ($result as $noticia) {
            echo "<tr>";
            // Itera sobre cada elemento del array $noticia
            foreach ($noticia as $clave => $valor) {//la clave es el nombre de la columna y el valor es el contenido
                if ($clave!=='id'){//por clave que no sea el id se muestra el valor. Lo hago porque si no lo hiciera se mostraría también id
                    if ($clave=='imagen'){
                        echo "<td><a href='imagenesCasas/$valor'><img src='imagenesCasas/camara.jpg' width='50px' height='50px'></a></td>";
                    }else {
                        echo "<td>$valor</td>";
                    }
                }
            }
            echo "<td> <input type='checkbox' name='noticia_a_borrar[]' value='{$noticia['id']}'></td>"; // Crea un checkbox con el id de la noticia como value
            echo "</tr>";
        }
        echo "</table>";

    } catch (PDOException $exception) {
        echo $exception->getMessage();
        die("Connection to database failed!");
    }
    echo "<br><br><input type='submit' value='Delete' name='delete'>";
    ?>

</form>
<a href="logIn.php">Back home</a>

<?php
    if (isset($_REQUEST['delete'])){
        try {
            $del = $conn->prepare("Delete from noticias where id = :id");
            foreach ($_REQUEST['noticia_a_borrar'] as $id){
                $del->bindParam(":id",$id,PDO::PARAM_INT);
                $del->execute();
                header("Location: deleteNew.php");
            }
        }catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Connection to database failed!");
        }
    }
?>

</body>
</html>
