<?php

/* Foursquare Community Site
 * 
 * Copyright (C) 2011 Foursquare Church.
 * 
 * Developers: Jesse Morgan <jmorgan@foursquarestaff.com>
 *
 */

require_once "base.inc.php";

class Post {
    private $info;


    public function __construct($info=null) {
        $this->info = $info;
    }

    public static function getById($id) {
        $where = "id='$id'";

        return Post::getPost($where);
    }

    public static function getBySecretId($secretid) {
        $where = "secretid='$secretid'";

        return Post::getPost($where);
    }

    private static function getPost($where) {
        $query = "SELECT * FROM post WHERE $where";

        $db = getDatabase();
        
        $row = $db->fetchAssocRow($query);

        if ($row) {
            $user = new Post();
            $user->info = $row;

            return $user;

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
        return $this->info['name'];
    }

    public function getStage() {
        return $this->info['stage'];
    }

    public function approve() {
        $this->info['stage'] = 'approved';
    }

    public function verify() {
        $this->info['stage'] = 'verify';
    }

    public function getCreated() {
        return $this->info['created'];
    }
}

?>
