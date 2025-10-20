<?php
namespace Modules\Order\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class OrderModel extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $guarded = [];
}
