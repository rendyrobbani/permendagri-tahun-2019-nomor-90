<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\FungsiLogEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: FungsiLogEntity::class)]
interface FungsiLogRepository
{
	/**
	 * @return FungsiLogEntity[]
	 */
	function findAll(): array;

	/**
	 * @param int $id
	 * @return FungsiLogEntity|null
	 */
	function findById(int $id): FungsiLogEntity|null;

	/**
	 * @param FungsiLogEntity $entity
	 * @return FungsiLogEntity
	 */
	function save(FungsiLogEntity $entity): FungsiLogEntity;

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