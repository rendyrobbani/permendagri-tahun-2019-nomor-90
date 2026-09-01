<?php

namespace RendyRobbani\Keuangan\Entity;

use RendyRobbani\PHP\Persistence\Column;
use RendyRobbani\PHP\Persistence\Entity;
use RendyRobbani\PHP\Persistence\Id;

#[Entity(table: "neraca")]
class NeracaEntity extends AbstractEntity
{
	#[Id]
	#[Column(name: "id", type: "varchar", size: "16")]
	public string|null $id;

	#[Column(name: "kode", type: "varchar", size: "16")]
	public string|null $kode;

	#[Column(name: "kode_rekening1", type: "varchar", size: "1")]
	public string|null $kodeRekening1;

	#[Column(name: "kode_rekening2", type: "varchar", size: "1")]
	public string|null $kodeRekening2;

	#[Column(name: "kode_rekening3", type: "varchar", size: "2")]
	public string|null $kodeRekening3;

	#[Column(name: "kode_rekening4", type: "varchar", size: "2")]
	public string|null $kodeRekening4;

	#[Column(name: "kode_rekening5", type: "varchar", size: "2")]
	public string|null $kodeRekening5;

	#[Column(name: "kode_rekening6", type: "varchar", size: "3")]
	public string|null $kodeRekening6;

	#[Column(name: "nama", type: "varchar", size: "255")]
	public string|null $nama;

	#[Column(name: "keterangan", type: "varchar", size: "961")]
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
		$this->kodeRekening1 = null;
		$this->kodeRekening2 = null;
		$this->kodeRekening3 = null;
		$this->kodeRekening4 = null;
		$this->kodeRekening5 = null;
		$this->kodeRekening6 = null;
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
		if ($this->kodeRekening1 !== null) {
			$kode[] = $this->kodeRekening1;
			if ($this->kodeRekening2 !== null) {
				$kode[] = $this->kodeRekening2;
				if ($this->kodeRekening3 !== null) {
					$this->kodeRekening3 = str_pad($this->kodeRekening3, 2, "0", STR_PAD_LEFT);
					$kode[] = $this->kodeRekening3;
					if ($this->kodeRekening4 !== null) {
						$this->kodeRekening4 = str_pad($this->kodeRekening4, 2, "0", STR_PAD_LEFT);
						$kode[] = $this->kodeRekening4;
						if ($this->kodeRekening5 !== null) {
							$this->kodeRekening5 = str_pad($this->kodeRekening5, 2, "0", STR_PAD_LEFT);
							$kode[] = $this->kodeRekening5;
							if ($this->kodeRekening6 !== null) {
								$this->kodeRekening6 = str_pad($this->kodeRekening6, 3, "0", STR_PAD_LEFT);
								$kode[] = $this->kodeRekening6;
							}
						}
					}
				}
			}
		}
		$this->kode = implode(".", $kode);
		$this->id = $this->kode;
	}

	public function log(): NeracaLogEntity
	{
		$logEntity = new NeracaLogEntity();
		$logEntity->idReference = $this->id;
		$logEntity->kode = $this->kode;
		$logEntity->kodeRekening1 = $this->kodeRekening1;
		$logEntity->kodeRekening2 = $this->kodeRekening2;
		$logEntity->kodeRekening3 = $this->kodeRekening3;
		$logEntity->kodeRekening4 = $this->kodeRekening4;
		$logEntity->kodeRekening5 = $this->kodeRekening5;
		$logEntity->kodeRekening6 = $this->kodeRekening6;
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