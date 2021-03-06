<?php
class Blog{
  
    // database connection and table name
    private $conn;
    private $table_name = "blogs";
  
    // object properties
    public $id;
    public $title;
    public $type;
    public $detail;
    public $view; 
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    // read blog
function read(){
  
    // select all query
    $query = "SELECT * FROM " . $this->table_name . "  ORDER BY id DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}

// create product
function create(){
  
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                type=:type, title=:title, detail=:detail, view=:view";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->type=htmlspecialchars(strip_tags($this->type));
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->detail=htmlspecialchars(strip_tags($this->detail));
    $this->view=htmlspecialchars(strip_tags($this->view)); 
  
    // bind values
    $stmt->bindParam(":type", $this->type);
    $stmt->bindParam(":title", $this->title);
    $stmt->bindParam(":detail", $this->detail);
    $stmt->bindParam(":view", $this->view); 
  
    // execute query
    if($stmt->execute()){
        return true;
    } 
    return false;  
}

// used when filling up the update product form
function readOne(){
  
    // query to read single record
    $query = "SELECT * FROM " . $this->table_name . "  
            WHERE
                id = ?
            LIMIT
                0,1";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind id of product to be updated
    $stmt->bindParam(1, $this->id);
  
    // execute query
    $stmt->execute();
  
    // get retrieved row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    // set values to object properties
    $this->title = $row['title'];
    $this->type = $row['type'];
    $this->detail = $row['detail'];
    $this->view = $row['view']; 
}

// update the product
function update(){
  
    // update query
    $query = "UPDATE
                " . $this->table_name . "
            SET
                type=:type, title=:title, detail=:detail, view=:view
            WHERE
                id = :id";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize    
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->type=htmlspecialchars(strip_tags($this->type));
    $this->detail=htmlspecialchars(strip_tags($this->detail));
    $this->view=htmlspecialchars(strip_tags($this->view)); 
  
    // bind new values
    $stmt->bindParam(':id', $this->id);
    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':detail', $this->detail);
    $stmt->bindParam(':view', $this->view); 
  
    // execute the query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}


// delete the product
function delete(){
  
    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;
}

// search blog
function search($keywords){
  
    // select all query
    $query = "SELECT * FROM
                " . $this->table_name . " b  WHERE
            b.title LIKE ? OR b.detail LIKE ? OR b.type LIKE ? ORDER BY b.id DESC";
  
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";
  
    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);
  
    // execute query
    $stmt->execute();
  
    return $stmt;
}

// read blog with pagination
public function readPaging($from_record_num, $records_per_page){
  
    // select query
    $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC LIMIT ?, ?";
  
    // prepare query statement
    $stmt = $this->conn->prepare( $query );
  
    // bind variable values
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
  
    // execute query
    $stmt->execute();
  
    // return values from database
    return $stmt;
}
    
    // used for paging products
public function count(){
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
  
    $stmt = $this->conn->prepare( $query );
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
  
    return $row['total_rows'];
}

}
?>