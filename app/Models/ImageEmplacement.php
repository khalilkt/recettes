<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImageEmplacement
 *
 * @property int $id
 * @property int $emplacement_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Emplacement $emplacement
 *
 * @package App\Models
 */
class ImageEmplacement extends Eloquent
{
	use SoftDeletes;
	protected $table = 'image_emplacements';

	protected $casts = [
		'emplacement_id' => 'int'
	];

	protected $fillable = [
		'emplacement_id'
	];

	public function emplacement()
	{
		return $this->belongsTo(Emplacement::class);
	}
}
