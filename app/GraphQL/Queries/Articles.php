<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\Models\Article;

final class Articles
{
    /**
     * @param  null  $_
     * @param  array  $args
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $search = isset($args['search']) ? $args['search'] : null;
        $sourceId = isset($args['source_id']) ? $args['source_id'] : null;
        $categoryId = isset($args['category_id']) ? $args['category_id'] : null;

        $query = Article::query();

        // Apply search conditions
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%')
                    ->orWhere(function ($query) use ($search) {
                        $date = \DateTime::createFromFormat('d/m/Y', $search); // Assuming $search is in the format '31/05/2023'
                        if ($date) {
                            $dateString = $date->format('Y-m-d');
                            $query->whereDate('published_at', $dateString);
                        }
                    });
            });
        }

        // Apply additional filters
        if ($sourceId) {
            $query->where('source_id', $sourceId);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Pagination
        $page = isset($args['page']) ? $args['page'] : 1;
        $perPage = isset($args['first']) ? $args['first'] : 10;

        $total = $query->count();
        $lastPage = ceil($total / $perPage);

        $articles = $query->forPage($page, $perPage)->get();

        return [
            'data' => $articles,
            'paginatorInfo' => [
                'count' => $articles->count(),
                'currentPage' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'lastPage' => $lastPage,
            ],
        ];
    }
}
