<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = trim(fake()->realTextBetween(10, 60), '.');

        $publishedOptions = [true, false];
        $published = $publishedOptions[rand(0,1)];

        $dateRange = [Carbon::parse('2000-01-01'), Carbon::parse('2022-11-12')];
        $date = fake()->dateTimeBetween($dateRange[0], $dateRange[1]);

        $tagOptions = ['PHP', 'Laravel', 'Vite', 'InertiaJS', 'VueJS', 'Linux', 'Git', 'Docker'];
        // $count = rand(1, 4);
        // $tags = [];
        // for ($i=0; $i<$count; $i++) {
        //     do {
        //         $tag = $tagOptions[rand(0, 7)];
        //     } while(in_array($tag, $tags));
        //     array_push($tags, $tag);
        // }

        return [
            'title' => $title,
            'slug' => Str::slug($title, '-'),
            'content' => fake()->realTextBetween(300, 1000, 3),
            'published' => $published,
            'published_at' => $published ? $date : null,
            'created_at' => $date,
            'updated_at' => $date,
            'tags' => Arr::wrap($tagOptions[rand(0, 7)]),
        ];
    }
}
