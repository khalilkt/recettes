<?php

/**
 * Created by Illuminate Model.
 * Date: Sat, 11 Jul 2020 11:32:41 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class SysGroupesTraitement
 * 
 * @property int $id
 * @property string $libelle
 * @property int $ordre
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $supprimer
 * 
 * @property \Illuminate\Database\Eloquent\Collection $sys_droits
 *
 * @package App\Models
 */
class SysGroupesTraitement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'ordre' => 'int',
		'supprimer' => 'int'
	];

	protected $fillable = [
		'libelle',
		'ordre',
		'supprimer'
	];

	public function sys_droits()
	{
		return $this->hasMany(\App\Models\SysDroit::class);
	}
}
