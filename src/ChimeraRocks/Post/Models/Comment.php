<?php

namespace ChimeraRocks\Post\Models;

use ChimeraRocks\Post\Models\Post;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	private $validator;
	
	public $errors;

	protected $table = "chimerarocks_comments";

	protected $fillable = [
		'content',
		'post_id'
	];

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
			'content' => 'required'
		]);
		$validator->setData($this->attributes);

		if ($validator->fails()) {
			$this->errors = $validator->errors();
			return false;
		}
		return true;
	}

	public function post()
	{
		return $this->belongsTo(Post::class);
	}
}