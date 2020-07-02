<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model {
	
	protected $fillable = ['name', 'url'];
   
   	public function clicks(){
		
		return $this->hasMany('App\Models\Click');
   	
   	}

}
