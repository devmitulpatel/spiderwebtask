<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {



        $speakerRole=Role::factory(1)->create([
            'name'=>'Speaker',
            'slug'=>Str::slug('Speaker'),
        ]);
        $listenerRole=Role::factory(1)->create([
            'name'=>'Listener',
            'slug'=>Str::slug('Listener'),
        ]);

        $listenerNo=100;

        User::factory(1)->create(['email'=>'speaker@admin.com','password'=>Hash::make('password'),'role_id'=>$speakerRole->first()]);
        User::factory()->count($listenerNo)
            ->state(new Sequence(
                fn ($x) => [
                    'email'=>implode('',['listener','_',$x->index+1,'@admin.com']),
                    'role_id' => $listenerRole->first()
                ]
            ))
            ->create();
    }
}
