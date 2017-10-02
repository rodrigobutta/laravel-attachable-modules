<?php
namespace App\Modules\Mailist;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

use App\Modules\User\UserModel;
use App\Modules\Campaign\CampaignModel;


class MailistModel extends \App\Models\Profiled
{

    protected $table = 'mailist';


    public function campaigns()
    {
        return $this->belongsToMany(CampaignModel::class, 'campaign_mailist', 'mailist_id', 'campaign_id');
    }

    public function users()
    {
        return $this->belongsToMany(UserModel::class, 'mailist_user', 'mailist_id', 'user_id');
    }
}