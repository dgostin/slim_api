<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\HttpBasicAuthentication;

class MainApp {

  public $app;

  public function __construct()
  {

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $this->app = AppFactory::create();
  
    $this->app->get('/users', function (Request $request, Response $response) {
      $sql = "select * from users";

      try {

          $db = new Db();
          $conn = $db->connect();
          $stmt = $conn->query($sql);
          $users = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;

          $response->getBody()->write(json_encode($users));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        } catch (PDOException $e) {
          $error = array("message" => $e->getMessage());
      
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }    
    });

    $this->app->post('/users', function (Request $request, Response $response, array $args) {

      $data = $request->getParsedBody();
      $name = $data['name'];
      $email = $data['email'];

      if (!$name || !$email || strlen($name)>255 || strlen($email)>255)
        return $response->withStatus(500);

      $sql = "insert into users (name, email) VALUES (:name, :email)";

      try {

          $db = new Db();
          $conn = $db->connect();
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':name', $name);
          $stmt->bindParam(':email', $email);

          $result = $stmt->execute();
          $db = null;

          $response->getBody()->write(json_encode($result));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        } catch (PDOException $e) {
          $error = array("message" => $e->getMessage());
      
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }    
    });

    $this->app->post('/users/{id}/{txn}', function (Request $request, Response $response, array $args) {

      $db = new Db();
      $conn = $db->connect();

      $user_id = $args['id'];
      $txn = $args['txn'];

      if (!in_array($txn, ['earn','redeem'])) {
        return $response->withStatus(500);
      }

      $data = $request->getParsedBody();
      $points = $data['points'];
      if ($txn=='redeem') { $points *= -1; }
      $description = $data['description'];
      
      $sql1 = "
        insert into transactions (user_id, points, description) 
        VALUES (:user_id, :points, :description)
        ";
      
      $sql2 = "
        update users set points_balance = points_balance + :points
        where id = :user_id
      ";
      
      try {

          $stmt = $conn->prepare($sql1);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':points', $points);
          $stmt->bindParam(':description', $description);
          $result = $stmt->execute();

          $stmt = $conn->prepare($sql2);
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(':points', $points);
          $result = $stmt->execute();

          $db = null;

          $response->getBody()->write(json_encode($result));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        } catch (PDOException $e) {
          $error = array("message" => $e->getMessage());
      
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }    
    });

    $this->app->delete('/users/{id}', function (Request $request, Response $response, array $args) {

      $db = new Db();
      $conn = $db->connect();

      $user_id = $args['id'];

      $sql = "
        delete from users where id = :user_id
        ";
      
      try {

          $db = new Db();
          $conn = $db->connect();
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':user_id', $user_id);
          $result = $stmt->execute();

          $db = null;

          $response->getBody()->write(json_encode($result));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
        } catch (PDOException $e) {
          $error = array("message" => $e->getMessage());
      
          $response->getBody()->write(json_encode($error));
          return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
        }    
    });

    $this->app->add(new HttpBasicAuthentication([
      "users" => [
        $_ENV['API_USER'] => $_ENV['API_PASS']
      ]
    ]));
  
  }

  public function run() {
    $this->app->run();
  }

}