<?php

declare(strict_types=1);

use Application\Form;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require __DIR__ . '/../vendor/autoload.php';

$loader = new FilesystemLoader('../views');
$twig = new Environment($loader, [
    'debug' => true,
]);
$twig->addExtension(new DebugExtension());

$result = [];
$errors = false;

if (isset($_FILES["csv_file"])) {
    // Initiate new form object.
    $form = new Form($_FILES['csv_file']['tmp_name']);

    // Process CSV and catch results and errors.
    [$result, $errors] = $form->process();
}

try {
    echo $twig->render('form.html', [
        'results' => $result,
        'error' => $errors,
        'raw' => print_r($result, true)
    ]);
} catch (Exception $exception) {
    echo "<h1>Unable to render template.</h1>>";
    echo $exception->getMessage();
}
