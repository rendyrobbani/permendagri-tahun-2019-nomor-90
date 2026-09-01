<?php

namespace RendyRobbani\Keuangan\Exception;

class BidangNotFoundException extends \RuntimeException
{
	public function __construct(string $kode)
	{
		parent::__construct("Bidang dengan kode '$kode' tidak ditemukan");
	}
}