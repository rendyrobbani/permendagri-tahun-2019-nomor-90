<?php

namespace RendyRobbani\Keuangan\Exception;

class SubkegiatanNotFoundException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Subkegiatan dengan kode '$kode' tidak ditemukan");
	}
}