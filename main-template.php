<?php
	/**
	 * Template Name: CB Dashbaord Template
	 *
	 * @brief Main Template
	 *
	 * ## Overview
	 * This file will request client information from Ontraport and summarily retrieve
	 * the accounts details. Modifications can be made there after through auto built
	 * forms thereafter.
	 *
	 * @author TronNet DevOps [Sean Murray] <smurray@tronnet.me>
	 * @date 12/25/2015
	 */
	
	require_once('includes/request-update.php');
	require_once('includes/template-loader.php');
	
	get_header(); 
?>

	<div class="row columns">
		<nav aria-label="You are here:" role="navigation">
			<ul class="breadcrumbs">
				<li><a href="#">Home</a></li>
				<li>
					<span class="show-for-sr">Current: </span> Account Management
				</li>
			</ul>
		</nav>
	</div>
	
	<div class="row">
		<div class="large-12 columns">
			<?php if (!empty($_REQUEST['action'])) : ?>
				<div class="callout success" data-closable>
					<h5>Your request is being processed!</h5>
					<p>Please allow 24 hours for processing your request. You will be notified once complete.</p>
				</div>
			<?php endif; ?>
			
			<h3>Account Manager</h3>
			<h5><?php echo $primary['name']; ?></h5>
			<p>Lift or Block your Credit.</p>
		</div>
	</div>
	
	<div class="column row">
		<hr>
		
		<?php if ($error) : ?>
			
		<h4>Error</h4>
		<h6>There was an issue attempting to retrieve account information</h6>
		<em> - "<?php echo $error; ?>"</em>

		<?php else : ?>
		<ul class="tabs" data-tabs id="example-tabs">
			<li class="tabs-title is-active"><a href="#panel1" aria-selected="true">Accounts</a></li>
			<li class="tabs-title hide"><a href="#panel2">Other</a></li>
		</ul>
		<div class="tabs-content" data-tabs-content="example-tabs">
			<div class="tabs-panel is-active" id="panel1">
				<h4>Accounts</h4>
				<table class="rf-accounts-table" style="width: 100%;">
					<thead>
						<tr>
							<th>Name</th>
							<th>Equifax</th>
							<th>Experian</th>
							<th>TransUnion</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<?php
								switch($primary['type']){
									case 'manager':
										echo '<i class="fa fa-user"></i>';
										break;
									case 'managed':
										echo '<i class="fa fa-user-plus"></i>';
										break;
									case 'family':
										echo '<i class="fa fa-users"></i>';
										break;
								}
								?>
								<?php echo $primary['name']; ?>
							</td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo empty($primary['equifaxStatus']) || $primary['equifaxStatus'] == "Lifted" ? "Lifted" : "Blocked"; ?>">
								<?php if (empty($primary['equifaxStatus']) || $primary['equifaxStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo  empty($primary['experianStatus']) || $primary['experianStatus'] == "Lifted" ? "Lifted" : "Blocked"; ?>">
								<?php if (empty($primary['experianStatus']) || $primary['experianStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo  empty($primary['transunionStatus']) || $primary['transunionStatus'] == "Lifted" ? "Lifted" : "Blocked"; ?>">
								<?php if (empty($primary['transunionStatus']) || $primary['transunionStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td><a class="button tiny" data-toggle="modalAccount<?php echo $primary['id']; ?>"><i class="fa fa-cog"></i> Manage</a></td>
						</tr>
						<?php
							foreach($persons as $person) :
						?>
						<tr>
							<td>
								<?php
								switch($person['type']){
									case 'manager':
										echo '<i class="fa fa-user"></i>';
										break;
									case 'managed':
										echo '<i class="fa fa-user-plus"></i>';
										break;
									case 'family':
										echo '<i class="fa fa-users"></i>';
										break;
								}
								?>
								<?php echo $person['name']; ?></td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo $person['equifaxPin']; ?>">
								<?php if (empty($person['equifaxStatus']) || $person['equifaxStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo $person['experianPin']; ?>">
								<?php if (empty($person['experianStatus']) || $person['experianStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td>
								<span data-tooltip aria-haspopup="true" class="has-tip tip-right radius round" title="PIN: <?php echo $person['transunionPin']; ?>">
								<?php if (empty($person['transunionStatus']) || $person['transunionStatus'] == "Lifted") : ?>
									<i class="fa fa-toggle-off cb-lifted-icon" title="Lifted"></i>
								<?php else :?>
									<i class="fa fa-toggle-on cb-blocked-icon" title="Blocked"></i>
								<?php endif; ?>
								</span>
							</td>
							<td><a class="button tiny" data-toggle="modalAccount<?php echo $person['id'].$person['pos']; ?>"><i class="fa fa-cog"></i> Manage</a></td>
						</tr>
						<?php
							endforeach;
						?>
					</tbody>
				</table>
			</div>
			<div class="tabs-panel" id="panel2">
				<p>Not sure what to put here yet, or if this is even needed...</p>
			</div>
		</div>
		<?php endif; ?>
	</div>
		
	<?php
		$history = CBDashboard::get_data($user_email.$primary['type'].$primary['id']);
		$last = $history['updates'][ count($history['updates']) - 1 ];
	?>
	<div id="modalAccount<?php echo $primary['id']; ?>" class="reveal" data-reveal>
		<h2>Manage Account.</h2>
		<p class="lead"><?php echo $primary['name']; ?></p>
		<p>Lift or block credit.</p>
				
		<div class="column row">
			<hr>

			<ul class="tabs" data-tabs id="account-tabs-<?php echo $primary['id']; ?>">
				<li class="tabs-title is-active"><a href="#status-<?php echo $primary['id']; ?>" aria-selected="true">Status</a></li>
				<li class="tabs-title hide"><a href="#bureaus-<?php echo $primary['id']; ?>">Bureaus</a></li>
				<li class="tabs-title"><a href="#status-<?php echo $primary['id']; ?>" onclick="javascript:location.href='/customer_center';">Billing</a></li>
				<li class="tabs-title"><a href="#history-<?php echo $primary['id']; ?>">History</a></li>
			</ul>
			<div class="tabs-content" data-tabs-content="account-tabs-<?php echo $primary['id']; ?>">
				<div class="tabs-panel is-active" id="status-<?php echo $primary['id']; ?>">
					<form method="post" date-abide>
					    <div data-abide-error class="alert callout" style="display: none;">
					      <p><i class="fi-alert"></i> There are some errors in your form.</p>
					    </div>
						
						<div class="row">
							<div class="small-6 columns">
								<strong>Status:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="status-option-<?php echo $primary['id']; ?>" type="checkbox" <?php echo ((!$last['status'] || $last['status'] == 'Lift') ? 'checked="checked" ':""); ?> name="status">
									<label class="switch-paddle cb-lock-checkbox" for="status-option-<?php echo $primary['id']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="small-6 columns">
							    <div class="start-date-field">
									<label>Start <small>required</small>
										<input type="text" name="dateStart" class="cb-datefield" placeholder="mm/dd/yyyy" required />
										<span class="form-error">A start date is required.</span>
									</label>
							    </div>
							</div>
							<div class="small-6 columns">
							    <div class="end-date-field">
									<label>End <small>required</small>
										<input type="text" name="dateEnd" class="cb-datefield" placeholder="mm/dd/yyyy" required />
										<span class="form-error">An end date is required.</span>
									</label>
							    </div>
							</div>
						</div>
					
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="type" value="<?php echo $primary['type']; ?>" />
						<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="tabs-panel" id="bureaus-<?php echo $primary['id']; ?>">
					<form method="post">
						<div class="row">
							<div class="small-6 columns">
								<strong>Equifax:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="equifax-option-<?php echo $primary['id']; ?>" type="checkbox" <?php echo ($primary['equifaxStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="equifax">
									<label class="switch-paddle cb-lock-checkbox" for="equifax-option-<?php echo $primary['id']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="small-6 columns">
								<strong>Experian:</strong>
							</div>
							<div class="small-6 columns">
							
								<div class="switch large">
									<input class="switch-input" id="experian-option-<?php echo $primary['id']; ?>" type="checkbox" <?php echo ($primary['experianStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="experian">
									<label class="switch-paddle cb-lock-checkbox" for="experian-option-<?php echo $primary['id']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="small-6 columns">
								<strong>TransUnion:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="transunion-option-<?php echo $primary['id']; ?>" type="checkbox" <?php echo ($primary['transunionStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="transunion">
									<label class="switch-paddle cb-lock-checkbox" for="transunion-option-<?php echo $primary['id']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
					
						<input type="hidden" name="action" value="update-bureaus" />
						<input type="hidden" name="type" value="<?php echo $primary['type']; ?>" />
						<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="tabs-panel" id="billing-<?php echo $primary['id']; ?>">
					<form method="post">
						
						<div class="row">
							<div class="small-12 columns">
								<label>
									Address
									<textarea name="address"><?php echo $primary['address']; ?></textarea>
								</label>
								
							</div>
						</div>

						<input type="hidden" name="original" value="<?php echo htmlspecialchars($primary['address']); ?>" />
						<input type="hidden" name="action" value="update-address" />
						<input type="hidden" name="type" value="<?php echo $primary['type']; ?>" />
						<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form>
				
				</div>
				
				<div class="tabs-panel" id="history-<?php echo $primary['id']; ?>">
					<table class="rf-accounts-table" style="width: 100%;">
						<thead>
							<tr>
								<th>Date</th>
								<th>Action</th>
								<th>From</th>
								<th>To</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if (!is_array($history)){
									$history['updates'] = array();
								}
								
								foreach($history['updates'] as $item) :
							?>
							<tr>
								<td><?php echo date("M d, Y @ h:ia", strtotime($item['date'])); ?></td>
								<td><?php echo $item['status']; ?></td>
								<?php if (empty($item['address'])) :?>
									<td><?php echo date('m/d/Y', $item['start']); ?></td>
									<td><?php echo date('m/d/Y', $item['end']); ?></td>
								<? else :?>
									<td></td>
									<td><?php echo $item['address']; ?></td>
								<?php endif;?>
							</tr>
							<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		
		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	
	<?php
		foreach($persons as $person) :
			$history = CBDashboard::get_data($user_email.$person['type'].( ($person['type'] == 'family') ? $primary['id'].$person['pos'] : $primary ));
			$last = $history['updates'][ count($history['updates']) - 1 ];
			
	?>
	<div id="modalAccount<?php echo $person['id'].$person['pos']; ?>" class="reveal" data-reveal>
		<h2>Manage Account.</h2>
		<p class="lead"><?php echo $person['name']; ?></p>
		<p>Lift or block accounts.</p>
		
		<div class="column row">
			<hr>

			<ul class="tabs" data-tabs id="account-tabs-<?php echo $person['id']; ?>">
				<li class="tabs-title is-active"><a href="#status-<?php echo $person['id']; ?>" aria-selected="true">Status</a></li>
				<li class="tabs-title hide"><a href="#bureaus-<?php echo $person['id']; ?>">Bureaus</a></li>
				<li class="tabs-title"><a href="#status-<?php echo $person['id']; ?>" onclick="javascript:location.href='/customer_center';">Billing</a></li>
				<li class="tabs-title"><a href="#history-<?php echo $person['id']; ?>">History</a></li>
			</ul>
			<div class="tabs-content" data-tabs-content="account-tabs-<?php echo $person['id']; ?>">
				<div class="tabs-panel is-active" id="status-<?php echo $person['id']; ?>">
					<form method="post" date-abide>
					    <div data-abide-error class="alert callout" style="display: none;">
					      <p><i class="fi-alert"></i> There are some errors in your form.</p>
					    </div>
						
						<div class="row">
							<div class="small-6 columns">
								<strong>Status:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="option-<?php echo $person['id'].$person['pos']; ?>" type="checkbox" <?php echo ((!$last['status'] || $last['status'] == 'Lift') ? 'checked="checked" ':""); ?> name="status">
									<label class="switch-paddle cb-lock-checkbox" for="option-<?php echo $person['id'].$person['pos']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
					
						<div class="row">
							<div class="small-6 columns">
							    <div class="start-date-field">
									<label>Start <small>required</small>
										<input type="text" name="dateStart" class="cb-datefield" placeholder="mm/dd/yyyy" required />
										<span class="form-error">A start date is required.</span>
									</label>
							    </div>
							</div>
							<div class="small-6 columns">
							    <div class="end-date-field">
									<label>End <small>required</small>
										<input type="text" name="dateEnd" class="cb-datefield" placeholder="mm/dd/yyyy" required />
										<span class="form-error">An end date is required.</span>
									</label>
							    </div>
							</div>
						</div>
					
						<input type="hidden" name="action" value="update" />
						<input type="hidden" name="type" value="<?php echo $person['type']; ?>" />
						<?php if ($person['type'] == 'family') :?>
							<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
							<input type="hidden" name="attrPos" value="<?php echo $person['pos']; ?>" />
						<? else :?>
							<input type="hidden" name="id" value="<?php echo $person['id']; ?>" />
						<?php endif;?>

					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form>
				
				</div>
				
				<div class="tabs-panel" id="bureaus-<?php echo $person['id']; ?>">
					<form method="post">
						<div class="row">
							<div class="small-6 columns">
								<strong>Equifax:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="equifax-option-<?php echo $person['id'].$person['pos']; ?>" type="checkbox" <?php echo ($person['equifaxStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="equifax">
									<label class="switch-paddle cb-lock-checkbox" for="equifax-option-<?php echo $person['id'].$person['pos']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="small-6 columns">
								<strong>Experian:</strong>
							</div>
							<div class="small-6 columns">
							
								<div class="switch large">
									<input class="switch-input" id="experian-option-<?php echo $person['id'].$person['pos']; ?>" type="checkbox" <?php echo ($person['experianStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="experian">
									<label class="switch-paddle cb-lock-checkbox" for="experian-option-<?php echo $person['id'].$person['pos']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
						<hr/>
						<div class="row">
							<div class="small-6 columns">
								<strong>TransUnion:</strong>
							</div>
							<div class="small-6 columns">
								<div class="switch large">
									<input class="switch-input" id="transunion-option-<?php echo $person['id'].$person['pos']; ?>" type="checkbox" <?php echo ($person['transunionStatus'] == "Lifted" ? 'checked="checked" ':""); ?> name="transunion">
									<label class="switch-paddle cb-lock-checkbox" for="transunion-option-<?php echo $person['id'].$person['pos']; ?>" style="width: 100px;">
										<span class="switch-active" aria-hidden="true">Lift</span>
										<span class="switch-inactive" aria-hidden="true">Block</span>
									</label>
								</div>
							</div>
						</div>
					
						<input type="hidden" name="action" value="update-bureaus" />
						<input type="hidden" name="type" value="<?php echo $person['type']; ?>" />
						<?php if ($person['type'] == 'family') :?>
							<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
							<input type="hidden" name="attrPos" value="<?php echo $person['pos']; ?>" />
						<? else :?>
							<input type="hidden" name="id" value="<?php echo $person['id']; ?>" />
						<?php endif;?>
					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="tabs-panel" id="billing-<?php echo $person['id']; ?>">
					<form method="post">
						
						<div class="row">
							<div class="small-12 columns">
								<label>
									Address
									<textarea name="address"><?php echo ($person['type'] == 'family') ? $primary['address'] : $person['address']; ?></textarea>
								</label>
								
							</div>
						</div>

						<input type="hidden" name="action" value="update-address" />
						<input type="hidden" name="type" value="<?php echo $person['type']; ?>" />
						<?php if ($person['type'] == 'family') :?>
							<input type="hidden" name="original" value="<?php echo htmlspecialchars($primary['address']); ?>" />
							<input type="hidden" name="id" value="<?php echo $primary['id']; ?>" />
							<input type="hidden" name="attrPos" value="<?php echo $person['pos']; ?>" />
						<? else :?>
							<input type="hidden" name="original" value="<?php echo htmlspecialchars($person['address']); ?>" />
							<input type="hidden" name="id" value="<?php echo $person['id']; ?>" />
						<?php endif;?>
						
					
						<hr/>
						<div class="row">
							<div class="small-4 text-center columns">
							</div>
							<div class="small-4 text-center columns">
								<button type="submit" class="button primary expand"><i class="fa fa-save"></i> Submit</button>
							</div>
						</div>
					</form> 
				
				</div>
				
				<div class="tabs-panel" id="history-<?php echo $person['id']; ?>">
					<table class="rf-accounts-table" style="width: 100%;">
						<thead>
							<tr>
								<th>Date</th>
								<th>Action</th>
								<th>From</th>
								<th>To</th>
							</tr>
						</thead>
						<tbody>
							<?php
								if (!is_array($history)){
									$history['updates'] = array();
								}
								
								foreach($history['updates'] as $item) :
							?>
							<tr>
								<td><?php echo date("M d, Y @ h:ia", strtotime($item['date'])); ?></td>
								<td><?php echo $item['status']; ?></td>
								<?php if (empty($item['address'])) :?>
									<td><?php echo date('m/d/Y', $item['start']); ?></td>
									<td><?php echo date('m/d/Y', $item['end']); ?></td>
								<? else :?>
									<td></td>
									<td><?php echo $item['address']; ?></td>
								<?php endif;?>
							</tr>
							<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>

		<button class="close-button" data-close aria-label="Close reveal" type="button">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
	<?php
		endforeach;
		
		get_footer();
	?>