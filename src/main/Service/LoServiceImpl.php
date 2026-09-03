<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\LoEntity;
use RendyRobbani\Keuangan\Entity\LoLogEntity;
use RendyRobbani\Keuangan\Exception\RekeningExistsException;
use RendyRobbani\Keuangan\Exception\RekeningNotFoundException;
use RendyRobbani\Keuangan\Repository\LoLogRepository;
use RendyRobbani\Keuangan\Repository\LoRepository;
use RendyRobbani\Keuangan\Util\PhpSpreadsheetUtil;
use RendyRobbani\PHP\Configuration\Configuration;
use RendyRobbani\PHP\Connection\Connection;

final readonly class LoServiceImpl implements LoService
{
	/**
	 * @param Connection $connection
	 * @param LoRepository $repository
	 * @param LoLogRepository $logRepository
	 * @param string $referensi
	 * @param string $penetapan
	 */
	public function __construct(protected Connection                                     $connection,
	                            protected LoRepository                                   $repository,
	                            protected LoLogRepository                                $logRepository,
	                            #[Configuration("peraturan.referensi")] protected string $referensi,
	                            #[Configuration("peraturan.penetapan")] protected string $penetapan)
	{
	}

	/**
	 * @inheritDoc
	 */
	function fromXlsx(Worksheet $worksheet): void
	{
		try {
			$this->connection->beginTransaction();

			$this->repository->deleteAll();
			$this->logRepository->deleteAll();

			/** @var array<string, LoEntity> $intoEntities */
			$intoEntities = [];

			/** @var LoLogEntity[] $logEntities */
			$logEntities = [];

			for ($rowNum = 4; $rowNum <= $worksheet->getHighestRow(); $rowNum++) {
				echo "Reading row : " . $rowNum . PHP_EOL;

				$rowValues = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 7);
				$rowChecks = array_map(fn($value) => $value === null || trim($value) === "" ? 0 : 1, $rowValues);
				$rowChecks = array_sum($rowChecks);
				if ($rowChecks === 0) continue;

				$intoEntity = new LoEntity();
				for ($i = 0; $i < 7; $i++) {
					$value = $rowValues[$i];
					switch ($i + 1) {
						case 1:
							$intoEntity->kodeRekening1 = $value;
							break;
						case 2:
							$intoEntity->kodeRekening2 = $value;
							break;
						case 3:
							$intoEntity->kodeRekening3 = $value;
							break;
						case 4:
							$intoEntity->kodeRekening4 = $value;
							break;
						case 5:
							$intoEntity->kodeRekening5 = $value;
							break;
						case 6:
							$intoEntity->kodeRekening6 = $value;
							break;
						case 7:
							$intoEntity->nama = $value;
							break;
					}
				}
				$intoEntity->createdAt = $this->penetapan;
				$intoEntity->createdBy = $this->referensi;
				$intoEntity->isDeleted = false;
				$intoEntity->generateIdAndKode();

				if (isset($nextRowNum)) unset($nextRowNum);
				while (true) {
					$nextRowNum = ($nextRowNum ?? $rowNum) + 1;
					$nextRowValues = $nextRowNum <= $worksheet->getHighestRow() ? PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $nextRowNum, 1, 7) : [];
					$nextRowChecks = array_map(fn($value) => $value === null || trim($value) === "" ? 0 : 1, $nextRowValues);
					$nextRowChecks = array_slice($nextRowChecks, 0, 6);
					$nextRowChecks = array_sum($nextRowChecks);
					$nextNama = $nextRowValues[6] ?? null;
					if ($nextRowChecks > 0 || $nextNama === null) break;
					else {
						if ($intoEntity->keterangan !== null) $intoEntity->keterangan = PhpSpreadsheetUtil::cleanValue($intoEntity->keterangan . " " . $nextNama);
						else {
							if (str_starts_with(trim(strtolower($nextNama)), "digunakan")) {
								$intoEntity->keterangan = $nextNama;
							} else {
								$intoEntity->nama = PhpSpreadsheetUtil::cleanValue($intoEntity->nama . " " . $nextNama);
							}
						}
					}
				}

				if ($intoEntity->keterangan !== null) {
					$intoEntity->keterangan = ucfirst($intoEntity->keterangan);
					if (!str_ends_with($intoEntity->keterangan, ".")) {
						$intoEntity->keterangan .= ".";
					}
				}

				if (in_array($rowNum, [1083, 1085, 1088])) {
					$intoEntity->kodeRekening4 = "02";
				}

				if ($rowNum === 3926) {
					$intoEntity->kodeRekening6 = "06";
				}

				if (in_array($rowNum, [7162, 7418])) {
					$intoEntity->kodeRekening6 = "002";
				}

				if (in_array($rowNum, [7430, 7433, 7436])) {
					$intoEntity->kodeRekening5 = "03";
				}

				if (in_array($rowNum, [7526, 7681, 7689, 9294])) {
					$intoEntity->kodeRekening6 = "002";
				}

				if ($rowNum === 10065) {
					$intoEntity->kodeRekening3 = "06";
				}

				$intoEntity->generateIdAndKode();

				$level = sizeof(explode(".", $intoEntity->kode));

				for ($i = 1; $i <= $level; $i++) {
					$kode = [];
					if ($i >= 1) $kode[] = $intoEntity->kodeRekening1;
					if ($i >= 2) $kode[] = $intoEntity->kodeRekening2;
					if ($i >= 3) $kode[] = $intoEntity->kodeRekening3;
					if ($i >= 4) $kode[] = $intoEntity->kodeRekening4;
					if ($i >= 5) $kode[] = $intoEntity->kodeRekening5;
					if ($i >= 6) $kode[] = $intoEntity->kodeRekening6;

					$kode = implode(".", $kode);

					if ($i < $level) {
						if (isset($intoEntities[$kode])) continue;
						else {
							switch ($i) {
								case 1:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_1, $kode);
								case 2:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_2, $kode);
								case 3:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_3, $kode);
								case 4:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_4, $kode);
								case 5:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_5, $kode);
								case 6:
									throw new RekeningNotFoundException(RekeningNotFoundException::LO, RekeningNotFoundException::LEVEL_6, $kode);
							}
						}
					}

					if (isset($intoEntities[$kode])) {
						switch ($i) {
							case 1:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_1, $kode);
							case 2:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_2, $kode);
							case 3:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_3, $kode);
							case 4:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_4, $kode);
							case 5:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_5, $kode);
							case 6:
								throw new RekeningExistsException(RekeningExistsException::LO, RekeningExistsException::LEVEL_6, $kode);
						}
					}
				}

				$intoEntities[$intoEntity->kode] = $intoEntity;

				$logEntity = $intoEntity->log();
				$logEntity->loggedAt = $this->penetapan;
				$logEntity->loggedBy = $this->referensi;
				$logEntities[] = $logEntity;

				if (isset($nextRowNum)) $rowNum = max($rowNum, $nextRowNum - 1);
			}

			foreach ($intoEntities as $entity) $this->repository->save($entity);
			foreach ($logEntities as $entity) $this->logRepository->save($entity);

			$this->connection->commit();
		} catch (\Throwable $exception) {
			$this->connection->rollBack();
			if ($entity = $entity ?? $intoEntity ?? false) {
				echo PHP_EOL;
				echo "kode : " . $entity->kode . PHP_EOL;
				echo "nama : " . $entity->nama . PHP_EOL;
				echo PHP_EOL;
			}
			throw $exception;
		}
	}

	/**
	 * @inheritDoc
	 */
	function intoXlsx(Worksheet $worksheet): void
	{
		$worksheet->getPageSetup()
			->setPaperSize(PageSetup::PAPERSIZE_FOLIO)
			->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
		$worksheet->getPageMargins()
			->setTop(PhpSpreadsheetUtil::inch(20))
			->setLeft(PhpSpreadsheetUtil::inch(30))
			->setRight(PhpSpreadsheetUtil::inch(20))
			->setBottom(PhpSpreadsheetUtil::inch(25))
			->setHeader(PhpSpreadsheetUtil::inch(10))
			->setFooter(PhpSpreadsheetUtil::inch(10));

		$worksheet->setTitle("J");

		$rowNum = 1;
		$worksheet->getRowDimension($rowNum)->setRowHeight(48);
		$worksheet->getCell([1, $rowNum])->setValueExplicit($worksheet->getTitle() . ".");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("KLASIFIKASI, KODEFIKASI, DAN NOMENKLATUR REKENING LAPORAN OPERASIONAL");
		$worksheet->mergeCells("B$rowNum:G$rowNum");
		for ($i = 0; $i < 7; $i++) {
			$worksheet->getStyle([$i + 1, $rowNum])->getAlignment()
				->setHorizontal(Alignment::HORIZONTAL_GENERAL)
				->setWrapText(true);
		}

		$rowNum = $rowNum + 2;
		$worksheet->getCell([1, $rowNum])->setValueExplicit("Kode Akun");
		$worksheet->mergeCells("A$rowNum:F$rowNum");

		$worksheet->getCell([7, $rowNum])->setValueExplicit("Uraian Akun");
		$worksheet->mergeCells("G$rowNum:G" . $rowNum + 1);

		$rowNum++;
		$worksheet->getRowDimension($rowNum)->setRowHeight(16 * 8);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(1))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(2))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(3))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(4))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(5))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(6))->setWidth(6);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(7))->setWidth(36);

		$worksheet->getCell([1, $rowNum])->setValueExplicit("Akun");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("Kelompok");
		$worksheet->getCell([3, $rowNum])->setValueExplicit("Jenis");
		$worksheet->getCell([4, $rowNum])->setValueExplicit("Objek");
		$worksheet->getCell([5, $rowNum])->setValueExplicit("Rincian Objek");
		$worksheet->getCell([6, $rowNum])->setValueExplicit("Sub Rincian Objek");

		$worksheet->getPageSetup()->setRowsToRepeatAtTop([$rowNum - 1, $rowNum + 1]);

		for ($r = $rowNum - 1; $r <= $rowNum; $r++) {
			for ($c = 1; $c <= 7; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal(Alignment::HORIZONTAL_CENTER)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setTextRotation($r == $rowNum && $c < 7 ? 90 : 0)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}

		$fromRow = $rowNum + 1;
		foreach ($this->repository->findAll() as $entity) {
			$rowNum = $rowNum + 2;
			for ($colNum = 1; $colNum <= 7; $colNum++) {
				$value = match ($colNum) {
					1 => $entity->kodeRekening1,
					2 => $entity->kodeRekening2,
					3 => $entity->kodeRekening3,
					4 => $entity->kodeRekening4,
					5 => $entity->kodeRekening5,
					6 => $entity->kodeRekening6,
					7 => $entity->nama,
				};
				if ($value != null) $worksheet->getCell([$colNum, $rowNum])->setValueExplicit($value);
			}

			if ($entity->keterangan != null) {
				$rowNum++;
				$worksheet->getCell([7, $rowNum])->setValueExplicit($entity->keterangan);
			}
		}
		$intoRow = $rowNum + 1;

		for ($r = $fromRow; $r <= $intoRow; $r++) {
			for ($c = 1; $c <= 7; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal($c < 7 ? Alignment::HORIZONTAL_CENTER : Alignment::HORIZONTAL_GENERAL)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}
	}
}