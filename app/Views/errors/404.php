<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Not Found — MD Goatco Farm</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&family=Fraunces:wght@700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #F4F7FD;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .wrap {
      text-align: center;
      max-width: 500px;
    }

    .code {
      font-family: 'Fraunces', serif;
      font-size: 8rem;
      font-weight: 700;
      color: #EDF2FB;
      line-height: 1;
      margin-bottom: -20px;
    }

    h1 {
      font-size: 2rem;
      color: #1E3F7A;
      margin-bottom: 12px;
    }

    p {
      color: #4A5568;
      font-size: 0.96rem;
      margin-bottom: 30px;
      line-height: 1.7;
    }

    .actions {
      display: flex;
      gap: 12px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 11px 24px;
      border-radius: 8px;
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      border: 2px solid transparent;
      transition: all .15s;
    }

    .btn-primary {
      background: #2B5BA8;
      color: #fff;
    }

    .btn-primary:hover {
      background: #1E3F7A;
    }

    .btn-ghost {
      border-color: #D8E1F0;
      color: #4A5568;
    }

    .btn-ghost:hover {
      border-color: #4A5568;
    }

    .logo {
      margin-bottom: 32px;
    }

    .logo img {
      width: 52px;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="logo">
      <img src="<?= base_url('img/logo.png') ?>" class="logo" alt="MD Goatco Farm">
    </div>
    <div class="code">404</div>
    <h1>Page not found</h1>
    <p>The page you're looking for doesn't exist or may have moved. Check the URL, or use the buttons below to get back on track.</p>
    <div class="actions">
      <a href="/" class="btn btn-primary">← Go home</a>
      <a href="javascript:history.back()" class="btn btn-ghost">Go back</a>
    </div>
  </div>
</body>

</html>