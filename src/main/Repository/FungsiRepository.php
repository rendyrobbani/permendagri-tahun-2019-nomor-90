<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\FungsiEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: FungsiEntity::class)]
interface FungsiRepository
{
	/**
	 * @return FungsiEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return FungsiEntity|null
	 */
	function findById(string $id): FungsiEntity|null;

	/**
	 * @param FungsiEntity $entity
	 * @return FungsiEntity
	 */
	function save(FungsiEntity $entity): FungsiEntity;

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