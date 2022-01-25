<?php

namespace App\Repositories\User;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function create(Request $request);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function update(Request $request, int $id);

    public function delete(int $id);

    public function count();

    public function countBy(string $field, string $value);

    public function checkPassword(string $password, int $id);
}
