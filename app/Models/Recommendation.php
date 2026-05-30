<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model {
    protected $fillable = ['title','description','contact_name','contact_type','contact_value'];
}
