<?php

namespace App\Controllers;

use Elasticsearch\ClientBuilder;
use App\Models\Article;

class Elasticsearch extends View
{
    private $client;
    private $indexName;

    public function __construct()
    {
        parent::__construct();
        $this->indexName = $_ENV['INDEX_NAME'];
        /*
         * If server is not started,
         * results in Elasticsearch\Common\Exceptions\NoNodesAvailableException
         */
        $this->client = ClientBuilder::create()->build();
    }

    public function indexPosts() {

        $responses = [];

        foreach(Post::all() as $post) {
            $responses[] = $this->client->index([
                'index' => 'elasticindex',
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

    public function showSearch() {
        // Reset indexes ( testing )
        $this->indexDelete();
        $this->indexArticles();

        $template = $this->twig->load('search.twig');
        echo $template->render();
    }

    public function handleSearch() {
        $searchQuery = !empty($_POST['query']) ? $_POST['query'] : false;
        $response = '';
        if ($searchQuery !== false) {
            $response = $this->search($searchQuery);
        }
        // Show hits
        echo 'Searching: ' . $searchQuery . '<br>';
        print_r($response['hits']['hits']);
    }

    public function testElastic()
    {
        // Note:
        // Accessing already deleted index triggers exception
        // Elasticsearch\Common\Exceptions\Missing404Exception
        $this->elasticFill();
        $searchResponse = $this->search('some');
        foreach($searchResponse['hits']['hits'] as $response) {
            echo print_r($response, true) . '<br>';
        }
    }

    /**
     * @return array
     */
    public function elasticFill()
    {
        // Fill elastic with test documents
        $documents = [
            [
                'index' => self::INDEX_NAME,
                'id' => '1',
                'body' => [
                    'title' => 'Title1',
                    'content' => 'This is some free text. Article 1',
                    'author' => 'Martins'
                ]
            ],
            [
                'index' => self::INDEX_NAME,
                'id' => '2',
                'body' => [
                    'title' => 'Title2',
                    'content' => 'Totally other article',
                    'author' => 'Martins'
                ]
            ]
        ];

        $responses = [];
        foreach ($documents as $document) {
            $responses[] = $this->client->index($document);
        }

        return $responses;

    }

    /**
     * @return array
     */
    public function indexDocument()
    {
        $params = [
            'index' => self::INDEX_NAME,
            'id' => 'my_id',
            'body' => [
                'testField' => 'abc'
            ]
        ];

        return $this->client->index($params);
    }

    /**
     * @return array
     */
    public function indexDelete()
    {
        $deleteParams = [
            'index' => self::INDEX_NAME
        ];

        return $this->client->indices()->delete($deleteParams);
    }

    public function getDocument()
    {
        $params = [
            'index' => self::INDEX_NAME,
            'id' => 'my_id'
        ];

        return $this->client->get($params);
    }

    /**
     * @return array
     */
    public function deleteDocument()
    {
        $params = [
            'index' => self::INDEX_NAME,
            'id' => 'my_id'
        ];

        return $this->client->delete($params);
    }

    /**
     * @param $queryString
     * @return array
     */
    public function search($queryString)
    {
        $params = [
            'body' => [
                'query' => [
                    'query_string' => [
                        'fields' => ['title', 'content', 'author'],
                        'query' => $queryString
                    ]
                ]
            ]
        ];

        return $this->client->search($params);
    }
}