<?php

class Invoice {
    private $conn;
    private $table_name = "invoices";

    public $invoice_id;
    public $gas_station_id;
    public $user_id;
    public $fuel_type;
    public $fuel_price;
    public $money_spent;
    public $refuel_date;
    public $created;

    /**
     * Constructor
     * @param PDO $db
     */
    public function __construct(PDO $db){
        $this->conn = $db;
    }

    /**
     * cuenta las facturas para paginarlas
     * @return mixed
     */
    public function countAllInvoices(){

    $query = "SELECT invoice_id FROM " . $this->table_name . " where user_id = :user_id";

    $stmt = $this->conn->prepare($query);

    $stmt->bindParam(':user_id', $this->user_id);

    $stmt->execute();

    $num = $stmt->rowCount();

     return $num;
    }

    /**
     * Inserta una nueva factura
     * @return bool
     */
    public function insertNewInvoice() {

        $this->created=date('Y-m-d H:i:s');

        $query = "INSERT INTO
                " . $this->table_name . "
            SET 
            gas_station_id = :gas_station_id,
            user_id = :user_id,
            fuel_type = :fuel_type,
            fuel_price = :fuel_price,
            money_spent = :money_spent,
            refuel_date = :refuel_date,
            created = :created";

        $stmt = $this->conn->prepare($query);

        $this->gas_station_id=htmlspecialchars(strip_tags($this->gas_station_id));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->fuel_type=htmlspecialchars(strip_tags($this->fuel_type));
        $this->fuel_price=htmlspecialchars(strip_tags($this->fuel_price));
        $this->money_spent=htmlspecialchars(strip_tags($this->money_spent));
        $this->refuel_date=htmlspecialchars(strip_tags($this->refuel_date));

        $stmt->bindParam('gas_station_id', $this->gas_station_id);
        $stmt->bindParam('user_id', $this->user_id);
        $stmt->bindParam('fuel_type', $this->fuel_type);
        $stmt->bindParam('fuel_price', $this->fuel_price);
        $stmt->bindParam('money_spent', $this->money_spent);
        $stmt->bindParam('refuel_date', $this->refuel_date);
        $stmt->bindParam('created', $this->created);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }

    }

    /**
     * Obtiene los datos de una factura para mostrar en la lista
     * @param $from_record_num
     * @param $records_per_page
     * @return mixed
     */
    public function getUserInvoices($from_record_num, $records_per_page){
        $query = "SELECT i.invoice_id, gs.brand, gs.address, gs.municipality, i.refuel_date  FROM gas_stations as gs inner join " . $this->table_name . " as i on i.gas_station_id = gs.gas_station_id WHERE user_id = ? ORDER BY i.refuel_date DESC LIMIT ?, ?;";

        $stmt = $this->conn->prepare($query);

        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(1, $this->user_id);

        $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    /**
     * Borra una factura
     */
    public function deleteInvoice() {
        $query = "DELETE FROM " . $this->table_name . " WHERE invoice_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->invoice_id);

        $stmt->execute();

    }

    /**
     * Busca facturas entre un rango de fechas (paginado)
     * @param $date1
     * @param $date2
     * @param $from_record_num
     * @param $records_per_page
     * @return mixed
     */
    public function searchInvoicesByDate($date1, $date2, $from_record_num, $records_per_page) {

            $query = "SELECT i.invoice_id, gs.brand, gs.address, gs.municipality, i.refuel_date  FROM gas_stations as gs inner join " . $this->table_name . " as i on i.gas_station_id = gs.gas_station_id WHERE user_id = ? AND i.refuel_date BETWEEN '".$date1."' AND '".$date2."' ORDER BY i.refuel_date DESC LIMIT ?, ?;";

            $stmt = $this->conn->prepare($query);

            $this->user_id=htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(1, $this->user_id);

            $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    /**
     * Busca facturas en un rango de fechas
     * @param $date1
     * @param $date2
     * @return mixed
     */
    public function countAllInvoicesByDate($date1, $date2) {

            $query = "SELECT i.invoice_id, gs.brand, gs.address, gs.municipality, i.refuel_date  FROM gas_stations as gs inner join " . $this->table_name . " as i on i.gas_station_id = gs.gas_station_id WHERE user_id = ? AND i.refuel_date BETWEEN '".$date1."' AND '".$date2."'";

        $stmt = $this->conn->prepare($query);

            $this->user_id=htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(1, $this->user_id);

        $stmt->execute();
        $num = $stmt->rowCount();
        return $num;
    }

    /**
     * Obtiene los datos necesarios para llenar todos los campos de una factura en el PDF
     * @return mixed
     */
    public function getPdfInvoiceData() {
        $query = "SELECT i.fuel_type, i.fuel_price, i.money_spent, i.refuel_date, i.created, gs.address as gas_address, gs.postal_code, gs.brand, gs.province, gs.municipality, r.region, gs.opening_hours, u.firstname, u.lastname, u.address, u.postal_code from " . $this->table_name . " as i inner join gas_stations as gs on i.gas_station_id = gs.gas_station_id inner join users as u on i.user_id = u.id inner join regions as r on r.region_id = gs.region_id where i.invoice_id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->invoice_id);

        $stmt->execute();
        return $stmt;

    }


}