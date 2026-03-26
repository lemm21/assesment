<?php
// Ito yung file na nagha-handle ng pag-save ng order sa database, at pag-update ng stock sa product page nyo.

include "db.php";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["product_id"];
    // Hidden to si ID sa form, para malaman kung anong product yung binebenta.
    $name = $_POST["product_name"];
    $price = $_POST["product_price"];
    $qty = $_POST["quantity"];
    $total = $_POST["total_price"];
    $c_name = $_POST["buyer-name"];
    $contact = $_POST["contact"];
    $address = $_POST["address"];
    $email = $_POST["email"];

    $sql_stock = "SELECT stock FROM products WHERE product_id='$id'";
    $result = mysqli_query($conn, $sql_stock);
    $row_stock = mysqli_fetch_assoc($result);
    $current_stock = $row_stock["stock"];

    if ($qty > $current_stock) {
        echo "<script> alert('Order exceeds available stock! Only {$current_stock} left.'); window.location.href='product.php'; </script>";
        // Chineck kung sobra ung order kaysa sa available na stock, kung sobra, alert at redirect sa product page
    } else {
        $sql_insert = "INSERT INTO sold (product_id, product_name, product_price, quantity, total, c_name, contact, address, email) VALUES ('$id', '$name', '$price', '$qty', '$total', '$c_name', '$contact', '$address', '$email')";

        if (mysqli_query($conn, $sql_insert)) {
            $new_stock = $current_stock - $qty;
            // Uupdate yung stock sa isang table nyo para mabawasan yung stock sa product page nyo
            $sql_update = "UPDATE products SET stock = '$new_stock' WHERE product_id = '$id'";
            mysqli_query($conn, $sql_update);
            echo "<script> alert('Order placed.'); window.location.href='product.php'; </script>";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
