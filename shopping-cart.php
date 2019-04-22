<?php

try {
    // MIME-Type der Antwort definieren (*vor* allem HTML):
    header("Content-type: text/html; charset=UTF-8");
    // alle möglichen Fehlermeldungen aktivieren:
    error_reporting(E_ALL);

    // Datenbank öffnen:
    require_once 'pwd.php'; // Passwort einlesen
    $Connection = new MySQLi($host, $user, $pwd, "sushi_lieferservice");

    // Verbindung prüfen:
    if (mysqli_connect_errno())
        throw new Exception("Connect failed: " . mysqli_connect_error());
    if (!$Connection->set_charset("utf8"))
        throw new Exception("Charset failed: " . $Connection->error);

    $sql = "SELECT ID, title, price, picture FROM product";
    $Recordset = $Connection->query($sql);
    $Products = array();

    if ($Recordset) {
        $Record = $Recordset->fetch_assoc();
        while ($Record) {
            $title = htmlspecialchars($Record["title"], ENT_QUOTES);
            $price = htmlspecialchars($Record["price"], ENT_QUOTES);
            $picture = htmlspecialchars($Record["picture"], ENT_QUOTES);
            $Products[] = [
                "picture" => $picture,
                "title" => $title,
                "price" => $price
            ];
            $Record = $Recordset->fetch_assoc();
        }
        $Recordset->free();
    }


    function insert_tablerow($entry1 = "", $entry2 = "", $entry3 = "")
    {
        echo "<div class=\"cart-row\">
            <div class=\"cart-item cart-column\">";
        echo  "<img src=\"$entry1\" width=\"100\" height=\"100\" alt=\"Bild nicht verfügbar\">
        <span class=\"product-title\">$entry2</span>
        </div>";
        echo "<span class=\"cart-price cart-column\" data-price=\"2.00\">$entry3 €</span>
    <div class=\"cart-quantity cart-column\">
        <input class=\"cart-quantity-input\" type=\"number\" value=\"1\">
        <button class=\"btn btn-dark\" type=\"button\"> Hinzufügen </button>
    </div>
</div>";
    }

    echo <<<HTML
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>Sushi Lieferservice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style/shopping-cart.css">
    <script src="./js/shopping-cart.js" async></script>
</head>

<body>
    <div class="layer"></div>
    <div class="top-navbar">
        <ul>
            <li> <a href="index.html">Über uns</a></li>
            <li> <a href="menu.html">Speisekarte</a></li>
            <li> <a href="order-status.html">Lieferstatus</a></li>
            <li> <a href="shopping-cart.html" class="active-menu"><i class="fas fa-shopping-cart"></i></a></li>
        </ul>
    </div>
    <div id="logo"> Sushi Lieferservice</div>

    <div id="container-overview">
        <section id="order-overview">
            <h2>Bestellübersicht</h2>
            <div class="cart-row">
                <span class="cart-item cart-header cart-column">Produkt</span>
                <span class="cart-price cart-header cart-column">Preis</span>
                <span class="cart-quantity cart-header cart-column">Anzahl</span>
            </div>
            <div class="cart-items">
HTML;



    //PHP Code here
    foreach ($Products as $product) {
        insert_tablerow($product["picture"], $product["title"], $product["price"]);
    }

    echo <<<HTML
            </div>
        </section>

        <section id="customer-info">
            <h2>Kundeninformation</h2>
            <form action="shopping-cart.php" method="POST">
                <div id="firstname" class="left-field">Vorname: <br>
                    <input type="text" class="form-control" name="firstname">
                </div>
                <div id="lastname" class="right-field">Nachname: <br>
                    <input type="text" class="form-control" name="lastname">
                </div>
                <div id="address" class="one-field">Straße mit Hausnr.: <br>
                    <input type="text" class="form-control" name="address">
                </div>
                <div id="zip" class="left-field">PLZ: <br>
                    <input type="text" class="form-control" name="zip">
                </div>
                <div id="city" class="right-field">Ort: <br>
                    <input type="text" class="form-control" name="city">
                </div>
                <div id="phone" class="one-field">Telefonnummer (zur Rückfrage): <br>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="cart-list">
                    <h2>Warenkorb</h2>
                </div>
                <div class="cart-total">
                    <h4>Gesamtpreis:
                        <span class="cart-total-price">0€</span>
                        <input class="cart-total-price-input" type = "hidden" name = "total"/>
                    </h4>
                </div>
                <button id="btn-pay" type="submit" class="btn btn-warning">Jetzt bestellen</button>     
            </form>
        </section>

    </div>
</body>
</html>
HTML;

    $user_firstname = $_POST["firstname"];
    $user_lastname = $_POST["lastname"];
    $user_address = $_POST["address"];
    $user_zip = $_POST["zip"];
    $user_city = $_POST["city"];
    $user_phone = $_POST["phone"];

    if (strlen($user_lastname)  <= 0 || strlen($user_address) <= 0 || strlen($user_phone) <= 0) {
        throw new Exception("Bitte geben Sie in beiden Feldern etwas an!");
    } else {
        $sql_user_firstname = $Connection->real_escape_string($user_firstname);
        $sql_user_lastname = $Connection->real_escape_string($user_lastname);
        $sql_user_address = $Connection->real_escape_string($user_address);
        $sql_user_zip = $Connection->real_escape_string($user_zip);
        $sql_user_city = $Connection->real_escape_string($user_city);
        $sql_user_phone = $Connection->real_escape_string($user_phone);

        $SQL_statement = "SELECT * FROM customer
                        WHERE lastname = \"$sql_user_lastname\" AND phone = \"$sql_user_phone\"";
        $Recordset = $Connection->query ($SQL_statement);

        if($Recordset->num_rows>0){
            throw new Exception("Dieser Eintrag ist bereits vorhanden.");
            $Recordset->free();
        }else{
            $SQL_statement =
            "INSERT INTO customer (surname, lastname, address, zip, city, phone) ". "VALUES (\"$sql_user_firstname\",
             \"$sql_user_lastname\",  \"$sql_user_address\", \"$sql_user_zip\",
             \"$sql_user_city\", \"$sql_user_phone\")";
            $Recordset = $Connection->query($SQL_statement); 
        }
    }
    $Connection->close();
} catch (Exception $e) {
    echo $e->getMessage();
}
