document.addEventListener("DOMContentLoaded", function () {
  const formAddEvent = document.querySelector("#add-event");
  const formEditEvent = document.querySelector("#edit-event");
  const editEventBtn = document.querySelectorAll(".btn-edit-event");
  const hapusEventBtn = document.querySelectorAll(".btn-hapus-event");
  const eventsCard = document.querySelectorAll(".day");
  formAddEvent.addEventListener("submit", function (e) {
    e.preventDefault();
    const name = formAddEvent.elements[0].value;
    const description = formAddEvent.elements[3].value;
    const dateFrom = formAddEvent.elements[1].value;
    const dateTo = formAddEvent.elements[2].value;
    if (name.match(/^.+$/)) {
      fetch("".concat(window.location.origin, "/api/event.php"), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
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
          return alert("Data acara tidak berhasil ditambahkan");
        });
    } else {
      alert("Isikan input dengan benar");
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
      fetch("".concat(window.location.origin, "/api/event.php"), {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
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
      alert("Isikan input dengan benar");
    }
  });
  editEventBtn.forEach(function (btn) {
    btn.addEventListener("click", function () {
      fetch(
        ""
          .concat(window.location.origin, "/api/event.php?eId=")
          .concat(btn.dataset.eId),
        {
          method: "GET",
          credentials: "include",
          headers: {
            Accept: "application/json",
            "Content-type": "application/json",
            Authentication: "",
          },
        }
      )
        .then(function (res) {
          return res.json();
        })
        .then(function (eventData) {
          formEditEvent.querySelector("#inputs").classList.remove("hidden");
          const formEditInputs = formEditEvent.elements;
          formEditInputs[0].value = btn.dataset.eId;
          formEditInputs[1].value = eventData.nama;
          formEditInputs[4].value = eventData.deskripsi
            .split(/\s/)
            .map(function (kata) {
              kata = kata.replace(/[^\\r]\\n/g, "\r\n");
              kata = kata.replace(/\\\\/g, "\\");
              return kata;
            })
            .join(" ");
          formEditInputs[2].value = eventData.dari;
          formEditInputs[3].value = eventData.sampai;
        });
    });
  });
  hapusEventBtn.forEach(function (btn) {
    btn.addEventListener("click", function () {
      fetch("".concat(window.location.origin, "/api/event.php"), {
        credentials: "include",
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
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
          return alert("Data tidak berhasil dihapus");
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
