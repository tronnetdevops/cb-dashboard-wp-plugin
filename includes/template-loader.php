<?php
	/**
	 * @brief Template Loader
	 *
	 * ## Overview
	 * Makes requests to Ontraport.
	 *
	 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	 * @date 12/25/2015
	 */
	
	require_once('utilities.php');

	global $user_email;
	get_currentuserinfo();

	$persons = array();
	

	try{
		$ret = RFPluginOPConnector::FindData(
<<<STRING
	<search>
		<equation>
			<field>Email</field>
			<op>c</op>
			<value>$user_email</value>
		</equation>
	</search>
STRING
		);
		
		$p = new SimpleXMLElement($ret);
		
	} catch(Exception $e){
		
	}

	if ($p->{"error"}){
		$error = $p->{"error"};
	} else {
		$manager = ((string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Credit Block Acct Manager\']')[0]);

		$name = trim( ((string)$p->xpath('//contact/Group_Tag[@name=\'Contact Information\']/field[@name=\'First Name\']')[0]) .' '. ((string)$p->xpath('//contact/Group_Tag[@name=\'Contact Information\']/field[@name=\'Last Name\']')[0]));
		
		if (empty($name)){
			$error = "Current logged in user does not appear to have any CreditBlock account data in Ontraport!";
		} else {
			
			
			$id = (string)$p->xpath('//contact')[0]->attributes()->id;
	
			$primary = array(
				'id' => $id,
				'name' => $name,
				'type' => 'manager',
				'tag' => 'Main',
				'address' => (string)$p->xpath('//contact/Group_Tag[@name=\'Contact Information\']/field[@name=\'Address\']')[0],
				'equifaxPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Equifax PIN\']')[0],
				'equifaxStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Equifax Status\']')[0],
				'experianPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Experian PIN\']')[0],
				'experianStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Experian Status\']')[0],
				'transunionPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'TransUnion PIN\']')[0],
				'transunionStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'TransUnion Status\']')[0]
			);
			
			$persons = array();
	

			if (empty($manager)){
				/**
				 * If the manager field has NOT been populated, then this account might be a manager
				 */
		
				$dd = RFPluginOPConnector::FindData(
<<<STRING
<search>
	<equation>
		<field>Credit Block Acct Manager</field>
		<op>c</op>
		<value>$name</value>
	</equation>
</search>
STRING
				);
				
		
				$pn = new SimpleXMLElement($dd);
		
				$a = 0;
				while(++$a){
					$name = trim( ((string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Contact Information\']/field[@name=\'First Name\']')[0]) .' '. ((string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Contact Information\']/field[@name=\'Last Name\']')[0]));
			
					if (empty($name)){
						break;
					}
			
					$id = (string)$pn->xpath('//contact[position()='.$a.']')[0]->attributes()->id;
			
					$persons[] = array(
						'id' => $id,
						'name' => $name,
						'type' => 'managed',
						'tag' => 'Main',
						'address' => (string)$pn->xpath('//contact/Group_Tag[@name=\'Contact Information\']/field[@name=\'Address\']')[0],
						'equifaxPin' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Equifax PIN\']')[0],
						'equifaxStatus' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Equifax Status\']')[0],
						'experianPin' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Experian PIN\']')[0],
						'experianStatus' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'Experian Status\']')[0],
						'transunionPin' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'TransUnion PIN\']')[0],
						'transunionStatus' => (string)$pn->xpath('//contact[position()='.$a.']/Group_Tag[@name=\'Credit Block - Main\']/field[@name=\'TransUnion Status\']')[0]
					);

				}
			}
			
			
			/**
			 * If the manager field has been populated, then this is a managed account
			 */
			$additionals = $p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']')[0];
			if ($additionals){
				$a = 1;
				while($a++ < 5){
					$pname = (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'Person #'.$a.' Name\']')[0];
					
					if ($pname){
						$persons[] = array(
							'id' => str_replace(' ', '_', $pname),
							'name' => $pname,
							'type' => 'family',
							'tag' => '%23'.$a,
							'pos' => $a,
							'address' => $primary['address'],
							'equifaxPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'Equifax #'.$a.' PIN\']')[0],
							'equifaxStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'Equifax #'.$a.' Status\']')[0],
							'experianPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'Experian #'.$a.' PIN\']')[0],
							'experianStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'Experian #'.$a.' Status\']')[0],
							'transunionPin' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'TransUnion #'.$a.' PIN\']')[0],
							'transunionStatus' => (string)$p->xpath('//contact/Group_Tag[@name=\'Credit Block - Additional Persons\']/field[@name=\'TransUnion #'.$a.' Status\']')[0]
						);
					} else{
						/**
						 * Field is blank, and we are assuming they'll be populated in order without gaps
						 */
						break;
					}
		
				}
			}
		}
	}