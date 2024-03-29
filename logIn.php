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
    <title>LogIn</title>
</head>
<body>
<section class="formulario">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <label>User:</label>
            <input name="user" type="text"><br>
            <label>Password:</label>
            <input name="password" type="password"><br>
            <input type="submit" value="LogIn" name="submit">
    </form>
</section>


<?php
if (isset($_SESSION['user'])) {
    showOptions();
} else {
    if (isset($_REQUEST['submit'])) {
        $user = $_REQUEST['user'];
        $pw = md5($_REQUEST['password']);
        try {
            $stmt = $conn->prepare("Select usuario, clave from usuarios where usuario =:user and clave =:clave");
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':clave', $pw);
            $stmt->execute();
            $result = $stmt->fetchAll();
            $count = $stmt->rowCount();
            if ($count == 0) {
                echo "<script>alert('Usuario equivocado')</script>";
            } else {
                $_SESSION['user'] = $user;
                if (isset($_SESSION['user'])) {
                    showOptions();
                }
            }
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die("Connection to database failed!");
        }

    }
}
function showOptions()
{
    ?>
    <table class="opciones">
        <th><a href='listNews.php' class="opcion">List of News</a></th>
        <th><a href='insertNew.php'class="opcion">Insert a piece of news</a></th>
        <th><a href='deleteNew.php'class="opcion">Delete News</a></th>
        <th><a href='logOut.php'   class="opcion">Log Out</a></th>
    </table>
    <?php
}
?>

</body>
</html>
