<?php

class User{

    private $conn;
    private $table_name = "users";

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_number;
    public $address;
    public $postal_code;
    public $password;
    public $access_level;
    public $access_code;
    public $status;
    public $created;
    public $modified;

// constructor
  public function __construct(PDO $db){
      $this->conn = $db;
  }

  //comprueba si el email existe
  public function emailExists(){

      $query = "SELECT id, firstname, lastname, access_level, password, status, created
          FROM " . $this->table_name . "
          WHERE email = ?
          LIMIT 0,1";

      $stmt = $this->conn->prepare( $query );

      $this->email=htmlspecialchars(strip_tags($this->email));

      $stmt->bindParam(1, $this->email);

      $stmt->execute();

      $num = $stmt->rowCount();

      if($num>0){

          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          $this->id = $row['id'];
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->access_level = $row['access_level'];
          $this->password = $row['password'];
          $this->status = $row['status'];
          $this->created = $row['created'];

          return true;
      }

      return false;
  }

  // comprueba si el email introducido ya está asignado a otro usuario (true) o está libre (false)
  public function repeatedEmail() {

      $query = "SELECT id
          FROM " . $this->table_name . "
          WHERE email LIKE ? AND id != ?";

      $stmt = $this->conn->prepare( $query );

      $this->email=htmlspecialchars(strip_tags($this->email));

      $stmt->bindParam(1, $this->email);
      $stmt->bindParam(2, $this->id);

      $stmt->execute();

      $num = $stmt->rowCount();

      if($num>0){

          return true;
      }

      return false;
  }

  // crea un usuario
  public function create(){

      $this->created=date('Y-m-d H:i:s');

      $query = "INSERT INTO
              " . $this->table_name . "
          SET
              firstname = :firstname,
              lastname = :lastname,
              email = :email,
              contact_number = :contact_number,
              address = :address,
              postal_code = :postal_code,
              password = :password,
              access_level = :access_level,
              access_code = :access_code,
              status = :status,
              created = :created";

      $stmt = $this->conn->prepare($query);

      $this->firstname=htmlspecialchars(strip_tags($this->firstname));
      $this->lastname=htmlspecialchars(strip_tags($this->lastname));
      $this->email=htmlspecialchars(strip_tags($this->email));
      $this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
      $this->address=htmlspecialchars(strip_tags($this->address));
      $this->postal_code=htmlspecialchars(strip_tags($this->postal_code));
      $this->password=htmlspecialchars(strip_tags($this->password));
      $this->access_level=htmlspecialchars(strip_tags($this->access_level));
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));
      $this->status=htmlspecialchars(strip_tags($this->status));

      $stmt->bindParam(':firstname', $this->firstname);
      $stmt->bindParam(':lastname', $this->lastname);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':contact_number', $this->contact_number);
      $stmt->bindParam(':address', $this->address);
      $stmt->bindParam(':postal_code', $this->postal_code);
      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);

      $stmt->bindParam(':access_level', $this->access_level);
      $stmt->bindParam(':access_code', $this->access_code);
      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':created', $this->created);

      if($stmt->execute()){
          return true;
      }else{
          return false;
      }

  }

  // consulta si existe el código de acceso proporcionado
  public function accessCodeExists(){

      $query = "SELECT id
          FROM " . $this->table_name . "
          WHERE access_code = ?
          LIMIT 0,1";

      $stmt = $this->conn->prepare( $query );

      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      $stmt->bindParam(1, $this->access_code);

      $stmt->execute();

      $num = $stmt->rowCount();

      if($num>0){

          return true;
      }

      return false;

  }

  // usado en la verificación de email
  public function updateStatusByAccessCode(){

      $query = "UPDATE " . $this->table_name . "
          SET status = :status
          WHERE access_code = :access_code";

      $stmt = $this->conn->prepare($query);

      $this->status=htmlspecialchars(strip_tags($this->status));
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':access_code', $this->access_code);

      if($stmt->execute()){
          return true;
      }

      return false;
  }

  // usado con la caract. de contraseña olvidad
  public function updateAccessCode(){

      $query = "UPDATE
              " . $this->table_name . "
          SET
              access_code = :access_code
          WHERE
              email = :email";

      $stmt = $this->conn->prepare($query);

      $this->access_code=htmlspecialchars(strip_tags($this->access_code));
      $this->email=htmlspecialchars(strip_tags($this->email));

      $stmt->bindParam(':access_code', $this->access_code);
      $stmt->bindParam(':email', $this->email);

      if($stmt->execute()){
          return true;
      }

      return false;
  }

// usado con la caract. de contraseña olvidad
  public function updatePassword(){

      $query = "UPDATE " . $this->table_name . "
          SET password = :password
          WHERE access_code = :access_code";

      $stmt = $this->conn->prepare($query);

      $this->password=htmlspecialchars(strip_tags($this->password));
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);
      $stmt->bindParam(':access_code', $this->access_code);

      if($stmt->execute()){
          return true;
      }

      return false;
  }

    public function showUserData() {
        $query = "SELECT firstname, lastname, contact_number, address, email, postal_code, created, modified FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;

    }

    public function editProfile()
    {
        $query = "UPDATE " . $this->table_name . "
            SET firstname = :firstname,
            lastname = :lastname,
            email = :email,
            contact_number = :contact_number,
            address = :address,
            postal_code = :postal_code
            WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->postal_code = htmlspecialchars(strip_tags($this->postal_code));
        $this->id=htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}