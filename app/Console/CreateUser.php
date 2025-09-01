<?php

namespace Modules\Iuser\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use function Laravel\Prompts\{text, password, select};
use Modules\Iuser\Repositories\UserRepository;
use Modules\Iuser\Repositories\RoleRepository;

class CreateUser extends Command
{
  /**
   * The name and signature of the console command.
   */
  protected $signature = 'iuser:create-user';

  /**
   * The console command description.
   */
  protected $description = 'Interactively create a new user and assign a role';

  private UserRepository $userRepository;
  private RoleRepository $roleRepository;

  /**
   * Create a new command instance.
   */
  public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
  {
    parent::__construct();
    $this->userRepository = $userRepository;
    $this->roleRepository = $roleRepository;
  }

  /**
   * Execute the console command.
   */
  public function handle(): void
  {
    $this->info("-> Create User");

    // Fetch available roles
    $params = json_decode(json_encode(["filter" => []]));
    $roles = $this->roleRepository->getItemsBy($params);

    if ($roles->isEmpty()) {
      $this->error("❌ No roles found in the system. Cannot continue.");
      return;
    }

    // Ask for email first
    $email = text(
      label: 'Email',
      placeholder: 'user@example.com',
      required: true,
      validate: fn($value) => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email format.'
    );

    // Check if user already exists
    $params = json_decode(json_encode(["filter" => ["field" => "email"]]));
    $user = $this->userRepository->getItem($email, $params);

    if (!empty($user)) {
      $this->warn("⚠️ User with email $email already exists!");
      return;
    }

    // If email is valid and does not exist, ask for names and password
    $firstName = text(label: 'First Name', placeholder: 'John', required: true);
    $lastName = text(label: 'Last Name', placeholder: 'Doe', required: true);
    $password = password(label: 'Password (leave empty to auto-generate)');

    if (empty($password)) {
      $password = Str::password(16, true, true, false);
      $this->warn("⚠️ No password entered. Generated one automatically.");
    }

    // Map roles to id => translated title
    $roleOptions = $roles->mapWithKeys(function ($role) {
      return [$role->id => trans($role->title)];
    })->toArray();
    $selectedRole = select(label: 'Select a role for this user', options: $roleOptions);

    // Create user
    $user = $this->userRepository->create([
      'email' => $email,
      'password' => $password,
      'first_name' => $firstName,
      'last_name' => $lastName,
      'roles' => [$selectedRole]
    ]);

    if ($user) {
      $this->info("✅ User Created Successfully");
      $this->line("Name: $firstName $lastName");
      $this->line("Email: $email");
      $this->line("Password: $password");
      $this->line("Role: " . $roleOptions[$selectedRole]);
      $this->line(str_repeat('-', 40));
      $this->warn("⚠️ Important: Save your password securely. It cannot be retrieved later.");
    } else {
      $this->error("❌ Failed to create user.");
    }
  }
}
