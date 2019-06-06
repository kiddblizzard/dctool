<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Receiving;
use App\Constants\BauStatusOptions;
use App\Controller\Traits\HasRepositories;

class SiteController extends Controller
{
    use HasRepositories;

    
}
