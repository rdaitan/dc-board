<?php
class ThreadController extends AppController {
    /*
     * Show all threads.
     */
    public function index() {
        $threads = Thread::getAll();
        $this->set(get_defined_vars());
    }
}
