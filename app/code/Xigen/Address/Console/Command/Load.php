<?php

namespace Xigen\Address\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Load command
 */
class Load extends Command
{
    private $logger;
    private $state;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Customer\Model\AddressFactory $addressFactory
    ) {
        $this->logger = $logger;
        $this->state = $state;
        $this->dateTime = $dateTime;
        $this->addressFactory = $addressFactory;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);

        $this->output->writeln('[' . $this->dateTime->gmtDate() . '] Start');

        // $address = $this->addressFactory->create()->load('backoffice_id', 123456);
        // $address = $this->addressFactory->create()->load(123456, 'backoffice_id');

        
        $address = $this->addressFactory->create()
            ->getCollection()
            ->addFieldToFilter('backoffice_id', ['eq' => 123456])
            ->getFirstItem();
        

        if ($address && $address->getId()) {
            $this->output->writeln('[' . $this->dateTime->gmtDate() . '] ' . $address->getCity());
        }

        $this->output->writeln('[' . $this->dateTime->gmtDate() . '] Finish');
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("xigen:address:load");
        $this->setDescription("Load customer address by custom attribute");
        parent::configure();
    }
}
