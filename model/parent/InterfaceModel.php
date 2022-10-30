<?php 

interface ModelInterface {
    public static function findAll();
    public static function find(int $id);
    public static function create($data): bool;
    public function update($data): bool;
    public function delete(): bool;
}