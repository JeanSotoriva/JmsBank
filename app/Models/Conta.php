<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;



class Conta extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario_id',
        'numero',
        'nome',
        'saldo',
    ];

    //converte qlqr tipo para boolean direto no banco.
    protected $casts = [
        'numero' => 'integer'
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class);
    }

    public function conta()
    {
        return $this->hasMany(Lancamento::class);
    }
}
