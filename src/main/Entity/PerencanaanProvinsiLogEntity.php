<?php

namespace RendyRobbani\Keuangan\Entity;

use RendyRobbani\PHP\Persistence\Column;
use RendyRobbani\PHP\Persistence\Entity;
use RendyRobbani\PHP\Persistence\Id;

#[Entity(table: "perencanaan_provinsi_log")]
class PerencanaanProvinsiLogEntity
{
	#[Id(isGeneratedValue: true)]
	#[Column(name: "id", type: "bigint", size: "20")]
	public int|null $id;

	#[Column(name: "id_reference", type: "varchar", size: "15")]
	public string|null $idReference;

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

	#[Column(name: "logged_at", type: "date")]
	public string|null $loggedAt;

	#[Column(name: "logged_by", type: "varchar", size: "255")]
	public string|null $loggedBy;

	public function __construct()
	{
		$this->id = null;
		$this->idReference = null;
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
		$this->loggedAt = null;
		$this->loggedBy = null;
	}
}