<?php
/**
 *
 * Autoload file, used to bootstrap tests and application
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

// to avoid problem with root files, chdir to root folder
chdir(dirname(__FILE__));

// normal autoload of composer dependency
require 'vendor/autoload.php';

// dotenv
$dotenv = Dotenv\Dotenv::create(dirname(__FILE__));
$dotenv->load();

// RedbeanPHP (ORM)
R::setup( 'sqlite:data/joker.db' );