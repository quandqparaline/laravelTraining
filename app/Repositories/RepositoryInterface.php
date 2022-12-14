<?php
namespace App\Repositories;

interface RepositoryInterface {
    /**
     * Get all
     * @return mixed
     */
    public function getAll();

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes=[]);

    /**
     * Update
     * @param array $attributes
     * @param $id
     * @return mixed
     */
    public function update(array $attributes, $id);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
