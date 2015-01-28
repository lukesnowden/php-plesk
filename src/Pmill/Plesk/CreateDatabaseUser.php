<?php namespace Pmill\Plesk;

class CreateDatabaseUser extends BaseRequest
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.5.0">
<database>
   <add-db-user>
      <webspace-id>{SUBSCRIPTION_ID}</webspace-id>
      <db-server-id>{SERVER_ID}</db-server-id>
      <login>{USERNAME}</login>
      <password>{PASSWORD}</password>
      </add-db-user>
</database>
</packet>
EOT;

	/**
	 * [$default_params description]
	 * @var array
	 */

	protected $default_params = array(
		'subscription_id'	=> NULL,
		'server_id'			=> NULL,
		'username'			=> NULL,
		'password'			=> NULL
	);

	/**
	 * [processResponse description]
	 * @param  [type] $xml [description]
	 * @return [type]      [description]
	 */

	protected function processResponse($xml) {
        if ( $xml->database->{'add-db-user'}->result->status == 'error' ) {
		 	throw new ApiRequestException((string)$xml->database->{'add-db-user'}->result->errtext);
		}
        return $xml->database->{'add-db-user'}->result;
    }

}