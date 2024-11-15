<?php

namespace Database\Seeders;

use App\Jobs\PostsCacheJob;
use App\Models\Post;
use App\Models\User;
use App\Observers\PostObserver;
use App\Services\ElasticsearchService;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            DB::table('comments')->delete();
            DB::table('posts')->delete();
            DB::table('users')->delete();
            $user = User::factory(1)->create();
            $batchSize = 100;
            $totalPosts = 1000;
            $faker = Faker::create();
            $id = 0;
            for ($i = 0; $i < $totalPosts / $batchSize; $i++) {
                $posts = [];
                for ($j = 0; $j < $batchSize; $j++) {
                    $posts[] = [
                        'id' => ++$id,
                        'title' => $faker->sentence(),
                        'title_ar' => "هذا هو النص التجريبي لجملة.",
                        'excerpt' => $faker->text(10),
                        'description' => $faker->paragraph(3, true),
                        'image' => '',
                        'keywords' => implode(',', $faker->words(5)),
                        'meta_title' => $faker->sentence,
                        'meta_description' => $faker->text(10),
                        'published_at' => now(),
                        'author_id' => $user->first()->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                Post::insert($posts);
                app(ElasticsearchService::class)->bulkIndex('posts' , $posts);
            }
    
            (new PostObserver())->invalidateCacheAndRebuildIndividual();
            PostsCacheJob::dispatch();
    }
}
