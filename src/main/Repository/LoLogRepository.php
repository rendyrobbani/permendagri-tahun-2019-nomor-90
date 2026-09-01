<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\LoLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: LoLogEntity::class)]
interface LoLogRepository
{
	/**
	 * @return LoLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return LoLogEntity|null
	 */
	function findById(int $id): LoLogEntity|null;

	/**
	 * @param LoLogEntity $entity
	 * @return LoLogEntity
	 */
	function save(LoLogEntity $entity): LoLogEntity;

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