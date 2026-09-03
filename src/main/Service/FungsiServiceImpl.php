<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\FungsiEntity;
use RendyRobbani\Keuangan\Entity\FungsiLogEntity;
use RendyRobbani\Keuangan\Exception\FungsiExistsException;
use RendyRobbani\Keuangan\Exception\FungsiNotFoundException;
use RendyRobbani\Keuangan\Exception\SubfungsiExistsException;
use RendyRobbani\Keuangan\Exception\SubfungsiNotFoundException;
use RendyRobbani\Keuangan\Repository\FungsiLogRepository;
use RendyRobbani\Keuangan\Repository\FungsiRepository;
use RendyRobbani\Keuangan\Util\PhpSpreadsheetUtil;
use RendyRobbani\PHP\Configuration\Configuration;
use RendyRobbani\PHP\Connection\Connection;

final readonly class FungsiServiceImpl implements FungsiService
{
	/**
	 * @param Connection $connection
	 * @param FungsiRepository $repository
	 * @param FungsiLogRepository $logRepository
	 * @param string $referensi
	 * @param string $penetapan
	 */
	public function __construct(protected Connection                                     $connection,
	                            protected FungsiRepository                               $repository,
	                            protected FungsiLogRepository                            $logRepository,
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

			/** @var array<string, FungsiEntity> $intoEntities */
			$intoEntities = [];

			/** @var FungsiLogEntity[] $logEntities */
			$logEntities = [];

			for ($rowNum = 4; $rowNum <= $worksheet->getHighestRow(); $rowNum++) {
				echo "Reading row : " . $rowNum . PHP_EOL;

				$rowValues = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 3);
				$rowChecks = array_map(fn($value) => $value === null || trim($value) === "" ? 0 : 1, $rowValues);
				$rowChecks = array_sum($rowChecks);
				if ($rowChecks === 0) continue;

				$intoEntity = new FungsiEntity();
				for ($i = 0; $i < 3; $i++) {
					$value = $rowValues[$i];
					if ($i < 2 && $value !== null) $value = strtoupper($value);
					switch ($i + 1) {
						case 1:
							$intoEntity->kodeFungsi = $value;
							break;
						case 2:
							$intoEntity->kodeSubfungsi = $value;
							break;
						case 3:
							$intoEntity->nama = $value;
							break;
					}
				}
				$intoEntity->createdAt = $this->penetapan;
				$intoEntity->createdBy = $this->referensi;
				$intoEntity->isDeleted = false;
				$intoEntity->generateIdAndKode();

				$level = sizeof(explode(".", $intoEntity->kode));

				for ($i = 1; $i <= $level; $i++) {
					$kode = [];
					if ($i >= 1) $kode[] = $intoEntity->kodeFungsi;
					if ($i >= 2) $kode[] = $intoEntity->kodeSubfungsi;

					$kode = implode(".", $kode);

					if ($i < $level) {
						if (isset($intoEntities[$kode])) continue;
						else {
							switch ($i) {
								case 1:
									throw new FungsiNotFoundException($kode);
								case 2:
									throw new SubfungsiNotFoundException($kode);
							}
						}
					}

					if (isset($intoEntities[$kode])) {
						switch ($i) {
							case 1:
								throw new FungsiExistsException($kode);
							case 2:
								throw new SubfungsiExistsException($kode);
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

		$worksheet->setTitle("D");

		$rowNum = 1;
		$worksheet->getRowDimension($rowNum)->setRowHeight(48);
		$worksheet->getCell([1, $rowNum])->setValueExplicit($worksheet->getTitle() . ".");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("KLASIFIKASI, KODEFIKASI, DAN NOMENKLATUR FUNGSI");
		$worksheet->mergeCells("B$rowNum:C$rowNum");
		for ($i = 0; $i < 3; $i++) {
			$worksheet->getStyle([$i + 1, $rowNum])->getAlignment()
				->setHorizontal(Alignment::HORIZONTAL_GENERAL)
				->setWrapText(true);
		}

		$rowNum = $rowNum + 2;
		$worksheet->getCell([1, $rowNum])->setValueExplicit("KODE");
		$worksheet->mergeCells("A$rowNum:B$rowNum");

		$worksheet->getCell([3, $rowNum])->setValueExplicit("URAIAN");
		$worksheet->mergeCells("C$rowNum:C" . $rowNum + 1);

		$rowNum++;
		$worksheet->getRowDimension($rowNum)->setRowHeight(96);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(1))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(2))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(3))->setWidth(57);

		$worksheet->getCell([1, $rowNum])->setValueExplicit("FUNGSI");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("SUB FUNGSI");

		$worksheet->getPageSetup()->setRowsToRepeatAtTop([$rowNum - 1, $rowNum + 1]);

		for ($r = $rowNum - 1; $r <= $rowNum; $r++) {
			for ($c = 1; $c <= 3; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal(Alignment::HORIZONTAL_CENTER)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setTextRotation($r == $rowNum && $c < 3 ? 90 : 0)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}

		$fromRow = $rowNum + 1;
		foreach ($this->repository->findAll() as $entity) {
			if ($entity->kodeFungsi != null && $entity->kodeSubfungsi == null) $rowNum++;

			$rowNum++;
			for ($colNum = 1; $colNum <= 3; $colNum++) {
				$value = match ($colNum) {
					1 => $entity->kodeFungsi,
					2 => $entity->kodeSubfungsi,
					3 => $entity->nama,
				};
				if ($value != null) $worksheet->getCell([$colNum, $rowNum])->setValueExplicit($value);
			}

			if ($entity->keterangan != null) {
				$rowNum++;
				$worksheet->getCell([3, $rowNum])->setValueExplicit($entity->keterangan);
			}
		}
		$intoRow = $rowNum + 1;

		for ($r = $fromRow; $r <= $intoRow; $r++) {
			for ($c = 1; $c <= 3; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal($c < 3 ? Alignment::HORIZONTAL_CENTER : Alignment::HORIZONTAL_GENERAL)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}
	}
}