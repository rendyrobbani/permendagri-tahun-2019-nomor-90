<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\SumberEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: SumberEntity::class)]
interface SumberRepository
{
	/**
	 * @return SumberEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return SumberEntity|null
	 */
	function findById(string $id): SumberEntity|null;

	/**
	 * @param SumberEntity $entity
	 * @return SumberEntity
	 */
	function save(SumberEntity $entity): SumberEntity;

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