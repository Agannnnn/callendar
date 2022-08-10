<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="<?= APP_URL ?>style/dashboard.css">
</head>

<body>
  <main>
    <h1>Kalender <?= "$m/$y" ?></h1>
    <section class="section-input">
      <a id="logout-btn" href="<?= APP_URL ?>logout/">Logout</a>
      <div>
        <form class="section-input-form" id="add-event">
          <h2>Tambah Acara</h2>
          <div class="input">
            <label for="nama">Nama acara</label>
            <input type="text" name="nama" id="nama">
          </div>
          <div class="input">
            <label for="dari">Dari</label>
            <input type="date" name="dari" id="dari" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="input">
            <label for="sampai">Sampai</label>
            <input type="date" name="sampai" id="sampai" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="input">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi"></textarea>
          </div>
          <button id="input-submit" type="submit">Simpan</button>
        </form>

        <form class="section-input-form" id="edit-event">
          <h2>Edit Acara</h2>
          <div class="input">
            <label for="eId">ID</label>
            <input type="text" name="eId" id="eId" placeholder="ID" readonly>
          </div>
          <div class="hidden" id="inputs">
            <div class="input">
              <label for="nama-edit">Nama acara</label>
              <input type="text" name="nama" id="nama-edit">
            </div>
            <div class="input">
              <label for="dari-edit">Dari</label>
              <input type="date" name="dari" id="dari-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
            </div>
            <div class="input">
              <label for="sampai-edit">Sampai</label>
              <input type="date" name="sampai" id="sampai-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
            </div>
            <div class="input">
              <label for="deskripsi-edit">Deskripsi</label>
              <textarea name="deskripsi" id="deskripsi-edit"></textarea>
            </div>
            <button id="input-submit" type="submit">Simpan</button>
          </div>
        </form>
      </div>
    </section>
    <section class="container">
      <div class="navigation">
        <a href="<?= APP_URL . "index.php?m=$prevMonth&y=$prevYear" ?>">&leftarrow;</a>
        <?php
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        if (!preg_match("/^index\.php$/", end($uri))) : ?>
          <a href="<?= APP_URL ?>index.php">Kembali</a>
        <?php endif ?>
        <a href="<?= APP_URL . "index.php?m=$nextMonth&y=$nextYear" ?>">&rightarrow;</a>
      </div>
      <div class="calendar">
        <?php foreach ($calendar as $day) : ?>
          <div class="day<?= $day['event'] > 0 ? ' ada-acara' : '' ?>" data-day="<?= $day['date'] ?>" data-month="<?= $m ?>" data-year="<?= $y ?>">
            <div class="day-date">
              <h2><?= $day['date'] ?></h2>
            </div>
            <ul class="event">
              <?php if (count($day['events']) > 0) : ?>
                <li>
                  <b>Daftar acara</b>
                </li>
                <?php foreach ($day['events'] as $event) : ?>
                  <li>
                    <span><?= $event['name'] ?></span>
                    <div class="buttons">
                      <button class="btn-hapus-event" data-e-id="<?= $event['e_id'] ?>">Hapus</button>
                      <button class="btn-edit-event" data-e-id="<?= $event['e_id'] ?>">Edit</button>
                    </div>
                  </li>
                <?php endforeach ?>
              <?php else : ?>
                <li>Tidak ada acara</li>
              <?php endif ?>
            </ul>
          </div>
        <?php endforeach ?>
      </div>
    </section>
  </main>

  <script src="<?= APP_URL ?>script/main.js"></script>
</body>

</html>