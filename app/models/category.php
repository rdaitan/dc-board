<?php
class Category extends AppModel
{
    const TABLE_NAME = 'category';

    public static function getAll()
    {
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM category');

        $categories = array();

        foreach ($rows as $row) {
            $categories[] = new self($row);
        }

        return $categories;
    }

    public static function getName($id)
    {
        $db = DB::conn();
        return $db->value("SELECT name FROM category WHERE id=?", array($id));
    }
}
