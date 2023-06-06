<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Source;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FetchNewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:news {country?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from the NewsAPI.org API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get All the category
        $apikey = config('services.newsapi.key');
        $keyIndex = 0;
        $categories = Category::all();
        foreach ($categories as $category) {
            $totalResults = 0;
            $articles = [];
            $country = $this->argument('country');
            $params = [
                'apiKey' => config('services.newsapi.key')[$keyIndex],
                // last seven days
                'from' => now()->subDays(7)->format('Y-m-d'),
                'to' => now()->format('Y-m-d'),
                'language' => 'en',
                'sortBy' => 'publishedAt',
                'pageSize' => 100,
                'page' => 1,
                'category' => $category->name,
            ];

            if ($country) {
                $params['country'] = $country;
            }

            do {
                try {
                    $response = Http::get('https://newsapi.org/v2/top-headlines', $params);

                    if ($response->successful()) {
                        $totalResults = $response->json()['totalResults'];
                        $articles = $response->json()['articles'];

                        foreach ($articles as $article) {
                            $source = Source::firstOrCreate([
                                'source_id' => $article['source']['id'],
                            ], [
                                'name' => $article['source']['name'],
                            ]);
                            $publishedAt = Carbon::parse($article['publishedAt']);
                            Article::firstOrCreate([
                                'category_id' => $category->id,
                                'source_id' => $source->id,
                                'author' => $article['author'],
                                'title' => $article['title'],
                                'description' => $article['description'],
                                'url' => $article['url'],
                                'url_to_image' => $article['urlToImage'],
                                'published_at' => $publishedAt,
                                'content' => $article['content'],
                            ]);
                        }
                    } else {
                        Log::error('Failed to fetch articles from NewsAPI.org', [
                            'params' => $params,
                            'response' => $response->json(),
                        ]);
                        if ($keyIndex == count($apikey) - 1) {
                            break;
                        }
                        $keyIndex += 1;
                    }

                    $params['page'] = $params['page'] + 1;
                } catch (\Exception $e) {
                    Log::error('API Error: ' . $e->getMessage());
                    break;
                }
                if ($keyIndex == count($apikey) - 1) {
                    break;
                }
            } while ($totalResults > count($articles));
        }
    }
}
