<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $serialNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $barcode_number;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $cpu;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $memory;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $rems_id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $name;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="devices")
    * @ORM\JoinColumn(nullable=true)
    */
    private $model;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="devices")
    * @ORM\JoinColumn(nullable=true)
    */
    private $site;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Rack", inversedBy="devices")
    * @ORM\JoinColumn(nullable=true)
    */
    private $rack;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=10, nullable=true)
     *
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $mount_type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $backup_type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $cluster;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $unique_id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $parent_unique_id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $system_type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $machine_category;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $machine_type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $platform;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $c_business_sector;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $business_area;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $c_system_alias_name;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $vob_id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $tcr_scap_id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $storage_type;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $database_type;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     *
     */
    private $commodity_device;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $terminal_server;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $device_avail_mntrng;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $device_avail_mntrng_tier;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $application_avail_mntrng;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $application_avail_mntrng_tier;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=100, nullable=true)
     *
     */
    private $po;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=20, nullable=true)
     *
     */
    private $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @param string $serialNumber
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getBackupType()
    {
        return $this->backup_type;
    }

    /**
     * @param string $backup_type
     */
    public function setBackupType($backup_type)
    {
        $this->backup_type = $backup_type;
    }

    public function getBarcodeNumber(): ?string
    {
        return $this->barcode_number;
    }

    public function setBarcodeNumber(string $barcode_number): self
    {
        $this->barcode_number = $barcode_number;

        return $this;
    }

    public function getCpu(): ?string
    {
        return $this->cpu;
    }

    public function setCpu(string $cpu): self
    {
        $this->cpu = $cpu;

        return $this;
    }

    public function getMemory(): ?string
    {
        return $this->memory;
    }

    public function setMemory(string $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getRemsId(): ?string
    {
        return $this->rems_id;
    }

    public function setRemsId(string $rems_id): self
    {
        $this->rems_id = $rems_id;

        return $this;
    }

    public function getMountType(): ?string
    {
        return $this->mount_type;
    }

    public function setMountType(string $mount_type): self
    {
        $this->mount_type = $mount_type;

        return $this;
    }

    public function getCluster(): ?string
    {
        return $this->cluster;
    }

    public function setCluster(string $cluster): self
    {
        $this->cluster = $cluster;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->unique_id;
    }

    public function setUniqueId(string $unique_id): self
    {
        $this->unique_id = $unique_id;

        return $this;
    }

    public function getParentUniqueId(): ?string
    {
        return $this->parent_unique_id;
    }

    public function setParentUniqueId(string $parent_unique_id): self
    {
        $this->parent_unique_id = $parent_unique_id;

        return $this;
    }

    public function getSystemType(): ?string
    {
        return $this->system_type;
    }

    public function setSystemType(string $system_type): self
    {
        $this->system_type = $system_type;

        return $this;
    }

    public function getMachineCategory(): ?string
    {
        return $this->machine_category;
    }

    public function setMachineCategory(string $machine_category): self
    {
        $this->machine_category = $machine_category;

        return $this;
    }

    public function getMachineType(): ?string
    {
        return $this->machine_type;
    }

    public function setMachineType(string $machine_type): self
    {
        $this->machine_type = $machine_type;

        return $this;
    }

    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function getCBusinessSector(): ?string
    {
        return $this->c_business_sector;
    }

    public function setCBusinessSector(string $c_business_sector): self
    {
        $this->c_business_sector = $c_business_sector;

        return $this;
    }

    public function getBusinessArea(): ?string
    {
        return $this->business_area;
    }

    public function setBusinessArea(string $business_area): self
    {
        $this->business_area = $business_area;

        return $this;
    }

    public function getCSystemAliasName(): ?string
    {
        return $this->c_system_alias_name;
    }

    public function setCSystemAliasName(string $c_system_alias_name): self
    {
        $this->c_system_alias_name = $c_system_alias_name;

        return $this;
    }

    public function getVobId(): ?string
    {
        return $this->vob_id;
    }

    public function setVobId(string $vob_id): self
    {
        $this->vob_id = $vob_id;

        return $this;
    }

    public function getTcrScapId(): ?string
    {
        return $this->tcr_scap_id;
    }

    public function setTcrScapId(string $tcr_scap_id): self
    {
        $this->tcr_scap_id = $tcr_scap_id;

        return $this;
    }

    public function getStorageType(): ?string
    {
        return $this->storage_type;
    }

    public function setStorageType(string $storage_type): self
    {
        $this->storage_type = $storage_type;

        return $this;
    }

    public function getDatabaseType(): ?string
    {
        return $this->database_type;
    }

    public function setDatabaseType(string $database_type): self
    {
        $this->database_type = $database_type;

        return $this;
    }

    public function getCommodityDevice(): ?bool
    {
        return $this->commodity_device;
    }

    public function setCommodityDevice(bool $commodity_device): self
    {
        $this->commodity_device = $commodity_device;

        return $this;
    }

    public function getTerminalServer(): ?string
    {
        return $this->terminal_server;
    }

    public function setTerminalServer(string $terminal_server): self
    {
        $this->terminal_server = $terminal_server;

        return $this;
    }

    public function getDeviceAvailMntrng(): ?string
    {
        return $this->device_avail_mntrng;
    }

    public function setDeviceAvailMntrng(string $device_avail_mntrng): self
    {
        $this->device_avail_mntrng = $device_avail_mntrng;

        return $this;
    }

    public function getDeviceAvailMntrngTier(): ?string
    {
        return $this->device_avail_mntrng_tier;
    }

    public function setDeviceAvailMntrngTier(string $device_avail_mntrng_tier): self
    {
        $this->device_avail_mntrng_tier = $device_avail_mntrng_tier;

        return $this;
    }

    public function getApplicationAvailMntrng(): ?string
    {
        return $this->application_avail_mntrng;
    }

    public function setApplicationAvailMntrng(string $application_avail_mntrng): self
    {
        $this->application_avail_mntrng = $application_avail_mntrng;

        return $this;
    }

    public function getApplicationAvailMntrngTier(): ?string
    {
        return $this->application_avail_mntrng_tier;
    }

    public function setApplicationAvailMntrngTier(string $application_avail_mntrng_tier): self
    {
        $this->application_avail_mntrng_tier = $application_avail_mntrng_tier;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getRack(): ?Rack
    {
        return $this->rack;
    }

    public function setRack(?Rack $rack): self
    {
        $this->rack = $rack;

        return $this;
    }

    public function getPo(): ?string
    {
        return $this->po;
    }

    public function setPo(?string $po): self
    {
        $this->po = $po;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }


}
