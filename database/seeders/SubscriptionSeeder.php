<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->founder();

        // todo: add product ids
        $this->monthly();
        $this->annually();
    }

    public function founder()
    {
        Subscription::create([
            'name' => 'Founder',
            'slug' => 'founder',
            'description' => 'This is the founder tier',
            'price' => 0,
            'max_agents' => 100,
            'max_workflows' => 100,
        ]);
    }

    public function monthly()
    {
        Subscription::create([
            'name' => 'Basic',
            'slug' => 'basic-monthly',
            'description' => 'Begin with core botting features, perfect for newcomers.',
            'product_id' => '64658dbf9b949',
            'price' => 10,
            'max_agents' => 1,
            'max_workflows' => 3,
        ]);

        Subscription::create([
            'name' => 'Essential',
            'slug' => 'essential-monthly',
            'description' => 'Enhanced tools and workflows for serious botters.',
            'product_id' => '65acb376813f6',
            'price' => 25,
            'max_agents' => 3,
            'max_workflows' => 10,
        ]);

        Subscription::create([
            'name' => 'Farm',
            'slug' => 'farm-monthly',
            'description' => 'Maximum botting productivity and customization.',
            'product_id' => '65acb37bc4399',
            'price' => 40,
            'max_agents' => 50,
            'max_workflows' => 50,
        ]);
    }

    public function annually()
    {
        Subscription::create([
            'name' => 'Basic',
            'slug' => 'basic-annually',
            'description' => 'Begin with core botting features, perfect for newcomers.',
            'product_id' => '65acef36448ed',
            'price' => 96,
            'max_agents' => 1,
            'max_workflows' => 3,
        ]);

        Subscription::create([
            'name' => 'Essential',
            'slug' => 'essential-annually',
            'description' => 'Enhanced tools and workflows for serious botters.',
            'product_id' => '65acefabcdad1',
            'price' => 240,
            'max_agents' => 3,
            'max_workflows' => 10,
        ]);

        Subscription::create([
            'name' => 'Farm',
            'slug' => 'farm-annually',
            'description' => 'Maximum botting productivity and customization.',
            'product_id' => '65acf02ae854a',
            'price' => 384,
            'max_agents' => 10,
            'max_workflows' => 50,
        ]);
    }
}
