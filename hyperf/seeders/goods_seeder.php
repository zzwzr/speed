<?php

declare(strict_types=1);

use App\Model\Brand;
use App\Model\Good;
use App\Model\GoodCategory;
use Hyperf\Database\Seeders\Seeder;
use Faker\Factory;

class GoodsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // 生成商品分类
        $categories = [
            ['name' => '运动服饰', 'slug' => 'sportswear'],
            ['name' => '外套/夹克', 'slug' => 'jackets-coats'],
            ['name' => '牛仔裤', 'slug' => 'jeans'],
            ['name' => 'T恤/上衣', 'slug' => 't-shirts'],
            ['name' => '连衣裙', 'slug' => 'dresses'],
            ['name' => '睡衣/家居服', 'slug' => 'sleepwear'],
            ['name' => '内衣/泳装', 'slug' => 'lingerie-swimwear'],
            ['name' => '工作服', 'slug' => 'workwear'],
            ['name' => '儿童服饰', 'slug' => 'kidswear'],
            ['name' => '时尚配件', 'slug' => 'fashion-accessories']
        ];
        GoodCategory::truncate();
        GoodCategory::insert($categories);

        // 生成商品品牌
        $brands = [
            ['name' => 'Nike'],
            ['name' => 'Adidas'],
            ['name' => 'Levi\'s'],
            ['name' => 'Uniqlo'],
            ['name' => 'H&M'],
            ['name' => 'Puma'],
            ['name' => 'Tommy Hilfiger'],
            ['name' => 'Calvin Klein'],
            ['name' => 'Ralph Lauren'],
            ['name' => 'Vans']
        ];
        Brand::truncate();
        Brand::insert($brands);

        $faker = Factory::create('zh_CN');

        // 生成商品
        $goods = [];
        for ($i = 1; $i <= 100; $i++) {
            $price = $faker->randomFloat(2, 10, 1000);
            $goods[] = [
                'name' => $faker->word,
                'sku' => $faker->unique()->ean13(),
                'price' => $price,
                'original_price' => $price,
                'stock' => $faker->numberBetween(0, 100),
                'sales' => $faker->numberBetween(0, 1000),
                'description' => $faker->sentence,
                'image' => $faker->imageUrl(),
                'gallery' => json_encode([$faker->imageUrl(), $faker->imageUrl()]),
                'status' => 2,
                'category_id' => $faker->numberBetween(1, 10),
                'brand_id' => $faker->numberBetween(1, 10),
            ];
        }
        Good::truncate();
        Good::insert($goods);
    }
}
