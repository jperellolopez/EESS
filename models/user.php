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

  //check if given email exist in the database
  public function emailExists(){

      // query to check if email exists
      $query = "SELECT id, firstname, lastname, access_level, password, status, created
          FROM " . $this->table_name . "
          WHERE email = ?
          LIMIT 0,1";

      // prepare the query
      $stmt = $this->conn->prepare( $query );

      // sanitize
      $this->email=htmlspecialchars(strip_tags($this->email));

      // bind given email value
      $stmt->bindParam(1, $this->email);

      // execute the query
      $stmt->execute();

      // get number of rows
      $num = $stmt->rowCount();

      // if email exists, assign values to object properties for easy access and use for php sessions
      if($num>0){

          // get record details / values
          $row = $stmt->fetch(PDO::FETCH_ASSOC);

          // assign values to object properties
          $this->id = $row['id'];
          $this->firstname = $row['firstname'];
          $this->lastname = $row['lastname'];
          $this->access_level = $row['access_level'];
          $this->password = $row['password'];
          $this->status = $row['status'];
          $this->created = $row['created'];

          // return true because email exists in the database
          return true;
      }

      // return false if email does not exist in the database
      return false;
  }

  // comprueba si el email introducido ya está asignado a otro usuario (true) o está libre (false)
  public function repeatedEmail() {

      $query = "SELECT id
          FROM " . $this->table_name . "
          WHERE email LIKE ? AND id != ?";

      // prepare the query
      $stmt = $this->conn->prepare( $query );

      // sanitize
      $this->email=htmlspecialchars(strip_tags($this->email));

      // bind given email value
      $stmt->bindParam(1, $this->email);
      $stmt->bindParam(2, $this->id);

      // execute the query
      $stmt->execute();

      // get number of rows
      $num = $stmt->rowCount();

      // if email exists, assign values to object properties for easy access and use for php sessions
      if($num>0){

          // return true because email exists in the database
          return true;
      }

      // return false if email do not exist in the database
      return false;
  }

  // create new user record
  public function create(){

      // to get time stamp for 'created' field
      $this->created=date('Y-m-d H:i:s');

      // insert query
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

      // prepare the query
      $stmt = $this->conn->prepare($query);

      // sanitize
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

      // bind the values
      $stmt->bindParam(':firstname', $this->firstname);
      $stmt->bindParam(':lastname', $this->lastname);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':contact_number', $this->contact_number);
      $stmt->bindParam(':address', $this->address);
      $stmt->bindParam(':postal_code', $this->postal_code);

      // hash the password before saving to database
      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);

      $stmt->bindParam(':access_level', $this->access_level);
      $stmt->bindParam(':access_code', $this->access_code);
      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':created', $this->created);

      // execute the query, also check if query was successful
      if($stmt->execute()){
          return true;
      }else{
          return false;
      }

  }

  // check if given access_code exist in the database
  public function accessCodeExists(){

      // query to check if access_code exists
      $query = "SELECT id
          FROM " . $this->table_name . "
          WHERE access_code = ?
          LIMIT 0,1";

      // prepare the query
      $stmt = $this->conn->prepare( $query );

      // sanitize
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      // bind given access_code value
      $stmt->bindParam(1, $this->access_code);

      // execute the query
      $stmt->execute();

      // get number of rows
      $num = $stmt->rowCount();

      // if access_code exists
      if($num>0){

          // return true because access_code exists in the database
          return true;
      }

      // return false if access_code does not exist in the database
      return false;

  }

  // used in email verification feature
  public function updateStatusByAccessCode(){

      // update query
      $query = "UPDATE " . $this->table_name . "
          SET status = :status
          WHERE access_code = :access_code";

      // prepare the query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->status=htmlspecialchars(strip_tags($this->status));
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      // bind the values from the form
      $stmt->bindParam(':status', $this->status);
      $stmt->bindParam(':access_code', $this->access_code);

      // execute the query
      if($stmt->execute()){
          return true;
      }

      return false;
  }

  // used in forgot password feature
  public function updateAccessCode(){

      // update query
      $query = "UPDATE
              " . $this->table_name . "
          SET
              access_code = :access_code
          WHERE
              email = :email";

      // prepare the query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));
      $this->email=htmlspecialchars(strip_tags($this->email));

      // bind the values from the form
      $stmt->bindParam(':access_code', $this->access_code);
      $stmt->bindParam(':email', $this->email);

      // execute the query
      if($stmt->execute()){
          return true;
      }

      return false;
  }

  // used in forgot password feature
  public function updatePassword(){

      // update query
      $query = "UPDATE " . $this->table_name . "
          SET password = :password
          WHERE access_code = :access_code";

      // prepare the query
      $stmt = $this->conn->prepare($query);

      // sanitize
      $this->password=htmlspecialchars(strip_tags($this->password));
      $this->access_code=htmlspecialchars(strip_tags($this->access_code));

      // bind the values from the form
      $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);
      $stmt->bindParam(':access_code', $this->access_code);

      // execute the query
      if($stmt->execute()){
          return true;
      }

      return false;
  }

    public function showUserData() {
        $query = "SELECT firstname, lastname, contact_number, address, email, postal_code, created, modified FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        // this is the first question mark
        $stmt->bindParam(1, $this->id);

        // execute our query
        $stmt->execute();

        // store retrieved row to a variable
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

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->postal_code = htmlspecialchars(strip_tags($this->postal_code));
        $this->id=htmlspecialchars(strip_tags($this->id));

        // bind the values
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':contact_number', $this->contact_number);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':postal_code', $this->postal_code);
        $stmt->bindParam(':id', $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

}