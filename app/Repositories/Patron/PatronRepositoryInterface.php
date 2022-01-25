<?php

namespace App\Repositories\Patron;

use Illuminate\Http\Request;

interface PatronRepositoryInterface
{
    public function get(string $order = 'id', string $sort = 'ASC');

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC');

    public function getBy(string $field, string $value);

    public function createPatronNo();

    public function create(Request $request);

    public function find(int $id);

    public function findBy(string $field, string $value);

    public function update(Request $request, int $id);

    public function delete(int $id);

    public function count();

    public function countBy(string $field, string $value);

    public function getTopLibraryUserReport(array $data);

    public function getTopBorrowerReport(array $data);
}
