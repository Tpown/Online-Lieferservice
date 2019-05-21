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
        $sql = "SELECT b.ID, b.CustID, o.quantity, o.fProductID, o.Status,p.title, p.picture FROM Bestellung b
        INNER JOIN ordered_products o ON (o.fOrderID = b.ID)
        INNER JOIN product p ON (p.ID = o.fProductID)";

        $Recordset = $this->_database->query($sql);
        $Orders = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $ID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $CustID = htmlspecialchars($Record["CustID"], ENT_QUOTES);
                $Picture = htmlspecialchars($Record["picture"], ENT_QUOTES);
                $quantity = htmlspecialchars($Record["quantity"], ENT_QUOTES);
                $fProductID = htmlspecialchars($Record["fProductID"], ENT_QUOTES);
                $status = htmlspecialchars($Record["Status"],  ENT_QUOTES);
                $title = htmlspecialchars($Record["title"],  ENT_QUOTES);
                $Orders[] = [
                    "ID" => $ID,
                    "CustID" => $CustID,
                    "Picture" => $Picture,
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
    echo  "ID " . $orders["ID"] . " CustID: " .  $orders["CustID"] . " Time: " . $orders["Time"] . " Anzahl: " . $orders["Quantity"] . " ProductID: " . $orders["ProductID"] . " Titel: " . $orders["Title"];
    echo "<br>";
}*/

        return $Orders;
    }

    protected function generateView()
    {
        $Products = $this->getViewData();
        $this->generatePageHeader("Sushi - Lieferstatus");
        //echo $_SESSION["orderID"];

        echo <<<HTML
            <script type="text/javascript">
            setTimeout(function(){
                 window.location.reload(1);
            }, 5000);
        </script>
    <section class="container">
        <h2 id="header">Lieferstatus</h3>
            <div class="content">
                <div class="cart-row">
                    <span class="cart-item cart-header cart-column">Produkt</span>
                    <span class="cart-item-status cart-header cart-column">Status</span>
                </div>
HTML;

        $ck = 0;
        foreach ($Products as $products) {
            if (isset($_SESSION["orderID"])) {
                if ($_SESSION["orderID"] == $products["ID"]) {
                    $this->insert_deliveryStatus($products["Title"], $products["Picture"], $ck, $products["ProductID"], $products["Status"]);
                    //echo $products["Status"];
                    $ck += 4;
                }
            }
        }

        echo <<<HTML
        </div>
</section>       
HTML;


        $this->generatePageFooter();
    }

    protected function insert_deliveryStatus($title, $picture, $ck, $nr, $status)
    {
        $ck1 = $ck + 1;
        $ck2 = $ck + 2;
        $ck3 = $ck + 3;
        $ck4 = $ck + 4;

        $tmp0 = "";
        $tmp1 = "";
        $tmp2 = "";
        $tmp3 = "";

        switch ($status) {
            case 0:
                $tmp0 = "checked";
                break;
            case 1:
                $tmp1 = "checked";
                break;
            case 2:
                $tmp1 = "checked";
                break;
            case 3:
                $tmp1 = "checked";
                break;
            case 4:
                $tmp2 = "checked";
                break;
            case 5:
                $tmp3 = "checked";
                break;
        }

        echo <<<HTML
            <div class="cart-items">
                <div class="cart-row">
                    <div class="cart-item cart-column"><img src="$picture" width="100"
                            height="100" alt="Bild nicht verfügbar">
                        <span class="product-title">$nr.$title</span>
                    </div>
                    <div class="cart-item-status">
                            <ul class="checkbox-items">
                                <li>
                                    <input type="radio" id="checkbox$ck1" name ="order-id$nr" value="0" disabled $tmp0>
                                    <label class="status" for="checkbox$ck1">
                                        Bestellt
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="checkbox$ck2" name="order-id$nr" value="1" disabled $tmp1>
                                    <label class="status" for="checkbox$ck2">Wird zubereitet</label>
                                </li>
                                <li>
                                    <input type="radio" id="checkbox$ck3" name="order-id$nr" value="4" disabled $tmp2> 
                                    <label class="status" for="checkbox$ck3">
                                        Ist unterwegs
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="checkbox$ck4" name="order-id$nr" value="5" disabled $tmp3>
                                    <label class="status" for="checkbox$ck4">
                                        Wurde zugestellt
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
HTML;
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
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
