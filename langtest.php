<?php
require_once __DIR__.'/vendor/autoload.php';
use \inkpro\trustpilot\TrustpilotData;
use \Dotenv\Dotenv;
use \Gettext\GettextTranslator;

$locale = isset($_GET['lang']) ? $_GET['lang'] : "sv_SE";
$t = new GettextTranslator();
$t->setLanguage($locale);
$t->loadDomain("messages", "Locale");

$t->register();

echo __("%s anmeldelser", 200);
