<?php

namespace App\Models;

use App\Models\Concerns\Paginatable;
use App\Scopes\CampaignScope;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CampaignBoost
 * @package App\Models
 *
 * @property int $user_id
 * @property int $campaign_id
 * @property User $user
 * @property Campaign $campaign
 */
class CampaignBoost extends Model
{
    use Paginatable, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'campaign_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo('App\Models\Campaign', 'campaign_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}