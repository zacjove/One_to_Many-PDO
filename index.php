<?php
require_once __DIR__ . '/core/handleForms.php';

$modes = getAllModes($pdo);
$routes = getAllRoutes($pdo);
$statusMessages = [
	'insert' => 'Mode added.',
	'update' => 'Mode updated.',
	'delete' => 'Mode deleted.',
	'route_insert' => 'Route added.',
	'route_update' => 'Route updated.',
	'route_delete' => 'Route deleted.',
];
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Transport Modes</title>
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<main>
		<h1>Transport Modes</h1>

		<?php if (isset($statusMessages[$status])): ?>
			<p class="notice" role="status"><?= htmlspecialchars($statusMessages[$status]) ?></p>
		<?php endif; ?>
		<?php if ($modeFormMessage !== null): ?>
			<p class="error" role="alert"><?= htmlspecialchars($modeFormMessage) ?></p>
		<?php endif; ?>
		<?php if ($routeFormMessage !== null): ?>
			<p class="error" role="alert"><?= htmlspecialchars($routeFormMessage) ?></p>
		<?php endif; ?>

		<section aria-labelledby="add-mode-heading">
			<h2 id="add-mode-heading">Add a mode</h2>
			<form action="index.php" method="POST" class="mode-form">
				<input type="hidden" name="modeAction" value="insert">
				<label>
					Mode name
					<input type="text" name="mode_name" maxlength="100" required>
				</label>
				<label>
					Description
					<input type="text" name="description" maxlength="255">
				</label>
				<button type="submit">Add mode</button>
			</form>
		</section>

		<section aria-labelledby="saved-modes-heading">
			<h2 id="saved-modes-heading">Saved modes</h2>
			<?php if (count($modes) === 0): ?>
				<p>No modes have been added yet.</p>
			<?php else: ?>
				<table>
					<thead>
						<tr>
						<th scope="col">ID</th>
						<th scope="col" colspan="2">Mode details and update</th>
						<th scope="col">Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($modes as $mode): ?>
							<tr>
								<td><?= (int) $mode['mode_id'] ?></td>
								<td colspan="2">
									<form action="index.php" method="POST" class="mode-form">
										<input type="hidden" name="modeAction" value="update">
										<input type="hidden" name="mode_id" value="<?= (int) $mode['mode_id'] ?>">
										<label>
											<span class="visually-hidden">Mode name</span>
											<input type="text" name="mode_name" maxlength="100" required value="<?= htmlspecialchars($mode['mode_name'], ENT_QUOTES, 'UTF-8') ?>">
										</label>
										<label>
											<span class="visually-hidden">Description</span>
											<input type="text" name="description" maxlength="255" value="<?= htmlspecialchars($mode['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
										</label>
										<button type="submit">Update</button>
									</form>
								</td>
								<td>
									<form action="index.php" method="POST">
										<input type="hidden" name="modeAction" value="delete">
										<input type="hidden" name="mode_id" value="<?= (int) $mode['mode_id'] ?>">
										<button type="submit">Delete</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</section>

		<section aria-labelledby="add-route-heading">
			<h2 id="add-route-heading">Add a route</h2>
			<?php if (count($modes) === 0): ?>
				<p>Add a transport mode before creating a route.</p>
			<?php else: ?>
				<form action="index.php" method="POST" class="mode-form">
					<input type="hidden" name="routeAction" value="insert">
					<label>
						Route name
						<input type="text" name="route_name" maxlength="150" required>
					</label>
					<label>
						Origin
						<input type="text" name="origin" maxlength="150" required>
					</label>
					<label>
						Destination
						<input type="text" name="destination" maxlength="150" required>
					</label>
					<label>
						Mode
						<select name="mode_id" required>
							<?php foreach ($modes as $mode): ?>
								<option value="<?= (int) $mode['mode_id'] ?>"><?= htmlspecialchars($mode['mode_name'], ENT_QUOTES, 'UTF-8') ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<button type="submit">Add route</button>
				</form>
			<?php endif; ?>
		</section>

		<section aria-labelledby="saved-routes-heading">
			<h2 id="saved-routes-heading">Saved routes</h2>
			<?php if (count($routes) === 0): ?>
				<p>No routes have been added yet.</p>
			<?php else: ?>
				<table>
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col" colspan="4">Route details and update</th>
							<th scope="col">Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($routes as $route): ?>
							<tr>
								<td><?= (int) $route['route_id'] ?></td>
								<td colspan="4">
									<form action="index.php" method="POST" class="mode-form">
										<input type="hidden" name="routeAction" value="update">
										<input type="hidden" name="route_id" value="<?= (int) $route['route_id'] ?>">
										<label>
											<span class="visually-hidden">Route name</span>
											<input type="text" name="route_name" maxlength="150" required value="<?= htmlspecialchars($route['route_name'], ENT_QUOTES, 'UTF-8') ?>">
										</label>
										<label>
											<span class="visually-hidden">Origin</span>
											<input type="text" name="origin" maxlength="150" required value="<?= htmlspecialchars($route['origin'], ENT_QUOTES, 'UTF-8') ?>">
										</label>
										<label>
											<span class="visually-hidden">Destination</span>
											<input type="text" name="destination" maxlength="150" required value="<?= htmlspecialchars($route['destination'], ENT_QUOTES, 'UTF-8') ?>">
										</label>
										<label>
											<span class="visually-hidden">Mode</span>
											<select name="mode_id" required>
												<?php foreach ($modes as $mode): ?>
													<option value="<?= (int) $mode['mode_id'] ?>" <?= (int) $mode['mode_id'] === (int) $route['mode_id'] ? 'selected' : '' ?>><?= htmlspecialchars($mode['mode_name'], ENT_QUOTES, 'UTF-8') ?></option>
												<?php endforeach; ?>
											</select>
										</label>
										<button type="submit">Update</button>
									</form>
								</td>
								<td>
									<form action="index.php" method="POST">
										<input type="hidden" name="routeAction" value="delete">
										<input type="hidden" name="route_id" value="<?= (int) $route['route_id'] ?>">
										<button type="submit">Delete</button>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</section>
	</main>
</body>
</html>
