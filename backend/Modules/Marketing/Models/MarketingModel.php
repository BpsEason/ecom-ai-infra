<?php
namespace Modules\Marketing\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MarketingModel extends Model
{
    use HasFactory;
    protected $table = 'marketings';
    protected $guarded = [];
}
