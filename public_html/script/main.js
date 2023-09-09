document.addEventListener("DOMContentLoaded", function () {
  const formAddEvent = document.querySelector("#add-event");
  const formEditEvent = document.querySelector("#edit-event");
  const editEventBtn = document.querySelectorAll(".edit-event-btn");
  const deleteEventBtn = document.querySelectorAll(".delete-event-btn");
  const eventsCard = document.querySelectorAll(".day");

  formAddEvent.addEventListener("submit", function (e) {
    e.preventDefault();
    const name = formAddEvent.elements[0].value;
    const description = formAddEvent.elements[3].value;
    const dateFrom = formAddEvent.elements[1].value;
    const dateTo = formAddEvent.elements[2].value;
    if (name.match(/^.+$/)) {
      fetch(new URL("api/event.php", window.location), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          name: name,
          description: description,
          dateFrom: dateFrom,
          dateTo: dateTo,
          method: "POST",
        }),
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (res) {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        ["catch"](function () {
          return alert("Failed to insert new event");
        });
    } else {
      alert("Please fill all the inputs");
    }
  });

  formEditEvent.addEventListener("submit", function (e) {
    e.preventDefault();
    const eId = formEditEvent.elements[0].value;
    const name = formEditEvent.elements[1].value;
    const description = formEditEvent.elements[4].value;
    const dateFrom = formEditEvent.elements[2].value;
    const dateTo = formEditEvent.elements[3].value;
    if (name.match(/^.+$/)) {
      fetch(new URL("api/event.php", window.location), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({
          eId: eId,
          name: name,
          description: description,
          dateFrom: dateFrom,
          dateTo: dateTo,
          method: "PATCH",
        }),
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (res) {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        ["catch"](function () {
          return alert("Data acara tidak berhasil diubah");
        });
    } else {
      alert("Please fill all the inputs");
    }
  });

  editEventBtn.forEach(function (btn) {
    btn.addEventListener("click", function () {
      fetch(new URL(`api/event.php?eId=${btn.dataset.eId}`, window.location), {
        method: "GET",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (eventData) {
          formEditEvent.querySelector("#inputs").classList.remove("hidden");
          const formEditInputs = formEditEvent.elements;
          formEditInputs[0].value = btn.dataset.eId;
          formEditInputs[1].value = eventData.name;
          formEditInputs[4].value = eventData.description
            .split(/\s/)
            .map(function (word) {
              word = word.replace(/[^\\r]\\n/g, "\r\n");
              word = word.replace(/\\\\/g, "\\");
              return word;
            })
            .join(" ");
          formEditInputs[2].value = eventData.start;
          formEditInputs[3].value = eventData.end;
        });
    });
  });

  deleteEventBtn.forEach(function (btn) {
    btn.addEventListener("click", function () {
      fetch(new URL("api/event.php", window.location), {
        credentials: "include",
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
        },
        body: JSON.stringify({ eId: btn.dataset.eId, method: "DELETE" }),
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (res) {
          if (res.code === 203) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
          return;
        })
        ["catch"](function () {
          return alert("Failed to remove event");
        });
    });
  });

  eventsCard.forEach(function (card) {
    card.addEventListener("click", function () {
      const cardDate = ""
        .concat(card.dataset.year, "-")
        .concat(
          parseInt(card.dataset.month) < 10
            ? "0".concat(card.dataset.month)
            : card.dataset.month,
          "-"
        )
        .concat(
          parseInt(card.dataset.day) < 10
            ? "0".concat(card.dataset.day)
            : card.dataset.day
        );
      formAddEvent.elements[1].value = cardDate;
      formAddEvent.elements[2].value = cardDate;
      formAddEvent.elements[0].focus();
    });
  });
});
