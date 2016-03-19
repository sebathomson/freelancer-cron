<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FreelancerScrapFromQueryCommand extends ContainerAwareCommand
{
	private $scrap_type     = 'Query';
	private $argument_value = '';
	private $default_skill  = 'Symfony';

	protected function configure()
	{
		$this
		->setName('freelancer:scrap:query')
		->setDescription('Command to get all publications of the query (default is: "' . $this->default_skill . '")')
		->addArgument(
			'query',
			InputArgument::OPTIONAL,
			'Please, insert a query?',
			$this->default_skill
			)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$utils_service = $this->getContainer()->get('utils_service');

		$url                  = $this->getContainer()->getParameter( 'freelancer.url_scrap' );
		$this->argument_value = $input->getArgument('query');
		$url                 .= '?keyword=' . $this->argument_value;

		$offers = $utils_service->getOffersFromUrl( $url );
		$offers = $utils_service->getRelevantDataFromOfers( $offers );
		$offers = $utils_service->excludeSendedBefore( $offers );

		if ( COUNT($offers) > 0 ) {

			$email_body = $this->getContainer()->get('templating')->render('AppBundle:email_body.txt.twig',
				[
				'offers' => $offers
				]
				);

			$subject = $utils_service->getSubjectMessage($this->scrap_type, $this->argument_value);

			$utils_service->sendMail( $subject, $email_body );

			// write in the log file all offers than we send by email.
			foreach ($offers as $offer) {
				$utils_service->writeInLogFile( $offer[ 'id_offer' ] );
			}

		}

		$output->writeln('successfull');
	}

}
