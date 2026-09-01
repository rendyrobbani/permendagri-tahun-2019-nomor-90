<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\PerencanaanKabupatenEntity;
use RendyRobbani\Keuangan\Exception\BidangNotFoundException;
use RendyRobbani\Keuangan\Exception\KegiatanNotFoundException;
use RendyRobbani\Keuangan\Exception\ProgramNotFoundException;
use RendyRobbani\Keuangan\Exception\SubkegiatanNotFoundException;
use RendyRobbani\Keuangan\Exception\UrusanNotFoundException;
use RendyRobbani\Keuangan\Repository\PerencanaanKabupatenLogRepository;
use RendyRobbani\Keuangan\Repository\PerencanaanKabupatenRepository;
use RendyRobbani\Keuangan\Util\PhpSpreadsheetUtil;
use RendyRobbani\PHP\Configuration\Configuration;
use RendyRobbani\PHP\Connection\Connection;

final readonly class PerencanaanKabupatenServiceImpl implements PerencanaanKabupatenService
{
	/**
	 * @param Connection $connection
	 * @param PerencanaanKabupatenRepository $repository
	 * @param PerencanaanKabupatenLogRepository $logRepository
	 * @param string $referensi
	 * @param string $penetapan
	 */
	public function __construct(protected Connection                                     $connection,
	                            protected PerencanaanKabupatenRepository                 $repository,
	                            protected PerencanaanKabupatenLogRepository              $logRepository,
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

				$row0 = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 7);
				if ($row0[0] == null || !preg_match("/^[X1-9]+$/", $row0[0])) continue;

				$entity = new PerencanaanKabupatenEntity();

				$entity->kodeUrusan = $row0[0];
				$entity->kodeUrusan = $entity->kodeUrusan == null ? null : strtoupper($entity->kodeUrusan);

				$entity->kodeBidang = $row0[1] ?? null;
				$entity->kodeBidang = $entity->kodeBidang == null ? null : strtoupper($entity->kodeBidang);

				$entity->kodeProgram = $row0[2] ?? null;
				$entity->kodeKegiatan = $row0[3] ?? null;
				$entity->kodeSubkegiatan = $row0[4] ?? null;
				$entity->nama = $row0[5] ?? null;
				$entity->createdAt = $this->penetapan;
				$entity->createdBy = $this->referensi;
				$entity->isDeleted = false;
				$entity->generateIdAndKode();

				if ($entity->kode === "2.16.17.2.02.05" && $entity->nama === "Koordinasi dan Sinkronisasi Sistem Keamanan Informasi") {
					$entity->kodeProgram = "03";
				}

				if ($entity->kode === "3.27.04.2.04" && $entity->nama === "Pengembangan Lahan Penggembalaan Umum") {
					$entity->kodeProgram = "03";
				}

				$entity->generateIdAndKode();

				$rowNum1 = $rowNum;
				while (true) {
					$rowNum1++;
					$row1 = $rowNum1 <= $worksheet->getHighestRow() ? PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum1, 1, 7) : [];
					$nextKode = $row1[0] ?? null;
					$nextNama = $row1[5] ?? null;
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
				$splitID[] = $entity->kodeUrusan;
				if ($entity->kodeBidang !== null) {
					$splitID[] = $entity->kodeBidang;
					if ($entity->kodeProgram !== null) {
						$splitID[] = $entity->kodeProgram;
						if ($entity->kodeKegiatan !== null) {
							$splitID[] = $entity->kodeKegiatan;
							if ($entity->kodeSubkegiatan !== null) {
								$splitID[] = $entity->kodeSubkegiatan;
							}
						}
					}
				}

				for ($level = 1; $level <= sizeof($splitID); $level++) {
					$checkID = implode(".", array_slice($splitID, 0, $level));
					if ($entity->kode != $checkID && !in_array($checkID, $cacheID)) {
						switch ($level) {
							case 1:
								throw new UrusanNotFoundException($checkID);
							case 2:
								throw new BidangNotFoundException($checkID);
							case 3:
								throw new ProgramNotFoundException($checkID);
							case 4:
								throw new KegiatanNotFoundException($checkID);
							case 5:
								throw new SubkegiatanNotFoundException($checkID);
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

		$worksheet->setTitle("C");

		$rowNum = 1;
		$worksheet->getRowDimension($rowNum)->setRowHeight(48);
		$worksheet->getCell([1, $rowNum])->setValueExplicit($worksheet->getTitle() . ".");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("KLASIFIKASI, KODEFIKASI, DAN NOMENKLATUR PERENCANAAN PEMBANGUNAN DAN KEUANGAN DAERAH KABUPATEN/KOTA");
		$worksheet->mergeCells("B$rowNum:F$rowNum");
		for ($i = 0; $i < 6; $i++) {
			$worksheet->getStyle([$i + 1, $rowNum])->getAlignment()
				->setHorizontal(Alignment::HORIZONTAL_GENERAL)
				->setWrapText(true);
		}

		$rowNum = $rowNum + 2;
		$worksheet->getCell([1, $rowNum])->setValueExplicit("KODE");
		$worksheet->mergeCells("A$rowNum:E$rowNum");

		$worksheet->getCell([6, $rowNum])->setValueExplicit("NOMENKLATUR URUSAN KABUPATEN/KOTA");
		$worksheet->mergeCells("F$rowNum:F" . $rowNum + 1);

		$rowNum++;
		$worksheet->getRowDimension($rowNum)->setRowHeight(96);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(1))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(2))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(3))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(4))->setWidth(6);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(5))->setWidth(5);
		$worksheet->getColumnDimension(Coordinate::stringFromColumnIndex(6))->setWidth(41);

		$worksheet->getCell([1, $rowNum])->setValueExplicit("URUSAN");
		$worksheet->getCell([2, $rowNum])->setValueExplicit("BIDANG URUSAN");
		$worksheet->getCell([3, $rowNum])->setValueExplicit("PROGRAM");
		$worksheet->getCell([4, $rowNum])->setValueExplicit("KEGIATAN");
		$worksheet->getCell([5, $rowNum])->setValueExplicit("SUB KEGIATAN");

		$worksheet->getPageSetup()->setRowsToRepeatAtTop([$rowNum - 1, $rowNum + 1]);

		for ($r = $rowNum - 1; $r <= $rowNum; $r++) {
			for ($c = 1; $c <= 6; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal(Alignment::HORIZONTAL_CENTER)
					->setVertical(Alignment::VERTICAL_CENTER)
					->setTextRotation($r == $rowNum && $c < 6 ? 90 : 0)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}

		$fromRow = $rowNum + 1;
		foreach ($this->repository->findAll() as $entity) {
			if ($entity->kodeUrusan != null && $entity->kodeBidang == null) $rowNum++;

			$rowNum++;
			for ($colNum = 1; $colNum <= 6; $colNum++) {
				$value = match ($colNum) {
					1 => $entity->kodeUrusan,
					2 => $entity->kodeBidang,
					3 => $entity->kodeProgram,
					4 => $entity->kodeKegiatan,
					5 => $entity->kodeSubkegiatan,
					6 => $entity->nama,
				};
				if ($value != null) $worksheet->getCell([$colNum, $rowNum])->setValueExplicit($value);
			}

			if ($entity->keterangan != null) {
				$rowNum++;
				$worksheet->getCell([6, $rowNum])->setValueExplicit($entity->keterangan);
			}
		}
		$intoRow = $rowNum + 1;

		for ($r = $fromRow; $r <= $intoRow; $r++) {
			for ($c = 1; $c <= 6; $c++) {
				$style = $worksheet->getStyle([$c, $r]);
				$style->getAlignment()
					->setHorizontal($c < 6 ? Alignment::HORIZONTAL_CENTER : Alignment::HORIZONTAL_GENERAL)
					->setWrapText(true);
				$style->getBorders()
					->getAllBorders()
					->setBorderStyle(Border::BORDER_THIN);
			}
		}
	}
}