<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\NeracaEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: NeracaEntity::class)]
interface NeracaRepository
{
	/**
	 * @return NeracaEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return NeracaEntity|null
	 */
	function findById(string $id): NeracaEntity|null;

	/**
	 * @param NeracaEntity $entity
	 * @return NeracaEntity
	 */
	function save(NeracaEntity $entity): NeracaEntity;

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