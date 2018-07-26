<?php
require_once __DIR__.'/vendor/autoload.php';
use \inkpro\trustpilot\TrustpilotData;
use \Dotenv\Dotenv;
use \Gettext\GettextTranslator;

$locale = "sv_SE";
$t = new GettextTranslator();
$t->setLanguage($locale);
$t->loadDomain("messages", "Locale");
echo $t->gettext("%s anmeldelser");
