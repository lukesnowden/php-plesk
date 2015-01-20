<?php namespace Pmill\Plesk;

class CreateSiteAlias extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.5">
    <site-alias>
        <create>
            <status>0</status>
            <pref>
                <web>{WEB_ENABLED}</web>
                <mail>{MAIL_ENABLED}</mail>
                <tomcat>{TOMCAT_ENABLED}</tomcat>
            </pref>
            <site-id>{SITE_ID}</site-id>
            <name>{ALIAS}</name>
        </create>
    </site-alias>
</packet>
EOT;

	protected $default_params = array(
		'site_id'=>NULL,
		'alias'=>NULL,
		'web_enabled'=>1,
		'mail_enabled'=>0,
		'tomcat_enabled'=>0,
	);

    public function __construct($config, $params)
    {
        if (!isset($params['site_id'])) {
            if (is_int($params['domain'])) {
                $params['site_id'] = $params['domain'];
            }
            else {
                $request = new GetSite($config, $params);
                $info = $request->process();
                $params['site_id'] = $info['id'];
            }
        }

    	parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
        $result = $xml->{'site-alias'}->create->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        $this->id = (int)$result->id;
        return TRUE;
    }
}
