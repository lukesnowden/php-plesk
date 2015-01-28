<?php namespace Pmill\Plesk;

class CreateDatabase extends BaseRequest
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<database>
<add-db>
   <webspace-id>{SUBSCRIPTION_ID}</webspace-id>
   <name>{NAME}</name>
   <type>{TYPE}</type>
</add-db>
</database>
</packet>
EOT;

	/**
	 * [$default_params description]
	 * @var array
	 */

	protected $default_params = array(
		'subscription_id'	=> NULL,
		'name'				=> NULL,
		'type'				=> 'mysql'
	);

	/**
	 * [processResponse description]
	 * @param  [type] $xml [description]
	 * @return [type]      [description]
	 */

	protected function processResponse($xml) {
        if ( $xml->database->{'add-db'}->result->status == 'error' ) {
		 	throw new ApiRequestException((string)$xml->database->{'add-db'}->result->errtext);
		}
        return $xml->database->{'add-db'}->result;
    }

}