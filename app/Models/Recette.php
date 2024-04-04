<?php

/**
 * Created by Illuminate Model.
 * Date: Sat, 11 Jul 2020 11:32:41 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Recette
 *
 * @property int $id
 * @property string $annee
 * @property int $ref_type_recette_id
 * @property int $nomenclature_element_id
 * @property \Carbon\Carbon $date
 * @property float $montant
 * @property int $user_id
 * @property string $ged
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\RefTypeRecette $ref_type_recette
 * @property \App\Models\NomenclatureElement $nomenclature_element
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Recette extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ref_type_recette_id' => 'int',
		'nomenclature_element_id' => 'int',
		'montant' => 'float',
		'user_id' => 'int'
	];

	protected $dates = [
		'date'=>'date::d-m-Y'
	];

	protected $fillable = [
		'annee',
		'ref_type_recette_id',
		'nomenclature_element_id',
		'date',
		'montant',
		'user_id',
		'ged'
	];

	public function ref_type_recette()
	{
		return $this->belongsTo(\App\Models\RefTypeRecette::class);
	}

	public function nomenclature_element()
	{
		return $this->belongsTo(\App\Models\NomenclatureElement::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
