<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = ["title", "company", "email", "location", "website", "description", "tags", "logo"];

    public function scopeFilter($query, array $filters) {
        if($filters["tag"] ?? false) {
            $query->where("tags", "like", "%" . request("tag") . "%");
        }

        if($filters["search"] ?? false) {
            $query->where("title", "like", "%" . request("search") . "%")->orWhere("description", "like", "%" . request("search") . "%");
        }
    }
}
