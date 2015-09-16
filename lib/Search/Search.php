<?php
class Search
{
    public $result          = array();
    public $total_result    = 0;

    public function __construct($class_name, $results = array())
    {
        foreach ($results as $result) {
            $this->result[] = new $class_name($result);
        }
    }
}
