<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // ---------------- Roles ----------------
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin (Wizards)', 'guard_name' => 'web']);
        $mainAdmin = Role::firstOrCreate(['name' => 'Main Admin (Owner)', 'guard_name' => 'web']);
        $salesman = Role::firstOrCreate(['name' => 'Salesman', 'guard_name' => 'web']);
        $client = Role::firstOrCreate(['name' => 'Client (TBD)', 'guard_name' => 'web']);

        // ---------------- Dashboard ----------------
        $dashboard = Menu::updateOrCreate([
            'name' => 'Dashboard',
            'url' => 'dashboard',
            'icon' => 'solar:widget-5-bold-duotone',
            'order' => 1,
            'is_active' => 1,
        ]);
        $dashboard->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        // ---------------- Products ----------------
        $products = Menu::updateOrCreate([
            'name' => 'Products',
            'url' => '#',
            'icon' => 'solar:t-shirt-bold-duotone',
            'order' => 2,
            'is_active' => 1,
        ]);
        $products->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $productSubmenus = [
            ['name' => 'Brand', 'url' => 'brand'],
            ['name' => 'Label', 'url' => 'label'],
            ['name' => 'Main Category', 'url' => 'category'],
            ['name' => 'Sub Category', 'url' => 'subcategory'],
            ['name' => 'Product', 'url' => 'product'],
            ['name' => 'Attributes', 'url' => 'attributes'],
            ['name' => 'Storage', 'url' => 'manage-storage'],
            ['name' => 'Product Review', 'url' => 'manage-rating'],
        ];

        foreach ($productSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $products->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Inventory ----------------
        $inventory = Menu::updateOrCreate([
            'name' => 'Manage Inventory',
            'url' => 'manage-inventory',
            'icon' => 'solar:box-bold-duotone',
            'order' => 3,
            'is_active' => 1,
        ]);
        $inventory->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        // ---------------- Manage Purchase ----------------
        $purchase = Menu::updateOrCreate([
            'name' => 'Manage Purchase',
            'url' => '#',
            'icon' => 'solar:card-send-bold-duotone',
            'order' => 4,
            'is_active' => 1,
        ]);
        $purchase->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $purchaseSubmenus = [
            ['name' => 'Item', 'url' => 'manage-item'],
            ['name' => 'Vendor', 'url' => 'manage-vendor'],
            ['name' => 'Item', 'url' => 'manage-item'],
        ];

        foreach ($purchaseSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $purchase->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Customer ----------------
        $customer = Menu::updateOrCreate([
            'name' => 'Manage Customer',
            'url' => '#',
            'icon' => 'solar:users-group-two-rounded-bold-duotone',
            'order' => 5,
            'is_active' => 1,
        ]);
        $customer->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $customerSubmenus = [
            ['name' => 'Customer List', 'url' => 'manage-customer'],
            ['name' => 'Manage Group Category', 'url' => 'manage-group-category'],
            ['name' => 'Manage Group', 'url' => 'manage-group'],
        ];

        foreach ($customerSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $customer->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Orders ----------------
        $orders = Menu::updateOrCreate([
            'name' => 'Manage Orders',
            'url' => '#',
            'icon' => 'solar:bag-smile-bold-duotone',
            'order' => 6,
            'is_active' => 1,
        ]);
        $orders->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $ordersSubmenus = [
            ['name' => 'Order', 'url' => 'order-list'],
        ];

        foreach ($ordersSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $orders->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Blogs ----------------
        $blogs = Menu::updateOrCreate([
            'name' => 'Manage Blogs',
            'url' => '#',
            'icon' => 'solar:gift-bold-duotone',
            'order' => 7,
            'is_active' => 1,
        ]);
        $blogs->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $blogsSubmenus = [
            ['name' => 'Blog Category', 'url' => 'manage-blog-category'],
            ['name' => 'Blog', 'url' => 'manage-blog'],
        ];

        foreach ($blogsSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $blogs->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Home Section ----------------
        $homeSection = Menu::updateOrCreate([
            'name' => 'Manage Home Section',
            'url' => '#',
            'icon' => 'solar:checklist-bold-duotone',
            'order' => 8,
            'is_active' => 1,
        ]);
        $homeSection->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $homeSubmenus = [
            ['name' => 'Banner', 'url' => 'manage-banner'],
            ['name' => 'Primary Category', 'url' => 'manage-primary-category'],
            ['name' => 'Home Video', 'url' => 'manage-video'],
        ];

        foreach ($homeSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $homeSection->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Whatsapp ----------------
        $whatsapp = Menu::updateOrCreate([
            'name' => 'Manage Whatsapp',
            'url' => '#',
            'icon' => 'solar:chat-round-bold-duotone',
            'order' => 9,
            'is_active' => 1,
        ]);
        $whatsapp->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $whatsappSubmenus = [
            ['name' => 'Make Conversation to Whatsapp', 'url' => 'manage-whatsapp-conversation'],
            ['name' => 'Single Whats App', 'url' => 'manage-whatsapp'],
            ['name' => 'Group Whats App', 'url' => 'manage-group-whatsapp'],
            ['name' => 'Social Media Track List', 'url' => 'social-media-track-list'],
        ];

        foreach ($whatsappSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $whatsapp->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Landing Page ----------------
        $landingPage = Menu::updateOrCreate([
            'name' => 'Manage Landing Page',
            'url' => '#',
            'icon' => 'solar:gift-bold-duotone',
            'order' => 10,
            'is_active' => 1,
        ]);
        $landingPage->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $landingSubmenus = [
            ['name' => 'Landing Page', 'url' => 'manage-landing-page'],
        ];

        foreach ($landingSubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $landingPage->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }

        // ---------------- Manage Enquiry ----------------
        $enquiry = Menu::updateOrCreate([
            'name' => 'Manage Enquiry',
            'url' => '#',
            'icon' => 'solar:question-circle-bold-duotone',
            'order' => 11,
            'is_active' => 1,
        ]);
        $enquiry->roles()->sync([$superAdmin->id, $mainAdmin->id]);

        $enquirySubmenus = [
            ['name' => 'Request a Product or Item', 'url' => 'manage-enquiry/request-product-list'],
        ];

        foreach ($enquirySubmenus as $index => $submenu) {
            $child = Menu::updateOrCreate([
                'name' => $submenu['name'],
                'url' => $submenu['url'],
                'order' => $index + 1,
                'parent_id' => $enquiry->id,
                'is_active' => 1,
            ]);
            $child->roles()->sync([$superAdmin->id, $mainAdmin->id]);
        }
    }
}
