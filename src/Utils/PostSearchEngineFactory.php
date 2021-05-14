<?php


namespace App\Utils;

use App\Repository\PostRepository;

class PostSearchEngineFactory
{
    private PostRepository $postRepository;
    private AdapterTransformedFinder $postFinder;

    public function __construct(PostRepository $postRepository, AdapterTransformedFinder $adapterTransformedFinder)
    {
        $this->postRepository = $postRepository;
        $this->postFinder = $adapterTransformedFinder;
    }

    public function create(string $type)
    {
        switch ($type) {
            case 'elasticsearch':
                return $this->postFinder;
            case 'database':
                return $this->postRepository;
            default:
                throw new \Exception("Type: '" . $type . '" is not valid.');
        }
    }
}
