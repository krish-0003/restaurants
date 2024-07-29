<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <title>Feasty</title>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.menu-toggle').addEventListener('click', function () {
                document.querySelector('nav ul').classList.toggle('show');
            });
        });
    </script>
</head>

<?php
// Start the session
session_start();
?>

<body>