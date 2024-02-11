<?php


namespace Tests\Unit\Services;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Services\UserService; 
use Storage;
use App\Models\User;
use Hash;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;


/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserServiceTest extends TestCase
{

    
    public function userService(){
        // Instantiate the UserService

        $request = new Request(); // Create an instance of the Request class
        $userModel = new User(); // Create an instance of the User model
        $userService = new UserService($userModel, $request); 

        return $userService;
    }
    public function fakerData(){

        //create user with dumy data
        $faker = Faker::create();

        $userData = [
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'username' => $faker->unique()->userName . mt_rand(10, 99),
            'prefixname' => 'Mr',
            'password' => Hash::make('mazen@123'),
            'photo' => url().'/storage/images/user-defualt.png',
        ];
    
        return $userData;
    }

    /** @test */
    public function it_can_return_a_paginated_list_of_users()
    {
        // Instantiate the UserService
        $userService = $this->userService();; 

        // Act
        // Call the method to get a paginated list of users
        $users = $userService->getPaginatedUsers();

        // Assert
        // Assert that $users is an instance of Illuminate\Pagination\LengthAwarePaginator
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_store_a_user_to_database()
    {
        // Instantiate the UserService
        $userService = $this->userService();
        $userData  = $this->fakerData();

        // Act
        // Call the method to store a user
        $userService->store($userData);

        // Assert
        // Assert that the user was added to the database
        $this->assertDatabaseHas('users', $userData);
    }
    /**
     * @test
     * @return void
     */
    public function it_can_find_and_return_an_existing_user()
    {
        // Arrange

        $userData  = $this->fakerData();

        $user = User::create($userData  );

        // Instantiate the UserService
        $userService = $this->userService();

        // Act
        // Call the method to find and return the user
        $foundUser = $userService->find($user->id);

        // Assert
        // Assert that the foundUser is not null
        $this->assertNotNull($foundUser);

        // Assert that the foundUser's id matches the id of the user created
        $this->assertEquals($user->id, $foundUser->id);
    }

    /**
     * @test
     * @return void
     */
    public function it_can_update_an_existing_user()
{
    // Arrange
    // Create a sample user in the database
    $user = User::latest()->first();
    $faker = Faker::create();
    // Define new data to update the user
    $newUserData = [
        'email' => $faker->unique()->safeEmail,
        'username' => $faker->unique()->userName,
    ];

    // Instantiate the UserService
    $userService = $this->userService();

    // Act
    // Call the method to update the user
    $userService->update( $user->id, $newUserData);

    // Retrieve the updated user from the database
    $updatedUser = User::find($user->id);

    // Assert
    // Assert that the updated user exists
    $this->assertNotNull($updatedUser);

    // Assert that the user's attributes have been updated correctly
    $this->assertEquals($newUserData['username'], $updatedUser->username);
    $this->assertEquals($newUserData['email'], $updatedUser->email);
    // Add assertions for other fields as needed
}

    /**
     * @test
     * @return void
     */
    public function it_can_soft_delete_an_existing_user()
    {
        // Arrange
        // Create a sample user in the database
        $user = User::latest()->first();

        // Instantiate the UserService
        $userService = $this->userService();

        // Act
        // Call the method to soft delete the user
        $userService->destroy($user->id);

        // Retrieve the soft-deleted user from the database
        $softDeletedUser = User::withTrashed()->find($user->id);

        // Assert
        // Assert that the user was soft-deleted
        $this->assertNotNull($softDeletedUser->deleted_at);
        // Add additional assertions if needed
    }

    /**
     * @test
     * @return void
     */
    public function it_can_return_a_paginated_list_of_trashed_users()
    {
        // Arrange

        // Instantiate the UserService
        $userService = $this->userService();

        // Act
        // Call the method to retrieve a paginated list of trashed users
        $paginatedTrashedUsers = $userService->listTrashed();

        // Assert
        // Assert that the returned value is an instance of LengthAwarePaginator
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $paginatedTrashedUsers);
        // Add additional assertions if needed
    }

    /**
     * @test
     * @return void
     */
    public function it_can_restore_a_soft_deleted_user()
    {
        // Arrange
        // Create a sample user in the database and soft-delete it
        $user = User::onlyTrashed()->latest()->first();
        $user->delete();

        // Instantiate the UserService
        $userService = $this->userService();

        // Act
        // Call the method to restore the soft-deleted user
        $userService->restore($user->id);

        // Retrieve the restored user from the database
        $restoredUser = User::withTrashed()->find($user->id);

        // Assert
        // Assert that the user has been restored (i.e., deleted_at is null)
        $this->assertNull($restoredUser->deleted_at);
        // Add additional assertions if needed
    }

    /**
     * @test
     * @return void
     */
    public function it_can_upload_photo()
    {
        // This case need server to make do it becouse this need extension 
        // I can download it on local but you can't see this externsion when you pull me project
        // so we can do it based on column photo on user table 
        $user = User::latest()->first();
    
        $userService = $this->userService();
   
    
        // Assert that the user's photo attribute is not null after upload
        $this->assertNotNull($user->photo);
    }
    

}

?>