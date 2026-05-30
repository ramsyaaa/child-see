<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Subscription Products
            [
                'type' => 'subscription',
                'name' => 'Monthly Unlimited Pass',
                'description' => 'Unlimited access to all classes for 30 days. Perfect for dedicated fitness enthusiasts who want to attend multiple classes per week.',
                'price' => 1500000,
                'duration_days' => 30,
                'quota' => null,
                'quota_type' => 'unlimited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => '8-Class Monthly Pass',
                'description' => 'Attend 8 classes within 30 days. Ideal for those with a moderate workout schedule (2 classes per week).',
                'price' => 800000,
                'duration_days' => 30,
                'quota' => 8,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => '12-Class Monthly Pass',
                'description' => 'Attend 12 classes within 30 days. Great value for regular members (3 classes per week).',
                'price' => 1100000,
                'duration_days' => 30,
                'quota' => 12,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => '20-Class Monthly Pass',
                'description' => 'Attend 20 classes within 30 days. Best for active members who love variety (5 classes per week).',
                'price' => 1300000,
                'duration_days' => 30,
                'quota' => 20,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => '3-Month Unlimited Pass',
                'description' => 'Unlimited access to all classes for 90 days. Save 15% compared to monthly passes!',
                'price' => 3800000,
                'duration_days' => 90,
                'quota' => null,
                'quota_type' => 'unlimited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => '6-Month Unlimited Pass',
                'description' => 'Unlimited access to all classes for 180 days. Save 25% - our best value for committed members!',
                'price' => 6750000,
                'duration_days' => 180,
                'quota' => null,
                'quota_type' => 'unlimited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => 'Weekend Warrior Pass',
                'description' => 'Unlimited weekend classes (Saturday & Sunday) for 30 days. Perfect for busy professionals.',
                'price' => 900000,
                'duration_days' => 30,
                'quota' => null,
                'quota_type' => 'unlimited',
                'is_active' => true,
            ],
            [
                'type' => 'subscription',
                'name' => 'Student Monthly Pass',
                'description' => 'Special rate for students with valid ID. 12 classes within 30 days.',
                'price' => 900000,
                'duration_days' => 30,
                'quota' => 12,
                'quota_type' => 'limited',
                'is_active' => true,
            ],

            // Drop-in Products
            [
                'type' => 'dropin',
                'name' => 'Single Class Drop-in',
                'description' => 'Pay per class - no commitment required. Try any class before committing to a membership.',
                'price' => 150000,
                'duration_days' => null,
                'quota' => 1,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => '5-Class Pack',
                'description' => 'Bundle of 5 drop-in classes. Valid for 60 days. Save 10% compared to single drop-ins.',
                'price' => 675000,
                'duration_days' => 60,
                'quota' => 5,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => '10-Class Pack',
                'description' => 'Bundle of 10 drop-in classes. Valid for 90 days. Save 15% - great for occasional visitors.',
                'price' => 1275000,
                'duration_days' => 90,
                'quota' => 10,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => 'Premium Class Drop-in',
                'description' => 'Access to premium classes (Reformer Pilates, Personal Training). Single session.',
                'price' => 200000,
                'duration_days' => null,
                'quota' => 1,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => 'First Timer Special',
                'description' => 'Special introductory offer for new members. Try your first class at a discounted rate!',
                'price' => 100000,
                'duration_days' => null,
                'quota' => 1,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => 'Couple Class Package',
                'description' => 'Bring a friend! Two drop-in classes for the price of 1.5. Perfect for working out together.',
                'price' => 225000,
                'duration_days' => null,
                'quota' => 2,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
            [
                'type' => 'dropin',
                'name' => 'Early Bird Special',
                'description' => 'Discounted drop-in for morning classes (before 10 AM). Start your day right!',
                'price' => 120000,
                'duration_days' => null,
                'quota' => 1,
                'quota_type' => 'limited',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

