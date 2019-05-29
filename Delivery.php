<?php    // UTF-8 marker äöüÄÖÜß€
require_once './Page.php';

class Admin extends Page
{
    protected function __construct()
    {
        parent::__construct();
        // to do: instantiate members representing substructures/blocks
    }

    protected function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData_orders()
    {
        $sql = "SELECT DISTINCT b.ID, b.CustID, b.Time, c.surname, c.lastname, c.address, c.zip, c.city, c.phone FROM Bestellung b
        INNER JOIN customer c ON (c.ID = b.CustID)
        INNER JOIN ordered_products o ON o.fOrderID = b.ID
        WHERE o.Status < 5";

        $Recordset = $this->_database->query($sql);
        $Orders = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $ID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $CustID = htmlspecialchars($Record["CustID"], ENT_QUOTES);
                $Time = htmlspecialchars($Record["Time"], ENT_QUOTES);
                $Surname = htmlspecialchars($Record["surname"], ENT_QUOTES);
                $Lastname = htmlspecialchars($Record["lastname"], ENT_QUOTES);
                $Address = htmlspecialchars($Record["address"], ENT_QUOTES);
                $Zip = htmlspecialchars($Record["zip"], ENT_QUOTES);
                $City = htmlspecialchars($Record["city"], ENT_QUOTES);
                $Phone = htmlspecialchars($Record["phone"], ENT_QUOTES);
                $Orders[] = [
                    "ID" => $ID,
                    "CustID" => $CustID,
                    "Time" => $Time,
                    "surname" => $Surname,
                    "lastname" => $Lastname,
                    "address" => $Address,
                    "zip" => $Zip,
                    "city" => $City,
                    "phone" => $Phone
                ];
                $Record = $Recordset->fetch_assoc();
            }
            $Recordset->free();
        }
        array_multisort(array_column($Orders, 'ID'), SORT_DESC, $Orders);
        return $Orders;
    }

    protected function generateView()
    {
        $Orders = $this->getViewData_orders();
        $this->generatePageHeader("Sushi - Lieferant");


        echo <<<HTML
        
    <section class="container">
        <h2 id="header">privater Bereich</h2>
        <button class="accordion">Sushi-Lieferant</button>
        <div id="Meister" class="accordion-content">
            <section class="left-area">
                <h4>Bestellungen: </h4>
                <ul class="itemlist"> 
HTML;

        /*------------------------------INSERT Orders--------------------------------------------------------*/

       /* echo $_SESSION["orderID"];
        echo $_SESSION["Delivery_orderID"];
        echo $_SESSION["selectID"];*/

        $x = 1;
        $ck = "checked";
        foreach ($Orders as $order) {
            if (isset($_SESSION["selectID"])) {
                if ($order["ID"] == $_SESSION["selectID"]) {
                    $this->insert_order_ckd($order["ID"], $x, $ck);
                } else {
                    $this->insert_tablerow($order["ID"], $x);
                }
            } else if (isset($_SESSION["Delivery_orderID"])) {
                if ($order["ID"] == $_SESSION["Delivery_orderID"]) {
                    $this->insert_order_ckd($order["ID"], $x, $ck);
                } else {
                    $this->insert_tablerow($order["ID"], $x);
                }
            } else {
                $this->insert_tablerow($order["ID"], $x);
            }
            ++$x;
        };
        /*--------------------------------------------------------------------------------------------------*/
        echo <<<HTML
            </ul>
</section> 
        <section class="right-area"> 
        <h4 id="status-title"></h4>
        <section id ="order-item-status">
        <form action="Delivery.php" id="formid" method="POST">
            <input   name = "Bestellung"  type = "hidden"/>
HTML;

        if (isset($_SESSION["selectID"])) {
            $selectId = $_SESSION["selectID"];
            echo <<<HTML
            <script type="text/javascript">
              getOrder($selectId);
            </script>
HTML;
        } else if (isset($_SESSION["Delivery_orderID"])) {
            $deliveryOrderID = $_SESSION["Delivery_orderID"];
            echo <<<HTML
            <script type="text/javascript">
             getOrder($deliveryOrderID);
            </script>
HTML;
        }else{
            foreach($Orders as $order) {
                echo $order['ID'], '<br>';
            }
        };

        echo <<<HTML
        </form>
            </section>
    </section>           
        </div>

    </section>
    </div>
HTML;

        $this->generatePageFooter();
    }

    /**--------------------------------------------------------------------------------------------------**
     **                   Hilfsfunktionen                                                                **
     **--------------------------------------------------------------------------------------------------**/
    protected function insert_tablerow_items($entry1, $ck, $entry3, $nr, $status)
    {

        $ck1 = $ck + 1;
        $ck2 = $ck + 2;
        $ck3 = $ck + 3;

        $curr0 = "";
        $curr1 = "";
        $curr2 = "";

        switch ($status) {
            case 3:
                $curr0 = "checked";
                break;
            case 4:
                $curr1 = "checked";
                break;
            case 5:
                $curr2 = "checked";
                break;
        }


        echo <<<HTML
        <h6  id="product"> Anzahl: $entry3    |    Produkt: $entry1 </h6>
        <ul class="checkbox-items"> 
            <li>
            <input type="radio" id="checkbox$ck1" name="item$nr" value="3" onclick="document.forms['formid'].submit()" $curr0>
                <label class="status" for="checkbox$ck1">
                   Bereit zur Lieferung
                </label>
            </li>
            <li>
            <input type="radio" id="checkbox$ck2" name="item$nr" value="4" onclick="document.forms['formid'].submit()" $curr1>
                <label class="status" for="checkbox$ck2">
                    Wird zugestellt
                </label>
            </li>
            <li>
            <input type="radio" id="checkbox$ck3" name="item$nr" value="5" onclick="document.forms['formid'].submit()" $curr2>
                <label class="status" for="checkbox$ck3">
                   Ist zugestellt
                </label>
            </li>
        </ul>
HTML;
    }

    protected function insert_tablerow($entry, $x)
    {
        echo <<<HTML
            <li>   
            <input type="radio" name="order" id="item$x" onclick="getOrder($entry)">
                <label  class="items" for="item$x">
                #$entry Bestellung
                </label> 
                </li>  
HTML;
    }

    protected function insert_order_ckd($entry, $x, $ck)
    {
        echo <<<HTML
            <li>   
            <input type="radio" name="order" id="item$x" onclick="getOrder($entry)" checked="$ck">
                <label  class="items" for="item$x">
                #$entry Bestellung
                </label> 
                </li>  
HTML;
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();
        $Orders = $this->getViewData_orders();
        echo $_SESSION["selectID"];
        if(in_array($_SESSION["selectID"], $Orders, true)){
            echo "test";
        }
        echo $_SESSION["selectID"];


        if (isset($_POST["Bestellung"])) {

            $_SESSION["selectID"] = $_POST["Bestellung"];
            $OrderID = $this->_database->real_escape_string($_POST["Bestellung"]);
          
            //echo "BestellID: " . $OrderID . "<br>";
            for ($i = 1; $i <= 4; ++$i)
                if (isset($_POST["item$i"])) {
                    $item = $_POST["item$i"];
                    $sql_item = $this->_database->real_escape_string($item);
                    $sql = "UPDATE ordered_products SET Status=\"$sql_item\" WHERE fOrderID = \"$OrderID\" AND fProductID = \"$i\"";
                    $this->_database->query($sql);

                   /* if (empty($Orders)) {
                        unset($_SESSION["selectID"]);
                        unset($_SESSION["Delivery_orderID"]);
                    }else if(!in_array($_SESSION["selectID"], $Orders["ID"]) || !in_array($_SESSION["Delivery_orderID"], $Orders["ID"])){
                        unset($_SESSION["selectID"]);
                        unset($_SESSION["Delivery_orderID"]);
                    }*/
                    header('Location: http://127.0.0.1/Webseite/Delivery.php');
                }
        }
    }
    public static function main()
    {
        session_start();
        try {
            $page = new Admin();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}
Admin::main();
