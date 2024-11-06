<?php

namespace RecitModel;

class RecitModel {
    private $id;
    private $title;
    private $description;

    private $creation_date;
    private $slug;

    private $last_update_date;

    private $author_id;

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getCreationDate() {
        return $this->creation_date;
    }

    public function setCreationDate($creation_date) {
        $this->creation_date = $creation_date;
    }

    public function getLastUpdateDate() {
        return $this->last_update_date;
    }

    public function setLastUpdateDate($last_update_date) {
        $this->last_update_date = $last_update_date;
    }

    public function getAuthorId() {
        return $this->author_id;
    }

    public function setAuthorId($author_id) {
        $this->author_id = $author_id;
    }
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

}