<?php
  require_once "core/init.php";
  include "includes/head.php";
  include "includes/navigation.php";
  include "includes/headerfull.php";
  include "includes/leftbar.php";
  
//  $sql = "SELECT * FROM products WHERE featured = 1 ORDER BY id DESC";
  $sql = "SELECT * FROM products WHERE featured = 1";
  $featured = $db->query($sql);
?>


<!--        Main Content-->
  <div class="col-8">
    <h2 class="text-center m-3">Featured Products</h2>
    <div class="row">
      <?php while ($product = mysqli_fetch_assoc($featured)) : ?>
        <div class="col-3 text-center mb-5">
          <h4><?= $product['title']; ?></h4>
          <img src="<?= $product['image']; ?>" alt="<?= $product['title']; ?>" class="img-thumb">
          <p class="list-price text-danger">List Price: <s>$<?= $product['list_price']; ?></s></p>
          <p class="price">Our Price: $<?= $product['price']; ?></p>
          <button type="button" class="btn btn-sm btn-success" onclick="detailsmodal(<?= $product['id']; ?>);">
              Details
          </button>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
<?php
  include "includes/rightbar.php";
  include "includes/footer.php";
?>


