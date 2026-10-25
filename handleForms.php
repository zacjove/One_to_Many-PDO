<?php

require_once __DIR__ . '/dbConfig.php';
require_once __DIR__ . '/models.php';

$modeFormMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modeAction'])) {
	$modeAction = $_POST['modeAction'];

	try {
		if ($modeAction === 'insert') {
			insertMode($pdo, trim($_POST['mode_name']), trim($_POST['description']));
		} elseif ($modeAction === 'update') {
			updateMode(
				$pdo,
				trim($_POST['mode_name']),
				trim($_POST['description']),
				(int) $_POST['mode_id']
			);
		} elseif ($modeAction === 'delete') {
			deleteMode($pdo, (int) $_POST['mode_id']);
		} else {
			throw new InvalidArgumentException('Unknown mode action.');
		}

		header('Location: index.php?status=' . urlencode($modeAction));
		exit;
	} catch (PDOException $exception) {
		$modeFormMessage = $modeAction === 'delete'
			? 'Unable to delete this mode. Remove or reassign its routes first.'
			: 'Unable to save the mode. Check the values and try again.';
	}
}

$routeFormMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['routeAction'])) {
	$routeAction = $_POST['routeAction'];

	try {
		if ($routeAction === 'insert') {
			insertRoute(
				$pdo,
				trim($_POST['route_name']),
				trim($_POST['origin']),
				trim($_POST['destination']),
				(int) $_POST['mode_id']
			);
		} elseif ($routeAction === 'update') {
			updateRoute(
				$pdo,
				trim($_POST['route_name']),
				trim($_POST['origin']),
				trim($_POST['destination']),
				(int) $_POST['mode_id'],
				(int) $_POST['route_id']
			);
		} elseif ($routeAction === 'delete') {
			deleteRoute($pdo, (int) $_POST['route_id']);
		} else {
			throw new InvalidArgumentException('Unknown route action.');
		}

		header('Location: index.php?status=route_' . urlencode($routeAction));
		exit;
	} catch (PDOException $exception) {
		$routeFormMessage = $routeAction === 'delete'
			? 'Unable to delete this route. Check whether other records depend on it.'
			: 'Unable to save the route. Check the values and selected mode.';
	}
}

if (isset($_POST['insertWebDevBtn'])) {

	$query = insertWebDev($pdo, $_POST['username'], $_POST['firstName'], 
		$_POST['lastName'], $_POST['dateOfBirth'], $_POST['specialization']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Insertion failed";
	}

}


if (isset($_POST['editWebDevBtn'])) {
	$query = updateWebDev($pdo, $_POST['firstName'], $_POST['lastName'], 
		$_POST['dateOfBirth'], $_POST['specialization'], $_GET['web_dev_id']);

	if ($query) {
		header("Location: ../index.php");
	}

	else {
		echo "Edit failed";;
	}

}




if (isset($_POST['deleteWebDevBtn'])) {
	$query = deleteWebDev($pdo, $_GET['web_dev_id']);

	if ($query) {
		header("Location: ../index.php");
	}

	else {
		echo "Deletion failed";
	}
}




if (isset($_POST['insertNewProjectBtn'])) {
	$query = insertProject($pdo, $_POST['projectName'], $_POST['technologiesUsed'], $_GET['web_dev_id']);

	if ($query) {
		header("Location: ../viewprojects.php?web_dev_id=" .$_GET['web_dev_id']);
	}
	else {
		echo "Insertion failed";
	}
}




if (isset($_POST['editProjectBtn'])) {
	$query = updateProject($pdo, $_POST['projectName'], $_POST['technologiesUsed'], $_GET['project_id']);

	if ($query) {
		header("Location: ../viewprojects.php?web_dev_id=" .$_GET['web_dev_id']);
	}
	else {
		echo "Update failed";
	}

}




if (isset($_POST['deleteProjectBtn'])) {
	$query = deleteProject($pdo, $_GET['project_id']);

	if ($query) {
		header("Location: ../viewprojects.php?web_dev_id=" .$_GET['web_dev_id']);
	}
	else {
		echo "Deletion failed";
	}
}




?>




