<?php

namespace RendyRobbani\Keuangan\Exception;

class KegiatanNotFoundException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Kegiatan dengan kode '$kode' tidak ditemukan");
	}
}