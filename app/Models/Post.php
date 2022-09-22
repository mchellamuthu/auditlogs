<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Auditable;

    protected $guarded = ['id'];
    /**
    * Auditable attributes allow to the Audit.
    *
    * @var array
    */
    protected $allowedAudits = ['title','body'];

    /**
     * Auditable  relationships.
     *
     * @var array
     */
    protected $auditableRelationships = ['category'];
    /**
     * Get the category that owns the Post
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
