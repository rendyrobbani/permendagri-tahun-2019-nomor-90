<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\LraLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: LraLogEntity::class)]
interface LraLogRepository
{
	/**
	 * @return LraLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return LraLogEntity|null
	 */
	function findById(int $id): LraLogEntity|null;

	/**
	 * @param LraLogEntity $entity
	 * @return LraLogEntity
	 */
	function save(LraLogEntity $entity): LraLogEntity;

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