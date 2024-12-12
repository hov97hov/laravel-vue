<?php

namespace App\Interfaces;

use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

interface PostInterface
{
    /**
     * @param $data
     * @return Post
     */
    public function store($data): Post;

    /**
     * @param $data
     * @param Post $post
     * @return Post
     */
    public function update($data, Post $post): Post;

    /**
     * @return LengthAwarePaginator
     */
    public function getAllPosts(): LengthAwarePaginator;
}
