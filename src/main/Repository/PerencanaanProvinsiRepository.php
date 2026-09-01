<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\PerencanaanProvinsiEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: PerencanaanProvinsiEntity::class)]
interface PerencanaanProvinsiRepository
{
	/**
	 * @return PerencanaanProvinsiEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return PerencanaanProvinsiEntity|null
	 */
	function findById(string $id): PerencanaanProvinsiEntity|null;

	/**
	 * @param PerencanaanProvinsiEntity $entity
	 * @return PerencanaanProvinsiEntity
	 */
	function save(PerencanaanProvinsiEntity $entity): PerencanaanProvinsiEntity;

	/**
	 * @return void
	 */
	function deleteAll(): void;

	/**
	 * @param string $id
	 * @return void
	 */
	function deleteById(string $id): void;
}