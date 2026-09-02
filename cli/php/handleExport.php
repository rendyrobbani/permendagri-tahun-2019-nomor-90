<?php

ini_set("memory_limit", "-1");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RendyRobbani\Keuangan\Service\DefaultService;
use RendyRobbani\Keuangan\Service\FungsiService;
use RendyRobbani\Keuangan\Service\LoService;
use RendyRobbani\Keuangan\Service\LraService;
use RendyRobbani\Keuangan\Service\NeracaService;
use RendyRobbani\Keuangan\Service\PerencanaanKabupatenService;
use RendyRobbani\Keuangan\Service\PerencanaanProvinsiService;
use RendyRobbani\Keuangan\Service\SumberService;
use RendyRobbani\PHP\Application;

require_once __DIR__ . "/../../vendor/autoload.php";

Application::setConfig(__DIR__ . "/../res/application-test.json");

/** @var DefaultService[] $services */
$services = [];
$services[] = Application::getComponent(PerencanaanProvinsiService::class);
$services[] = Application::getComponent(PerencanaanKabupatenService::class);
$services[] = Application::getComponent(FungsiService::class);
$services[] = Application::getComponent(SumberService::class);
$services[] = Application::getComponent(NeracaService::class);
$services[] = Application::getComponent(LraService::class);
$services[] = Application::getComponent(LoService::class);

$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
$spreadsheet->getDefaultStyle()->getFont()->setName("Bookman Old Style")->setSize(12);
for ($i = 0; $i < sizeof($services); $i++) {
	$services[$i]->intoXlsx($i === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet());
}

$filename = __DIR__ . "/../rel/permendagri-tahun-2019-nomor-90.xlsx";
if (!file_exists(dirname($filename))) mkdir(dirname($filename), 0777, true);
new Xlsx($spreadsheet)->save($filename);