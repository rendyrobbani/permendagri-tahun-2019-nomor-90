<?php

namespace RendyRobbani\Keuangan\Exception;

class ProgramExistsException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Program dengan kode '$kode' sudah tersedia");
	}
}