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
    <h1>Callendar
      <?= "$m/$y" ?>
    </h1>
    <section class="section-input">
      <a id="logout-btn" href="<?= APP_URL ?>logout/">Logout</a>
      <div>
        <form class="section-input-form" id="add-event">
          <h2>New Event</h2>
          <div class="input">
            <label for="name">Event Name</label>
            <input type="text" name="name" id="name">
          </div>
          <div class="input">
            <label for="start">Starts From</label>
            <input type="date" name="start" id="start" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="input">
            <label for="end">Ends At</label>
            <input type="date" name="end" id="end" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="input">
            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>
          </div>
          <button id="input-submit" type="submit">Save</button>
        </form>

        <form class="section-input-form" id="edit-event">
          <h2>Edit Event</h2>
          <div class="input">
            <label for="eId">ID</label>
            <input type="text" name="eId" id="eId" placeholder="ID" readonly>
          </div>
          <div class="hidden" id="inputs">
            <div class="input">
              <label for="name-edit">Event Name</label>
              <input type="text" name="name" id="name-edit">
            </div>
            <div class="input">
              <label for="start-edit">Starts From</label>
              <input type="date" name="start" id="start-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
            </div>
            <div class="input">
              <label for="end-edit">Ends At</label>
              <input type="date" name="end" id="end-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
            </div>
            <div class="input">
              <label for="description-edit">Description</label>
              <textarea name="description" id="description-edit"></textarea>
            </div>
            <button id="input-submit" type="submit">Save Changes</button>
          </div>
        </form>
      </div>
    </section>

    <section class="container">
      <div class="navigation">
        <a href="<?= APP_URL . "index.php?m=$prevMonth&y=$prevYear" ?>">&leftarrow;</a>
        <?php
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        if (!preg_match("/^index\.php$/", end($uri))): ?>
          <a href="<?= APP_URL ?>index.php">Today</a>
        <?php endif ?>
        <a href="<?= APP_URL . "index.php?m=$nextMonth&y=$nextYear" ?>">&rightarrow;</a>
      </div>
      <div class="calendar">
        <?php foreach ($calendar as $day): ?>
          <div class="day<?= $day['event'] > 0 ? ' filled' : '' ?>" data-day="<?= $day['date'] ?>" data-month="<?= $m ?>"
            data-year="<?= $y ?>">
            <div class="day-date">
              <h2>
                <?= $day['date'] ?>
              </h2>
            </div>
            <ul class="event">
              <?php if (count($day['events']) > 0): ?>
                <li>
                  <b>Events</b>
                </li>
                <?php foreach ($day['events'] as $event): ?>
                  <li>
                    <span>
                      <?= $event['name'] ?>
                    </span>
                    <div class="buttons">
                      <button class="edit-event-btn" data-e-id="<?= $event['e_id'] ?>">Edit</button>
                      <button class="delete-event-btn" data-e-id="<?= $event['e_id'] ?>">Remove</button>
                    </div>
                  </li>
                <?php endforeach ?>
              <?php else: ?>
                <li>No Event</li>
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