<?php
require_once dirname(__FILE__) . '/../config.php';
require_once _BASE_DATA_ACCESS_PATH;

class CongresoDataAccess extends BaseDataAccess {

    public function __construct() {
        parent::__construct();
    }

    protected function connect() {
        $link = mysqli_connect(_DB_HOST, _DB_USER, _DB_PASS, _DB_NAME);
        return $link;
    }
}
