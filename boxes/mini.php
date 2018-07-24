<?php
require_once __DIR__.'/../vendor/autoload.php';
use \inkpro\trustpilot\TrustpilotData;
use \Dotenv\Dotenv;
$dotenv = new Dotenv(__DIR__.'/../');
$dotenv->load();
$data = TrustpilotData::getData();
$theme = isset($_GET['theme']) ? $_GET['theme'] : "default";
$background_color = isset($_GET['bg_hex']) ? $_GET['bg_hex'] : "fff";
?>
<!doctype html>
<html>
    <head>
        <title>Trustbox for <?= $data->display_name; ?></title>
        <link href="../static/themes/<?= $theme; ?>.css" rel="stylesheet">
        <meta charset="utf-8">
    </head>
    <body style="background-color:#<?= $background_color; ?>">
    <a target="_blank" href="<?= $data->profile_url; ?>">
        <div id="content-wrapper">
            <h3><img src="../static/img/trustpilot_logo_10x10.gif">Trustscore <?= $data->trust_score; ?></h3>
            <div id="trust-stars">
                <?php for($i=1; $i<=$data->stars; $i++): ?>
                <span class="trust-star star-<?= $i; ?>">&#9733;</span>
                <?php endfor; ?>
            </div>
            <p id="review-count"><?= sprintf("%s anmeldelser",number_format($data->total_number_of_reviews,0,",",".")); ?></p>
        </div>
    </a>
    </body>
</html>