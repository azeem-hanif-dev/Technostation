<?php

use App\Models\StaffingCompany\ContainerSupplier;
use Illuminate\Database\Seeder;

class ContainerSupplierTableSeeder extends Seeder
{
    public function run()
    {
        $container_supplier = ContainerSupplier::create([
            'company_name' => 'Tajammal',
            'code' => '123',
            'telephone' => '090078601',
            'mobile' => '03000789654',
            'fax' => '0258741',
            'email' => 'neo@gmail.com',
            'address' => 'USA',
            'post_code' => '56810',
            'city' => 'NYC',
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => '101',
            'description' => 'Construction and demolition waste (sortable)',
            'price' => '1051',
            'unit' => 'KG',
        ]);

        $container_supplier = ContainerSupplier::create([
            'company_name' => 'Ali Con',
            'code' => '111',
            'telephone' => '564646',
            'mobile' => '5555555',
            'fax' => '6666',
            'email' => 'ddd@gmail.com',
            'address' => 'UK',
            'post_code' => '56810',
            'city' => 'NYC',
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => '123',
            'description' => 'demolition waste (sortable)',
            'price' => '158',
            'unit' => 'KG',
        ]);

        $container_supplier = ContainerSupplier::create([
            'company_name' => 'SAmi',
            'code' => 'AB89',
            'telephone' => '564654',
            'mobile' => '645456',
            'fax' => '315631',
            'email' => 'wqdqw@gmail.com',
            'address' => 'INdia',
            'post_code' => '564354',
            'city' => 'NYC',
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => '101',
            'description' => 'Construction and demolition waste (sortable)',
            'price' => '1051',
            'unit' => 'KG',
        ]);

        $container_supplier = ContainerSupplier::create([
            'company_name' => 'Ahmad',
            'code' => '123',
            'telephone' => '090078601',
            'mobile' => '03000789654',
            'fax' => '0258741',
            'email' => 'neo@gmail.com',
            'address' => 'USA',
            'post_code' => '56810',
            'city' => 'NYC',
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => '333',
            'description' => 'Construction and demolition waste (sortable)',
            'price' => '1051',
            'unit' => 'KG',
        ]);

        $container_supplier = ContainerSupplier::create([
            'company_name' => 'Haseeb',
            'code' => '2222',
            'telephone' => '0900601',
            'mobile' => '03000789654',
            'fax' => '0258741',
            'email' => 'neo@gmail.com',
            'address' => 'USA',
            'post_code' => '56810',
            'city' => 'NYC',
        ]);

        $container_supplier->priceLists()->create([
            'article_no' => '555',
            'description' => 'Construction and demolition waste (sortable)',
            'price' => '1051',
            'unit' => 'KG',
        ]);
    }
}
