<?php
namespace App\service\nav;

use App\Entity\MainNav;
use App\Repository\MainNavRepository;

class MainNavService
{
    private $mainNavRepository;
    public function __construct(MainNavRepository $mainNavRepository)
    {
        $this->mainNavRepository = $mainNavRepository;
    }

    public function findAll():array
    {
        return $this->mainNavRepository->findAll();
    }
}