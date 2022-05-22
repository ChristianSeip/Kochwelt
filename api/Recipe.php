<?php
namespace API;
require_once ROOT_DIR . '/API.php';

class Recipe extends API
{

  public function __construct()
  {
    parent::__construct();
    if($this->getAction() === 'get') {
      $this->getRecipe();
    }
  }

  /**
   * Get Recipe from database.
   *
   * @return void
   */
  private function getRecipe(): void
  {
    if(!$this->isValidId()) {
      $this->sendErrorResponse('A valid ID parameter is missing.');
      return;
    }
    $id = intval($_GET['id']);

    $stmt = $this->getDB()->prepare("
        SELECT *
        FROM recipe
        WHERE id = ?
        LIMIT 1"
    );
    $stmt->execute(array($id));
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if(!$result) {
      $this->sendErrorResponse('No entry could be found.');
      return;
    }

    $result['success'] = true;
    $result['ingredients'] = json_decode($result['ingredients']);
    $this->sendResponse($result);
  }

  /**
   * Check if id parameter is a valid id.
   *
   * @return bool
   */
  private function isValidId(): bool
  {
    if(!isset($_GET['id'])) {
      return false;
    }

    if(!is_numeric($_GET['id'])) {
      return false;
    }

    return true;
  }
}
