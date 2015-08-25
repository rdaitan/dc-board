<?php
class Comment extends AppModel {
    public $validation = array(
        'username'  => array('length' => 'validate_between', 1, 16),
        'body'      => array('length' => 'validate_between', 1, 200),
    );
}
