<?php
require_once __DIR__ . '/config.php';


# initialisation sqlite 
Flight::register('db', 'PDO', array('sqlite:' . DB_FILE), function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$sqlFile = __DIR__ . '/../database/init.sql';

    // On vérifie d'abord si DB_FILE est absent ou vide
    $dbIsEmpty = !file_exists(DB_FILE) || filesize(DB_FILE) === 0;

    // On ne tente d'exécuter init.sql QUE s'il existe réellement
    if ($dbIsEmpty && file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        if (!empty(trim($sql))) {
            $db->exec($sql);
        }
    }
});
Flight::set('flight.views.path', __DIR__ . '/views');

require_once __DIR__ . '/routes.php';
