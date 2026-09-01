<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\PerencanaanKabupatenLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: PerencanaanKabupatenLogEntity::class)]
interface PerencanaanKabupatenLogRepository
{
	/**
	 * @return PerencanaanKabupatenLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return PerencanaanKabupatenLogEntity|null
	 */
	function findById(int $id): PerencanaanKabupatenLogEntity|null;

	/**
	 * @param PerencanaanKabupatenLogEntity $entity
	 * @return PerencanaanKabupatenLogEntity
	 */
	function save(PerencanaanKabupatenLogEntity $entity): PerencanaanKabupatenLogEntity;

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