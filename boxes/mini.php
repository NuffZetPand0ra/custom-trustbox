<?php
require_once __DIR__.'/../vendor/autoload.php';
use \inkpro\trustpilot\TrustpilotData;
use \Dotenv\Dotenv;
$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();
$data = TrustpilotData::getData();
$theme = isset($_GET['theme']) ? $_GET['theme'] : "default";
$background_color = isset($_GET['bg_hex']) ? $_GET['bg_hex'] : "fff";
$strings = [
    "dk"=>[
        "review_count"=>"%s anmeldelser"
    ],
    "se"=>[
        "review_count"=>"%s recensioner"
    ]
];
$lang = (isset($_GET['lang']) && isset($strings[$_GET['lang']])) ? $_GET['lang'] : "dk";
$font_size = isset($_GET['fontsize']) ? $_GET['fontsize'] : "14";
?>
<!doctype html>
<html>
    <head>
        <title><?= sprintf("Trustbox for %s",$data->display_name); ?></title>
        <link href="../static/themes/<?= $theme; ?>.css" rel="stylesheet">
        <meta charset="utf-8">
    </head>
    <body style="background-color:#<?= $background_color; ?>;font-size:<?= $font_size; ?>px">
    <a target="_blank" href="<?= $data->profile_url; ?>">
        <div id="content-wrapper">
            <h3><img src="../static/img/trustpilot_logo_10x10.gif"><?= sprintf("Trustscore %s",$data->trust_score); ?></h3>
            <div id="trust-stars">
                <?php for($i=1; $i<=$data->stars; $i++): ?>
                <span class="trust-star star-<?= $i; ?>">&#9733;</span>
                <?php endfor; ?>
            </div>
            <p id="review-count"><?= sprintf($strings[$lang]["review_count"],number_format($data->total_number_of_reviews,0,",",".")); ?></p>
        </div>
    </a>
    </body>
</html>