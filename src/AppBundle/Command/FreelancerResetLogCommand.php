<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FreelancerResetLogCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
		->setName('freelancer:reset:log')
		->setDescription('Reset the log of offers sended')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = $this->getContainer()->getParameter( 'freelancer.path_log' );
		$msg  = 'File not exist';

		if (file_exists( $file )) {
			unlink($this->getContainer()->getParameter( 'freelancer.path_log' ));		
			$msg  = 'File deleted';
		}

		$output->writeln( $msg );
	}
}
