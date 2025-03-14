<?php

namespace Tests\Unit;

use App\Models\Dishe;
use App\Models\User;
use Database\Factories\DisheFactory;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class DisheTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_dishes_can_be_see(){
        Dishe::factory()->count(10)->create();
        $response = $this->getJson('api/dishes')->assertStatus(200);
        $response->assertJsonCount(10);
    }
    public function test_cannot_see_non_existant_dishes(){
        $response = $this->getJson('api/dishes')->assertStatus(404);
        $response->assertJson(['error' => 'No dishe found']);
    }
    public function test_can_see_a_dish(){
        $dish = Dishe::factory()->create();
        $response = $this->getJson("api/dishes/{$dish->id}")->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $dish->name,
            'description' => $dish->description,
            'user_id' => $dish->user->id,
        ]);
    }
    public function test_cannot_see_non_existant_dish(){
        $response = $this->getJson('api/dishes/999')->assertStatus(404);
        $response->assertJson(['error' => 'No dish found']);
    }
    public function test_admin_can_delete_a_dish(){
        $adminUser = User::factory()->create();
        $this->seed(PermissionSeeder::class);
        $adminRole = Role::findByName('admin', 'api');
        $adminUser->AssignRole($adminRole);
        $dish = Dishe::factory()->create();
        $this->actingAs($adminUser)->deleteJson("api/dishes/{$dish->id}")->assertStatus(204);
        $this->assertDatabaseMissing('dishes', ['id' => $dish->id]);
    }
    public function test_non_admin_cannot_delete_dish(){
        $user = User::factory()->create();
        $dish = Dishe::factory()->create();
        $this->actingAs($user)->deleteJson("api/dishes/{$dish->id}")->assertStatus(403);
    }
    public function test_admin_can_create_dish(){
        $adminUser = User::factory()->create();
        $this->seed(PermissionSeeder::class);
        $adminRole = Role::findByName('admin', 'api');
        $adminUser->AssignRole($adminRole);
        $dishData = [
            'name' => 'Dish',
            'description' => 'dish description',
            'image' => UploadedFile::fake()->image('dish.jpg'),
            'user_id' => $adminUser->id
        ];
        $response = $this->actingAs($adminUser)->postJson('/api/dishes', $dishData)->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Dish']);
    }
    public function test_non_admin_user_cannot_create_dish(){
        $user = User::factory()->create();
        $dishData = [
            'name' => 'Dish',
            'description' => 'dish description',
            'image' => UploadedFile::fake()->image('dish.jpg'),
            'user_id' => $user->id
        ];
        $this->actingAs($user)->postJson('/api/dishes', $dishData)->assertStatus(403);
    }
    public function test_can_update_dish(){
        $dish = Dishe::factory()->create();
        $dishData = [
            'name' => 'DishUpdate',
            'description' => 'dish description',
            'image' => UploadedFile::fake()->image('dish.jpg'),
            'user_id' => $dish->user->id
        ];
        $response = $this->actingAs($dish->user)->putJson("/api/dishes/{$dish->id}", $dishData);
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'DishUpdate']);
    }
}
