<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:44 AM
 */

namespace WellsFargo\ACHBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class WellsFargoACHExtension extends Extension {
    /**
     * @var ContainerBuilder
     */
    protected $container;
    /**
     * Loads any resources/services we need
     *
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container->setParameter('wellsfargo.routing_number', $config['routing_number']);
        $container->setParameter('wellsfargo.company_id', $config['company_id']);
        $container->setParameter('wellsfargo.application_id', $config['application_id']);
        $container->setParameter('wellsfargo.file_id', $config['file_id']);
        $container->setParameter('wellsfargo.originating_bank', $config['originating_bank']);
        $container->setParameter('wellsfargo.company_name', $config['company_name']);

        $container->setParameter('wellsfargo.transmission_host', $config['transmission']['host']);
        $container->setParameter('wellsfargo.transmission_username', $config['transmission']['username']);
        $container->setParameter('wellsfargo.transmission_private_key_path', $config['transmission']['private_key_path']);
        $container->setParameter('wellsfargo.transmission_private_key_password', $config['transmission']['private_key_password']);
        $container->setParameter('wellsfargo.transmission_public_key_path', $config['transmission']['public_key_path']);

        $container->setParameter('wellsfargo.inbound_folder', $config['folders']['inbound']);
        $container->setParameter('wellsfargo.outbound_folder', $config['folders']['outbound']);
        $container->setParameter('wellsfargo.archive_out_folder', $config['folders']['archive_out']);
        $container->setParameter('wellsfargo.returns_report_folder', $config['folders']['returns_report']);
    }
}