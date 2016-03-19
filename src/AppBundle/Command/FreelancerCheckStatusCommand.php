<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FreelancerCheckStatusCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('freelancer:check:status')
        ->setDescription('Check the status of this system')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command_query = $this->getApplication()->find('freelancer:scrap:query');
        $command_skill = $this->getApplication()->find('freelancer:scrap:skill');

        try {
            $command_query->run($input, $output);
            $command_skill->run($input, $output);
        } catch (Exception $e) {
            $utils_service     = $this->getContainer()->get('utils_service');
            $title_and_message = $this->getContainer()->getParameter( 'freelancer.message_error' );
            
            $utils_service->sendMail( $title_and_message, $title_and_message );
        }
    }
}
