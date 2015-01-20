<?php namespace Pmill\Plesk;

class GetClient extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<customer>
	<get>
		<filter>
			{FILTER}
		</filter>
		<dataset>
			<gen_info/>
			<stat/>
		</dataset>
	</get>
</customer>
</packet>
EOT;

	protected $default_params = array(
		'filter'=>NULL,
	);

	public function __construct($config, $params=array())
    {
    	if(isset($params['username'])) {
    		$params['filter'] = '<login>'.$params['username'].'</login>';
    	}

    	if(isset($params['id'])) {
			$params['filter'] = '<id>'.$params['id'].'</id>';
    	}

    	parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $client = $xml->customer->get->result;

        if ((string)$client->status == 'error') {
            throw new ApiRequestException((string)$client->errtext);
        }

        if ((string)$client->result->status == 'error') {
            throw new ApiRequestException((string)$client->result->errtext);
        }

        return array(
            'id'=>(int)$client->id,
			'status'=>(string)$client->status,
			'created'=>(string)$client->data->gen_info->cr_date,
			'name'=>(string)$client->data->gen_info->cname,
			'contact_name'=>(string)$client->data->gen_info->pname,
			'username'=>(string)$client->data->gen_info->login,
			'phone'=>(string)$client->data->gen_info->phone,
			'email'=>(string)$client->data->gen_info->email,
			'address'=>(string)$client->data->gen_info->address,
			'city'=>(string)$client->data->gen_info->city,
			'state'=>(string)$client->data->gen_info->state,
			'post_code'=>(string)$client->data->gen_info->pcode,
			'country'=>(string)$client->data->gen_info->country,
			'locale'=>(string)$client->data->gen_info->locale,
			'stat'=>array(
				'domains'=>(int)$client->data->stat->active_domains,
				'subdomains'=>(int)$client->data->stat->subdomains,
				'disk_space'=>(int)$client->data->stat->disk_space,
				'web_users'=>(int)$client->data->stat->web_users,
				'databases'=>(int)$client->data->stat->data_bases,
				'traffic'=>(int)$client->data->stat->traffic,
				'traffic_prevday'=>(int)$client->data->stat->traffic_prevday,
			),
        );
    }
}
