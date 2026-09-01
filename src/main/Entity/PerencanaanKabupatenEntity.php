<?php

namespace RendyRobbani\Keuangan\Entity;

use RendyRobbani\PHP\Persistence\Column;
use RendyRobbani\PHP\Persistence\Entity;
use RendyRobbani\PHP\Persistence\Id;

#[Entity(table: "perencanaan_kabupaten")]
class PerencanaanKabupatenEntity extends AbstractEntity
{
	#[Id]
	#[Column(name: "id", type: "varchar", size: "15")]
	public string|null $id;

	#[Column(name: "kode", type: "varchar", size: "15")]
	public string|null $kode;

	#[Column(name: "kode_urusan", type: "varchar", size: "1")]
	public string|null $kodeUrusan;

	#[Column(name: "kode_bidang", type: "varchar", size: "2")]
	public string|null $kodeBidang;

	#[Column(name: "kode_program", type: "varchar", size: "2")]
	public string|null $kodeProgram;

	#[Column(name: "kode_kegiatan", type: "varchar", size: "4")]
	public string|null $kodeKegiatan;

	#[Column(name: "kode_subkegiatan", type: "varchar", size: "2")]
	public string|null $kodeSubkegiatan;

	#[Column(name: "nama", type: "varchar", size: "345")]
	public string|null $nama;

	#[Column(name: "keterangan", type: "varchar", size: "255")]
	public string|null $keterangan;

	#[Column(name: "created_at", type: "date")]
	public string|null $createdAt;

	#[Column(name: "created_by", type: "varchar", size: "255")]
	public string|null $createdBy;

	#[Column(name: "updated_at", type: "date")]
	public string|null $updatedAt;

	#[Column(name: "updated_by", type: "varchar", size: "255")]
	public string|null $updatedBy;

	#[Column(name: "is_deleted", type: "bit", size: "1")]
	public bool|null $isDeleted;

	#[Column(name: "deleted_at", type: "date")]
	public string|null $deletedAt;

	#[Column(name: "deleted_by", type: "varchar", size: "255")]
	public string|null $deletedBy;

	public function __construct()
	{
		$this->id = null;
		$this->kode = null;
		$this->kodeUrusan = null;
		$this->kodeBidang = null;
		$this->kodeProgram = null;
		$this->kodeKegiatan = null;
		$this->kodeSubkegiatan = null;
		$this->nama = null;
		$this->keterangan = null;
		$this->createdAt = null;
		$this->createdBy = null;
		$this->updatedAt = null;
		$this->updatedBy = null;
		$this->isDeleted = null;
		$this->deletedAt = null;
		$this->deletedBy = null;
	}

	public function toString(): string
	{
		$return = [];
		$return[] = $this->kode === null ? "" : strtolower($this->kode);
		$return[] = $this->nama === null ? "" : strtolower($this->nama);
		$return[] = $this->keterangan === null ? "" : strtolower($this->keterangan);
		return preg_replace("/[^0-9a-z|]+/i", "", implode("|", $return));
	}

	public function generateIdAndKode(): void
	{
		$kode = [];
		if ($this->kodeUrusan !== null) {
			$this->kodeUrusan = strtoupper($this->kodeUrusan);
			$kode[] = $this->kodeUrusan;
			if ($this->kodeBidang !== null) {
				$this->kodeBidang = strtoupper($this->kodeBidang);
				$this->kodeBidang = str_pad($this->kodeBidang, 2, "0", STR_PAD_LEFT);
				$kode[] = $this->kodeBidang;
				if ($this->kodeProgram !== null) {
					$this->kodeProgram = str_pad($this->kodeProgram, 2, "0", STR_PAD_LEFT);
					$kode[] = $this->kodeProgram;
					if ($this->kodeKegiatan !== null) {
						$kode[] = $this->kodeKegiatan;
						if ($this->kodeSubkegiatan !== null) {
							$this->kodeSubkegiatan = str_pad($this->kodeSubkegiatan, 2, "0", STR_PAD_LEFT);
							$kode[] = $this->kodeSubkegiatan;
						}
					}
				}
			}
		}
		$this->kode = implode(".", $kode);
		$this->id = str_replace("X", "0", $this->kode);
	}

	public function log(): PerencanaanKabupatenLogEntity
	{
		$logEntity = new PerencanaanKabupatenLogEntity();
		$logEntity->idReference = $this->id;
		$logEntity->kode = $this->kode;
		$logEntity->kodeUrusan = $this->kodeUrusan;
		$logEntity->kodeBidang = $this->kodeBidang;
		$logEntity->kodeProgram = $this->kodeProgram;
		$logEntity->kodeKegiatan = $this->kodeKegiatan;
		$logEntity->kodeSubkegiatan = $this->kodeSubkegiatan;
		$logEntity->nama = $this->nama;
		$logEntity->keterangan = $this->keterangan;
		$logEntity->createdAt = $this->createdAt;
		$logEntity->createdBy = $this->createdBy;
		$logEntity->updatedAt = $this->updatedAt;
		$logEntity->updatedBy = $this->updatedBy;
		$logEntity->isDeleted = $this->isDeleted;
		$logEntity->deletedAt = $this->deletedAt;
		$logEntity->deletedBy = $this->deletedBy;
		return $logEntity;
	}
}