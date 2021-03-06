<?php
	// For help on using hooks, please refer to http://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function orders_init(&$options, $memberInfo, &$args){
		/* Inserted by Search Page Maker for AppGini on 2020-07-02 01:41:11 */
		$options->FilterPage = 'hooks/orders_filter.php';
		/* End of Search Page Maker for AppGini code */


		return TRUE;
	}

	function orders_header($contentType, $memberInfo, &$args){
		$header='';

		switch($contentType){
			case 'tableview':
				$header='';
				break;

			case 'detailview':
				$header='';
				break;

			case 'tableview+detailview':
				$header='';
				break;

			case 'print-tableview':
				$header='';
				break;

			case 'print-detailview':
				$header='';
				break;

			case 'filters':
				$header='';
				break;
		}

		return $header;
	}

	function orders_footer($contentType, $memberInfo, &$args){
		$footer='';

		switch($contentType){
			case 'tableview':
				$footer='';
				break;

			case 'detailview':
				$footer='';
				break;

			case 'tableview+detailview':
				$footer='';
				break;

			case 'print-tableview':
				$footer='';
				break;

			case 'print-detailview':
				$footer='';
				break;

			case 'filters':
				$footer='';
				break;
		}

		return $footer;
	}

	function orders_before_insert(&$data, $memberInfo, &$args){

		return TRUE;
	}

	function orders_after_insert($data, $memberInfo, &$args){

		return TRUE;
	}

	function orders_before_update(&$data, $memberInfo, &$args){

		return TRUE;
	}

	function orders_after_update($data, $memberInfo, &$args){

		return TRUE;
	}

	function orders_before_delete($selectedID, &$skipChecks, $memberInfo, &$args){

		return TRUE;
	}

	function orders_after_delete($selectedID, $memberInfo, &$args){

	}

	function orders_dv($selectedID, $memberInfo, &$html, &$args){

	}

	function orders_csv($query, $memberInfo, &$args){

		return $query;
	}
	function orders_batch_actions(&$args){

		return array();
	}
