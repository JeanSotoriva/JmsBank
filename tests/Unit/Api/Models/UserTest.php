<?php

namespace Tests\Unit\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserTest extends TestCase
{
    protected function model(): Model
    {
        return new User();
    }

    public function testTraitsModelUser()
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

    public function testFillablesModelUser()
    {
        $fillable = $this->model()->getFillable();
        $expectedFillable = [
            'name',
            'email',
            'password',
        ];
        $this->assertEquals($expectedFillable, $fillable);
    }
}
