<?php

namespace RendyRobbani\Keuangan\Entity;

use RendyRobbani\PHP\Persistence\Column;
use RendyRobbani\PHP\Persistence\Entity;
use RendyRobbani\PHP\Persistence\Id;

#[Entity(table: "lo_log")]
class LoLogEntity
{
	#[Id(isGeneratedValue: true)]
	#[Column(name: "id", type: "bigint", size: "20")]
	public int|null $id;

	#[Column(name: "id_reference", type: "varchar", size: "16")]
	public string|null $idReference;

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

	#[Column(name: "logged_at", type: "date")]
	public string|null $loggedAt;

	#[Column(name: "logged_by", type: "varchar", size: "255")]
	public string|null $loggedBy;

	public function __construct()
	{
		$this->id = null;
		$this->idReference = null;
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
		$this->loggedAt = null;
		$this->loggedBy = null;
	}
}