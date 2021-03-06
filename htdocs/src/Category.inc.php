<?php

/* Foursquare Community Site
 * 
 * Copyright (C) 2011 Foursquare Church.
 * 
 * Developers: Jesse Morgan <jmorgan@foursquarestaff.com>
 *
 */

require_once "base.inc.php";

class Category {
    private $info;
    private $options = array('price' => false);

    public function __construct($info=null) {
        $this->info = $info;

        if (isset($this->info['options'])) {
            foreach (split(',', $this->info['options']) as $op) {
                $this->options[$op] = true;
            }
        }
    }

    public static function getCategories() {
        $db = getDatabase();

        $query = "SELECT * FROM category ORDER BY name";

        $rows = $db->fetchAssocRows($query);
       
        $result = array();
        foreach ($rows as $row) {
            $cat = new Category($row);
            $result[$row['shortname']] = $cat;
        }

        return $result;
    }

    public static function getById($id) {
        $where = "id='$id'";

        return Category::getCategory($where);
    }

    public static function getByShortname($shortname) {
        $where = "shortname='$shortname'";

        return Category::getCategory($where);
    }

    private static function getCategory($where) {
        $query = "SELECT * FROM category WHERE $where";

        $db = getDatabase();
        
        $row = $db->fetchAssocRow($query);

        if ($row) {
            $category = new Category($row);

            return $category;

        } else {
            return false;
        }
    }

    public function save() {
        $db = getDatabase();

        // TODO: Implement Save
    }

    public function getId() {
        return $this->info['id'];
    }

    public function getName() {
        return htmlspecialchars($this->info['name']);
    }

    public function getShortname() {
        return htmlspecialchars($this->info['shortname']);
    }

    public function getDescription() {
        return htmlspecialchars($this->info['description']);
    }

    public function getOption($name) {
        return $this->options[$name]; 
    }

    public function setOption($value) {
        $this->options[$name] = $value;
    }
}

?>
