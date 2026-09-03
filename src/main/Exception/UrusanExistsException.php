<?php

namespace RendyRobbani\Keuangan\Exception;

class UrusanExistsException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Urusan dengan kode '$kode' sudah tersedia");
	}
}