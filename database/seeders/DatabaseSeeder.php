<?php
namespace Database\Seeders;
use App\Models\{Category, Store, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
    public function run(): void {
        foreach (['Electronics','Groceries','Fashion','Books','Travel','Dining','Mobile & Internet','Bank & Wallet','Home & Living'] as $name) {
            Category::firstOrCreate(['slug'=>Str::slug($name)], ['name'=>$name]);
        }
        // Directory entries are for categorization; no partnership is implied.
        foreach (['Daraz','Pickaboo','Star Tech','Rokomari','General / Other'] as $name) {
            Store::firstOrCreate(['slug'=>Str::slug($name)], ['name'=>$name]);
        }
        // No public default password: add an admin only when credentials are supplied explicitly.
        if ($email = env('SEED_ADMIN_EMAIL')) {
            $password = env('SEED_ADMIN_PASSWORD');
            if (! $password || strlen($password) < 12) {
                throw new \RuntimeException('SEED_ADMIN_PASSWORD must be at least 12 characters.');
            }
            $user = User::firstOrCreate(['email'=>$email], ['name'=>'Site Admin','password'=>Hash::make($password)]);
            $user->forceFill(['role'=>'admin'])->save();
        }
    }
}
