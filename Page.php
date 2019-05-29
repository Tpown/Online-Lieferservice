<?php    // UTF-8 marker äöüÄÖÜß€
/**
 * Class Page for the exercises of the EWA lecture
 * Demonstrates use of PHP including class and OO.
 * Implements Zend coding standards.
 * Generate documentation with Doxygen or phpdoc
 * 
 * PHP Version 5
 *
 * @category File
 * @package  Pizzaservice
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 * @license  http://www.h-da.de  none 
 * @Release  1.2 
 * @link     http://www.fbi.h-da.de 
 */

/**
 * This abstract class is a common base class for all 
 * HTML-pages to be created. 
 * It manages access to the database and provides operations 
 * for outputting header and footer of a page.
 * Specific pages have to inherit from that class.
 * Each inherited class can use these operations for accessing the db
 * and for creating the generic parts of a HTML-page.
 *
 * @author   Bernhard Kreling, <b.kreling@fbi.h-da.de> 
 * @author   Ralf Hahn, <ralf.hahn@h-da.de> 
 */
abstract class Page
{
    // --- ATTRIBUTES ---

    /**
     * Reference to the MySQLi-Database that is
     * accessed by all operations of the class.
     */
    protected $_database = null;

    // --- OPERATIONS ---

    /**
     * Connects to DB and stores 
     * the connection in member $_database.  
     * Needs name of DB, user, password.
     *
     * @return none
     */
    protected function __construct()
    {
        try {
            error_reporting(E_ALL);
            require_once 'pwd.php';
            $this->_database = new MySQLi($host, $user, $pwd, "sushi_lieferservice");
            if (mysqli_connect_errno())
                throw new Exception("Connect failed: " . mysqli_connect_error());
            if (!$this->_database->set_charset("utf8"))
                throw new Exception("Charset failed: " .$this->_database->error);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Closes the DB connection and cleans up
     *
     * @return none
     */
    protected function __destruct()
    {
        $this->_database->close();
        // to do: close database
    }

    /**
     * Generates the header section of the page.
     * i.e. starting from the content type up to the body-tag.
     * Takes care that all strings passed from outside
     * are converted to safe HTML by htmlspecialchars.
     *
     * @param $headline $headline is the text to be used as title of the page
     *
     * @return none
     */
    protected function generatePageHeader($headline = "")
    {
        $headline = htmlspecialchars($headline);
        header("Content-type: text/html; charset=UTF-8");
        $tmp1 = "";
        $tmp2 = "";
        $tmp3 = "";
        $tmp4 = "";
        $css = "";
        $tmp5 = "";
        $js = "";

        switch($headline){
            case ("Sushi - Übersicht"):
            $tmp1 = "active-menu";
            $css = "index";
            break;
            case ("Sushi - Admin"):
            $tmp2 = "active-menu";
            $css = "admin";
            $js = "<script src=\"./js/admin.js\"></script>";
            break;
            case ("Sushi - Lieferant"):
            $tmp5 = "active-menu";
            $css = "delivery";
            $js = "<script src=\"./js/delivery.js\"></script>";
            break;
            case ("Sushi - Lieferstatus"):
            $tmp3 = "active-menu";
            $css = "admin";
            $js = "<script src=\"./js/admin.js\"></script>";
            break;
            case ("Sushi - Warenkorb"):
            $tmp4 = "active-menu";
            $css = "shopping-cart";
            $js = "<script src=\"./js/shopping-cart.js\" async></script>";
            break;
        };
        
        echo <<<HTML
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8" />
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <title>$headline</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style/$css.css">
        $js
    </head>

    <body>
    <div class="header">
    <img id="logo" src="./resources/img/logo2.jpg" alt="some text" width=60 height=40>
        <nav class="top-navbar"  id="myTopnav">
                <a href="index.php" class="$tmp1">Übersicht</a>
                <a href="admin.php" class="$tmp2">Sushi-Meister</a>
                <a href="Delivery.php" class="$tmp5">Lieferant</a>
                <a href="DeliveryStatus.php" class="$tmp3">Lieferstatus</a>
                <a href="shopping_cart.php" class="$tmp4"><i class="fas fa-shopping-cart"></i></a>
                <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
  </a> 
    </nav> 
    </div>
HTML;
    }

    /**
     * Outputs the end of the HTML-file i.e. /body etc.
     *
     * @return none
     */
    protected function generatePageFooter()
    {
        // to do: output common end of HTML code
        echo <<<HTML
        </body>
</html>
HTML;
    }

    /**
     * Processes the data that comes via GET or POST i.e. CGI.
     * If every page is supposed to do something with submitted
     * data do it here. E.g. checking the settings of PHP that
     * influence passing the parameters (e.g. magic_quotes).
     *
     * @return none
     */
    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} // end of class

// Zend standard does not like closing php-tag!
// PHP doesn't require the closing tag (it is assumed when the file ends). 
// Not specifying the closing ? >  helps to prevent accidents 
// like additional whitespace which will cause session 
// initialization to fail ("headers already sent"). 
//? >
