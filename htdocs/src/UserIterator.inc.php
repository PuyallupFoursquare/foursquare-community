<?php

/* Foursquare Community Site
 * 
 * Copyright (C) 2011 Foursquare Church.
 * 
 * Developers: Jesse Morgan <jmorgan@foursquarestaff.com>
 *
 */

require_once "base.inc.php";

class UserIterator implements Iterator {
    private $where;
    private $rows;
    private $position;
    private $order;
    private $limit;

    public function __construct() {
        $this->where = array();
        $this->rows = array();
        $this->position = 0;
        $this->order = "name asc";
        $this->limit = 0;
    }

    public function filterNotify($notify) {
        $sqlnotify = $notify ? '1' : '0';
        $this->where[] = "notify='$sqlnotify'";
    }
    
    public function orderBy($order) {
        $this->order = $order;
    }

    public function limit($limit) {
        $this->limit = $limit;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return new User($this->rows[$this->position]);
    }

    public function key() {
        return $this->rows[$this->position]['id'];
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->rows[$this->position]);
    }

    public function query() {
        $query = "SELECT * FROM user"; 
        
        if (count($this->where) > 0) {
            $where = join(' AND ', $this->where);
            $query .= " WHERE $where";
        }

        $query .= " ORDER BY ". $this->order;

        if ($this->limit != 0) {
            $query .= " LIMIT ". $this->limit;
        }

        $db = getDatabase();

        $this->rows = $db->fetchAssocRows($query);
        $this->position = 0;
    }
}

?>

