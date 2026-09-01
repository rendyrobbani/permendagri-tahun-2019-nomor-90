<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\NeracaLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: NeracaLogEntity::class)]
interface NeracaLogRepository
{
	/**
	 * @return NeracaLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return NeracaLogEntity|null
	 */
	function findById(int $id): NeracaLogEntity|null;

	/**
	 * @param NeracaLogEntity $entity
	 * @return NeracaLogEntity
	 */
	function save(NeracaLogEntity $entity): NeracaLogEntity;

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