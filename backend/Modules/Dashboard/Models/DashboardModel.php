<?php
namespace Modules\Dashboard\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DashboardModel extends Model
{
    use HasFactory;
    protected $table = 'dashboards';
    protected $guarded = [];
}
