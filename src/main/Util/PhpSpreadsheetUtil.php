<?php

namespace RendyRobbani\Keuangan\Util;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class PhpSpreadsheetUtil
{
	private function __construct()
	{
	}

	/**
	 * @param float $mm
	 * @return float
	 */
	public static function inch(float $mm): float
	{
		return $mm / 25.4;
	}

	/**
	 * @param string|null $value
	 * @return string|null
	 */
	public static function cleanValue(null|string $value): null|string
	{
		if ($value == null || trim($value) == "") return null;
		$value = preg_replace("/[\r\n\t ]/", " ", $value);
		while (str_contains($value, "  ")) $value = str_replace("  ", " ", $value);
		foreach (str_split("-:/") as $separator) {
			foreach ([" $separator", "$separator "] as $search) {
				while (str_contains($value, $search)) $value = str_replace($search, $separator, $value);
			}
		}
		foreach (str_split("([{") as $separator) {
			while (str_contains($value, "$separator ")) $value = str_replace("$separator ", $separator, $value);
		}
		foreach (str_split(")]}") as $separator) {
			while (str_contains($value, " $separator")) $value = str_replace(" $separator", $separator, $value);
		}
		$value = trim($value);
		return $value == "" ? null : $value;
	}

	/**
	 * @param Worksheet $worksheet
	 * @param int $rowNum
	 * @param int $colNum
	 * @return string|null
	 */
	public static function getCellValueAsString(Worksheet $worksheet, int $rowNum, int $colNum): null|string
	{
		return self::cleanValue($worksheet->getCell([$colNum, $rowNum])->getValueString());
	}

	/**
	 * @param Worksheet $worksheet
	 * @param int $rowNum
	 * @param int $fromColNum
	 * @param int $intoColNum
	 * @return array
	 */
	public static function getCellValuesAsStringFromRow(Worksheet $worksheet, int $rowNum, int $fromColNum, int $intoColNum): array
	{
		$values = [];
		for ($colNum = $fromColNum; $colNum <= $intoColNum; $colNum++) $values[] = self::getCellValueAsString($worksheet, $rowNum, $colNum);
		return $values;
	}
}