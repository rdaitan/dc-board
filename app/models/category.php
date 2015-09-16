<?php
class Category extends AppModel
{
    const TABLE_NAME = 'category';

    public static function getAll()
    {
        $db = DB::conn();
        $rows = $db->rows(sprintf('SELECT * FROM %s', self::TABLE_NAME));

        $categories = array();

        foreach ($rows as $row) {
            $categories[] = new self($row);
        }

        return $categories;
    }

    public static function getName($id)
    {
        $db = DB::conn();
        return $db->value(sprintf("SELECT name FROM %s WHERE id=?", self::TABLE_NAME), array($id));
    }
}
