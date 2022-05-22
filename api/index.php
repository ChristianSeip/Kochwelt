<?php
define('ROOT_DIR', __DIR__);

if(isset($_GET['ressource'])) {
  $ressource = strtolower($_GET['ressource']);

  switch ($ressource) {
    case 'recipe':
      require_once ROOT_DIR . '/Recipe.php';
      $response = new \API\Recipe();
      return;
    case 'contact':
      require_once ROOT_DIR . '/Contact.php';
      $response = new \API\Contact();
      return;
  }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(array(
  'success' => false,
  'msg' => 'We don\'t know how to process your request.',
));
