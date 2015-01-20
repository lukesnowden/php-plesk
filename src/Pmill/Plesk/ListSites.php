<?php namespace Pmill\Plesk;

class ListSites extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<site>
	<get>
		{FILTER}
		<dataset>
			<hosting/>
		</dataset>
	</get>
</site>
</packet>
EOT;

	protected $default_params = array(
		'filter'=>'<filter/>',
	);

	public function __construct($config, $params=array())
	{
		if(isset($params['subscription_id'])) {
			$params['filter'] = '<filter><parent-id>'.$params['subscription_id'].'</parent-id></filter>';
		}

		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        for ($i=0 ;$i<count($xml->site->get->result); $i++) {
            $site = $xml->site->get->result[$i];
            $hosting_type = (string)$site->data->gen_info->htype;

            $result[] = array(
                'id'=>(string)$site->id,
                'status'=>(string)$site->status,
                'created'=>(string)$site->data->gen_info->cr_date,
                'name'=>(string)$site->data->gen_info->name,
                'ip'=>(string)$site->data->gen_info->dns_ip_address,
                'hosting_type'=>$hosting_type,
                'ip_address'=>(string)$site->data->hosting->{$hosting_type}->ip_address,
                'www_root'=>$this->findHostingProperty($site->data->hosting->{$hosting_type}, 'www_root'),
                'ftp_username'=>$this->findHostingProperty($site->data->hosting->{$hosting_type}, 'ftp_login'),
                'ftp_password'=>$this->findHostingProperty($site->data->hosting->{$hosting_type}, 'ftp_password'),
            );
        }
        return $result;
    }

    /*
     * Helper function to search an XML tree for a specific property
     * @return string
     */
    protected function findHostingProperty($node, $key)
    {
    	foreach($node->children() AS $property)
    	{
    		if ($property->name == $key)
    			return (string)$property->value;
    	}
    	return NULL;
    }
}
