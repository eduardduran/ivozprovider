<?php

namespace Ivoz\Provider\Domain\Service\Carrier;

use Ivoz\Core\Application\Service\EntityTools;
use Ivoz\Provider\Domain\Model\Carrier\CarrierInterface;
use Symfony\Bridge\Monolog\Logger;
use Ivoz\Provider\Domain\Model\Carrier\CarrierRepository;
use Ivoz\Provider\Domain\Service\BalanceMovement\CreateByCarrier;

abstract class AbstractBalanceOperation
{
    protected $entityTools;
    protected $logger;
    protected $client;
    protected $carrierRepository;
    protected $syncBalanceService;
    protected $lastError;
    protected $createBalanceMovementByCarrier;

    public function __construct(
        EntityTools $entityTools,
        Logger $logger,
        CarrierBalanceServiceInterface $client,
        CarrierRepository $carrierRepository,
        SyncBalances $syncBalanceService,
        CreateByCarrier $createByCarrier
    ) {
        $this->entityTools = $entityTools;
        $this->logger = $logger;
        $this->client = $client;
        $this->carrierRepository = $carrierRepository;
        $this->syncBalanceService = $syncBalanceService;
        $this->createBalanceMovementByCarrier = $createByCarrier;
    }

    /**
     * @param int $carrierId
     * @param float $amount
     * @return boolean
     */
    abstract public function execute($carrierId, float $amount);

    /**
     * @param string $amount
     * @param array $response
     * @param CarrierInterface $carrier
     * @return boolean
     */
    protected function handleResponse($amount, array $response, CarrierInterface $carrier)
    {
        if (!empty($response['error'])) {
            $this->lastError = $response['error'];
            $this->logger->error('Could not modify balance: ' . $response['error']);
        }

        $success = $response['success'];

        if ($success) {
            $brandId = $carrier->getBrand()->getId();
            $carrierIds = [$carrier->getId()];

            $this->syncBalanceService->updateCarriers($brandId, $carrierIds);

            // Get current balance status
            $balance = $this->client->getBalance($brandId, $carrier->getId());

            // Store this transaction in a BalanceMovement
            $this->createBalanceMovementByCarrier->execute(
                $carrier,
                $amount,
                $balance
            );
        }

        return $success;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }
}
