<?php
class ThreadController extends AppController {
    /*
     * Show all threads.
     */
    public function index() {
        $threads = Thread::getAll();
        $this->set(get_defined_vars());
    }

    /*
    * Show a specific thread
    */
   public function view() {
       $thread = Thread::get(Param::get('thread_id'));
       $this->set(get_defined_vars());
   }

}
