<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createType("string");
        $this->createType("int");
        $this->createType("bool");
        $this->createType("float");
    }

    /**
     * @return void
     */
    public function createType(String $typeName): void
    {
        $typeName = new Type([
            "type_name" => $typeName
        ]);
        $typeName->save();
    }
}
