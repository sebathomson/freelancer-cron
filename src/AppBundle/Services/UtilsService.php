<?php
namespace AppBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class UtilsService
{

	private $container;

	public function __construct(Container $container) {
		$this->container = $container;
	}

	/**
	 * @param  [string] $url
	 * @return [array]
	 */
	public function getOffersFromUrl($url)
	{
		$ch = curl_init($url);
		
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$output = curl_exec($ch);       
		
		curl_close($ch);

		$output = json_decode($output);

		return $output->aaData;
	}

	/**
	 * @param  [array] $offers
	 * @return [array]
	 */
	public function getRelevantDataFromOfers($offers)
	{
		$relevant_offers = [];

		foreach ($offers as $value) {
			$offer = [];

			$offer[ 'id_offer' ]          = $value[0];
			$offer[ 'name_offer' ]        = $value[1];
			$offer[ 'description_offer' ] = $value[2];
			$offer[ 'url_offer' ]         = $value[21];
			
			$relevant_offers[] = $offer;
		}

		return $relevant_offers;
	}

	/**
	 * @param  [array] $offers
	 * @return [array]
	 */
	public function excludeSendedBefore($offers)
	{
		$file = $this->container->getParameter( 'freelancer.path_log' );
		
		if ( !file_exists($file) ) {
			fopen($file, 'a');
			fclose($file);
		}

		$offers_sended_before = file($file);

		$offers_sended_before = array_map(function ($offer){
			return trim($offer);
		}, $offers_sended_before);


		foreach ($offers as $key => $offer) {
			if (in_array($offer[ 'id_offer' ], $offers_sended_before)) {
				unset( $offers[ $key ] );
			}
		}

		return $offers;
	}

	/**
	 * @param  [string] $subject
	 * @param  [string] $email_body
	 */
	public function sendMail($subject, $email_body)
	{
		$message = \Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom($this->container->getParameter( 'mailer_user' ))
		->setTo($this->container->getParameter( 'mailer_user' ))
		->setBody($email_body, 'text/html');

		$this->container->get('mailer')->send($message);
	}

	/**
	 * @param  [string] $scrap_type
	 * @param  [string] $argument_value
	 * @return [string]
	 */
	public function getSubjectMessage($scrap_type, $argument_value)
	{
		$message_title = $this->container->getParameter( 'freelancer.message_title' );

		$message_title = str_replace('@type@', $scrap_type, $message_title);
		$message_title = str_replace('@arguments@', $argument_value, $message_title);

		return $message_title;
	}

	/**
	 * @param  [string] $input
	 */
	public function writeInLogFile($input)
	{
		$file = $this->container->getParameter( 'freelancer.path_log' );

		if (!file_exists($file)) {
			fopen($file, 'a');
			fclose($file);
		}

		file_put_contents($file, $input . PHP_EOL, FILE_APPEND | LOCK_EX);
	}
}
