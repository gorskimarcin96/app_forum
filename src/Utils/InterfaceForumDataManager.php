<?php


namespace App\Utils;


use App\Document\Post;
use App\Document\PostComment;
use App\Document\User;

interface InterfaceForumDataManager
{
    public function postCreate(array $post, User $user, $tags = [], $files = []): Post;

    public function postCommentCreate(array $postComment, User $user, $files = []): PostComment;
}