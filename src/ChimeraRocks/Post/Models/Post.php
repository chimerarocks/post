<?php

namespace ChimeraRocks\Post\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;
use ChimeraRocks\Category\Models\Category;

class Post extends Model implements SluggableInterface
{
	use SluggableTrait;

	private $validator;
	
	public $errors;

	protected $table = "chimerarocks_posts";

	protected $sluggable = [
		'build_from' => 'title',
		'save_to' => 'slug',
		'unique' => 'true'
	];

	protected $fillable = [
		'slug',
		'title',
		'content',
	];

	public function categories()
	{
		return $this->morphToMany(Category::class, 'categorizable', 'chimerarocks_categorizables');
	}

	public function setValidator(Validator $validator)
	{
		$this->validator = $validator;
	}

	public function getValidator()
	{
		return $this->validator;
	}

	public function isValid()
	{
		$validator = $this->validator;
		$validator->setRules([
			'title' => 'required|max:255',
			'content' => 'required'
		]);
		$validator->setData($this->attributes);

		if ($validator->fails()) {
			$this->errors = $validator->errors();
			return false;
		}
		return true;
	}
}