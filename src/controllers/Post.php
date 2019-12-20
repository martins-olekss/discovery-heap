<?php

namespace App\Controllers;

use App\Models\Post as PostModel;

/**
 * @property  post
 */
class Post extends View
{

    public function index()
    {
        $template = $this->twig->load('post/index.twig');
        echo $template->render(['posts' => PostModel::all()]);
    }

    /**
     * @param $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function view($id)
    {
        $post = PostModel::find($id);
        $template = $this->twig->load('post/view.twig');
        echo $template->render(['post' => $post]);
    }

    /**
     * @param $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showUpdateForm($id)
    {
        $post = PostModel::find($id);
        $template = $this->twig->load('post/update.twig');
        echo $template->render(['post' => $post]);
    }

    /**
     * @param $id
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function showDeleteForm($id)
    {
        $post = PostModel::find($id);
        $template = $this->twig->load('post/delete.twig');
        echo $template->render(['post' => $post]);
    }

    public function showCreateForm()
    {
        $template = $this->twig->load('post/create.twig');
        echo $template->render();
    }

    public function create()
    {
        $post = new PostModel();
        // TODO: Add validation
        $post->title = $this->request->request->get('title');
        $post->author = $this->request->request->get('author');
        $post->content = $this->request->request->get('content');
        $post->save();

        header('location: /post');
        exit;
    }

    public function update() {}

    /**
     * @param $id
     */
    public function delete($id) {
        $post = PostModel::find($id);
        $post->delete();

        header('location: /post');
        exit;
    }
    public function validate() {}
}