<?php    // UTF-8 marker äöüÄÖÜß€

require_once './Page.php';


class PageTemplate extends Page
{


    protected function __construct()
    {
        parent::__construct();
    }


    protected function __destruct()
    {
        parent::__destruct();
    }

    protected function getViewData()
    {
        $sql = "SELECT b.ID, b.CustID, b.Time, o.quantity, o.fProductID, o.Status, p.title FROM Bestellung b
        INNER JOIN ordered_products o ON (o.fOrderID = b.ID)
        INNER JOIN product p ON (p.ID = o.fProductID)";

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
                $title = htmlspecialchars($Record["title"], ENT_QUOTES);
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

        $json_data = json_encode($Orders);
        echo $json_data;

        /*   foreach ($Orders as $orders) {
    echo  "ID " . $orders["ID"] . " CustID: " .  $orders["CustID"] . " Time: " . $orders["Time"] . " Anzahl: " . $orders["Quantity"] . " ProductID: " . $orders["ProductID"] . " Titel: " . $orders["Title"];
    echo "<br>";
}*/
        return $Orders;
    }


    protected function generateView()
    {
        $this->getViewData();
        // $this->generatePageHeader('to do: change headline');

        //  $this->generatePageFooter();
    }


    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }


    public static function main()
    {
        try {
            $page = new PageTemplate();
            header("Content-Type: application/json; charset=UTF-8");
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

// This call is starting the creation of the page. 
// That is input is processed and output is created.
PageTemplate::main();

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >
