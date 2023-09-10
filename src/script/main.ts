document.addEventListener("DOMContentLoaded", () => {
  const formAddEvent = document.querySelector("#add-event") as HTMLFormElement;
  const formEditEvent = document.querySelector(
    "#edit-event"
  ) as HTMLFormElement;
  const editEventBtn = document.querySelectorAll(
    ".btn-edit-event"
  ) as NodeListOf<HTMLButtonElement>;
  const hapusEventBtn = document.querySelectorAll(
    ".btn-hapus-event"
  ) as NodeListOf<HTMLButtonElement>;
  const eventsCard = document.querySelectorAll(
    ".day"
  ) as NodeListOf<HTMLDivElement>;

  formAddEvent.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = (formAddEvent.elements[0] as HTMLInputElement).value;
    const description = (formAddEvent.elements[3] as HTMLInputElement).value;
    const dateFrom = (formAddEvent.elements[1] as HTMLInputElement).value;
    const dateTo = (formAddEvent.elements[2] as HTMLInputElement).value;

    if (name.match(/^.+$/)) {
      fetch(`${window.location.origin}/api/event.php`, {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
        },
        body: JSON.stringify({
          name,
          description,
          dateFrom,
          dateTo,
          method: "POST",
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        .catch(() => alert("Data acara tidak berhasil ditambahkan"));
    } else {
      alert("Isikan input dengan benar");
    }
  });

  formEditEvent.addEventListener("submit", (e) => {
    e.preventDefault();

    const eId = (formEditEvent.elements[0] as HTMLInputElement).value;
    const name = (formEditEvent.elements[1] as HTMLInputElement).value;
    const description = (formEditEvent.elements[4] as HTMLInputElement).value;
    const dateFrom = (formEditEvent.elements[2] as HTMLInputElement).value;
    const dateTo = (formEditEvent.elements[3] as HTMLInputElement).value;

    if (name.match(/^.+$/)) {
      fetch(`${window.location.origin}/api/event.php`, {
        method: "POST",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
        },
        body: JSON.stringify({
          eId,
          name,
          description,
          dateFrom,
          dateTo,
          method: "PATCH",
        }),
      })
        .then((res) => res.json())
        .then((res) => {
          if ((res.code = 200)) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
        })
        .catch(() => alert("Data acara tidak berhasil diubah"));
    } else {
      alert("Isikan input dengan benar");
    }
  });

  editEventBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      fetch(`${window.location.origin}/api/event.php?eId=${btn.dataset.eId}`, {
        method: "GET",
        credentials: "include",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
        },
      })
        .then((res) => res.json())
        .then((eventData) => {
          (
            formEditEvent.querySelector("#inputs") as HTMLDivElement
          ).classList.remove("hidden");

          const formEditInputs = formEditEvent.elements;

          (formEditInputs[0] as HTMLInputElement).value = btn.dataset
            .eId as string;
          (formEditInputs[1] as HTMLInputElement).value = eventData.nama;
          (formEditInputs[4] as HTMLInputElement).value = (
            eventData.deskripsi as string
          )
            .split(/\s/)
            .map((kata) => {
              kata = kata.replace(/[^\\r]\\n/g, "\r\n");
              kata = kata.replace(/\\\\/g, "\\");
              return kata;
            })
            .join(" ");
          (formEditInputs[2] as HTMLInputElement).value = eventData.dari;
          (formEditInputs[3] as HTMLInputElement).value = eventData.sampai;
        });
    });
  });

  hapusEventBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      fetch(`${window.location.origin}/api/event.php`, {
        credentials: "include",
        method: "POST",
        headers: {
          Accept: "application/json",
          "Content-type": "application/json",
          Authentication: "",
        },
        body: JSON.stringify({ eId: btn.dataset.eId, method: "DELETE" }),
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.code === 203) {
            alert(res.message);
            window.location.reload();
            return;
          }
          alert(res.message);
          return;
        })
        .catch(() => alert("Data tidak berhasil dihapus"));
    });
  });

  eventsCard.forEach((card) => {
    card.addEventListener("click", () => {
      const cardDate = `${card.dataset.year}-${
        parseInt(card.dataset.month as string) < 10
          ? `0${card.dataset.month}`
          : card.dataset.month
      }-${
        parseInt(card.dataset.day as string) < 10
          ? `0${card.dataset.day}`
          : card.dataset.day
      }`;
      (formAddEvent.elements[1] as HTMLInputElement).value = cardDate;
      (formAddEvent.elements[2] as HTMLInputElement).value = cardDate;
      (formAddEvent.elements[0] as HTMLInputElement).focus();
    });
  });
});
