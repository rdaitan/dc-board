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

        $search = new stdClass();
        switch($type) {
        case self::TYPE_THREAD:
            $search = Thread::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            break;
        case self::TYPE_COMMENT:
            $search = Comment::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            break;
        case self::TYPE_USER:
            $search = User::search(
                $query,
                $pagination->start_index - 1,
                $pagination->count + 1
            );
            break;
        default:
            throw new PageNotFoundException();
            break;
        }

        $pagination->checkLastPage($search->result);
        $pages = ceil($search->total_result / self::RESULTS_PERPAGE);

        $this->set(get_defined_vars());
    }
}
