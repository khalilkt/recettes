<?php

/**
 * Created by Illuminate Model.
 * Date: Sat, 11 Jul 2020 11:32:41 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class RefEtatBudget
 * 
 * @property int $id
 * @property string $libelle
 * @property string $libelle_ar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class RefEtatBudget extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $fillable = [
		'libelle',
		'libelle_ar'
	];
}
