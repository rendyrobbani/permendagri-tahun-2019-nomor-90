<?php

namespace RendyRobbani\Keuangan\Repository;

use RendyRobbani\Keuangan\Entity\PerencanaanKabupatenEntity;
use RendyRobbani\PHP\Component\Component;
use RendyRobbani\PHP\Persistence\Repository;

#[Component]
#[Repository(entity: PerencanaanKabupatenEntity::class)]
interface PerencanaanKabupatenRepository
{
	/**
	 * @return PerencanaanKabupatenEntity[]
	 */
	function findAll(): array;

	/**
	 * @param string $id
	 * @return PerencanaanKabupatenEntity|null
	 */
	function findById(string $id): PerencanaanKabupatenEntity|null;

	/**
	 * @param PerencanaanKabupatenEntity $entity
	 * @return PerencanaanKabupatenEntity
	 */
	function save(PerencanaanKabupatenEntity $entity): PerencanaanKabupatenEntity;

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