<?php

/**
 * Created by Illuminate Model.
 * Date: Sat, 11 Jul 2020 11:32:41 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class ArQualite extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	public function archives()
	{
		return $this->hasMany(\App\Models\Archive::class);
	}
}
