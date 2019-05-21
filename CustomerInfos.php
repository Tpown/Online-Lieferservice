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
        $sql = "SELECT b.ID, b.CustID, b.Time, c.surname, c.lastname, c.address, c.zip, c.city, c.phone FROM Bestellung b
        INNER JOIN customer c ON (c.ID = b.CustID)";

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

        $json_data = json_encode($Orders);
        echo $json_data;

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
