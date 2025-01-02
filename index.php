<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: forms/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .h-custom {
            height: calc(100% - 73px);
        }

        @media (max-width: 450px) {
            .h-custom {
                height: 100%;
            }
        }
    </style>
</head>

<body>
   <!-- Section: Design Block -->
<section class=" text-center text-lg-start">
  <style>
    .rounded-t-5 {
      border-top-left-radius: 0.5rem;
      border-top-right-radius: 0.5rem;
    }
    body{
     background-color: #f0f3f4;   
    }

    @media (min-width: 992px) {
      .rounded-tr-lg-0 {
        border-top-right-radius: 0;
      }

      .rounded-bl-lg-5 {
        border-bottom-left-radius: 0.5rem;
      }
    }
  </style>
  <div class="card m-5">
    <div class="row g-0 d-flex align-items-center">
      <div class="col-lg-4 d-none d-lg-flex">
        <img src="images/notebook.png" alt="Trendy Pants and Shoes"
          class="w-100 rounded-t-5 rounded-tr-lg-0 rounded-bl-lg-5" />
      </div>
      <div class="col-lg-8">
        <div class="card-body py-5 px-md-5">
            <h1 class="text-center">Inicio de Sesión</h1>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center"><?= htmlspecialchars($_GET['error']) ?></div>
            <?php endif; ?>

          <form action="actions/login.php" method="POST">
            <!-- Email input -->
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="text" id="username" name="username" class="form-control" />
              <label class="form-label" for="username">Usuario</label>
            </div>

            <!-- Password input -->
            <div data-mdb-input-init class="form-outline mb-4">
              <input type="password" id="password" name="password" class="form-control" />
              <label class="form-label" for="password">Password</label>
            </div>

    

            <!-- Submit button -->
            <button  type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Ingresar</button>

          </form>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- Section: Design Block -->
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>