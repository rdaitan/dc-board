<?php
class SearchController extends AppController
{
    const TYPE_THREAD       = 1;
    const TYPE_COMMENT      = 2;
    const TYPE_USER         = 3;
    const RESULTS_PERPAGE   = 15;

    public function index()
    {
        $type       = Param::get('type', self::TYPE_THREAD);
        $query      = trim_collapse(Param::get('query'));
        $page       = Param::get('page', 1);
        $pagination = new SimplePagination($page, self::RESULTS_PERPAGE);

        if (!$query) {
            redirect(APP_URL);
        }

        $results = new stdClass();
        switch($type) {
        case self::TYPE_THREAD:
            $results = Thread::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            // Get other info for each thread
            foreach ($results->result as $thread) {
                $thread->creator        = User::getByID($thread->user_id);
                $thread->category       = Category::getName($thread->category_id);
                $thread->replies_count  = Comment::countAll($thread->id);
            }
            break;
        case self::TYPE_COMMENT:
            $results = Comment::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            break;
        case self::TYPE_USER:
            $results = User::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            break;
        default:
            throw new PageNotFoundException();
            break;
        }

        $pagination->checkLastPage($results->result);
        $pages = ceil($results->total_result / self::RESULTS_PERPAGE);

        $title = "Search: '{$query}'";
        $this->set(get_defined_vars());
    }
}
