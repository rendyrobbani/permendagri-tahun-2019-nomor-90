<?php

namespace RendyRobbani\Keuangan\Exception;

class SubfungsiNotFoundException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Subfungsi dengan kode '$kode' tidak ditemukan");
	}
}