<?php
	/**
	 * @brief API Entry Point
	 *
	 * ## Overview
	 * This file will deligate api requests to their appropriate function call.
	 *
	 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	 * @date 12/25/2015
	 */
	
	require_once('utilities.php');
	
	global $user_email;
	get_currentuserinfo();

	if (isset($_REQUEST['action'])){
		switch($_REQUEST['action']){

			case 'update-bureaus':
				$id = $_REQUEST['id'];
	
				if (!is_numeric($id)){
					break;
				}
		
				$type = strtolower($_REQUEST['type']);
				if (!in_array($type, array('manager', 'managed', 'family'))){
					break;
				}
		
				$position = $_REQUEST['attrPos'];
				if (!empty($position) && !is_numeric($position)){
					break;
				}
				
				$sectionName = ($position) ? 'Credit Block - Additional Persons' : 'Credit Block - Main';
				$equifaxFieldName = ($position) ? 'Equifax #'.$position.' Status' : 'Equifax Status';
				$experianFieldName = ($position) ? 'Experian #'.$position.' Status' : 'Experian Status';
				$transunionFieldName = ($position) ? 'TransUnion #'.$position.' Status' : 'TransUnion Status';
				
				$equifaxStatus = isset($_REQUEST['equifax']) ? 'Blocked' : 'Lifted';
				$experianStatus = isset($_REQUEST['experian']) ? 'Blocked' : 'Lifted';
				$transunionStatus = isset($_REQUEST['transunion']) ? 'Blocked' : 'Lifted';
				$key = $user_email.$type.$id.$position;
				$date = date('c');
				
				$ret = RFPluginOPConnector::UpdateContactField(
<<<STRING
	<contact id='$id'>
		<Group_Tag name="$sectionName">
			<field name="$equifaxFieldName">$equifaxStatus</field>
			<field name="$experianFieldName">$experianStatus</field>
			<field name="$transunionFieldName">$transunionStatus</field>
		</Group_Tag>
	</contact>
STRING
				);

				$p = new SimpleXMLElement($ret);
				
				$existing = CBDashboard::get_data($key);
				if (!is_array($existing)){
					$existing = array();
				}

				
				$existing['updates'][] = array(
					'date' => $date,
					'status' => 'Bureaus Updated',
					'data' => array(
						'equifaxFieldName' => $equifaxFieldName,
						'experianFieldName' => $experianFieldName,
						'transunionFieldName' => $transunionFieldName,
						'equifaxStatus' => $equifaxStatus,
						'experianStatus' => $experianStatus,
						'transunionStatus' => $transunionStatus
					),
					'aid' => $id,
					'actor' => $user_email,
					'position' => $position
				);
				
				$existing['totals'][$tag][] = $date;
				
				CBDashboard::save_data($key, $existing);

				break;
			case 'update-address':
				$id = $_REQUEST['id'];
		
				if (!is_numeric($id)){
					break;
				}
			
				$type = strtolower($_REQUEST['type']);
				if (!in_array($type, array('manager', 'managed', 'family'))){
					break;
				}
			
				$position = $_REQUEST['attrPos'];
				if (!empty($position) && !is_numeric($position)){
					break;
				}
			
				$status = isset($_REQUEST['status']) ? 'Lift' : 'Block';
				$original = $_REQUEST['original'];
				$address = $_REQUEST['address'];
			
				$key = $user_email.$type.$id.$position;
				$date = date('c');

				$ret = RFPluginOPConnector::UpdateContactField(
<<<STRING
	<contact id='$id'>
		<Group_Tag name="Contact Information">
			<field name="Address">$address</field>
		</Group_Tag>
	</contact>
STRING
				);

				$p = new SimpleXMLElement($ret);
				
				$existing = CBDashboard::get_data($key);
				if (!is_array($existing)){
					$existing = array();
				}
				

				
				$existing['updates'][] = array(
					'date' => $date,
					'status' => 'Address Updated',
					'type' => $type,
					'original' => $address,
					'address' => $address,
					'aid' => $id,
					'actor' => $user_email,
					'position' => $position
				);
				
				$existing['totals'][$tag][] = $date;

				
				CBDashboard::save_data($key, $existing);
			
				break;
			case 'update':
			
				$id = $_REQUEST['id'];
			
				if (!is_numeric($id)){
					break;
				}
				
				$type = strtolower($_REQUEST['type']);
				if (!in_array($type, array('manager', 'managed', 'family'))){
					break;
				}
				
				$position = $_REQUEST['attrPos'];
				if (!empty($position) && !is_numeric($position)){
					break;
				}
				
				$status = isset($_REQUEST['status']) ? 'Lift' : 'Block';
				$tag = $status . ' '. ($position ? '#'.$position : 'Main');
				$sectionName = ($position) ? 'Credit Block - Additional Persons' : 'Credit Block - Main';
				$startDateFieldName = ($position) ? 'Lift #'.$position.' Start Date' : 'Lift Start Date';
				$endDateFieldName = ($position) ? 'Lift #'.$position.' End Date' : 'Lift End Date';
				$startDateFieldValue = date('U', strtotime($_REQUEST['dateStart']));
				$endDateFieldValue = date('U',strtotime($_REQUEST['dateEnd']));
				
				$key = $user_email.$type.$id.$position;
				$date = date('c');
								
				$ret = RFPluginOPConnector::AddTag(
<<<STRING
	<contact id='$id'>
		<tag>$tag</tag>
	</contact>
STRING
				);

				$p = new SimpleXMLElement($ret);


				$ret = RFPluginOPConnector::UpdateContactField(
<<<STRING
	<contact id='$id'>
		<Group_Tag name="$sectionName">
			<field name="$startDateFieldName">$startDateFieldValue</field>
			<field name="$endDateFieldName">$endDateFieldValue</field>
		</Group_Tag>
	</contact>
STRING
				);

				$p = new SimpleXMLElement($ret);
				
				$existing = CBDashboard::get_data($key);
				if (!is_array($existing)){
					$existing = array();
				}
				
				$existing['updates'][] = array(
					'date' => $date,
					'status' => $status,
					'type' => $type,
					'start' => $startDateFieldValue,
					'end' => $endDateFieldValue,
					'aid' => $id,
					'actor' => $user_email,
					'position' => $position
				);
				
				$existing['totals'][$tag][] = $date;
				
				$ret = CBDashboard::save_data($key, $existing);
							
				break;
			
			case 'hdti':
				RFPluginHDTIConnector::Request($_REQUEST);
				
				echo json_encode(array(
					'data' => array(),
					'status' => array(
						'code' => 0,
						'message' => 'Success'
					)
				));
				
				die();
				
				break;
		}
	}
	
	
	