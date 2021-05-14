<?php


namespace App\Utils;


use FOS\ElasticaBundle\Finder\TransformedFinder;

class AdapterTransformedFinder
{
    private TransformedFinder $postFinder;

    public function __construct(TransformedFinder $postFinder)
    {
        $this->postFinder = $postFinder;
    }

    public function page(int $page = 1, int $limit = 10, string $type = 'latest', string $phrase = null): array
    {
        $data = $this->postFinder->findPaginated($phrase)->setMaxPerPage($limit)->setCurrentPage($page)->jsonSerialize();

        return $this->sort($type, $data);
    }

    private function sort(string $type, array $data): array
    {
        switch ($type) {
            case 'latest':
                $data = $this->sortByCreatedAt($data);
                break;
            case 'popular':
                $data = $this->sortByNumberEntries($data);
                break;
            default:
                throw new \Exception("Type: '" . $type . '" is not valid.');
        }

        return $data;
    }

    private function sortByCreatedAt($data): array
    {
        usort($data, static function ($a, $b) {
            return $a->getCreatedAt() > $b->getCreatedAt() ? -1 : 1;
        });

        return $data;
    }

    private function sortByNumberEntries($data): array
    {
        usort($data, static function ($a, $b) {
            return $a->getNumberEntries() > $b->getNumberEntries() ? -1 : 1;
        });

        return $data;
    }
}