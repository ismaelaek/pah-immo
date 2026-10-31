<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Livewire\Livewire;

beforeEach(function () {
    Storage::fake('public');
});

test('a blog post is shown in the visitor language and falls back to french', function () {
    $post = Post::factory()->create([
        'title' => ['fr' => 'Acheter à Nice', 'en' => 'Buying in Nice'],
        'description' => ['fr' => 'Nos conseils.', 'en' => 'Our advice.'],
        'body' => ['fr' => 'Le marché niçois.', 'en' => 'The Nice market.'],
    ]);

    $this->get(route('blog.show', ['locale' => 'en', 'slug' => $post->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('post.title', 'Buying in Nice')
            ->where('post.description', 'Our advice.')
            ->where('post.body', "<p>The Nice market.</p>\n"));

    $this->get(route('blog.show', ['locale' => 'ru', 'slug' => $post->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('post.title', 'Acheter à Nice')
            ->where('post.body', "<p>Le marché niçois.</p>\n"));
});

test('the slug comes from the french title and is the same in every language', function () {
    $post = Post::factory()->create(['title' => ['en' => 'Buying in Nice', 'fr' => 'Acheter à Nice']]);

    expect($post->slug)->toBe('acheter-a-nice');

    $post->update(['title' => ['fr' => 'Vendre à Nice', 'en' => 'Selling in Nice']]);

    expect($post->refresh()->slug)->toBe('acheter-a-nice');

    $this->get(route('blog', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('posts.0.slug', 'acheter-a-nice'));
});

test('a post written only in another language gets its slug from that title', function () {
    $post = Post::factory()->create(['title' => ['en' => 'Buying in Nice']]);

    expect($post->slug)->toBe('buying-in-nice');
});

test('a post can be written in several languages from the admin panel', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Acheter à Nice',
            'description' => 'Nos conseils.',
            'body' => 'Le marché niçois.',
        ])
        ->set('activeLocale', 'en')
        ->fillForm([
            'title' => 'Buying in Nice',
            'description' => 'Our advice.',
            'body' => 'The Nice market.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $post = Post::query()->sole();

    expect($post->getTranslations('title'))->toBe(['fr' => 'Acheter à Nice', 'en' => 'Buying in Nice'])
        ->and($post->getTranslation('body', 'en'))->toBe('The Nice market.')
        ->and($post->slug)->toBe('acheter-a-nice');
});
