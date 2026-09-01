<?php

namespace RendyRobbani\Keuangan\Exception;

class RekeningExistsException extends \RuntimeException
{
	const string SUMBER_DANA = "Sumber Dana";

	const string NERACA = "Rekening Neraca";

	const string LRA = "Rekening LRA";

	const string LO = "Rekening LO";

	const string LEVEL_1 = "Akun";

	const string LEVEL_2 = "Kelompok";

	const string LEVEL_3 = "Jenis";

	const string LEVEL_4 = "Objek";

	const string LEVEL_5 = "Rincian Objek";

	const string LEVEL_6 = "Subrincian Objek";

	public function __construct(string $nama, string $level, string $kode)
	{
		parent::__construct("$level $nama dengan kode '$kode' sudah tersedia.");
	}
}