<?php
class CommentController extends AppController
{
    public function create()
    {
        redirect_guest_user(LOGIN_URL);

        $thread     = Thread::get(Param::get('thread_id'));
        $page       = Param::get('page_next');
        $auth_user  = User::getAuthenticated();

        if (!$auth_user) {
            return;
        }

        switch ($page) {
        case 'create_end':
            $comment            = new Comment();
            $comment->body      = Param::get('body');
            $comment->user_id   = $auth_user->id;

            try {
                $comment->create($thread);

                $follow = Follow::getByThreadAndUser($thread->id, $comment->user_id);

                if ($follow) {
                    $thread = Thread::get($thread->id);
                    $last_comment = Comment::getLastInThread($thread);
                    if ($last_comment) {
                        $follow->last_comment = $last_comment->id;
                        $follow->update();
                    }
                }

                redirect(VIEW_THREAD_URL, array('id' => $thread->id));
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

    public function edit()
    {
        redirect_guest_user(LOGIN_URL);

        $id         = Param::get('id');
        $page       = Param::get('page_next', 'edit');
        $auth_user  = User::getAuthenticated();
        $comment    = Comment::getOrFail($id);
        $thread     = Thread::get($comment->thread_id);

        if (!$comment->isOwnedBy($auth_user)) {
            throw new PermissionException();
        }

        switch($page) {
        case 'edit':
            break;
        case 'edit_end':
            try {
                $comment->body = Param::get('body');
                $comment->update();

                redirect(VIEW_THREAD_URL, array('id' => $thread->id));
            } catch (ValidationException $e) {
                $page = 'edit';
            }

            break;
        default:
            throw new PageNotFoundException("{$page} is not found");
            break;
        }

        $title = 'Edit comment';
        $this->set(get_defined_vars());
    }

    public function view()
    {
        $comment    = Comment::getOrFail(Param::get('id'));
        $auth_user  = User::getAuthenticated();

        $thread = Thread::get($comment->thread_id);

        $this->set(get_defined_vars());
    }

    public function delete()
    {
        redirect_guest_user(LOGIN_URL);

        $id         = Param::get('id');
        $comment    = Comment::getOrFail($id);
        $auth_user  = User::getAuthenticated();
        $page       = Param::get('page_next', 'delete');

        if (!$comment->isOwnedBy($auth_user)) {
            throw new PermissionException();
        }

        if ($comment->isThreadBody()) {
            redirect(DELETE_THREAD_URL, array('id' => $comment->thread_id));
        }

        switch($page) {
        case 'delete':
            break;
        case 'delete_end':
            $comment->delete();
            redirect(LIST_THREADS_URL);
            break;
        default:
            throw new PageNotFoundException();
            break;
        }

        $title = 'Delete comment';
        $this->set(get_defined_vars());
    }
}
