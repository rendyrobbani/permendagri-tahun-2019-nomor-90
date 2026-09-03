<?php

namespace RendyRobbani\Keuangan\Exception;

class SubkegiatanExistsException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Subkegiatan dengan kode '$kode' sudah tersedia");
	}
}