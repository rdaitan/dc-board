<?php
class Thread extends AppModel
{
    const MIN_TITLE_LENGTH  = 1;
    const MAX_TITLE_LENGTH  = 30;
    const ERR_CATEGORY      = 1452; // actually a foreign key constraint failure.

    public $validation = array(
        'title' => array(
            'length' => array('validate_between', self::MIN_TITLE_LENGTH, self::MAX_TITLE_LENGTH)
        ),
    );

    public static function getAll($offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows(
            sprintf("SELECT * FROM thread ORDER BY id DESC LIMIT %d, %d", $offset, $limit)
        );

        $threads = array();

        foreach ($rows as $row) {
            $threads[] = new self($row);
        }

        return $threads;
    }

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM thread WHERE id=?', array($id));

        if (!$row) {
            throw new RecordNotFoundException('No record found');
        }

        return new self($row);
    }

    public static function countAll()
    {
        $db = DB::conn();
        return $db->value("SELECT COUNT(*) FROM thread");
    }

    public function create(Comment $comment)
    {
        // before calling $this->validate(), $this->title was set first
        if (!$this->validate() | !$comment->validate()) {
            throw new ValidationException('Invalid thread or comment.');
        }

        $db = DB::conn();

        try {
            $db->begin();
            $db->insert('thread', array('title' => $this->title, 'category_id' => $this->category));

            // write first comment
            $this->id = $db->lastInsertId();
            $comment->create($this);

            $db->commit();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == self::ERR_CATEGORY) {
                throw new CategoryException();
            }

            $db->rollback();
        }
    }
}
