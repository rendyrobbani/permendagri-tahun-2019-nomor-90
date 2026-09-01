<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\LoEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: LoEntity::class)]
interface LoRepository
{
	/**
	 * @return LoEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return LoEntity|null
	 */
	function findById(string $id): LoEntity|null;

	/**
	 * @param LoEntity $entity
	 * @return LoEntity
	 */
	function save(LoEntity $entity): LoEntity;

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