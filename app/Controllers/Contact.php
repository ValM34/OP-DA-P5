<?php

include_once('../../models/Contact.php');

$contact = new Contact ();

$contact->setSujet($_POST['sujet']);
$contact->setCorp($_POST['corp']);
$contact->setName($_POST['name']);
$contact->setLastName($_POST['lastName']);
$contact->setEmail($_POST['email']);

$dest = $contact->getDest();
// $contactTitle = $contact->getContactTitle();
$headers = $contact->getHeaders();
$sujet = $contact->getTitle();
$corp = $contact->getCorp();

/* $contact
    ->setSujet($contactTitle, $_POST['sujet'])
    ->setCorp($_POST['corp'])
;
Ca met une erreur je sais pas pourquoi
*/


$contact->sendEmail($dest, $sujet, $corp, $headers);
header('Location: http://op-da-p5');
?>