<?php
require_once "Connection.php";
session_start();
//creamos conexion bbdd
$conn = (new Connection())->getPdo();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Create New</title>
</head>
<body>
<section id="Form">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <label>Title</label><input type="text" name="title"><br><br>
            <label>Text</label><textarea name="textArea"></textarea><br><br>
            <label>Category</label>
            <select name="category">
                <?php
                try {
                    $stmt = $conn->prepare("select distinct categoria from noticias");
                    $stmt->execute();
                    $result = $stmt->fetchAll();
                    foreach ($result as $row) {
                        foreach ($row as $clave => $category) {
                            echo "<option value='$category'>$category</option>";
                        }
                    }
                } catch (PDOException $exception) {
                    echo $exception->getMessage();
                    die("Connection to database failed!");
                }
                ?>
            </select><br><br>
            <label>Image</label><input type="file" value="Add File" size="44" name="image"><br>
            <input type="submit" value="Add piece of new" name="submit">
    </form>
    <a href="logIn.php">Come Back Home</a>
</section>
<?php
    if (isset($_REQUEST['submit'])){
        $title = $_REQUEST['title'];
        $text = $_REQUEST['textArea'];
        $cat = $_REQUEST['category'];
        $image = $_REQUEST['image'];
        $idExists = false;
        $id = rand(1,100);
        while ($idExists){
            try {
                $ids = $conn->prepare("Select id from noticias");
                $ids ->execute();
                $idNoticias = $ids->fetchAll();
                foreach ($idNoticias as $valor =>$numero){
                    if ($numero == $id){
                        $idExists= true;
                        $id = rand(1,100);
                    }
                }
            }catch (PDOException $exception) {
                echo $exception->getMessage();
                die("Connection to database failed!");
            }
        }

        $date = date('Y-m-d');
        try {
            $ins = $conn->prepare("Insert into noticias (id,titulo,texto,categoria,fecha,imagen) values(:id,:title,:text,:cat,:fecha,:image)");
            $ins->bindParam(':id',$id);
            $ins->bindParam(':title',$title);
            $ins->bindParam(':text',$text);
            $ins->bindParam(':cat',$cat);
            $ins->bindParam(':fecha',$date);
            $ins->bindParam(':image',$image);
            $ins->execute();
            echo "<h2>The new has been added correctly</h2>";
            echo "<b>id:</b> $id";
            echo "<b>Title: </b>$title";
            echo "<b>Text: </b>$text";
            echo "<b>Category: </b>$cat";
            echo "<b>Date: </b>$date";
            echo "<b>Image: </b>$image";
        }catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Connection to database failed!");
        }
    }
?>
</body>
</html>
