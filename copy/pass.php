<?php
$hash = '$2y$10$ASdFuxhYKZ7hlwYzwNGTj.TLjFhbeju4z0pNzaSlP22Ze8Lxo1upa';
$password = 'Admin123';

if (password_verify($password, $hash)) {
    echo "Password matches!";
} else {
    echo "Password does NOT match!";
}
