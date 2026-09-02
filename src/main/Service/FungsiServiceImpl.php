<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\FungsiEntity;
use RendyRobbani\Keuangan\Exception\FungsiNotFoundException;
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

			$cacheID = [];
			for ($rowNum = 4; $rowNum <= $worksheet->getHighestRow(); $rowNum++) {
				echo "Reading row : " . $rowNum . PHP_EOL;

				$row0 = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 3);
				if ($row0[0] == null) continue;

				$entity = new FungsiEntity();

				$entity->kodeFungsi = $row0[0];
				$entity->kodeFungsi = $entity->kodeFungsi == null ? null : strtoupper($entity->kodeFungsi);

				$entity->kodeSubfungsi = $row0[1] ?? null;
				$entity->kodeSubfungsi = $entity->kodeSubfungsi == null ? null : strtoupper($entity->kodeSubfungsi);

				$entity->nama = $row0[2] ?? null;
				$entity->createdAt = $this->penetapan;
				$entity->createdBy = $this->referensi;
				$entity->isDeleted = false;
				$entity->generateIdAndKode();

				$rowNum1 = $rowNum;
				while (true) {
					$rowNum1++;
					$row1 = $rowNum1 <= $worksheet->getHighestRow() ? PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum1, 1, 3) : [];
					$nextKode = $row1[0] ?? null;
					$nextNama = $row1[2] ?? null;
					if ($nextKode == null && $nextNama != null) {
						if (str_starts_with(trim(strtolower($nextNama)), "tidak ada kewenangan")) {
							$entity->keterangan = $nextNama;
						} else {
							$nama = $entity->nama === null ? $entity->nama : PhpSpreadsheetUtil::cleanValue(implode(" ", [$entity->nama, $nextNama]));
							$entity->nama = $nama;
						}
					} else {
						break;
					}
				}
				$rowNum = $rowNum1 - 1;

				$splitID = [];
				$splitID[] = $entity->kodeFungsi;
				if ($entity->kodeSubfungsi !== null) {
					$splitID[] = $entity->kodeSubfungsi;
				}

				for ($level = 1; $level <= sizeof($splitID); $level++) {
					$checkID = implode(".", array_slice($splitID, 0, $level));
					if ($entity->kode != $checkID && !in_array($checkID, $cacheID)) {
						switch ($level) {
							case 1:
								throw new FungsiNotFoundException($checkID);
							case 2:
								throw new SubfungsiNotFoundException($checkID);
						}
					}
				}

				$logEntity = $entity->log();
				$logEntity->loggedAt = $this->penetapan;
				$logEntity->loggedBy = $this->referensi;

				$cacheID[] = $entity->kode;
				$this->repository->save($entity);
				$this->logRepository->save($logEntity);
			}
			$this->connection->commit();
		} catch (\Throwable $exception) {
			$this->connection->rollBack();
			if (isset($entity)) {
				echo "kode : " . $entity->kode . PHP_EOL;
				echo "nama : " . $entity->nama . PHP_EOL;
				echo "len-nama : " . ($entity->nama === null ? 0 : strlen($entity->nama)) . PHP_EOL;
				echo "len-keterangan : " . ($entity->keterangan === null ? 0 : strlen($entity->keterangan)) . PHP_EOL;
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