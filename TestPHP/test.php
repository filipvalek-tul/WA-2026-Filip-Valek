<?php
$name = "";
$message = "";
$age = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["my_name"];
    $age = $_POST["my_age"];
    if($name == "Filip"){
        $message = "Ahoj Filipe";
    } else {
        $message = "Ty nejsi Filip";
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PHP</title>
</head>
<body>
    <h1>Test formulare</h1>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perspiciatis enim praesentium ducimus, repudiandae ad vero in reiciendis, voluptate eligendi debitis vel laudantium ea rerum officia maiores iusto aperiam fugiat dolores?</p>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit, a maxime minima asperiores quam iure nesciunt incidunt dolore architecto molestiae, possimus, voluptate eveniet ab voluptates rem! Repellat ducimus provident recusandae!</p>
    <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Inventore nostrum id, veritatis similique praesentium perferendis velit maxime enim ullam quam corporis quod quos aliquid tempora, sed quidem eum incidunt. Eligendi!</p>
    <form method="post">
        <input type="text" name="my_name" placeholder="Zadejte jmeno"> 
        <button type="submit">Odeslat</button> 
        <input type="number" name="my_age" placeholder="Zadej vek">
        <button type="submit">Odeslat</button>
    </form>

    <p>
        <?php   echo $message; ?>
    </p>

    <p>
        <?php   echo "Tvuj vek: ";
                echo $age;
        ?>
    </p>

</body>
</html>