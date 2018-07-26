<?php
require_once __DIR__.'/vendor/autoload.php';
use \inkpro\trustpilot\TrustpilotData;
use \Dotenv\Dotenv;
$box = $_GET['box'];

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$smarty = new Smarty;
$data = TrustpilotData::getData();

$base_path = $_ENV['BASE_PATH'];
$smarty->assign("base_path", $base_path);
$smarty->assign("data",$data);
$smarty->assign("theme", isset($_GET['theme']) ? $_GET['theme'] : "default");
$smarty->assign("background_color", isset($_GET['bg_hex']) ? "#".$_GET['bg_hex'] : "transparent");

$strings = [
    "dk"=>[
        "review_count"=>"%s anmeldelser"
    ],
    "se"=>[
        "review_count"=>"%s recensioner"
    ]
];
$smarty->assign("review_count_str", _("%s anmeldelser"));
$smarty->assign("trustscore_str", _("Trustscore %s"));
$smarty->assign("formated_review_count", number_format($data->total_number_of_reviews,0,",","."));
$lang = (isset($_GET['lang']) && isset($strings[$_GET['lang']])) ? $_GET['lang'] : "dk";
$smarty->assign("lang", $lang);
$smarty->assign("strings",$strings);
$smarty->assign("review_count_string", sprintf(
    $strings[$lang]["review_count"],
    number_format($data->total_number_of_reviews,0,",",".")
));
$smarty->assign("trustscore_string", sprintf("Trustscore %s", $data->trust_score));
$smarty->assign("font_size", isset($_GET['fontsize']) ? $_GET['fontsize'] : "14");


$smarty->display("boxes/$box.tpl");