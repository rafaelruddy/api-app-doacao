<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Campaign;
use App\Models\Institution;
use App\Models\Item;
use App\Models\NecessaryItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = User::create([
            'name' => 'Suporte App',
            'email' => 'suporte@appdedoacao.com.br',
            'password' => bcrypt('123456'),
        ]);

        $user->assignRole('super_admin');


        $item = Item::create([
            'name' => 'Vestimenta',
            'status' => 'active',
        ]);

        $institution = Institution::create([
            'name' => 'Ação Comunitária',
            'phone' => '+5521999999999',
            'description' => 'Ação Comunitária Unilasalle RJ',
            'status' => 'active'
        ]);

        $address = Address::create([
            'street' => 'Rua Gastão Gonçalves, 79',
            'city' => 'Niterói',
            'state' => 'RJ',
            'zipcode' => '24240030',
            'latitude' => -22.89712126271101,
            'longitude' => -43.1065840041689,
        ]);

        $institution->address()->associate($address);
        $institution->save();

        $campaign = Campaign::create([
            'name' => 'Campanha do Agasalho',
            'description' => 'Doe para ajudar pessoas com necessidade.',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'donation_start_time' => now()->format('H:i:s'),
            'donation_end_time' => now()->addHours(2)->format('H:i:s'),
            'institution_id' => $institution->id,
        ]);

        NecessaryItem::create([
            'campaign_id' => $campaign->id,
            'item_id' => $item->id,
            'quantity_objective' => 100,
        ]);
    }
}
