<?php

namespace RendyRobbani\Keuangan\Exception;

class UrusanNotFoundException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Urusan dengan kode '$kode' tidak ditemukan");
	}
}