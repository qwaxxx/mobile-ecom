<?php
include 'conn.php';
$result = $conn->query("SELECT id, name, email, contact, user_type, create_on FROM users");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['name']}</td>
        <td>{$row['email']}</td>
        <td>{$row['contact']}</td>
        <td>{$row['user_type']}</td>
        <td>{$row['create_on']}</td>
        <td>
            <button class='btn btn-sm btn-warning' onclick='editUser({$row['id']})'>Edit</button>";
    
    // Only show Delete button if user_type is NOT admin
    if ($row['user_type'] !== 'admin') {
        echo " <button class='btn btn-sm btn-danger' onclick='deleteUser({$row['id']})'>Delete</button>";
    }

    echo "</td>
    </tr>";
}
