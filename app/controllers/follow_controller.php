<?php
class FollowController extends AppController
{
    public function add()
    {
        redirect_guest_user(LOGIN_URL);

        $thread     = Thread::get(Param::get('id'));
        $auth_user  = User::getAuthenticated();

        if (!$auth_user) {
            die();
        }

        $follow             = new Follow();
        $follow->thread_id  = $thread->id;
        $follow->user_id    = $auth_user->id;

        $follow->create();

        redirect(VIEW_THREAD_URL, array('id' => $follow->thread_id));
    }

    public function remove()
    {
        redirect_guest_user(LOGIN_URL);

        $thread = Thread::get(Param::get('id'));
        $auth_user = User::getAuthenticated();

        if (!$auth_user) {
            die();
        }

        $follow = Follow::getByThreadAndUser($thread->id, $auth_user->id);

        if ($follow) {
            $follow->remove();
        }

        redirect(VIEW_THREAD_URL, array('id' => $follow->thread_id));
    }

    public function listAll()
    {
        redirect_guest_user(LOGIN_URL);

        $auth_user  = User::getAuthenticated();
        $follows    = Follow::getAll($auth_user);
        $updates    = Follow::getUpdates($auth_user);

        $threads            = array();
        $updated_threads    = array();

        foreach ($follows as $follow) {
            $threads[] = Thread::get($follow->thread_id);
        }

        foreach ($updates as $update) {
            $thread                 = Thread::get($update->thread_id);
            $thread->update_count   = $update->count;
            $thread->follow_id      = $update->id;
            $updated_threads[]      = $thread;
        }

        $title = 'Follows';
        $this->set(get_defined_vars());
    }

    public function redirect()
    {
        $follow = Follow::getOrFail(Param::get('id'));
        $thread = Thread::get($follow->thread_id);
        $last_comment = Comment::getLastInThread($thread);

        $follow->last_comment = $last_comment->id;
        $follow->update();

        redirect(VIEW_THREAD_URL, array('id' => $thread->id, 'page' => ThreadController::LAST_PAGE));
    }

    public function update()
    {
        $auth_user = User::getAuthenticated();

        if (!$auth_user) {
            throw new PermissionException();
        }

        send_json(array('hasUpdates' => Follow::getUpdates($auth_user) ? true : false));
    }
}
