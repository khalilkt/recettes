<?php

/**
 * Created by Illuminate Model.
 * Date: Sat, 11 Jul 2020 11:32:41 +0000.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Courrier extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;



	public function service()
	{
		return $this->belongsTo(\App\Models\Service::class, 'service_id');
	}

	public function niveau_importance()
	{
		return $this->belongsTo(\App\Models\RefNiveauImportance::class, 'ref_niveau_importances');
	}

	public function origine()
	{
		return $this->belongsTo(\App\Models\ArOrigine::class, 'ar_origine_id');
	}
}
