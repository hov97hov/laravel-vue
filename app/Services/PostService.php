<?php

namespace App\Services;

use App\Interfaces\PostInterface;
use App\Models\Post;
use App\Repositories\PostRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService implements PostInterface
{
    /**
     * @var PostRepository
     */
    protected PostRepository $postRepository;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @param $data
     * @return Post
     */
    public function store($data): Post
    {
        $post = Post::create([
            'title' => $data->title,
            'content' => $data->content,
            'user_id' => auth()->id()
        ]);

        if ($data->hasFile('image')) {
            $imagePath = $data->file('image')->store('images', 'public');
            $post->update([
                'image' => $imagePath
            ]);
        }

        return $post;
    }

    /**
     * @param $data
     * @param Post $post
     * @return Post
     */
    public function update($data, Post $post): Post
    {
        $post->update($data->except('image'));

        if ($data->hasFile('image')) {
            $imagePath = $data->file('image')->store('images', 'public');
            $post->update([
                'image' => $imagePath
            ]);
        }

        return $post;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAllPosts(): LengthAwarePaginator
    {
        return $this->postRepository->getAllPosts();
    }
}
