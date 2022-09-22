<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Relations\BelongsTo;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'catergory_id', 'id');
    }
}
