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
        $sql = "SELECT ID, CustID, Time FROM Bestellung";
        $Recordset = $this->_database->query($sql);
        $Orders = array();

        if ($Recordset) {
            $Record = $Recordset->fetch_assoc();
            while ($Record) {
                $ID = htmlspecialchars($Record["ID"], ENT_QUOTES);
                $CustID = htmlspecialchars($Record["CustID"], ENT_QUOTES);
                $Time = htmlspecialchars($Record["Time"], ENT_QUOTES);
                $Orders[] = [
                    "ID" => $ID,
                    "CustID" => $CustID,
                    "Time" => $Time
                ];
                $Record = $Recordset->fetch_assoc();
            }
            $Recordset->free();
        }
        return $Orders;
    }

    protected function generateView()
    {
        $Orders = $this->getViewData();
        $this->generatePageHeader("Sushi - Admin");
        echo <<<HTML
    <div id="logo"> Sushi Lieferservice</div>
    <section class="container">
        <h2 id="header">privater Bereich</h2>
        <button class="accordion">Sushi-Meister</button>
        <div id="Meister" class="accordion-content">
            <section class="left-area">
                <h4>Bestellungen: </h4>
                <ul class="itemlist"> 
HTML;

        /*------------------------------INSERT Orders--------------------------------------------------------*/
        function insert_tablerow($entry = "")
        {
            echo <<<HTML
                <li class="items"> #$entry Bestellung </li>    
HTML;
        };

        $key = array();
        foreach ($Orders as $order) {
            insert_tablerow($order["ID"]);
            $key["ID"] = $order["ID"];
        };
       
        /*--------------------------------------------------------------------------------------------------*/
        echo <<<HTML
                </ul>
    </section> 
            <section class="right-area"> 
            <h4>Status: </h4>
            <ul class="checkbox-items"> 
                <li>2x Nigiri</li>
                <li>
                <input type="checkbox" id="checkbox1">
                    <label class="status" for="checkbox1">
                       Bestellt
                    </label>
                </li>
                <li>
                <input type="checkbox" id="checkbox2">
                    <label class="status" for="checkbox2">Wird zubereitet</label>
                </li>
                <li>
                <input type="checkbox" id="checkbox3">
                    <label class="status" for="checkbox3">
                       Verpackt
                    </label>
                </li>
                <li>
                <input type="checkbox" id="checkbox4">
                    <label class="status" for="checkbox4">
                       Bereit zur Lieferung
                    </label>
                </li>
            </ul>
    </section>           
        </div>
        <button class="accordion">Lieferant</button>
        <div class="accordion-content">
            <p>Test Test</p>
        </div>
    </section>
    </div>
HTML;

foreach($key as $id){
    echo $id
    ;
};


        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        // to do: call processReceivedData() for all members
    }
    public static function main()
    {

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
