<?php
namespace App\Command;
use App\Entity\Product;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;

class lookUpCommand extends Command
{
    protected static $defaultName = 'app:products';
    /** @var EntityManagerInterface $em */
    private $em;

    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct(ProductRepository $productRepository, EntityManagerInterface $em)
    {
        $this->productRepository = $productRepository;
        $this->em = $em;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Products')
            ->setHelp('Product info')
            ->addArgument('duration', InputArgument::REQUIRED, 'days')
            ->addArgument('status', InputArgument::OPTIONAL, 'status');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $days = $input->getArgument('duration');
        $status = $input->getArgument('status');

        try {
            $products = $this->productRepository->getProductByDays($days,$status);

            if(!empty($products)) {
                /** @var Product $product */
                foreach($products as $product) {
                    $output->writeln("product with issn {$product->getIssn()} is older than {$days} day(s) with status {$product->getStatus()}");
                }
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }



    }

}