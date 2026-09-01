<?php

namespace RendyRobbani\Keuangan\Entity;

use RendyRobbani\PHP\Persistence\Column;
use RendyRobbani\PHP\Persistence\Entity;
use RendyRobbani\PHP\Persistence\Id;

#[Entity(table: "fungsi")]
class FungsiEntity extends AbstractEntity
{
	#[Id]
	#[Column(name: "id", type: "varchar", size: "5")]
	public string|null $id;

	#[Column(name: "kode", type: "varchar", size: "5")]
	public string|null $kode;

	#[Column(name: "kode_fungsi", type: "varchar", size: "2")]
	public string|null $kodeFungsi;

	#[Column(name: "kode_subfungsi", type: "varchar", size: "2")]
	public string|null $kodeSubfungsi;

	#[Column(name: "nama", type: "varchar", size: "255")]
	public string|null $nama;

	#[Column(name: "keterangan", type: "varchar", size: "30")]
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
		$this->kodeFungsi = null;
		$this->kodeSubfungsi = null;
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
		$this->kodeFungsi = strtoupper($this->kodeFungsi);
		$this->kodeFungsi = str_pad($this->kodeFungsi, 2, "0", STR_PAD_LEFT);
		$kode[] = $this->kodeFungsi;
		if ($this->kodeSubfungsi !== null) {
			$this->kodeSubfungsi = str_pad($this->kodeSubfungsi, 2, "0", STR_PAD_LEFT);
			$kode[] = $this->kodeSubfungsi;
		}
		$this->kode = implode(".", $kode);
		$this->id = $this->kode;
	}

	public function log(): FungsiLogEntity
	{
		$logEntity = new FungsiLogEntity();
		$logEntity->idReference = $this->id;
		$logEntity->kode = $this->kode;
		$logEntity->kodeFungsi = $this->kodeFungsi;
		$logEntity->kodeSubfungsi = $this->kodeSubfungsi;
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