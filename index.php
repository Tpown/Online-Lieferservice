<?php
require_once './Page.php';

class Orders extends Page
{

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
    }

    //Showing the HTML Format to the Viewport
    protected function generateView()
    {
        $this->generatePageHeader('Sushi - Übersicht');

        echo <<<HTML
        <section id="overview">
        <h1>Sushi Lieferservice</h1>
        <div class="card">
            <div class="card-header">
              Sushi - Meister
            </div>
            <div class="card-body">
              <h5 class="card-title">Bestellstatus ändern</h5>
              <p class="card-text">Halten Sie Ihre Kunden up to date, in dem Sie hier den Status der Bestellung ändern.</p>
              <a href="admin.php" class="btn btn-dark">Sushi-Meister</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              Lieferant
            </div>
            <div class="card-body">
              <h5 class="card-title">Lieferstatus ändern</h5>
              <p class="card-text">Informieren Sie Ihren Kunden, ob die Lieferung schon unterwegs ist.</p>
              <a href="Delivery.php" class="btn btn-dark">Lieferant</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              Lieferstatus
            </div>
            <div class="card-body">
              <h5 class="card-title">Status Ihrer Bestellung</h5>
              <p class="card-text">Sehen Sie hier Live wie in welchem Status ihre Bestellung sich gerade befindet!</p>
              <a href="DeliveryStatus.php" class="btn btn-dark">Lieferstatus</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              Warenkorb
            </div>
            <div class="card-body">
              <h5 class="card-title">Ihr Warenkorb</h5>
              <p class="card-text">Hier sehen Sie was momentan in Ihrem Warenkorb sich befindet.</p>
              <a href="shopping_cart.php" class="btn btn-dark">Warenkorb</a>
            </div>
          </div>

        </section>
HTML;


        $this->generatePageFooter();
    }

    protected function processReceivedData()
    {
        parent::processReceivedData();
        
    }

    public static function main()
    {
        session_start();
        try {
            $page = new Orders();
            $page->processReceivedData();
            $page->generateView();
        } catch (Exception $e) {
            header("Content-type: text/plain; charset=UTF-8");
            echo $e->getMessage();
        }
    }
}

Orders::main();
