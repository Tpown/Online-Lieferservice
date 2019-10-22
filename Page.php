<?php    
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
    protected function processReceivedData()
    {
        if (get_magic_quotes_gpc()) {
            throw new Exception("Bitte schalten Sie magic_quotes_gpc in php.ini aus!");
        }
    }
} 