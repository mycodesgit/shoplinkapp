<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void  // Changed from 'up()' to 'run()'
    {
        $categories = [
            // Category => [subcategories]
            'Clothing & Apparel' => [
                'Tops & Blouses',
                'T-Shirts & Tanks',
                'Dresses & Jumpsuits',
                'Pants & Leggings',
                'Jeans & Denim',
                'Shorts',
                'Skirts',
                'Suits & Blazers',
                'Sweaters & Cardigans',
                'Activewear & Sportswear',
                'Sleepwear & Loungewear',
                'Outerwear & Jackets',
                'Swimwear'
            ],
            'Shoes & Footwear' => [
                'Sneakers & Athletic Shoes',
                'Boots',
                'Sandals & Flip-Flops',
                'Loafers & Oxfords',
                'Heels & Wedges',
                'Flats',
                'Slippers',
                'Work & Safety Shoes'
            ],
            'Bags & Luggage' => [
                'Handbags & Purses',
                'Backpacks',
                'Totes & Shopper Bags',
                'Crossbody & Clutches',
                'Travel Bags & Suitcases',
                'Laptop Bags & Briefcases',
                'Gym Bags & Duffle Bags',
                "Kids' Bags"
            ],
            'Jewelry & Accessories' => [
                'Necklaces & Pendants',
                'Earrings',
                'Rings',
                'Bracelets & Anklets',
                'Watches',
                'Sunglasses & Eyewear',
                'Hats & Caps',
                'Scarves & Gloves',
                'Belts',
                'Wallets & Cardholders'
            ],
            'Beauty & Personal Care' => [
                'Makeup (Face, Eyes, Lips)',
                'Skincare (Cleansers, Serums, Moisturizers)',
                'Hair Care (Shampoo, Conditioner, Styling)',
                'Fragrances (Perfume, Cologne)',
                'Bath & Body (Soap, Lotion, Scrubs)',
                'Shaving & Hair Removal',
                'Nail Care',
                "Men's Grooming",
                'Oral Care',
                'Deodorants & Antiperspirants'
            ],
            'Health & Wellness' => [
                'Vitamins & Supplements',
                'First Aid & Medical Supplies',
                'Fitness Trackers & Smart Scales',
                'Massagers & Pain Relief',
                'Yoga & Meditation Gear',
                'Essential Oils & Diffusers',
                'Sexual Wellness'
            ],
            'Electronics' => [
                'Smartphones & Tablets',
                'Laptops & Computers',
                'Headphones & Earbuds',
                'Smartwatches & Wearables',
                'TVs & Home Theater',
                'Cameras & Photo Gear',
                'Gaming Consoles & Accessories',
                'Chargers & Cables',
                'Speakers & Docks',
                'Printers & Scanners'
            ],
            'Home & Kitchen' => [
                'Furniture (Sofas, Beds, Tables, Chairs)',
                'Bedding & Linens (Sheets, Pillows, Blankets)',
                'Bathroom (Towels, Shower Curtains, Mats)',
                'Kitchen Appliances (Blenders, Coffee Makers, Air Fryers)',
                'Cookware (Pots, Pans, Bakeware)',
                'Dinnerware & Glassware (Plates, Cups, Cutlery)',
                'Storage & Organization',
                'Home Décor (Candles, Frames, Vases)',
                'Cleaning Supplies',
                'Laundry & Ironing'
            ],
            'Kids & Baby' => [
                'Baby Clothing (0–24 months)',
                "Kids' Clothing (2–14 years)",
                'Diapers & Wipes',
                'Feeding (Bottles, Bibs, High Chairs)',
                'Nursery (Cribs, Mattresses, Gliders)',
                'Strollers & Car Seats',
                'Baby Monitors & Safety Gear',
                'Potty Training',
                'Toys & Stuffed Animals'
            ],
            'Toys & Games' => [
                'Action Figures & Dolls',
                'Building Sets (Lego, Blocks)',
                'Board Games & Puzzles',
                'Outdoor & Sports Toys',
                'Ride-Ons (Bikes, Scooters)',
                'Arts & Crafts Kits',
                'Educational Toys',
                'Collectibles & Trading Cards'
            ],
            'Sports & Outdoors' => [
                'Camping & Hiking Gear',
                'Fitness Equipment (Dumbbells, Mats, Bands)',
                'Cycling (Bikes, Helmets, Lights)',
                'Fishing & Hunting',
                'Water Sports (Swim Gear, Kayaks)',
                'Winter Sports (Ski, Snowboard)',
                'Team Sports (Basketball, Soccer)',
                'Skateboards & Rollerblades'
            ],
            'Automotive' => [
                'Car Care (Cleaning, Polish, Wax)',
                'Tires & Wheels',
                'Car Electronics (Dash Cams, GPS)',
                'Interior Accessories (Seat Covers, Mats)',
                'Tools & Maintenance',
                'Motorcycle & ATV'
            ],
            'Pet Supplies' => [
                'Dog Food & Treats',
                'Cat Food & Treats',
                'Dog Beds & Crates',
                'Cat Trees & Litter Boxes',
                'Collars, Leashes & Harnesses',
                'Pet Toys',
                'Grooming Tools',
                'Aquariums & Fish Supplies',
                'Small Pet (Hamster, Rabbit, Bird)'
            ],
            'Office & School Supplies' => [
                'Notebooks & Paper',
                'Pens, Pencils & Markers',
                'Desks & Chairs',
                'Printers, Scanners & Ink',
                'Filing & Storage',
                'Calendars & Planners',
                'Backpacks & Binders',
                'Calculators & Rulers'
            ],
            'Grocery & Gourmet Food' => [
                'Snacks & Candy',
                'Beverages (Coffee, Tea, Juice, Soda)',
                'Baking Supplies',
                'Pantry Staples (Rice, Pasta, Oil)',
                'Frozen Foods',
                'Dairy & Eggs',
                'Fresh Produce (if applicable)',
                'Gourmet & Gifts (Chocolate, Cheese, Wine)'
            ],
            'Books, Movies & Music' => [
                'Fiction & Non-Fiction Books',
                'Audiobooks',
                'eBooks',
                'DVDs & Blu-rays',
                'Vinyl Records & CDs',
                'Musical Instruments (Guitars, Keyboards, Drums)',
                'Sheet Music'
            ],
            'Garden & Outdoor Living' => [
                'Plants, Seeds & Bulbs',
                'Pots & Planters',
                'Garden Tools (Shovels, Rakes, Pruners)',
                'Patio Furniture',
                'Grills & Outdoor Cooking',
                'Fencing & Sheds',
                'Pools & Hot Tubs'
            ],
            'Tools & Home Improvement' => [
                'Power Tools (Drills, Saws, Sanders)',
                'Hand Tools (Hammers, Screwdrivers, Wrenches)',
                'Hardware (Nails, Screws, Anchors)',
                'Electrical & Lighting',
                'Plumbing Supplies',
                'Paint & Wallpaper',
                'Ladders & Scaffolding'
            ],
            'Party & Celebration' => [
                'Balloons & Banners',
                'Party Supplies (Plates, Cups, Napkins)',
                'Gift Wrapping (Paper, Bags, Ribbons)',
                'Invitations & Cards',
                'Bakeware for Parties (Cake Pans, Stands)',
                'Costumes & Decor'
            ]
        ];

        foreach ($categories as $catname => $subcategories) {
            foreach ($subcategories as $subcategory) {
                DB::table('category')->insert([
                    'catname' => $catname,
                    'subcategory' => $subcategory,
                    'caticon' => '', // You can set a default icon path later
                    'posted' => 'System', // Changed from now() to 'admin' or use a user ID
                    'pcstatus' => '2',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}