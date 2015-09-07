<?php
class CommentController extends AppController
{
    public function create()
    {
        redirect_guest_user('user/authenticate');

        $thread     = Thread::get(Param::get('thread_id'));
        $page       = Param::get('page_next');
        $auth_user  = User::getAuthenticated();

        if(!$auth_user) {
            return;
        }

        switch ($page) {
        case 'create_end':
            $comment            = new Comment();
            $comment->body      = Param::get('body');
            $comment->user_id   = $auth_user->id;

            try {
                $comment->create($thread);
            } catch (ValidationException $e) {
                $page = 'create';
            }
            break;
        default:
            throw new PageNotFoundException("{$page} is not found");
            break;
        }

        $title = 'Create Comment';
        $this->set(get_defined_vars());
        $this->render($page);
    }
}
