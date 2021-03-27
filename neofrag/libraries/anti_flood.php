<?php
/**
 * https://neofr.ag
 * @author: Michaël BILCOT <michael.bilcot@neofr.ag>
 */

namespace NF\NeoFrag\Libraries;

use NF\NeoFrag\Library;

class Anti_Flood extends Library
{
	public function __invoke($message = '', $time = '')
	{
		if (($date = $this->session('anti_flood', 'actions', $this->__id())) && $date->timestamp() > $this->date()->sub($time ?: '+10 minutes')->timestamp())
		{
			if (($attempts = $this->session('anti_flood', 'attempts') ?: 0) < 10)
			{
				$this->session->set('anti_flood', 'attempts', $attempts + 1);
				notify($message ?: $this->lang('10 minutes entre chaque message. Merci de patienter !'), 'danger');
				return FALSE;
			}
			else
			{
				//TODO Ban current user for flooding - Currently reset attempts to 0.
				//$this->session->set('anti_flood', 'attempts', 0);
				return FALSE;
			}
		}
		else
		{
			$this->session	->set('anti_flood', 'actions', $this->__id(), $this->date())
							->set('anti_flood', 'attempt', 0);
			return TRUE;
		}
	}
}
