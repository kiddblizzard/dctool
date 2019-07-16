<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Entity\Rack;
use App\Entity\Device;
use App\Entity\Model;
use App\Entity\Manufacturer;
use App\Controller\Traits\HasRepositories;
use App\Service\FileUploader;

class RackController extends Controller
{
    use HasRepositories;

    /**
     * show the list of devices by rack
     * @Route("/racks/{rack}/devices", name="rack_devices")
     * @param  Request $request [description]
     * @param  Rack $rack
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listDeviceByRack(Request $request, Rack $rack)
    {
        $devices =  $this->getDeviceRepository()
            ->findByRack($rack);

        $rackHigh = 42;
        $units = [];

        for($i=$rackHigh;$i>0;$i--) {
            $device = $this->getDeviceRepository()
                ->findByRackByTopUnit($rack, $i);
            $units[$i] = $device;

            if(!is_null($device)) {
                $i = $i - $device->getModel()->getHeight() + 1;
            }
        }

        return $this->render('rack/list.html.twig', array(
            'units' => $units,
            'navbar' => 'rack',
            'rack' => $rack,
        ));
    }

    /**
     * show the list of devices by rack
     * @Route("/racks/{rack}/units/{unit}", name="rack_unit")
     * @param  Request $request [description]
     * @param  Rack $rack
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getDeviceByRackByUnit(Rack $rack, $unit)
    {
        $rackHigh = 42;

        $device = $this->getDeviceRepository()
            ->findByRackByUnit($rack, $unit);

        if(is_null($device)) {
            return $this->render('rack/emptyUnit.html.twig');

        }
        else {

            return $this->render('rack/unit.html.twig', ['device' => $device]);
        }
    }
}
