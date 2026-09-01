<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\NeracaEntity;
use RendyRobbani\Keuangan\Exception\RekeningExistsException;
use RendyRobbani\Keuangan\Exception\RekeningNotFoundException;
use RendyRobbani\Keuangan\Repository\NeracaLogRepository;
use RendyRobbani\Keuangan\Repository\NeracaRepository;
use RendyRobbani\Keuangan\Util\PhpSpreadsheetUtil;
use RendyRobbani\PHP\Configuration\Configuration;
use RendyRobbani\PHP\Connection\Connection;

final readonly class NeracaServiceImpl implements NeracaService
{
	/**
	 * @param Connection $connection
	 * @param NeracaRepository $repository
	 * @param NeracaLogRepository $logRepository
	 * @param string $referensi
	 * @param string $penetapan
	 */
	public function __construct(protected Connection                                     $connection,
	                            protected NeracaRepository                               $repository,
	                            protected NeracaLogRepository                            $logRepository,
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
			for ($rowNum = 1; $rowNum <= $worksheet->getHighestRow(); $rowNum++) {
				echo "Reading row : " . $rowNum . PHP_EOL;
				$reading_row = $rowNum;

				$row0 = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 7);
				if ($row0[0] == null || !preg_match("/^[1-3]+$/", $row0[0])) continue;

				$entity = new NeracaEntity();

				$entity->kodeRekening1 = $row0[0];
				$entity->kodeRekening2 = $row0[1] ?? null;
				$entity->kodeRekening3 = $row0[2] ?? null;
				$entity->kodeRekening4 = $row0[3] ?? null;
				$entity->kodeRekening5 = $row0[4] ?? null;
				$entity->kodeRekening6 = $row0[5] ?? null;
				$entity->nama = $row0[6] ?? null;
				$entity->createdAt = $this->penetapan;
				$entity->createdBy = $this->referensi;
				$entity->isDeleted = false;
				$entity->generateIdAndKode();

				$rowNum1 = $rowNum;
				while (true) {
					$rowNum1++;
					$row1 = $rowNum1 <= $worksheet->getHighestRow() ? PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum1, 1, 7) : [];
					$nextKode = $row1[0] ?? null;
					$nextNama = $row1[6] ?? null;
					if ($nextKode == null && $nextNama != null) {
						if (str_starts_with(trim(strtolower($nextNama)), "digunakan")) {
							$entity->keterangan = $nextNama;
						} elseif ($entity->keterangan != null) {
							$entity->keterangan = PhpSpreadsheetUtil::cleanValue(implode(" ", [$entity->keterangan, $nextNama]));
						} else {
							$nama = $entity->nama == null ? $entity->nama : PhpSpreadsheetUtil::cleanValue(implode(" ", [$entity->nama, $nextNama]));
							$entity->nama = $nama;
						}
					} else {
						break;
					}
				}
				$rowNum = $rowNum1 - 1;

				if ($entity->keterangan !== null && $entity->keterangan !== "" && !str_ends_with($entity->keterangan, ".")) {
					$entity->keterangan .= ".";
				}

				if (in_array($reading_row, [418, 419])) {
					$entity->kodeRekening4 = "12";
				}

				if (in_array($reading_row, [485])) {
					$entity->kodeRekening5 = "19";
				}

				if (in_array($reading_row, [1445])) {
					$entity->kodeRekening4 = "01";
				}

				if (in_array($reading_row, [1644, 1689])) {
					$entity->kodeRekening6 = "002";
				}

				if (in_array($reading_row, [1995])) {
					$entity->kodeRekening5 = "02";
				}

				if (in_array($reading_row, [1998])) {
					$entity->kodeRekening5 = "03";
				}

				if (in_array($reading_row, [2009, 2011])) {
					$entity->kodeRekening5 = "02";
				}

				if (in_array($reading_row, [2022])) {
					$entity->kodeRekening6 = "002";
				}

				if (in_array($reading_row, [4990, 4992, 4994])) {
					$entity->kodeRekening5 = "02";
				}

				if (in_array($reading_row, [5196, 5216, 5238, 5260, 5269])) {
					$entity->kodeRekening2 = "3";
					$entity->kodeRekening3 = "07";
					$entity->kodeRekening5 = "03";
				}

				if (in_array($reading_row, [5371, 5373])) {
					$entity->kodeRekening4 = "01";
				}

				if (in_array($reading_row, [5393])) {
					$entity->kodeRekening6 = "003";
				}

				if (in_array($reading_row, [5414, 5417, 5419])) {
					$entity->kodeRekening3 = "06";
				}

				if (in_array($reading_row, [6440, 6583, 7281, 7415])) {
					$entity->kodeRekening3 = "06";
				}

				if ($reading_row >= 7572 && $reading_row <= 8456) {
					$entity->kodeRekening1 = "2";
					$entity->kodeRekening2 = "1";
					$entity->kodeRekening3 = "06";
					$entity->kodeRekening4 = "02";
					$entity->kodeRekening5 = "03";
				}

				if (in_array($reading_row, [8949, 8951, 8953, 8955])) {
					$entity->kodeRekening1 = "2";
					$entity->kodeRekening2 = "1";
					$entity->kodeRekening3 = "06";
					$entity->kodeRekening4 = "07";
					$entity->kodeRekening5 = "05";
				}

				if ($reading_row >= 5423 && $reading_row <= 10540) {
					$entity->kodeRekening1 = "2";
					$entity->kodeRekening2 = "1";
				}

				if (in_array($reading_row, [10433])) {
					$entity->kodeRekening6 = "002";
				}

				if (in_array($reading_row, [10436])) {
					$entity->kodeRekening3 = "07";
				}

				if (in_array($reading_row, [10568])) {
					$entity->kodeRekening6 = "002";
				}

				if (in_array($reading_row, [10628, 10630])) {
					$entity->kodeRekening5 = "02";
				}

				$entity->generateIdAndKode();

				$splitID = [];
				$splitID[] = $entity->kodeRekening1;
				if ($entity->kodeRekening2 !== null) {
					$splitID[] = $entity->kodeRekening2;
					if ($entity->kodeRekening3 !== null) {
						$splitID[] = $entity->kodeRekening3;
						if ($entity->kodeRekening4 !== null) {
							$splitID[] = $entity->kodeRekening4;
							if ($entity->kodeRekening5 !== null) {
								$splitID[] = $entity->kodeRekening5;
								if ($entity->kodeRekening6 !== null) {
									$splitID[] = $entity->kodeRekening6;
								}
							}
						}
					}
				}

				for ($level = 1; $level <= sizeof($splitID); $level++) {
					$checkID = implode(".", array_slice($splitID, 0, $level));
					if ($entity->kode != $checkID && !in_array($checkID, $cacheID)) {
						switch ($level) {
							case 1:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_1, $checkID);
							case 2:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_2, $checkID);
							case 3:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_3, $checkID);
							case 4:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_4, $checkID);
							case 5:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_5, $checkID);
							case 6:
								throw new RekeningNotFoundException(RekeningNotFoundException::NERACA, RekeningNotFoundException::LEVEL_6, $checkID);
						}
					}
				}

				if (in_array($entity->kode, $cacheID)) {
					if (in_array($reading_row, [7572, 8259])) {
						$entity->updatedAt = $entity->createdAt;
						$entity->updatedBy = $entity->createdBy;
					} else {
						switch (sizeof(explode(".", $entity->kode))) {
							case 1:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_1, $entity->kode);
							case 2:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_2, $entity->kode);
							case 3:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_3, $entity->kode);
							case 4:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_4, $entity->kode);
							case 5:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_5, $entity->kode);
							case 6:
								throw new RekeningExistsException(RekeningExistsException::NERACA, RekeningExistsException::LEVEL_6, $entity->kode);
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

				echo PHP_EOL;

				echo "if (in_array(\$reading_row, [$reading_row])) {" . PHP_EOL;
				echo "\t" . "\$entity->kodeRekening = \"XX\";" . PHP_EOL;
				echo "}" . PHP_EOL;
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

		$worksheet->setTitle("H");

		$rowNum = 1;
		$worksheet->getRowDimension($rowNum)->setRowHeight(48);
		$worksheet->getCell([1, $rowNum])->setValueExplicit($worksheet->getTitle() . ".");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("KLASIFIKASI, KODEFIKASI, DAN NOMENKLATUR REKENING NERACA");
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