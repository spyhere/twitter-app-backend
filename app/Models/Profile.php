<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'avatar'
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
     * Return relative url of the stored avatar
     * @return string
     */
    public function getRelativeAvatarUrlAttribute()
    {
        return $this->avatar ? Storage::url($this->avatar) : null;
    }

    /**
     * Set new avatar
     * @param $newAvatar
     * @return void
     */
    public function setNewAvatar($newAvatar)
    {
        if ($this->avatar) {
            Storage::delete("public/$this->avatar");
        }
        $path = $newAvatar->store('avatars', ['disk' => 'public']);
        $this->update(['avatar' => $path]);
    }

    /**
     * Delete avatar
     */
    public function destroyAvatar()
    {
        Storage::delete("public/$this->avatar");
        $this->update(['avatar' => null]);
    }
}
