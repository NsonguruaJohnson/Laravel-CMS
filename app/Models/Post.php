<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'content', 'image', 'published_at', 'category_id', 'user_id'
    ];

    /**
     * Delete post image from storage
     * @return void
    */

    public function deleteImage() {
        Storage::delete($this->image);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }


    public function tags() {
        return $this->belongsToMany(Tag::class);
        // return $this->belongsToMany(Tag::class, 'post_tag', 'tag_id', 'post_id');
    }

    /**
     *  Check if post has a tag
     * @return bool
     */
    public function hasTag($tagId) {
        return in_array($tagId, $this->tags->pluck('id')->toArray());
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

}
