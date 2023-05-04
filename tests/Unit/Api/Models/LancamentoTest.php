<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Lancamento;
use PHPUnit\Framework\TestCase;

class LancamentoTest extends TestCase
{
    protected function model(): Model
    {
        return new Lancamento();
    }

    public function testTraitsModelLancamento()
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

    public function testFillablesModelLancamento()
    {
        $fillable = $this->model()->getFillable();
        $expectedFillable = [
            'numero_conta',
            'descricao',
            'tipo',
            'valor',
        ];
        $this->assertEquals($expectedFillable, $fillable);
    }
}

