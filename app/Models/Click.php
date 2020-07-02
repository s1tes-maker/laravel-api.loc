<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Click extends Model {
	
	protected $fillable = ['x', 'y', 'site_id'];

}
