<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/public/common/css/all.min.css" />
  <link rel="stylesheet" href="/public/common/css/common.css" />
  <link rel="stylesheet" href="/public/common/css/dashboard.css" />
  <title>Dashboard</title>
</head>

<body>
  <nav class="head-nav">
    <div class="logo">
      <a href="/">
        <img src="/public/common/images/logo.png" />
      </a>
    </div>
    <form method="GET" action="/search.php">
      <div class="search">
        <input name="query" placeholder="Search for events...." type="text" />
      </div>
    </form>
    <div></div>
  </nav>
  <div class="layout-wrapper">
    <aside for="sidebar-content" class="sidebar">
      <a class="item selected">
        <i class="far fa-calendar-plus"></i>
        <span>Pay for fees</span>
      </a>
      <a class="item">
        <i class="far fa-calendar-check"></i>
        <span>Shop</span>
      </a>
      <a class="item">
        <i class="far fa-calendar-check"></i>
        <span>View Transactions</span>
      </a>
      <a class="item">
        <i class="far fa-calendar-check"></i>
        <span>Create Product</span>
      </a>
      <a class="item">
        <i class="far fa-calendar-check"></i>
        <span>Create Student</span>
      </a>
    </aside>
    <main id="sidebar-content" class="main-content sidebar-routes">
      <div class="route">
        <form class="form" id="pay-debt-form">
          <div class="form-group">
            <label>Student ID</label>
            <input required name="id" type="text" />
          </div>
          <div class="form-button">
            <button class="ui-button">Submit</button>
          </div>
        </form>
      </div>
      <div class="route">
        <ul class="events-list" id="shop-view"></ul>
      </div>
      <div class="route"></div>
      <div class="route">
        <form class="form" id="create-product-form">
          <div class="form-group">
            <label>Product Name</label>
            <input required name="name" type="text" />
          </div>
          <div class="form-group">
            <label>Product Image Url</label>
            <input required name="image" type="url" />
          </div>
          <div class="form-group">
            <label>Product Price</label>
            <input required name="price" type="number" />
          </div>
          <div class="form-button">
            <button class="ui-button">Submit</button>
          </div>
        </form>
      </div>
      <div class="route">
        <form class="form" id="create-student-form">
          <div class="form-group">
            <label>Student Name</label>
            <input required name="name" type="text" />
          </div>
          <div class="form-button">
            <button class="ui-button">Submit</button>
          </div>
        </form>
      </div>
    </main>
  </div>

  <footer>
    <div class="logo">
      <img src="/public/common/images/logo.png" />
    </div>
    <ul class="links">
      <li class="header">Explore</li>
      <li><a href="/search.php?query=">Events</a></li>
    </ul>
    <ul class="links">
      <li class="header">Join Us</li>
      <li><a href="/signin.php">Sign In</a></li>
      <li><a href="/org-signup.php">Become an Organizer</a></li>
      <li><a href="/signup.php">Sign Up</a></li>
    </ul>
  </footer>
</body>
<script src="/public/common/js/common.js"></script>

<script>
  (function() {
    let form = document.getElementById("create-product-form");
    form.addEventListener("submit", async function(e) {
      e.preventDefault();
      const data = {
        name: form.elements["name"].value,
        price: form.elements["price"].value,
        image: form.elements["image"].value,
      };
      const options = {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
        },
      };
      let res = await fetch("http://adepa.com/api/product", options);
      if (!res.ok) {
        let resData = await res.json();
        alert("Failed to add product " + resData.message);
      } else {
        alert("Product Added Successfully");
      }
      return false;
    });
  })();
</script>

<script>
  (function() {
    let form = document.getElementById("create-student-form");
    form.addEventListener("submit", async function(e) {
      e.preventDefault();
      const data = {
        name: form.elements["name"].value,
        owedFees: 1,
      };
      const options = {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
        },
      };

      let res = await fetch("http://adepa.com/api/student", options);
      if (!res.ok) {
        let resData = await res.json();
        alert("Failed to add student " + resData.message);
      } else {
        alert("Student Added Successfully");
      }
      return false;
    });
  })();
</script>

<script>
  let getPayFunc = async function(id, price) {
    let quantity = window.prompt("Please enter quantity");
    let fullName = window.prompt("Please enter your name");
    let phoneNumber = window.prompt("Please enter your phone number");

    let metaData = JSON.stringify({
      type: 'product',
      productId: id
    })

    let data = {
      amount: price * quantity,
      payerPhone: phoneNumber,
      payerName: fullName,
      data: metaData
    }

    let options = {
      method: "POST",
      body: JSON.stringify(data),
      headers: {
        "Content-Type": "application/json",
        "authorization": "b735eb61-3adb-452a-8b47-a03eef9da37f"
      },
    }

    let res = await fetch("http://covpay.com/api/payment/start/", options);
    let resData = await res.json();
    if (res.ok) {
      window.open(resData['redirectURL'], '_blank');
    } else {
      alert("Failed, " + resData['message']);
    }


  };
  (async function() {


    function genCard(id, image, name, price) {
      return `<li>
                        <a onclick="getPayFunc('${id}', '${price}')"   class="preview-card">
                            <div>
                                <img class="image" src="${image}" />
                            </div>
                            <div class="content">
                                <h2 class="title">${name}</h2>
                                <ul class="extra-details">
                                    <li>
                                        <i class="fas fa-map-marked-alt"></i>
                                       GHS ${price}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>`;
    }

    let shopList = document.getElementById("shop-view");
    let res = await fetch("http://adepa.com/api/products");
    if (!res.ok) {
      let resData = await res.json();
      alert("Failed to load products, " + resData["message"]);
    } else {
      let html = "";
      let resData = await res.json();
      for (let item of resData) {
        html += genCard(
          item["id"],
          item["image"],
          item["name"],
          item["price"]
        );
      }
      shopList.innerHTML = html;
    }
  })();
</script>
<script>
  (function() {
    let form = document.getElementById("pay-debt-form");

    form.addEventListener("submit", async function(e) {
      e.preventDefault();
      let id = form.elements["id"].value;
      let res = await fetch("http://adepa.com/api/student?id=" + id);
      let resData = await res.json();

      if (!res.ok) {
        alert("Failed to get student, " + resData["message"]);
        return false;
      }

      if (resData["owedFees"] == 1) {
        alert("Student doesn't have any debt");
        return false;
      }

      let result = window.confirm(
        `${resData["name"]} is owing ${resData["owedFees"]} GHS do you want to pay now ? `
      );

      if (!result) {
        return;
      }

      return false;
    });
  })();
</script>

</html>