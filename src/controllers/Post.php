<?php

namespace App\Controllers;

use Elasticsearch\ClientBuilder;
use App\Models\Post as PostModel;
use Dompdf\Dompdf;

/**
 * @property  post
 */
class Post extends View
{
    public $elasticClient;
    public $indexName;

    public function __construct()
    {
        parent::__construct();
        $this->indexName = $_ENV['INDEX_NAME'];
        /*
         * If server is not started,
         * results in Elasticsearch\Common\Exceptions\NoNodesAvailableException
         */
        try {
            $this->elasticClient = ClientBuilder::create()->build();
        } catch (Exception $e) {
            $this->elasticClient = false;
        }
    }

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

    public function update($id)
    {
        $post = PostModel::find($id);
        $post->title = $this->request->request->get('title');
        $post->author = $this->request->request->get('author');
        $post->content = $this->request->request->get('content');
        $post->save();

        header('location: /post');
        exit;
    }

    public function pdf($id) {
        $post = PostModel::find($id);
        $template = $this->twig->load('post/pdf/post.twig');
        $html = $template->render(['post' => $post]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream();
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

    /**
     * @param $id
     */
    public function delete($id)
    {
        $post = PostModel::find($id);
        $post->delete();

        header('location: /post');
        exit;
    }

    /**
     * @return array|bool
     */
    public function searchIndex()
    {
        if (!$this->elasticClient) {
            echo 'error';
            return false;
        }

        $responses = [];
        foreach (PostModel::all() as $post) {
            $responses[] = $this->elasticClient->index([
                'index' => 'ela',
                'id' => $post->id,
                'body' => [
                    'title' => $post->title,
                    'content' => $post->content,
                    'author' => $post->author
                ]
            ]);
        }

        return $responses;
    }
}