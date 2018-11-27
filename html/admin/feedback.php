<?php

// This file allows the administrator to view every order.
// This script is created in Chapter 11.

// Require the configuration before any PHP code as configuration controls error reporting.
require ('../includes/config.inc.php');

// Set the page title and include the header:
$page_title = 'Customer Messages';
include ('./includes/header.html');
// The header file begins the session.

// Require the database connection:
require(MYSQL);

echo '<h3>View Customer Messages:</h3><table border="0" width="100%" cellspacing="4" cellpadding="4">
<thead>
	<tr>
    <th align="center">Customer Name</th>
    <th align="center">Email</th>
    <th align="center">Title</th>
    <th align="center">Message</th>
  </tr></thead>
<tbody>';

// Make the query:
$q = 'SELECT name, email, title, message FROM messages';

$r = mysqli_query ($dbc, $q);
while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
	echo '<tr>
    <td align="center">' . $row['name'] . '</td>
    <td align="center">' . $row['email'] .'</td>
    <td align="center">' . $row['title'] .'</td>
    <td align="center">' . $row['message'] .'</td>
  </tr>';
}

echo '</tbody></table>';

// Include the footer file to complete the template.
include ('./includes/footer.html');
?>
