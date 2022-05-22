<?php
namespace API;

class API
{

  private ?\PDO $db = null;

  private string $action = '';

  public $time;

  public function __construct()
  {
    $this->time = time();
    $this->db = new \PDO('mysql:host=INSERT_HOST;dbname=INSERT_YOUR_DB_NAME', 'INSERT_YOUR_USERNAME', 'INSERT_YOUR_PASSWORT');
    $this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
    if(!isset($_GET['action'])) {
      $this->sendErrorResponse('The action parameter is missing.');
      return;
    }
    $this->action = strtolower($_GET['action']);
  }

  /**
   * Get DB Object
   *
   * @return \PDO
   */
  protected function getDB(): \PDO
  {
    return $this->db;
  }

  /**
   * Get action parameter.
   *
   * @return string
   */
  protected function getAction(): string
  {
    return $this->action;
  }

  /**
   * Send error message as json response.
   *
   * @param $msg
   * @return void
   */
  protected function sendErrorResponse($msg)
  {
    $response = array(
      'success' => false,
      'msg' => $msg,
    );
    $this->sendResponse($response);
  }

  /**
   * Send array as json response.
   *
   * @param array $response
   * @return void
   */
  protected function sendResponse(array $response)
  {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($response);
  }

  /**
   * Add action log to database.
   *
   * @param string $api
   * @return void
   */
  protected function logAction(string $api)
  {
    $ip = inet_pton($_SERVER['REMOTE_ADDR']);
    $stmt = $this->getDB()->prepare("
        INSERT INTO action_log (ip, timestamp, action, api)
        VALUES (:ip, :timestamp, :action, :api)"
    );
    $stmt->bindParam(':ip', $ip);
    $stmt->bindParam(':timestamp', $this->time);
    $stmt->bindParam(':action', $this->action);
    $stmt->bindParam(':api', $api);
    $stmt->execute();
  }

}
