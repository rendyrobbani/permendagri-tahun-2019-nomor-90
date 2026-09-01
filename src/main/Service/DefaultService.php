<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface DefaultService
{
	/**
	 * @param Worksheet $worksheet
	 * @return void
	 * @throws \Throwable
	 */
	function fromXlsx(Worksheet $worksheet): void;

	/**
	 * @param Worksheet $worksheet
	 * @return void
	 * @throws \Throwable
	 */
	function intoXlsx(Worksheet $worksheet): void;
}