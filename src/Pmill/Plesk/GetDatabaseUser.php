<?php namespace Pmill\Plesk;

class GetDatabaseUser extends BaseRequest
{
	public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.4.2.0">
<database>
   <get-default-user>
      <filter>
          <db-id>{DATABASE_ID}</db-id>
      </filter>
   </get-default-user>
</database>
</packet>
EOT;

	/**
	 * [$default_params description]
	 * @var array
	 */

	protected $default_params = array(
		'database_id'	=> NULL,
	);

	/**
	 * [processResponse description]
	 * @param  [type] $xml [description]
	 * @return [type]      [description]
	 */

	protected function processResponse($xml) {
        if ( $xml->database->{'get-default-user'}->result->status == 'error' ) {
		 	return false;
		}
        return $xml->database->{'get-default-user'}->result;
    }

}