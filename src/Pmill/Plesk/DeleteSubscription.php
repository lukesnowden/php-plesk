<?php namespace Pmill\Plesk;

class DeleteSubscription extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="{PACKET_VERSION}">
<webspace>
	<del>
		<filter>
			<id>{ID}</id>
		</filter>
	</del>
</webspace>
</packet>
EOT;

	protected $default_params = array(
		'id'=>NULL,
	);

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
    	$webspace = $xml->webspace->del;

        if ($webspace->result->status == 'error') {
            throw new ApiRequestException((string)$webspace->result->errtext);
        }

        return TRUE;
    }
}
