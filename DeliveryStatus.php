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
    }

    protected function generateView()
    {
        $this->getViewData();
        $this->generatePageHeader("Sushi - Lieferstatus");
        echo <<<HTML
    <div id="logo"> Sushi Lieferservice</div>
    <section class="container">
        <h2 id="header">Lieferstatus</h3>
            <div class="content">
                <div class="cart-row">
                    <span class="cart-item cart-header cart-column">Produkt</span>
                    <span class="cart-item-status cart-header cart-column">Status</span>
                </div>
                <div class="cart-items">
                    <div class="cart-row">
                        <div class="cart-item cart-column"><img src="resources/img/salmon-nigir.jpg" width="100"
                                height="100" alt="Bild nicht verfügbar">
                            <span class="product-title">1. Nigiri</span>
                        </div>
                        <div class="cart-item-status">
                            <ul class="checkbox-items">
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
                                        Ist unterwegs
                                    </label>
                                </li>
                                <li>
                                    <input type="checkbox" id="checkbox4">
                                    <label class="status" for="checkbox4">
                                        Wurde zugestellt
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
    </section>       
HTML;

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
