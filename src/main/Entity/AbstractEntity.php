<?php

namespace RendyRobbani\Keuangan\Entity;

abstract class AbstractEntity
{
	public abstract function toString(): string;

	public abstract function generateIdAndKode(): void;
}