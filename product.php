
<?php include "db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Page</title>
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* PAGE */
body{
  background:#f5f6fa;
}

/* PRODUCT GRID (5 per row) */
.products{
  display:grid;
  grid-template-columns: repeat(5, 1fr);
  gap:20px;
}

/* PRODUCT CARD */
.product-card{
  background:white;
  border-radius:12px;
  overflow:hidden;
  transition:0.2s;
  border:1px solid #eee;
}

.product-card:hover{
  transform:translateY(-4px);
  box-shadow:0 6px 15px rgba(0,0,0,0.1);
}

.product-card img{
  width:100%;
  height:200px;
  object-fit:cover;
}

.product-info{
  padding:15px;
}

.product-info h5{
  font-size:16px;
  margin-bottom:5px;
}

.price{
  font-weight:bold;
  color:#e63946;
}

.stock{
  font-size:13px;
  color:gray;
}

.btn-group{
  display:flex;
  gap:10px;
  margin-top:10px;
}

.buy-btn:disabled{
  background:gray !important;
  border:none;
}

/* MODAL STYLE (E-COMMERCE LOOK) */
.modal{
  display:none;
  position:fixed;
  inset:0;
  background:rgba(0,0,0,0.5);
  justify-content:center;
  align-items:center;
  z-index:999;
}

.modal.active{
  display:flex;
}

.modal-box{
  background:white;
  width:800px;
  max-width:95%;
  border-radius:15px;
  padding:20px;
}

.buy-form{
  display:flex;
  gap:20px;
}

.buy-form img{
  width:250px;
  border-radius:10px;
}

.form-section{
  flex:1;
}

input{
  width:100%;
  margin-bottom:10px;
  padding:6px;
  border:1px solid #ddd;
  border-radius:6px;
}

/* RESPONSIVE */
@media (max-width:1100px){
  .products{
    grid-template-columns: repeat(3,1fr);
  }
}

@media (max-width:600px){
  .products{
    grid-template-columns: repeat(2,1fr);
  }

  .buy-form{
    flex-direction:column;
    align-items:center;
  }
}

</style>
</head>

<body class="p-4">

<h2 class="mb-4">Products</h2>

<div class="products">

<?php
$result = $conn->query("SELECT * FROM products");

while($p = $result->fetch_assoc()){
?>

<div class="product-card">
  <img src="showImage.php?id=<?php echo $p['product_id']; ?>">

  <div class="product-info">
    <h5><?php echo $p['product_name']; ?></h5>
    <div class="price">₱<?php echo $p['product_price']; ?></div>
    <div class="stock">Stock: <?php echo $p['stock']; ?></div>

    <div class="btn-group">
      <button class="btn btn-primary">Add</button>

      <button class="btn btn-success buy-btn"
        data-id="<?php echo $p['product_id']; ?>"
        data-name="<?php echo $p['product_name']; ?>"
        data-price="<?php echo $p['product_price']; ?>"
        data-stock="<?php echo $p['stock']; ?>">
        Buy
      </button>
    </div>
  </div>
</div>

<?php } ?>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
  <div class="modal-box">

    <form action="sold.php" method="POST" class="buy-form">

      <img id="img">

      <div class="form-section">
        <input type="hidden" name="product_id" id="pid">

        Product:
        <input type="text" name="product_name" id="pname" readonly>

        Price:
        <input type="number" name="product_price" id="pprice" readonly>

        Quantity:
        <input type="number" name="quantity" id="qty" min="1" required>

        Total:
        <input type="number" name="total_price" id="total" readonly>

        <button type="submit" class="btn btn-success w-100">Order</button>
        <button type="button" onclick="closeModal()" class="btn btn-danger w-100 mt-2">Close</button>
      </div>

      <div class="form-section">
        Name:
        <input type="text" name="buyer-name" required>

        Email:
        <input type="email" name="email" required>

        Address:
        <input type="text" name="address" required>

        Contact:
        <input type="text" name="contact" required>
      </div>

    </form>

  </div>
</div>

<script>

const buttons = document.querySelectorAll(".buy-btn");

buttons.forEach(btn => {

  const stock = parseInt(btn.dataset.stock) || 0;

  if(stock === 0){
    btn.disabled = true;
    btn.innerText = "Out of Stock";
  } else {
    btn.disabled = false;
  }

  btn.addEventListener("click", () => {

    document.getElementById("modal").classList.add("active");

    document.getElementById("pid").value = btn.dataset.id;
    document.getElementById("pname").value = btn.dataset.name;
    document.getElementById("pprice").value = btn.dataset.price;
    document.getElementById("qty").value = 1;
    document.getElementById("total").value = btn.dataset.price;

    document.getElementById("img").src =
      "showImage.php?id=" + btn.dataset.id;

    document.getElementById("qty").max = stock;

  });

});

// AUTO TOTAL
document.getElementById("qty").addEventListener("input", () => {

  let price = parseFloat(document.getElementById("pprice").value) || 0;
  let qty = parseInt(document.getElementById("qty").value) || 1;

  document.getElementById("total").value = price * qty;

});

function closeModal(){
  document.getElementById("modal").classList.remove("active");
}

</script>

</body>
</html>
```
