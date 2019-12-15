<?php

namespace App\Controllers;

use Elasticsearch\ClientBuilder;
use App\Models\Article;

class Elasticsearch extends View
{

    const INDEX_NAME = 'discovery-elastic-index';
    private $client;

    public function __construct()
    {
        parent::__construct();
        /*
         * If server is not started,
         * results in Elasticsearch\Common\Exceptions\NoNodesAvailableException
         */
        $this->client = ClientBuilder::create()->build();
    }

    public function indexArticles() {

        $responses = array();

        foreach(Article::all() as $article) {
            $responses[] = $this->client->index([
                'index' => self::INDEX_NAME,
                'id' => $article->id,
                'body' => [
                    'title' => $article->title,
                    'content' => $article->content,
                    'author' => $article->author
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
//        foreach(Article::all() as $article) {
//            echo $article->title . '<br>';
//        }
    }

    public function handleSearch() {
        $searchQuery = !empty($_POST['query']) ? $_POST['query'] : false;
        $response = '';
        if ($searchQuery !== false) {
            $response = $this->search($searchQuery);
        }
        //echo $this->twig->load('search.twig')->render();
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

        $responses = array();
        foreach ($documents as $document) {
            $responses[] = $this->client->index($document);
        }

        return $responses;

    }

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

    public function deleteDocument()
    {
        $params = [
            'index' => self::INDEX_NAME,
            'id' => 'my_id'
        ];

        return $this->client->delete($params);
    }

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