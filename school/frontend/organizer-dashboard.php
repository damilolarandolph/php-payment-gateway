<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/public/common/css/all.min.css" />
  <link rel="stylesheet" href="/public/common/css/common.css" />
  <link rel="stylesheet" href="/public/common/css/dashboard.css" />
  <link rel="stylesheet" href="/public/common/css/jquery.dataTables.min.css" />
  <title>Adepa</title>
</head>

<body>
  <nav class="head-nav">
    <div class="logo">
      <a href="/">
        <img src="/public/common/images/covpay.png" />
      </a>
    </div>

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
        <span>Add Owed Fees</span>
      </a>
      <a class="item">
        <i class="far fa-calendar-check"></i>
        <span>Refund Transaction</span>
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
          <div class="form-group">
            <label>Payer Name</label>
            <input required name="payerName" type="text" />
          </div>
          <div class="form-group">
            <label>Payer Phone</label>
            <input required name="payerPhone" type="text" />
          </div>
          <div class="form-button">
            <button class="ui-button">Submit</button>
          </div>
        </form>
      </div>
      <div class="route">
        <ul class="events-list" id="shop-view"></ul>
      </div>
      <div class="route">
        <form class="form" style="margin-bottom: 30px;" id="view-transactions-form">
          <div class="form-group">
            <label>Payer Phone</label>
            <input required name="payerPhone" type="search" />
          </div>
          <div class="form-button">
            <button class="ui-button">Get Transactions</button>
          </div>
          <table id="table" class="display" style="width:100%; margin-top: 10px;"></table>

        </form>
      </div>
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
        <form class="form" id="add-debt-form">
          <div class="form-group">
            <label>Student Id</label>
            <input required name="id" type="text" />
          </div>
          <div class="form-group">
            <label>Amount</label>
            <input required name="owedFees" type="number" />
          </div>
          <div class="form-button">
            <button class="ui-button">Submit</button>
          </div>
        </form>
      </div>
      <div class="route">
        <form class="form" id="refund-form">
          <div class="form-group">
            <label>Transaction ID</label>
            <input required name="id" type="text" />
          </div>
          <div class="form-button">
            <button class="ui-button">Refund Transaction</button>
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
      <img src="/public/common/images/covpay.png" />
    </div>
  </footer>
</body>
<script src="/public/common/js/common.js"></script>
<script src="/public/common/js/jquery-3.6.0.min.js"></script>
<script src="/public/common/js/jquery.dataTables.min.js"></script>
<script>
  window.covpayKey = "29a2c11d-e793-47ed-bf36-4e701c712fa5";
</script>
<script>
  (function() {
    let form = document.getElementById('refund-form');
    form.addEventListener('submit', async function(e) {
      e.preventDefault();
      let paymentId = form.elements['id'].value;
      let options = {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "authorization": window.covpayKey
        },
        body: JSON.stringify({
          paymentId: paymentId
        })
      }

      let req = await fetch("http://covpay.com/api/payment/refund", options);

      if (req.ok) {
        alert("Success !");
      } else {
        let res = await req.json();
        alert("Failed, " + res.message);
      }
      return false;
    });
  })();
</script>

