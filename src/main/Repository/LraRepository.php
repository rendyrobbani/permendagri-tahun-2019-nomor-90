<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\LraEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: LraEntity::class)]
interface LraRepository
{
	/**
	 * @return LraEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return LraEntity|null
	 */
	function findById(string $id): LraEntity|null;

	/**
	 * @param LraEntity $entity
	 * @return LraEntity
	 */
	function save(LraEntity $entity): LraEntity;

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