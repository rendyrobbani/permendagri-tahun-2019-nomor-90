<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\SumberLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: SumberLogEntity::class)]
interface SumberLogRepository
{
	/**
	 * @return SumberLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return SumberLogEntity|null
	 */
	function findById(int $id): SumberLogEntity|null;

	/**
	 * @param SumberLogEntity $entity
	 * @return SumberLogEntity
	 */
	function save(SumberLogEntity $entity): SumberLogEntity;

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