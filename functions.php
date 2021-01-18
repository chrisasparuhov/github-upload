<?php
// Path check
function check_login() {
    if (isset($_SESSION['user']) != "") {
        echo "<script>top.window.location = '" . $GLOBALS['url_path'] . "game.php'</script>";
        die;
    }
}

function check_logout() {
    if (isset($_SESSION['user']) == "") {
        session_destroy();
        unset($_SESSION['user']);
        echo "<script>top.window.location = '" . $GLOBALS['url_path'] . "'</script>";
        die;
    }
}

// EXP Calc
function experience($L) {
    $a = 0;
    for ($x = 0; $x < $L; $x++) {
        $a += floor($x + 300 * pow(2, ($x / 7)));
    }
    return floor($a / 4);
}

// Card Template
class Cards {

    public function showCard($id) {

        $con = New Connection();
        $sql = "SELECT * FROM cards WHERE card_id='" . $id . "'";
        $result = $con->selectQuery($sql);
        $row = $con->fetchArray($result);

        $template = new Template("tpl/card.tpl");
        $template->set("card_title", $row["card_title"]);
        $template->set("card_color", $row["card_color"]);
        $template->set("card_subtitle", $row["card_subtitle"]);
        $template->set("card_tier", $row["card_tier"]);
        $template->set("card_img", $row["card_img"]);
        $template->set("card_type", $row["card_type"]);
        $template->set("card_subtype", $row["card_subtype"]);
        $template->set("card_species", $row["card_species"]);
        $template->set("card_keyword", $row["card_keyword"]);
        $template->set("card_flavor", $row["card_flavor"]);
        $template->set("card_description", $row["card_description"]);



        return $template->output();
    }

}

// Item Template
class Items {

    public function showItem($id) {

        $con = New Connection();
        $sql = "SELECT item_id,item_type,item_img FROM items WHERE item_id='" . $id . "'";
        $result = $con->selectQuery($sql);
        $row = $con->fetchArray($result);

        $template = new Template("tpl/item.tpl");
        $template->set("item_type", $row["item_type"]);
        $template->set("item_id", $row["item_id"]);
        $template->set("item_img", $row["item_img"]);
        $template->set("item_img", $row["item_img"]);

        return $template->output();
    }

}

// Template
class Template {

    protected $file;
    protected $values = array();

    public function __construct($file) {
        $this->file = $file;
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    public function output() {

        if (!file_exists($this->file)) {
            return "Error loading template file ($this->file).<br />";
        }

        $output = file_get_contents($this->file);

        foreach ($this->values as $key => $value) {
            $tagToReplace = "[@$key]";
            $output = str_replace($tagToReplace, $value, $output);
        }

        return $output;
    }

    static public function merge($templates, $separator = "\n") {

        $output = "";

        foreach ($templates as $template) {
            $content = (get_class($template) !== "Template") ? "Error, incorrect type - expected Template." : $template->output();
            $output .= $content . $separator;
        }

        return $output;
    }

}
