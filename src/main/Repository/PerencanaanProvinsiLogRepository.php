<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\PerencanaanProvinsiLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: PerencanaanProvinsiLogEntity::class)]
interface PerencanaanProvinsiLogRepository
{
	/**
	 * @return PerencanaanProvinsiLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return PerencanaanProvinsiLogEntity|null
	 */
	function findById(int $id): PerencanaanProvinsiLogEntity|null;

	/**
	 * @param PerencanaanProvinsiLogEntity $entity
	 * @return PerencanaanProvinsiLogEntity
	 */
	function save(PerencanaanProvinsiLogEntity $entity): PerencanaanProvinsiLogEntity;

	/**
	 * @return void
	 */
	function deleteAll(): void;

	/**
	 * @param int $id
	 * @return void
	 */
	function deleteById(int $id): void;
}