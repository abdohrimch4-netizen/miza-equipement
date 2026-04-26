<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'nom_client', 'telephone', 'adresse', 'ville',
        'notes', 'total', 'statut', 'payment_status', 'payment_method',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->statut) {
            'pending'    => 'En attente',
            'processing' => 'En traitement',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
            default      => ucfirst($this->statut ?? 'Inconnu'),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->statut) {
            'pending'    => 'yellow',
            'processing' => 'blue',
            'shipped'    => 'purple',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            default      => 'gray',
        };
    }
}
