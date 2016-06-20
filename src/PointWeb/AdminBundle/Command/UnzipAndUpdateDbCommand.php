<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 14/02/2016
 * Time: 17:49
 */
namespace PointWeb\AdminBundle\Command;

use Doctrine\ORM\EntityManager;
use PointWeb\ProductBundle\Entity\Category;
use PointWeb\ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UnzipAndUpdateDbCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('pointweb:unzipandupdatedb')
            ->setDescription('Mettre à jour la base de donnée via un zip')
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'entrez le chemin du fichier'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('path');
        $zip = new \ZipArchive();
        if ($zip->open($path) === TRUE) {
            $zip->extractTo($this->getContainer()->get('kernel')->getRootDir() . '/../');
            $zip->close();
            $output->writeln('Extraction: OK');
            $inputFileName = $this->getContainer()->get('kernel')->getRootDir() . '/../backup/backup.xlsx';
            try {
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (\Exception $e) {
                die('Erreur en chargeant le fichier "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }
            $this->setCategories($objPHPExcel);
            $this->setProducts($objPHPExcel);
        } else {
            $output->writeln('Le fichier est soit corrompu soit inexistant');
        }
    }

    protected function setCategories(\PHPExcel $objPHPExcel)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('category');
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnNumber = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $highestRow = $objWorksheet->getHighestRow();
        $tab_attributes = array();
        for ($i = 0; $i < $highestColumnNumber; $i++) {
            $cell = $objWorksheet->getCellByColumnAndRow($i, 1);
            $val = $cell->getValue();
            $tab_attributes[$i] = $val;
        }
        for ($row = 2; $row <= $highestRow; $row++) {
            $category = new Category();
            for ($j = 0; $j < $highestColumnNumber; $j++) {
                $cell = $objWorksheet->getCellByColumnAndRow($j, $row);
                $val = $cell->getValue();
                switch ($tab_attributes[$j]) {
                    case "parent_id":
                        $parent = $em->getRepository('PointWebProductBundle:Category')->find($val);
                        $category->setParent($parent);
                        break;
                    default:
                        $category->{'set' . ucfirst($tab_attributes[$j])}($val);
                }
            }
            $em->persist($category);
        }
        $em->flush();
    }

    protected function setProducts(\PHPExcel $objPHPExcel)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $objWorksheet = $objPHPExcel->setActiveSheetIndexbyName('product');
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnNumber = \PHPExcel_Cell::columnIndexFromString($highestColumn);
        $highestRow = $objWorksheet->getHighestRow();
        $tab_attributes = array();
        for ($i = 0; $i < $highestColumnNumber; $i++) {
            $cell = $objWorksheet->getCellByColumnAndRow($i, 1);
            $val = $cell->getValue();
            $tab_attributes[$i] = $val;
        }
        for ($row = 2; $row <= $highestRow; $row++) {
            $product = new Product();
            for ($j = 0; $j < $highestColumnNumber; $j++) {
                $cell = $objWorksheet->getCellByColumnAndRow($j, $row);
                $val = $cell->getValue();
                $now = new \DateTime();
                switch ($tab_attributes[$j]) {
                    case "category_id":
                        $category = $em->getRepository('PointWebProductBundle:Category')->find($val);
                        $product->setCategory($category);
                        break;
                    case "promotion_price":
                        $product->setPromotionPrice($val);
                        break;
                    case "url_link":
                        $product->setUrlLink($val);
                        break;
                    case "promotion_start_date":
                        $promotionStartDate = new \DateTime();
                        $promotionStartDate->createFromFormat('Y-m-d', $val);
                        $product->setPromotionStartDate($promotionStartDate);
                        break;
                    case "promotion_end_date":
                        $promotionEndDate = new \DateTime();
                        $promotionEndDate->createFromFormat('Y-m-d', $val);
                        $product->setPromotionEndDate($promotionEndDate);
                        break;
                    case "update_date":
                        $product->setUpdateDate($now);
                        break;
                    case "create_date":
                        $product->setCreateDate($now);
                        break;
                    default:
                        $product->{'set' . ucfirst($tab_attributes[$j])}($val);
                }
            }
            $em->persist($product);
        }
        $em->flush();
    }
}