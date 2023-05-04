<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;



class Lancamento extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'numero_conta',
        'descricao',
        'tipo',
        'valor',
    ];

    //converte qlqr tipo para boolean direto no banco.
    protected $casts = [
        'descricao' => 'string',
        'tipo' => 'string',
    ];

    public function conta(){
        return $this->belongsTo(Conta::class);
    }
}
