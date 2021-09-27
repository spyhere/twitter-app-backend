<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'body'
    ];

    /**
     * Relationship with User model
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Posts to tags relations
     *
     * @return BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Parse hashtags from post
     *
     */
    public function parseTags()
    {
        preg_match_all('/#(\w+)/', $this->body, $tags);
        $this->saveTags($tags[1]);
        return $this;
    }

    /**
     * Save all given tags to DB
     *
     * @param array $tags
     */
    protected function saveTags($tags)
    {
        $tagsArray = collect([]);
        foreach ($tags as $tagName) {
            $tagsArray->push(Tag::firstOrCreate(['name' => strtolower($tagName)]));
        }
        $this->tags()->sync($tagsArray->pluck('id'));
    }
}
