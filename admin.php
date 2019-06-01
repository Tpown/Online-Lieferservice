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


    protected function getViewData()
    {
        $sql = "SELECT b.ID, b.CustID, b.Time, o.quantity, o.fProductID, o.Status,p.title FROM Bestellung b
                INNER JOIN ordered_products o ON (o.fOrderID = b.ID)
                INNER JOIN product p ON (p.ID = o.fProductID)
                WHERE o.Status < 3";

        $Recordset = $this->_database->query($sql);
        $Orders = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $ID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $CustID = htmlspecialchars($Record["CustID"], ENT_QUOTES);
                $Time = htmlspecialchars($Record["Time"], ENT_QUOTES);
                $quantity = htmlspecialchars($Record["quantity"], ENT_QUOTES);
                $fProductID = htmlspecialchars($Record["fProductID"], ENT_QUOTES);
                $status = htmlspecialchars($Record["Status"],  ENT_QUOTES);
                $title = htmlspecialchars($Record["title"],  ENT_QUOTES);
                $Orders[] = [
                    "ID" => $ID,
                    "CustID" => $CustID,
                    "Time" => $Time,
                    "ProductID" => $fProductID,
                    "Quantity" => $quantity,
                    "Status" => $status,
                    "Title" => $title
                ];
                $Record = $Recordset->fetch_assoc();
            }
            $Recordset->free();
        }

        array_multisort(array_column($Orders, 'ID'), SORT_ASC, $Orders);

        /*   foreach ($Orders as $orders) {
            echo  "ID " . $orders["ID"] . " CustID: " .  $orders["CustID"] . " Time: " . $orders["Time"] . " Anzahl: " . $orders["Quantity"] . " ProductID: " . $orders["ProductID"] . " Titel: " . $orders["Title"] . $orders["Status"];
            echo "<br>";
        }*/

        return $Orders;
    }
    protected function getViewData_orders()
    {
        $sql = "SELECT DISTINCT b.ID FROM Bestellung b
        INNER JOIN ordered_products o ON (o.fOrderID = b.ID)
        WHERE o.Status < 3";

        $Recordset = $this->_database->query($sql);
        $Orders = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $ID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $Orders[] = [
                    "ID" => $ID,
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
        $ordered_products = $this->getViewData();
        $this->generatePageHeader("Sushi - Admin");

        //echo $_SESSION["orderID"];

        echo <<<HTML

    <section class="container">
        <h2 id="header">privater Bereich</h2>
        <button class="accordion">Sushi-Meister</button>
        <div id="Meister" class="accordion-content">
            <section class="left-area">
                <h4>Bestellungen: </h4>
                <ul class="itemlist"> 
HTML;

        /*------------------------------INSERT Orders--------------------------------------------------------*/


        $x = 1;
        $ck = "checked";
        foreach ($Orders as $order) {
            if (isset($_SESSION["selectID"])) {
                if ($order["ID"] == $_SESSION["selectID"]) {
                    $this->insert_order_ckd($order["ID"], $x, $ck);
                } else {
                    $this->insert_tablerow($order["ID"], $x);
                }
            } else if (isset($_SESSION["Admin_orderID"])) {
                if ($order["ID"] == $_SESSION["Admin_orderID"]) {
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


        /*---------------------------------INSERT ITEMS FOR ORDER-----------------------------------------------------------------*/
       
        if (isset($_SESSION["selectID"])) {
            $isTrue = false;
            $selectId = $_SESSION["selectID"];
            foreach ($Orders as $order) {
                if ($order["ID"] == $selectId) {
                    $isTrue = true;
                };
            }
            if ($isTrue) {
                echo <<<HTML
                <script>
                getOrder($selectId);
                </script>
HTML;
            }
        } else if (isset($_SESSION["Admin_orderID"])) {
            $orderId = $_SESSION["Admin_orderID"];
            echo <<<HTML
        <script>
        getOrder($orderId);
        </script>
HTML;
        }


       
        /*--------------------------------------------------------------------------------------------------*/
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
        $ck4 = $ck + 4;

        $curr0 = "";
        $curr1 = "";
        $curr2 = "";
        $curr3 = "";


        switch ($status) {
            case 0:
                $curr0 = "checked";
                break;
            case 1:
                $curr1 = "checked";
                break;
            case 2:
                $curr2 = "checked";
                break;
            case 3:
                $curr3 = "checked";
                break;
        }

        echo <<<HTML
        <h6  id="product"> Anzahl: $entry3    |    Produkt: $entry1 </h6>
        <ul class="checkbox-items"> 
            <li>
            <input type="radio" id="checkbox$ck1" name="item$nr" value="0" onclick="document.forms['formid'].submit()" $curr0>
                <label class="status" for="checkbox$ck1">
                   Bestellt
                </label>
            </li>
            <li>
            <input type="radio" id="checkbox$ck2" name="item$nr" value="1" onclick="document.forms['formid'].submit()" $curr1>
                <label class="status" for="checkbox$ck2">
                    Wird zubereitet
                </label>
            </li>
            <li>
            <input type="radio" id="checkbox$ck3" name="item$nr" value="2" onclick="document.forms['formid'].submit()" $curr2>
                <label class="status" for="checkbox$ck3">
                   Verpackt
                </label>
            </li>
            <li>
            <input type="radio" id="checkbox$ck4" name="item$nr" value="3" onclick="document.forms['formid'].submit()" $curr3>
                <label class="status" for="checkbox$ck4">
                   Bereit zur Lieferung
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
        if (empty($Orders)) {
            unset($_SESSION["selectID"]);
            unset($_SESSION["Admin_orderID"]);
        }

        if (isset($_POST["Bestellung"])) {
            $_SESSION["selectID"] = $_POST["Bestellung"];
            $OrderID = $this->_database->real_escape_string($_POST["Bestellung"]);
            for ($i = 1; $i <= 4; ++$i)
                if (isset($_POST["item$i"])) {
                    $item = $_POST["item$i"];
                    $sql_item = $this->_database->real_escape_string($item);
                    $sql = "UPDATE ordered_products SET Status=\"$sql_item\" WHERE fOrderID = \"$OrderID\" AND fProductID = \"$i\"";
                    $this->_database->query($sql);
                    header('Location: http://127.0.0.1/Webseite/admin.php');
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
