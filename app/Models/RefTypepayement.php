<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class RefTypeEmplacement
 *
 * @property int $id
 * @property string $libelle
 * @property string|null $libelle_ar
 * @property int $ordre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 *
 * @property Collection|Emplacement[] $emplacements
 *
 * @package App\Models
 */
class RefTypepayement extends Eloquent
{
    use SoftDeletes;
    protected $table = 'ref_type_payements';

    protected $casts = [
        'ordre' => 'int'
    ];

    protected $fillable = [
        'libelle',
        'libelle_ar',
        'ordre'
    ];

}
