<?php

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, BelongsToCompany, SoftDeletes;

    protected $fillable = [
        'company_id',
        'parent_id',
        'name',
        'description',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Üst kategori ilişkisi
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Alt kategoriler ilişkisi
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    /**
     * Kategoriye ait ürünler
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Tüm alt kategorileri (recursive) getir
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Kategori yolunu getir (Ana > Alt > Alt Alt)
     */
    public function getPathAttribute()
    {
        $path = [$this->name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }
}
