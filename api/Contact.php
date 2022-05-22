<?php
namespace API;
require_once ROOT_DIR . '/API.php';

class Contact extends API
{

  private string $name;
  private string $mail;
  private string $msg;

  public function __construct()
  {
    parent::__construct();
    if($this->getAction() === 'add') {
      $json = file_get_contents('php://input');
      $post = json_decode($json, true);
      $this->name = trim($post['name']);
      $this->mail = trim($post['mail']);
      $this->msg = trim($post['msg']);
      if($this->isValidForm() && !$this->isSpam()) {
        $this->addContact();
      }
    }
  }

  /**
   * Insert contact to database and send response to client.
   *
   * @return void
   */
  private function addContact()
  {
    $stmt = $this->getDB()->prepare("
        INSERT INTO ticket (timestamp, name, mail, message)
        VALUES (:timestamp, :name, :mail, :message)"
    );
    $stmt->bindParam(':timestamp', $this->time);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':mail', $this->mail);
    $stmt->bindParam(':message', $this->msg);

    if(!$stmt->execute()) {
      $this->sendErrorResponse('There was an error saving your request. Please try again.');
      return;
    }

    $result['id'] = $this->getDB()->lastInsertId();
    $result['success'] = true;
    $result['msg'] = 'Thank you for your message.';
    $this->logAction(get_class($this));
    $this->sendResponse($result);
  }

  /**
   * Check if form is valid.
   *
   * @return bool
   */
  private function isValidForm(): bool
  {
    if(!$this->isValidMail()) {
      $this->sendErrorResponse('Invalid Mail.');
      return false;
    }

    if(!$this->isValidName()) {
      $this->sendErrorResponse('Invalid Name.');
      return false;
    }

    if(!$this->isValidMessage()) {
      $this->sendErrorResponse('Invalid Message.');
      return false;
    }

    return true;
  }

  /**
   * Check if we have a valid mail.
   *
   * @return bool
   */
  private function isValidMail(): bool
  {
    if(filter_var($this->mail, FILTER_VALIDATE_EMAIL)) {
      return true;
    }
    return false;
  }

  /**
   * Check if we have a valid name.
   *
   * @return bool
   */
  private function isValidName(): bool
  {
    if($this->name != '' && strlen($this->name) > 3 && strlen($this->name) <= 30) {
      return true;
    }
    return false;
  }

  /**
   * Check if we have a valid message.
   *
   * @return bool
   */
  private function isValidMessage(): bool
  {
    if($this->msg != '' && strlen($this->msg) > 10 && strlen($this->name) <= 1000) {
      return true;
    }
    return false;
  }

  /**
   * Check if user is flooding our form.
   *
   * @return bool
   */
  private function isSpam(): bool
  {
    $ip = inet_pton($_SERVER['REMOTE_ADDR']);
    $stmt = $this->getDB()->prepare("
        SELECT *
        FROM action_log
        WHERE ip = ?
        AND action = ?
        AND api = ?"
    );
    $stmt->execute(array($ip, $this->getAction(), get_class($this)));
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    foreach ($result as &$entry) {
      if(($entry['timestamp'] + 60) > $this->time) {
        $this->sendErrorResponse('Flood control: Please don\'t spam and flood the form.');
        return true;
      }
    }
    return false;
  }
}
