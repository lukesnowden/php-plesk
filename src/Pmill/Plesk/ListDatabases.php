<?php namespace Pmill\Plesk;

class ListDatabases extends BaseRequest
{
	public $xml_packet = <<<EOT
<packet version="1.6.3.0">
<database>
   <get-db>
      <filter>
          <webspace-id>{SUBSCRIPTION_ID}</webspace-id>
      </filter>
   </get-db>
</database>
</packet>
EOT;

	/**
	 * [$default_params description]
	 * @var array
	 */

	protected $default_params = array(
		'subscription_id'	=> NULL
	);

	/**
	 * [processResponse description]
	 * @param  [type] $xml [description]
	 * @return [type]      [description]
	 */

	protected function processResponse($xml) {

        if ( $xml->database->{'get-db'}->result->status == 'error' ) {
		 	throw new ApiRequestException((string)$xml->database->{'add-db'}->result->errtext);
		}
        return $xml->database->{'get-db'}->result;
    }

}