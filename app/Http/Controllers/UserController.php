<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use JsonSerializable;

class UserController
{
    public function __construct(protected UserRepository $userRepository) {}

    public function index()
    {
        $users = $this->userRepository->index();
        return response()->json($users);
    }

    public function show($id)
    {
        $user = $this->userRepository->show($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $user = $this->userRepository->store($request->all());
        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $this->userRepository->index($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $this->userRepository->index($id);
        return response()->json(null, 204);
    }

    function validate_create(array $data): ValidationResult
    {
        if (empty($data['username'])) {
            return new ValidationResult(false, 'Username is required.', 'username');
        }

        if (strlen($data['password'] ?? '') < 6) {
            return new ValidationResult(false, 'Password must be at least 6 characters.', 'password');
        }

        return new ValidationResult(true);
    }
//
//    public function validate_update(array $data): ValidationResult
//    {
//        //username and email unique
//        if (empty($data['username'])) {
//            return new ValidationResult(false, 'Username is required.', 'username');
//        }
//
//        if (strlen($data['username'] ?? '') < 6) {
//            return new ValidationResult(false, 'Username must be at least 6 characters.', 'username');
//        }
//
//        if (strlen($data['username'] ?? '') < 6) {
//            return new ValidationResult(false, 'Username must be less than 6 characters.', 'username');
//        }
//
//        if (empty($data['email'])) {
//            return new ValidationResult(false, 'Email is required.', 'email');
//        }
//
//        if (preg_match('/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $data['email'])) { 
//            return new ValidationResult(false, 'Email does not have a valid format.', 'email');
//        }
//
//        if (empty($data['password'])) {
//            return new ValidationResult(false, 'Password is required.', 'password');
//        }
//
//        if (strlen($data['password'] ?? '') < 6) {
//            return new ValidationResult(false, 'Password must be at least 6 characters.', 'password');
//        }
//
//        if (strlen($data['password'] ?? '') < 6) {
//            return new ValidationResult(false, 'Password must be less than 6 characters.', 'password');
//        }
//
//        return new ValidationResult(true);
//    }

    function validate_username(string $username): ValidationResult
    {
        if (empty($username)) {
            return new ValidationResult(false, 'Username is required.');
        }

        if (strlen($username) < 3 || strlen($username) > 20) {
            return new ValidationResult(false, 'Username must be between 3 and 20 characters.');
        }

        if (!$this->is_username_unique($username)) {
            return new ValidationResult(false, 'Username must be unique.');
        }

        return new ValidationResult(true);
    }

    function validate_password(string $password): ValidationResult
    {
        if (empty($password)) {
            return new ValidationResult(false, 'Password is required.');
        }

        if (strlen($password) < 6 || strlen($password) > 50) {
            return new ValidationResult(false, 'Password must be between 6 and 50 characters.');
        }

        return new ValidationResult(true);
    }

    function validate_email(string $email): ValidationResult
    {
        if (empty($email)) {
            return new ValidationResult(false, 'Email is required.');
        }

        if (!preg_match('/^[\w\-.]+@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
            return new ValidationResult(false, 'Email is not valid.');
        }

        if (!$this->is_email_unique($email)) {
            return new ValidationResult(false, 'Email must be unique.');
        }

        return new ValidationResult(true);
    }

    function validate(array $data): AggregateValidationResult
    {
        $aggregateResult = new AggregateValidationResult();

        if (isset($data['username'])) {
            $aggregateResult->addResult('username', $this->validate_username($data['username']));
        } else {
            $aggregateResult->addResult('username', new ValidationResult(false, 'Username is required.'));
        }

        if (isset($data['password'])) {
            $aggregateResult->addResult('password', $this->validate_password($data['password']));
        } else {
            $aggregateResult->addResult('password', new ValidationResult(false, 'Password is required.'));
        }

        if (isset($data['email'])) {
            $aggregateResult->addResult('email', $this->validate_email($data['email']));
        } else {
            $aggregateResult->addResult('email', new ValidationResult(false, 'Email is required.'));
        }

        return $aggregateResult;
    }

    // Example validation functions
    function is_username_unique(string $username): bool
    {
        // Example logic (should query a database in a real application)
        return !in_array($username, ['existingUser']);
    }

    function is_email_unique(string $email): bool
    {
        // Example logic (should query a database in a real application)
        return !in_array($email, ['existing@example.com']);
    }
}

class ValidationResult implements JsonSerializable
{
    public bool $success;
    public string $error;

    public function __construct(bool $success, string $error = '')
    {
        $this->success = $success;
        $this->error = $error;
    }

    public function jsonSerialize()
    {
        return [
            'success' => $this->success,
            'error' => $this->error
        ];
    }
}

class AggregateValidationResult implements JsonSerializable
{
    private array $results = [];
    public bool $success = true;

    public function __construct(array $results = [])
    {
        foreach ($results as $field => $result) {
            $this->addResult($field, $result);
        }
    }

    public function addResult(string $field, ValidationResult $result): void
    {
        $this->results[$field] = $result;
        $this->success = $this->success && $result->success;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function jsonSerialize()
    {
        $results = [];

        foreach ($this->results as $field => $result) {
            $results[$field] = $result->jsonSerialize();
        }

        return [
            'success' => $this->success,
            'results' => $results
        ];
    }
}