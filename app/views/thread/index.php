<div class="row">
    <div class="nine columns u-pull-right"><span class="title">Newest threads</span></div>
</div>
<div class="row">
    <!--Filters-->
    <div class="three columns sidebar">
        <ul>
            <strong>Filter by:</strong>
            <li class="<?php eh(!$filter ? 'selected' : ''); ?>"><a href="<?php eh(url(LIST_THREADS_URL)); ?>">All</a></li>
            <?php foreach ($categories as $category): ?>
                <li class="<?php eh($filter == $category->id ? 'selected' : ''); ?>"><a href="<?php eh(url(LIST_THREADS_URL, array('filter' => $category->id))); ?>"><?php eh($category->name); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul>
            <strong>Trending:</strong>
            <?php foreach ($trending as $thread): ?>
                <li>
                    <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>"><?php eh($thread->title); ?></a>
                    <strong><?php eh($thread->count); ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <!--Threads-->
    <div class="nine columns" id="thread_list">
        <?php foreach ($threads as $thread): ?>
            <div class='thread'>
                <div>
                    <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))) ?>">
                        <?php eh($thread->title); ?>
                    </a>
                    <span class='category-pill'>
                        <?php eh($thread->category); ?>
                    </span>
                </div>
                <div>
                    <small>by
                        <a href="<?php eh(url(VIEW_USER_URL, array('id' => $thread->user_id))); ?>"><?php eh($thread->creator->username); ?></a>
                    </small>
                    <small class='u-pull-right'>
                        replies: <i><?php eh($thread->replies_count); ?></i>
                    </small>

                </div>
            </div>
        <?php endforeach; ?>
        <?php print_pagination($pagination, $pages); ?>
    </div>
</div>
