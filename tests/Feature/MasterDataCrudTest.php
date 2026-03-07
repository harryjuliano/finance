<?php

use App\Models\MasterData;
use App\Models\User;

it('can perform master data crud', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('apps.cash-management.master-data.store'), [
            'code' => 'COMP-HO',
            'name' => 'Company Head Office',
            'category' => 'Company & Branch',
            'description' => 'Data perusahaan pusat',
            'is_active' => true,
        ])
        ->assertRedirect();

    $masterData = MasterData::first();

    expect($masterData)->not->toBeNull();
    expect($masterData->code)->toBe('COMP-HO');

    $this->actingAs($user)
        ->put(route('apps.cash-management.master-data.update', $masterData), [
            'code' => 'COMP-HO',
            'name' => 'Company HQ Updated',
            'category' => 'Company & Branch',
            'description' => 'Data perusahaan pusat diperbarui',
            'is_active' => false,
        ])
        ->assertRedirect();

    expect($masterData->fresh()->name)->toBe('Company HQ Updated');
    expect($masterData->fresh()->is_active)->toBeFalse();

    $this->actingAs($user)
        ->delete(route('apps.cash-management.master-data.destroy', $masterData))
        ->assertRedirect();

    $this->assertDatabaseCount('master_data', 0);
});