<script>
  (function() {
    if (window.dataTable) {
      window.dataTable.destroy();
    }
    let form = document.getElementById('view-transactions-form');
    let table = document.getElementById('table');
    form.addEventListener("submit", async function(e) {
      e.preventDefault();

      let payerPhone = form.elements['payerPhone'].value
      let options = {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
          "authorization": window.covpayKey
        },
      }
      res = await fetch("http://covpay.com/api/payer/transactions?payerPhone=" + payerPhone, options);
      resData = await res.json();

      table.innerHTML = "";

      let innerHTML = "<thead><tr><th>Transaction ID</th><th>Name</th><th>Phone</th><th>Amount</th><th>State</th><th>Description</th></tr></thead>"
      let body = "";

      for (let transaction of resData) {
        let name = transaction.payerName;
        let phone = transaction.payerPhone;
        let amount = transaction.amount;
        let state = transaction.state;
        let id = transaction.id;
        let data = JSON.parse(transaction.data);
        let description = "";
        if (data.type == "product") {
          res = await fetch("http://adepa.com/api/product?id=" + data.productId);
          resData = await res.json();
          description = "Paying for " + resData.name;
        } else {
          res = await fetch("http://adepa.com/api/student?id=" + data.studentId);
          resData = await res.json();
          description = "Paying for fee of " + resData.name;
        }

        let row = `<tr>
        <td>${id}</td>
        <td>${name}</td>
        <td>${phone}</td>
        <td>${amount}</td>
        <td>${state}</td>
        <td>${description}</td>
        </tr>`;
        body += row;
      }
      innerHTML += "<tbody>" + body + "</tbody> <tfoot><tr><th>Transaction ID</th><th>Name</th><th>Phone</th><th>Amount</th><th>State</th><th>Description</th></tr></tfoot>";
      table.innerHTML = innerHTML;
      window.dataTable = $("#table").DataTable();
      return false;
    });

  })();
</script>
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
    let form = document.getElementById("add-debt-form");
    form.addEventListener("submit", async function(e) {
      e.preventDefault();
      let studentId = form.elements['id'].value;
      let amount = form.elements['owedFees'].value;
      let req = await fetch("http://adepa.com/api/student?id=" + studentId);
      let res = await req.json();
      if (!req.ok) {
        alert("Failed, " + res['message']);
        return false;
      }
      res.owedFees = parseInt(res.owedFees);
      res.owedFees += parseInt(amount);


      const options = {
        method: "POST",
        body: JSON.stringify(res),
        headers: {
          "Content-Type": "application/json",
        },
      };

      req = await fetch("http://adepa.com/api/student/update", options);

      if (!req.ok) {
        res = await req.json();
        alert("Failed, " + res['message']);
        return false;
      }
      alert("Success");
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
        let resData = await res.json();
        alert("Student Added Successfully ID is " + resData['id']);
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
        "authorization": window.covpayKey
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
    console.log(form);
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
        return false;
      }

      let metaData = JSON.stringify({
        type: 'fees',
        studentId: id
      })

      let phoneNumber = form.elements['payerPhone'].value;
      let payerName = form.elements['payerName'].value;

      let data = {
        amount: resData["owedFees"],
        payerPhone: phoneNumber,
        payerName: payerName,
        data: metaData
      }
      let options = {
        method: "POST",
        body: JSON.stringify(data),
        headers: {
          "Content-Type": "application/json",
          "authorization": window.covpayKey
        },
      }
      res = await fetch("http://covpay.com/api/payment/start/", options);
      resData = await res.json();


      let debtChecker = async function(paymentId) {
        let options = {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            "authorization": window.covpayKey
          },
        }

        let req = await fetch("http://covpay.com/api/transaction?paymentId=" + paymentId, options);

        let res = await req.json();
        if (!req.ok) {
          console.log(res);
          return;
        }

        if (res.payment.state == 'failed') {
          alert('Transaction Failed');
          return;
        }

        if (res.payment.state != 'success') {
          return;
        }
        req = await fetch("http://adepa.com/api/student?id=" + id);
        res = await req.json();
        if (!req.ok) {
          console.log(res);
          return;
        }
        res.owedFees = 1;


        options = {
          method: "POST",
          body: JSON.stringify(res),
          headers: {
            "Content-Type": "application/json",
          },
        };

        req = await fetch("http://adepa.com/api/student/update", options);

        if (!req.ok) {
          console.log(res);
          return false;
        }
        alert("Success");
        clearInterval(window.debtInterval);
      }

      if (res.ok) {
        window.open(resData['redirectURL'], '_blank');
        window.debtInterval = window.setInterval(() => {
          debtChecker(resData['paymentId'])
        }, 6000)
      } else {
        alert("Failed, " + resData['message']);
      }
      return false;
    });
  })();
</script>

</html>