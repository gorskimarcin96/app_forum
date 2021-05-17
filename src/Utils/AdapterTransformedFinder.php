<?php


namespace App\Utils;

use App\Repository\PostRepository;
use Elastica\Query;
use Elastica\Query\MatchQuery;
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
        $query = new Query();

        switch ($type) {
            case PostRepository::ORDER_BY_TYPES[1]:
                $query->addSort(['numberEntries' => ['order' => 'desc']]);
                break;
            case PostRepository::ORDER_BY_TYPES[0]:
            default:
                $query->addSort(['createdAt' => ['order' => 'desc']]);
                break;
        }

        if ($phrase) {
            foreach (['title', 'description'] as $column) {
                $fieldQuery = new MatchQuery();
                $fieldQuery->setFieldQuery($column, $phrase);
                $query->setQuery($fieldQuery);
            }
        }

        return $this->postFinder->find($query, $limit, ['from' => ($page - 1) * $limit]);
    }
}
