<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    use Auditable;

    protected $guarded = ['id'];
    /**
     * Auditable attributes allow to the Audit.
     *
     * @var array
     */
    protected $allowedAudits = ['name'];
    /**
     * Auditable  relationships.
     *
     * @var array
     */
    protected $auditableRelationships = [
        [
            'name' => 'posts',
            'fields' => ['title', "body"]
        ]
    ];
    /**
     * Get all of the posts for the Category
     *
     * @return HasMany
     */

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'category_id', 'id');
    }
}
