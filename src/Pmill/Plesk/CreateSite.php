<?php namespace Pmill\Plesk;

class CreateSite extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.5">
<site>
	<add>
		<gen_setup>
			<name>{DOMAIN}</name>
			<webspace-id>{SUBSCRIPTION_ID}</webspace-id>
		</gen_setup>
		<hosting>
			<vrt_hst>
				<property>
					<name>php</name>
					<value>{PHP}</value>
				</property>
				<property>
					<name>php_handler_type</name>
					<value>{PHP_HANDLER_TYPE}</value>
				</property>
				<property>
					<name>webstat</name>
					<value>{WEBSTAT}</value>
				</property>
				<property>
					<name>www_root</name>
					<value>{WWW_ROOT}</value>
				</property>
			</vrt_hst>
		</hosting>
	</add>
</site>
</packet>
EOT;

	protected $default_params = array(
		'domain'=>NULL,
		'subscription_id'=>NULL,
		'php'=>TRUE,
		'php_handler_type'=>'module',
		'webstat'=>'none',
		'www_root'=>NULL,
	);

	public function __construct($config, $params=array())
	{
		if(!isset($params['www_root'])) {
			$params['www_root'] = $params['domain'];
		}

		parent::__construct($config, $params);
	}

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        if ($xml->site->add->result->status == 'error') {
			throw new ApiRequestException((string)$xml->site->add->result->errtext);
		}

		$this->id = (int)$xml->site->add->result->id;
        return TRUE;
    }
}
