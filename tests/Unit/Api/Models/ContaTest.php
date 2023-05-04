<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Conta;
use PHPUnit\Framework\TestCase;

class ContaTest extends TestCase
{
    protected function model(): Model
    {
        return new Conta();
    }

    public function testTraisModelconta()
    {
        $traits = array_keys(class_uses($this->model()));
        $expectedTraits = [
            HasApiTokens::class, 
            HasFactory::class, 
            Notifiable::class
        ];
        $model = class_uses($this->model());
        $this->assertEquals($expectedTraits, $traits);
    }

    public function testFillablesModelConta()
    {
        $fillable = $this->model()->getFillable();
        $expectedFillable = [
            'usuario_id',
            'numero',
            'nome',
            'saldo',
        ];
        $this->assertEquals($expectedFillable, $fillable);
    }

}
