<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Callendar -
    <?= $formattedDate ?>
  </title>
  <link rel="stylesheet" href="<?= APP_URL ?>style/dashboard.css">
</head>

<body>
  <main>
    <section class="sidepanel">
      <h1>
        <?= $formattedDate ?>
      </h1>
      <a id="logout-btn" href="<?= APP_URL ?>logout/">
        <button class="logout">Logout</button>
      </a>
      <form id="add-event">
        <h2>New Event</h2>
        <div class="form-input">
          <label for="name">Event Name</label>
          <input type="text" name="name" id="name">
        </div>
        <div class="form-input">
          <label for="start">Starts From</label>
          <input type="date" name="start" id="start" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
        </div>
        <div class="form-input">
          <label for="end">Ends At</label>
          <input type="date" name="end" id="end" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
        </div>
        <div class="form-input">
          <label for="description">Description</label>
          <textarea name="description" id="description"></textarea>
        </div>
        <button id="input-submit" type="submit">Save</button>
      </form>

      <form id="edit-event">
        <h2>Edit Event</h2>
        <div class="form-input">
          <label for="eId">ID</label>
          <input type="text" name="eId" id="eId" placeholder="ID" readonly>
        </div>
        <div class="hidden" id="inputs">
          <div class="form-input">
            <label for="name-edit">Event Name</label>
            <input type="text" name="name" id="name-edit">
          </div>
          <div class="form-input">
            <label for="start-edit">Starts From</label>
            <input type="date" name="start" id="start-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="form-input">
            <label for="end-edit">Ends At</label>
            <input type="date" name="end" id="end-edit" value="<?= "$y-" . ($m < 10 ? "0$m" : $m) . "-01" ?>">
          </div>
          <div class="form-input">
            <label for="description-edit">Description</label>
            <textarea name="description" id="description-edit"></textarea>
          </div>
          <button id="input-submit" type="submit">Save Changes</button>
        </div>
      </form>
    </section>

    <section class="main-content">
      <div class="navigation">
        <a href="<?= APP_URL . "index.php?m=$prevMonth&y=$prevYear" ?>" class="navigation-button">&leftarrow;</a>
        <?php
        $uri = explode("/", $_SERVER['REQUEST_URI']);
        if (isset($_GET['m']) || isset($_GET['y'])): ?>
          <a href="<?= APP_URL ?>index.php">Today</a>
        <?php endif ?>
        <a href="<?= APP_URL . "index.php?m=$nextMonth&y=$nextYear" ?>" class="navigation-button">&rightarrow;</a>
      </div>
      <div class="callendar">
        <?php foreach ($calendar as $day): ?>
          <div class="day<?= $day['event'] > 0 ? ' filled' : '' ?>" data-day="<?= $day['date'] ?>" data-month="<?= $m ?>"
            data-year="<?= $y ?>" title="Click to add new event" data-events='<?= json_encode($day['events']) ?>'>
            <div class="day-date">
              <h2>
                <?= $day['date'] ?>
              </h2>
              <div class="events-node">
                <?php foreach ($day['events'] as $event): ?>
                  <div class="event-node"></div>
                <?php endforeach ?>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </section>

    <div class="events-list">
      <h4>Event(s) At <span id="event-list-date"></span></h4>
      <ul class="events">

      </ul>
    </div>
  </main>

  <script src="<?= APP_URL ?>script/main.js"></script>
</body>

</html>