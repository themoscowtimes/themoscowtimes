<?php view::extend('template/main'); ?>


<?php view::start('main') ?>
	<div class="content-header" y-name="header-fixed">
		<div class="content-header-1">
			<h2>Mollie customers</h2>
		</div>
	</div>

	<div class="row m-2">
		<div class="col">
			<table class="table">
				<thead>
					<tr>
						<td>Name</td><td>Email</td><td>Payment created</td><td>Amount</td><td>Status</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach($customers as $email => $customer): ?>
						<?php
						$payments = $customer['payments'];
						$payment = array_shift($payments);
						?>

						<tr>
							<td><?php view::text($customer['name']) ?></td>
							<td><?php view::text($email) ?></td>
							<?php if ($payment): ?>
								<td><?php view::text($payment['created']) ?></td>
								<td><?php view::text($payment['amount']) ?> <?php view::text($payment['currency']) ?></td>
								<td><?php view::text($payment['status']) ?></td>
							<?php else: ?>
								<td></td>
								<td></td>
								<td></td>
							<?php endif; ?>
						</tr>

						<?php if (count($payments) > 0): ?>

							<?php foreach ($payments as $payment): ?>
								<tr>
									<td></td>
									<td></td>
									<td><?php view::text($payment['created']) ?></td>
									<td><?php view::text($payment['amount']) ?> <?php view::text($payment['currency']) ?></td>
									<td><?php view::text($payment['status']) ?></td>
								</tr>
							<?php endforeach; ?>

						<?php endif; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>

<?php view::end() ?>