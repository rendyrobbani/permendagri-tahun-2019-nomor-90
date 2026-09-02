<?php

ini_set("memory_limit", "-1");

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use RendyRobbani\Keuangan\Service\FungsiService;
use RendyRobbani\Keuangan\Service\LoService;
use RendyRobbani\Keuangan\Service\LraService;
use RendyRobbani\Keuangan\Service\NeracaService;
use RendyRobbani\Keuangan\Service\PerencanaanKabupatenService;
use RendyRobbani\Keuangan\Service\PerencanaanProvinsiService;
use RendyRobbani\Keuangan\Service\SumberService;
use RendyRobbani\PHP\Application;

require_once __DIR__ . "/../../vendor/autoload.php";

Application::setConfig(__DIR__ . "/../../res/application-test.json");
Application::getComponent(PerencanaanProvinsiService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/Perencanaan-Provinsi.xlsx"))->getActiveSheet());
Application::getComponent(PerencanaanKabupatenService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/Perencanaan-Kabupaten.xlsx"))->getActiveSheet());
Application::getComponent(FungsiService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/Fungsi.xlsx"))->getActiveSheet());
Application::getComponent(SumberService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/Sumber.xlsx"))->getActiveSheet());
Application::getComponent(NeracaService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/Neraca.xlsx"))->getActiveSheet());
Application::getComponent(LraService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/LRA.xlsx"))->getActiveSheet());
Application::getComponent(LoService::class)->fromXlsx((new Xlsx()->load(__DIR__ . "/../xlsx/LO.xlsx"))->getActiveSheet());