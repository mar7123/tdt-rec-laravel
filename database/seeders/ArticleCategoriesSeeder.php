<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ArticleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $name = [
            'Sport',
            'Health',
        ];
        $authors = User::take(2)->get();
        \App\Models\ArticleCategories::factory()
            ->count(count($name))
            ->sequence(fn ($sequence) => [
                'name' => $name[$sequence->index],
            ])
            ->has(
                \App\Models\Article::factory(count($authors))
                    ->state(new Sequence(
                        fn ($sequence) => [
                            'author_id' => function ($site) use ($sequence, $authors) {
                                $auth = $authors->get(($sequence->index % 2));
                                return $auth->user_id;
                            }
                        ]
                    ))->has(
                        \App\Models\ArticleComments::factory(count($authors))
                            ->state(new Sequence(
                                fn ($sequence) => [
                                    'author_id' => function ($site) use ($sequence, $authors) {
                                        $auth = $authors->get(($sequence->index % 2));
                                        return $auth->user_id;
                                    }
                                ]
                            )),
                        'comments'
                    ),
                'articles'
            )
            ->create();
    }
}
