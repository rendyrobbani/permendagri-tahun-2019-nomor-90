<?php

namespace RendyRobbani\Keuangan\Service;

use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use RendyRobbani\Keuangan\Entity\PerencanaanKabupatenEntity;
use RendyRobbani\Keuangan\Entity\PerencanaanKabupatenLogEntity;
use RendyRobbani\Keuangan\Exception\BidangExistsException;
use RendyRobbani\Keuangan\Exception\BidangNotFoundException;
use RendyRobbani\Keuangan\Exception\KegiatanExistsException;
use RendyRobbani\Keuangan\Exception\KegiatanNotFoundException;
use RendyRobbani\Keuangan\Exception\ProgramExistsException;
use RendyRobbani\Keuangan\Exception\ProgramNotFoundException;
use RendyRobbani\Keuangan\Exception\SubkegiatanExistsException;
use RendyRobbani\Keuangan\Exception\SubkegiatanNotFoundException;
use RendyRobbani\Keuangan\Exception\UrusanExistsException;
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
			$this->logRepository->deleteAll();

			/** @var array<string, PerencanaanKabupatenEntity> $intoEntities */
			$intoEntities = [];

			/** @var PerencanaanKabupatenLogEntity[] $logEntities */
			$logEntities = [];

			for ($rowNum = 4; $rowNum <= $worksheet->getHighestRow(); $rowNum++) {
				echo "Reading row  : " . $rowNum . PHP_EOL;

				$rowValues = PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $rowNum, 1, 6);
				$rowChecks = array_map(fn($value) => $value === null || trim($value) === "" ? 0 : 1, $rowValues);
				$rowChecks = array_sum($rowChecks);
				if ($rowChecks === 0) continue;

				$intoEntity = new PerencanaanKabupatenEntity();
				for ($i = 0; $i < 6; $i++) {
					$value = $rowValues[$i];
					if ($i < 2 && $value !== null) $value = strtoupper($value);
					switch ($i + 1) {
						case 1:
							$intoEntity->kodeUrusan = $value;
							break;
						case 2:
							$intoEntity->kodeBidang = $value;
							break;
						case 3:
							$intoEntity->kodeProgram = $value;
							break;
						case 4:
							$intoEntity->kodeKegiatan = $value;
							break;
						case 5:
							$intoEntity->kodeSubkegiatan = $value;
							break;
						case 6:
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
					$nextRowValues = $nextRowNum <= $worksheet->getHighestRow() ? PhpSpreadsheetUtil::getCellValuesAsStringFromRow($worksheet, $nextRowNum, 1, 6) : [];
					$nextRowChecks = array_map(fn($value) => $value === null || trim($value) === "" ? 0 : 1, $nextRowValues);
					$nextRowChecks = array_slice($nextRowChecks, 0, 5);
					$nextRowChecks = array_sum($nextRowChecks);
					$nextNama = $nextRowValues[5] ?? null;
					if ($nextRowChecks > 0 || $nextNama === null) break;
					else {
						if ($intoEntity->keterangan !== null) $intoEntity->keterangan = PhpSpreadsheetUtil::cleanValue($intoEntity->keterangan . " " . $nextNama);
						else {
							if (str_starts_with(trim(strtolower($nextNama)), "tidak ada kewenangan")) {
								$intoEntity->keterangan = $nextNama;
							} else {
								$intoEntity->nama = PhpSpreadsheetUtil::cleanValue($intoEntity->nama . " " . $nextNama);
							}
						}
					}
				}

				if ($rowNum === 1406) {
					$intoEntity->kodeProgram = "03";
				}

				if ($rowNum === 1627) {
					$intoEntity->kodeUrusan = "2";
				}

				if ($rowNum === 1795) {
					$intoEntity->kodeProgram = "03";
				}

				$intoEntity->generateIdAndKode();

				$level = match (sizeof(explode(".", $intoEntity->kode))) {
					1 => 1,
					2 => 2,
					3 => 3,
					5 => 4,
					6 => 5,
				};

				for ($i = 1; $i <= $level; $i++) {
					$kode = [];
					if ($i >= 1) $kode[] = $intoEntity->kodeUrusan;
					if ($i >= 2) $kode[] = $intoEntity->kodeBidang;
					if ($i >= 3) $kode[] = $intoEntity->kodeProgram;
					if ($i >= 4) $kode[] = $intoEntity->kodeKegiatan;
					if ($i >= 5) $kode[] = $intoEntity->kodeSubkegiatan;

					$kode = implode(".", $kode);

					if ($i < $level) {
						if (isset($intoEntities[$kode])) continue;
						else {
							switch ($i) {
								case 1:
									throw new UrusanNotFoundException($kode);
								case 2:
									throw new BidangNotFoundException($kode);
								case 3:
									throw new ProgramNotFoundException($kode);
								case 4:
									throw new KegiatanNotFoundException($kode);
								case 5:
									throw new SubkegiatanNotFoundException($kode);
							}
						}
					}

					if (isset($intoEntities[$kode])) {
						switch ($i) {
							case 1:
								throw new UrusanExistsException($kode);
							case 2:
								throw new BidangExistsException($kode);
							case 3:
								throw new ProgramExistsException($kode);
							case 4:
								throw new KegiatanExistsException($kode);
							case 5:
								throw new SubkegiatanExistsException($kode);
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