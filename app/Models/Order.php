<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Morilog\Jalali\Jalalian;
use App\Events\OrderStatusChanged;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_name',
        'status'
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Jalalian::forge($value)->format('H:i Y/m/d') : null;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity')
            ->using(OrderProduct::class)
            ->withTimestamps();
    }


    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('customer_name', 'like', '%'.$search.'%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    protected static function booted()
    {
        static::updating(function ($order) {
            if ($order->isDirty('status')) {
                event(new OrderStatusChanged(
                    $order,
                    $order->getOriginal('status'),
                    $order->status
                ));
            }
        });
    }

}
