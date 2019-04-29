<?php
require_once './Page.php';

class PageTemplate extends Page
{

    private $toDisplayAlert = false;
    private $toDisplaySuccess = false;

    protected function __construct()
    {
        parent::__construct();
    }


    protected function __destruct()
    {
        parent::__destruct();
    }

    //Get Products from the Database
    protected function getViewData()
    {
        $sql = "SELECT ID, title, price, picture FROM product";
        $Recordset = $this->_database->query($sql);
        $Products = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $pID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $title = htmlspecialchars($Record["title"], ENT_QUOTES);
                $price = htmlspecialchars($Record["price"], ENT_QUOTES);
                $picture = htmlspecialchars($Record["picture"], ENT_QUOTES);
                $Products[] = [
                    "ID" => $pID,
                    "picture" => $picture,
                    "title" => $title,
                    "price" => $price
                ];
                $Record = $Recordset->fetch_assoc();
            }
            $Recordset->free();
        }
        return $Products;
    }

    //Showing the HTML Format to the Viewport
    protected function generateView()
    {
        $Products = $this->getViewData();
        $this->generatePageHeader('Sushi-Lieferservice');
        echo <<<HTML
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
        function insert_tablerow($entry1 = "", $entry2 = "", $entry3 = "", $entry4 = "")
        {
            echo "<div class=\"cart-row\">
        <div class=\"cart-item cart-column\">";
            echo  "<img src=\"$entry1\" width=\"100\" height=\"100\" alt=\"Bild nicht verfügbar\">
    <span class=\"product-title\">$entry2. $entry3</span>
    </div>";
            echo "<span class=\"cart-price cart-column\">$entry4 €</span>
<div class=\"cart-quantity cart-column\">
    <input class=\"cart-quantity-input\" type=\"number\" value=\"1\">
    <button class=\"btn btn-dark\" type=\"button\"> Hinzufügen </button>
</div>
</div>";
        }
        foreach ($Products as $product) {
            insert_tablerow($product["picture"], $product["ID"], $product["title"], $product["price"]);
        }

        echo <<<HTML
        </div>
    </section>

    <section id="customer-info">
        <h2>Kundeninformation</h2>
HTML;
        if ($this->toDisplayAlert) {
            echo "<div id=\"success-alert\" class=\"alert alert-danger\" role=\"alert\" style=\"display: block\">Nutzer existiert bereits schon!</div>";
        }
        if ($this->toDisplaySuccess) {
            echo "<div id=\"success-alert\" class=\"alert alert-success\" role=\"alert\" style=\"display: block\">Ihre Bestellung wurde erfolgreich zugesendet!</div>";
        }
        echo <<<HTML
        <form action="shopping_cart.php" method="POST">
            <div id="firstname" class="left-field">Vorname: <br>
                <input type="text" class="form-control" name="firstname" required>
            </div>
            <div id="lastname" class="right-field">Nachname: <br>
                <input type="text" class="form-control" name="lastname" required>
            </div>
            <div id="address" class="one-field">Straße mit Hausnr.: <br>
                <input type="text" class="form-control" name="address" required>
            </div>
            <div id="zip" class="left-field">PLZ: <br>
                <input type="text" class="form-control" name="zip" required>
            </div>
            <div id="city" class="right-field">Ort: <br>
                <input type="text" class="form-control" name="city" required>
            </div>
            <div id="phone" class="one-field">Telefonnummer (zur Rückfrage): <br>
                <input type="text" class="form-control" name="phone" required>
            </div>
            <div class="cart-list">
                <h2>Warenkorb</h2>
            </div>
            <div class="cart-total">
                <h4>Gesamtpreis:
                    <span class="cart-total-price">0€</span>
                    <input class="cart-total-price-input" type = "hidden" name = "total" required/>
                </h4>
            </div>
            <button id="btn-pay" type="submit" class="btn btn-warning">Jetzt bestellen</button>     
        </form>
    </section>
</div>

HTML;
        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();

        if (isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["address"]) && isset($_POST["zip"]) && isset($_POST["city"]) && isset($_POST["phone"])) {
            $user_firstname = $_POST["firstname"];
            $user_lastname = $_POST["lastname"];
            $user_address = $_POST["address"];
            $user_zip = $_POST["zip"];
            $user_city = $_POST["city"];
            $user_phone = $_POST["phone"];


            if (strlen($user_lastname)  <= 0 || strlen($user_address) <= 0 || strlen($user_phone) <= 0) {
                throw new Exception("Bitte geben Sie in beiden Feldern etwas an!");
            } else {
                $sql_user_firstname = $this->_database->real_escape_string($user_firstname);
                $sql_user_lastname = $this->_database->real_escape_string($user_lastname);
                $sql_user_address = $this->_database->real_escape_string($user_address);
                $sql_user_zip = $this->_database->real_escape_string($user_zip);
                $sql_user_city = $this->_database->real_escape_string($user_city);
                $sql_user_phone = $this->_database->real_escape_string($user_phone);

                /*
                **
                **Insert customer into the database
                */
                $SQL_insert_customer =
                    "INSERT INTO customer (surname, lastname, address, zip, city, phone) " . "VALUES (\"$sql_user_firstname\",
                     \"$sql_user_lastname\",  \"$sql_user_address\", \"$sql_user_zip\",
                     \"$sql_user_city\", \"$sql_user_phone\")";

                $this->_database->query($SQL_insert_customer);
                $custID = $this->_database->insert_id;
                // echo $custID;

                /* Insert Order into the database */
                $SQL_insert_order =
                    "INSERT INTO bestellung (CustID)" . "VALUES(\"$custID\")";
                $this->_database->query($SQL_insert_order);
                $orderID = $this->_database->insert_id;


                $Products = $this->getViewData();
                $count = 1;
                foreach ($Products as $product) {
                    if (isset($_POST["p$count"])) {
                        $quantity = $_POST["p$count"];
                        $pID = $product["ID"];
                        $SQL_insert_items =
                            "INSERT INTO ordered_products (fProductID, fOrderID, Status, quantity)" .
                            "VALUES (\"$pID\", \"$orderID\", \"0\", \"$quantity\")";
                        $this->_database->query($SQL_insert_items);
                    }
                    ++$count;
                }

                $this->toDisplaySuccess = true;
                // }

            }
        }
    }

    public static function main()
    {
        try {
            $page = new PageTemplate();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

PageTemplate::main();
